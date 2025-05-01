OLD_DOMAIN="compassmylms.com"
NEW_DOMAIN=$PROJECT_BASE_URL
SOURCE_FILE='totara_compassgroupusa_live_daily_copy_for_web1.sql.gz'
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
  printf "$TIME - Replacing www.$OLD_DOMAIN with $NEW_DOMAIN \n"
  sed -i "s+www.$OLD_DOMAIN+$NEW_DOMAIN+g" $SOURCE_FILE
  TIME=$( date +"%D %T")
  printf "$TIME - Replacing $OLD_DOMAIN with $NEW_DOMAIN \n"
  sed -i "s+$OLD_DOMAIN+$NEW_DOMAIN+g" $SOURCE_FILE
  TIME=$( date +"%D %T")
  printf "$TIME - Rename existing DB and create empty DB. \n"
  PGPASSWORD=$DbPassword psql -h $DbHost -U $DbUser -d postgres -v OLD_DB_NAME=$DbName NEW_DB_NAME=obsolete-f /var/www/totara-latest/scripts/db/rename_db.sql > rename_db_sql.log
  TIME=$( date +"%D %T")
  printf "$TIME - Restoring DB from clean backup. \n"
  PGPASSWORD=$DbPassword psql -h $DbHost -U $DbUser -d $DbName -f $SOURCE_FILE
  TIME=$( date +"%D %T")
  printf "$TIME - Running global replace for encoded URLs. \n"
  php -f /var/www/totara-latest/web/blocks/html/replace_url_encoded_blocks.php
  TIME=$(date +"%T")
  printf "$TIME - Create Indexes. \n"
  PGPASSWORD=$DbPassword psql -h $DbHost -U $DbUser -d $DbName -f /var/www/totara-latest/scripts/db/indexing.sql > indexing_sql.log
  TIME=$( date +"%D %T")
  printf "$TIME - New Database Installed. Starting totara_upgrade.sh\n"
  ./totara_upgrade.sh > totara_upgrade.log
else
  printf "ERROR - $SOURCE_FILE does not exist. - Installation cancelled. \n"
fi