# Set maintenance message up
php /var/www/totara-latest/web/admin/cli/maintenance.php --enable
# Let Totara do its upgrade
php /var/www/totara-latest/web/admin/cli/upgrade.php
# HR Transfer -- Add needed folders with appropriate file permissions
#if [ ! -d /mnt/files/moodledata/sync/csv ]
#then
#  mkdir -p /mnt/files/moodledata/sync/csv
#fi
#if [ ! -d /mnt/files/moodledata/sync/csv/transfer ]
#then
#  mkdir -p /mnt/files/moodledata/sync/csv/transfer
#fi
#cd /mnt/files/moodledata/sync/csv
### make sure these directories exist
#if [ ! -d /mnt/files/moodledata/sync/csv/ready ]
#then
#  mkdir /mnt/files/moodledata/sync/csv/ready
#fi
#if [ ! -d /mnt/files/moodledata/sync/csv/store ]
#then
#  mkdir /mnt/files/moodledata/sync/csv/store
#fi
#chmod gu+rwx ready store
#if [ -d /mnt/files/moodledata_old/sync/csv/store ]
#then
#  cp -RTf /mnt/files/moodledata_old/sync/csv/store /mnt/files/moodledata/sync/csv/store
#else
#  printf 'HR Transfer - No pre-existing store files found.'
#fi
# Add database items
PGPASSWORD=$DbPassword psql -h $DbHost -U $DbUser -d $DbName -f /var/www/totara-latest/scripts/db/configure_totara.sql >> configure_totara_sql.log
PGPASSWORD=$DbPassword psql -h $DbHost -U $DbUser -d $DbName -v LRS_HOST=$LRS_HOST -v LRS_PASSWORD=$LRS_PASSWORD -v LRS_USER=$LRS_USER -f /var/www/totara-latest/scripts/db/add_lrs_config.sql >> configure_totara_sql.log
# Purge Caches -- Just in case
sudo -u apache php /var/www/totara-latest/web/admin/cli/purge_caches.php
# Turn off maintenance message
sudo -u apache php /var/www/totara-latest/web/admin/cli/maintenance.php --disable
