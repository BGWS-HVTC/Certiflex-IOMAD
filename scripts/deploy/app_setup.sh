#!/usr/bin/env bash

set -e

cd /var/www/moodle-latest

rm -rf /var/www/moodle-latest/web
mkdir -p /var/www/moodle-latest/localcache
mkdir -p /var/www/moodle-latest/web
unzip output.zip -d /var/www/moodle-latest/web

ln -s /var/www/moodle-latest/web /var/www/html

chown ec2-user.apache /var/www/moodle-latest/web -R
chown apache.apache /var/www/moodle-latest/localcache -R
#chown root /var/www/moodle-latest/localcache -R
#chown -R root /var/www/moodle-latest/web
#chmod -R 0755 /var/www/moodle-latest/web



# pull down file from S3 bucket
aws s3 cp --recursive s3://ceriflex-dev/ /
# Copy our environment variables into our Apache config file
cat /etc/profile.d/bgws-env.sh | sed "s/export/SetEnv/" | sed "s/'//g" | sed "s/=/ /g" >> /etc/httpd/conf.d/certiflex.conf


# We will always deploy cron on LFS System.
cp /var/www/moodle-latest/scripts/jobs/crontabs/job-* /etc/cron.d/
touch /var/log/cron.log
chmod 664 /var/log/cron.log
chown apache.apache /var/log/cron.log

sudo systemctl restart httpd