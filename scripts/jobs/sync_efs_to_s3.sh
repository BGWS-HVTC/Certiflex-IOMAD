#!/usr/bin/env bash

set -e

if [[ -n "$EFS_BACKUP_BUCKET_NAME" ]]; then
  echo "Starting sync of EFS mount /mnt/files/moodledata/filedir to s3://$EFS_BACKUP_BUCKET_NAME/moodledata/filedir"
  aws s3 sync /mnt/files/moodledata/filedir "s3://$EFS_BACKUP_BUCKET_NAME/moodledata/filedir" --delete
  echo "Completed sync of EFS mount /mnt/files/moodledata/filedir to $EFS_BACKUP_BUCKET_NAME"

  echo "Starting sync of EFS mount /mnt/files/moodledata/lang to s3://$EFS_BACKUP_BUCKET_NAME/moodledata/lang"
  aws s3 sync /mnt/files/moodledata/lang "s3://$EFS_BACKUP_BUCKET_NAME/moodledata/lang" --delete
  echo "Completed sync of EFS mount /mnt/files/moodledata/lang to $EFS_BACKUP_BUCKET_NAME"
fi

