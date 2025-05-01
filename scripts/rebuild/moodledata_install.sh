SOURCE_FILE='2021-05-21-prod-sitedata.tar.gz'
TIME=$( date +"%D %T")
  printf "$TIME - Download from SFTP. \n"
sftp -o "IdentityFile=/mnt/files/moodledata/sync/kineo_transfer_rsa" kineo-transfer@sftp.compassgrouplearns.com:$SOURCE_FILE $SOURCE_FILE
if [ -f $SOURCE_FILE ]
then
  TIME=$( date +"%D %T")
  printf "$TIME - Extract, chown, chmod filedir. \n"
  tar -xf $SOURCE_FILE data/disk1/moodledata/filedir --owner=apache --group=apache --mode=777
  TIME=$( date +"%D %T")
  printf "$TIME - Extracting lang. \n"
  tar -xf $SOURCE_FILE data/disk1/moodledata/lang --owner=apache --group=apache --mode=777
  TIME=$( date +"%D %T")
  printf "$TIME - Rename old filedir and lang folders. \n"
  mv /mnt/files/moodledata/filedir /mnt/files/moodledata/filedir_obsolete
  mv /mnt/files/moodledata/lang /mnt/files/moodledata/lang_obsolete
  TIME=$( date +"%D %T")
  printf "$TIME - tar|tar to copy to filedir. \n"
  cd /var/www/totara-latest/scripts/rebuild/data/disk1/moodledata
  sudo tar cpof - filedir |( cd /mnt/files/moodledata; tar xpof -)
  TIME=$( date +"%D %T")
  printf "$TIME - tar|tar to copy to lang. \n"
  cd /var/www/totara-latest/scripts/rebuild/data/disk1/moodledata
  sudo tar cpof - lang |( cd /mnt/files/moodledata; tar xpof -)
  TIME=$( date +"%D %T")
  printf "$TIME - Moodledata ready. \n"
else
  TIME=$( date +"%D %T")
  printf "$TIME - Error when downloading from SFTP. \n"
fi
