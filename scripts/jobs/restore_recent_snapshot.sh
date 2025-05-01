#!/bin/bash -xe

# This helps us exit if any of the AWS commands fail
set -e -o pipefail

RESTORE_FROM_RDS_INSTANCE_ID=$1
RESTORE_FROM_DBNAME=$2
RESTORE_TO_HOST=$3
RESTORE_TO_DBNAME=$4
RESTORE_TO_DBUSER=$5
RESTORE_TO_PW=$6
echo $RESTORE_TO_HOST
wait_for_instance () {

    instance_id=$1
    max_tries=${2:-30}
    i=0
    instance=""
    while [[ $i -le $max_tries ]];
    do
      instance=$(aws rds describe-db-instances --db-instance-identifier=$instance_id --query 'DBInstances[*].[DBInstanceIdentifier]' 2> /dev/null --output text)
      if [ -n "$instance" ]
      then
        # NOTE: This should be the ONLY place we echo in this function, as the callers use the stdout output in subsequent calls
        echo "$instance"
        return 0
      fi
      i=$(( i + 1 ))
      sleep 5
    done
  echo "Timed out waiting for instance to be ready"
  return 1
}

if [[ -n "$RESTORE_FROM_RDS_INSTANCE_ID" ]]; then
  echo "Restoring from $RESTORE_FROM_RDS_INSTANCE_ID"

  if [[ -n "$RESTORE_TO_HOST" && -n "$RESTORE_TO_DBNAME" ]]; then
    echo "Restoring to $RESTORE_TO_HOST.$RESTORE_TO_DBNAME"
    DB_IDENTIFIER="${RESTORE_TO_HOST%%.*}"
    TEMP_DB_IDENTIFIER="$DB_IDENTIFIER-temp"
    echo "Retrieving details for $DB_IDENTIFIER"
    RDS_INST=$(aws rds describe-db-instances --db-instance-identifier="$DB_IDENTIFIER")
    echo "Found instance details for restore-to: $RDS_INST"

    # Parse some details on the current instance we'll apply to the restored snapshot
    # This ensure we can replicate the security groups, etc exactly
    INSTANCE_CLASS=$(jq -r '.DBInstances[]|"\(.DBInstanceClass)"' <<< $RDS_INST)
    SUBNET_GROUP=$(jq -r '.DBInstances[]|"\(.DBSubnetGroup.DBSubnetGroupName)"' <<< $RDS_INST)
    PG_NAME=$(jq -r '.DBInstances[]|"\(.DBParameterGroups[].DBParameterGroupName)"' <<< $RDS_INST)
    SG_IDS=$(jq -r '[.DBInstances[]|"\(.VpcSecurityGroups[].VpcSecurityGroupId)"]|join(" ")' <<< $RDS_INST)


    if [[ -z "$INSTANCE_CLASS" || -z "$SUBNET_GROUP" || -z "$PG_NAME" || -z "$SG_IDS" ]]; then
      echo "RESTORE ERROR: Missing details for current instance"
      exit 1
    fi
    echo "Restoring to new instance with class: $INSTANCE_CLASS, subnet group: $SUBNET_GROUP, parameter group: $PG_NAME, security groups: $SG_IDS, db: $RESTORE_TO_DBNAME"

    # Lookup latest snapshot ID from restore from instance
    RESTORE_SNAPSHOTS=$(aws rds describe-db-snapshots --db-instance-identifier "$RESTORE_FROM_RDS_INSTANCE_ID")
    MOST_RECENT_SNAPSHOT_ID=$(jq -r '[.DBSnapshots[]]|sort_by(.SnapshotCreateTime)|last|"\(.DBSnapshotIdentifier)"' <<< $RESTORE_SNAPSHOTS)
    echo "Restoring snapshot: $MOST_RECENT_SNAPSHOT_ID"

  if [[ -z "$MOST_RECENT_SNAPSHOT_ID" ]]; then
    echo "RESTORE ERROR: Could not find recent snapshot for $RESTORE_FROM_RDS_INSTANCE_ID"
    exit 1
  fi

    # Rename the current instance to a temp name so that we can restore to the correct name
    aws rds modify-db-instance --db-instance-identifier "$DB_IDENTIFIER" --new-db-instance-identifier "$TEMP_DB_IDENTIFIER" --apply-immediately

    echo "Waiting for instance to be renamed"
    instance=$(wait_for_instance "$TEMP_DB_IDENTIFIER")

    if [[ -n "$instance" ]]; then
      echo "rename complete for $instance"
      echo "Restoring snapshot to $DB_IDENTIFIER"
      aws rds restore-db-instance-from-db-snapshot \
        --no-multi-az \
        --vpc-security-group-ids $SG_IDS \
        --db-instance-identifier "$DB_IDENTIFIER" \
        --db-snapshot-identifier "$MOST_RECENT_SNAPSHOT_ID" \
        --db-instance-class "$INSTANCE_CLASS" \
        --db-subnet-group-name "$SUBNET_GROUP" \
        --db-parameter-group-name "$PG_NAME"

      # Wait for the restore to complete
      wait_for_instance "$DB_IDENTIFIER" 50
      # We also need to wait for the instance to be available
      aws rds wait db-instance-available --db-instance-identifier "$DB_IDENTIFIER"

      MASTER_PW="$RESTORE_TO_PW-master"
      echo "Modifying master password for $DB_IDENTIFIER"
      aws rds modify-db-instance --db-instance-identifier "$DB_IDENTIFIER" --master-user-password "$MASTER_PW" --apply-immediately
      sleep 5
      aws rds wait db-instance-available --db-instance-identifier "$DB_IDENTIFIER"

      ## Perform some SQL updates
      # 1. Update database name
      path=$(dirname "$0")
      echo "calling $path/post_restore.php"
      php "$path/post_restore.php" "$RESTORE_TO_HOST" "$MASTER_PW" "$RESTORE_FROM_DBNAME" "$RESTORE_TO_DBNAME" "$RESTORE_TO_DBUSER" "$RESTORE_TO_PW"

      echo "Deleting existing (temp) database $TEMP_DB_IDENTIFIER"
      aws rds delete-db-instance --db-instance-identifier "$TEMP_DB_IDENTIFIER" --skip-final-snapshot --no-delete-automated-backups
    else
      echo "RESTORE ERROR: Could not rename $DB_IDENTIFIER to $TEMP_DB_IDENTIFIER in time"
      exit 1
    fi

  else
    echo "RESTORE ERROR: Could not retrieve information about current RESTORE_TO_HOST or RESTORE_TO_DBNAME, please check environment variables"
    exit 1
  fi

else
  echo "RESTORE ERROR: Improper script usage, please specify an RDS instance ID"
  exit 1
fi