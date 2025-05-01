OLD_DOMAIN="compassmylms.com"
NEW_DOMAIN=$PROJECT_BASE_URL
SOURCE_FILE='2021-05-27-totara_compassgroupusa_live_daily_copy_for_web1.sql.gz'
TIME=$( date +"%D %T")
  printf "$TIME - Download from SFTP. \n"
sftp -o "IdentityFile=/mnt/files/moodledata/sync/kineo_transfer_rsa" kineo-transfer@sftp.compassgrouplearns.com:$SOURCE_FILE $SOURCE_FILE
TIME=$( date +"%D %T")
printf "$TIME - Starting Database Installation \n"
if [ -f $SOURCE_FILE ]
then
  TIME=$( date +"%D %T")
  printf "$TIME - Decompressing file. \n"
  gzip -d $SOURCE_FILE
  SOURCE_FILE=${SOURCE_FILE%???}
  TIME=$( date +"%D %T")
  printf "$TIME - Restoring DB from clean backup. \n"
  PGPASSWORD=$DbPassword psql -h $DbHost -U $DbUser -d 'kineo_latest' -f $SOURCE_FILE
else
  printf "ERROR - $SOURCE_FILE does not exist. - Installation cancelled. \n"
fi