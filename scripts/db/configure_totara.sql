-- Add APCu cache instance

-- Update mapping to use APCu for application cache

-- Adjust scheduled task for HR Transfer plugin
UPDATE mdl_task_scheduled
SET minute = '45', hour = '4', day = '*', month = '*', dayofweek = '*'
WHERE component = 'local_hr_transfer' and classname = '\local_hr_transfer\task\hr_transfer_cron_task';

-- Update general settings for HR Transfer plugin
-- Check delimiter for email recipients
UPDATE mdl_config_plugins
SET value = CASE WHEN name = 'host' THEN 'sftp.compassgrouplearns.com'
                 WHEN name = 'port' THEN '22'
                 WHEN name = 'username' THEN 'kineo-transfer'
                 WHEN name = 'password' THEN ''
                 WHEN name = 'keyfile' THEN '/mnt/files/moodledata/sync/kineo_transfer_rsa'
                 WHEN name = 'defaultremote' THEN 'hr_import'
                 WHEN name = 'timeout' THEN '10'
                 WHEN name = 'notificationrecipients' THEN 'tblankenship@ingeniumplus.com;ahelton@ingeniumplus.com'
                 ELSE ''
            END
WHERE plugin = 'local_hr_transfer';

-- Create new task for HR Transfer
INSERT INTO mdl_local_hr_transfer_tasks (taskname, taskidnumber, localdir, remotedir, activetimestart, activetimeend, maxdailyruns, runcounttoday, notifications, runlock, active, lastrun, lasttransfer, lasterror, timecreated, timemodified, createdbyuserid)
VALUES ('HR Transfer', '1', '/mnt/files/moodledata/sync', 'hr_import', '04:30', '05:00', 1, 0, 7, 0, 1, 0, 0, 0, 0, 0, 0);

-- Update HR Import task to look in correct folder
UPDATE mdl_config_plugins
SET value = '/mnt/files/moodledata/sync'
WHERE plugin = 'totara_sync' and name ='filesdir';