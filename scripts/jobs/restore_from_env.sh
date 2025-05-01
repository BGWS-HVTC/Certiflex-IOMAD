#!/bin/bash -xe

RESTORE_FROM_RDS_INSTANCE_ID=${1:-$RestoreFromRdsInstanceId}
RESTORE_FROM_DB_NAME=${2:-$RestoreFromDbName}
RESTORE_FROM_S3_BUCKET=${3:-$RestoreFromS3Bucket}

cd "$(dirname "$0")"

if [[ "$DbHost" = *prod* || "$DbName" = *prod* ]]
then
    echo "You may not restore to a prod environment via this script"
    exit 1
fi

if [[ -n "$RESTORE_FROM_RDS_INSTANCE_ID" ]]; then

  if [[ -n "$DbHost" && -n "$DbName" && -n "$DbUser" && -n "$DbPassword" ]]; then
    echo "Restoring from RDS Instance: $RESTORE_FROM_RDS_INSTANCE_ID"
    # Restore db from snapshot
    bash restore_recent_snapshot.sh "$RESTORE_FROM_RDS_INSTANCE_ID" "$RESTORE_FROM_DB_NAME" "$DbHost" "$DbName" "$DbUser" "$DbPassword"

  else
    echo "Must specify DbHost, DbName, DbUser, and DbPassword in environment variables"
    exit 1
  fi
fi

if [[ -n "$RESTORE_FROM_S3_BUCKET" ]]; then
  echo "Restoring files from S3 bucket: $RESTORE_FROM_S3_BUCKET"
  aws s3 sync "s3://$RESTORE_FROM_S3_BUCKET/moodledata/filedir" /mnt/files/moodledata/filedir --delete
  aws s3 sync "s3://$RESTORE_FROM_S3_BUCKET/moodledata/lang" /mnt/files/moodledata/lang --delete
fi
