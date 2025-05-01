<?php

$restore_to_host=$argv[1];
$master_pw=$argv[2];
$restore_from_dbname=$argv[3];
$restore_to_dbname=$argv[4];
$restore_to_dbuser=$argv[5];
$restore_to_pw=$argv[6];

echo "Connecting to $restore_to_host";
$dbconn = pg_connect("host=$restore_to_host dbname=postgres user=postgres password=$master_pw") or die('Could not connect: ' . pg_last_error());

// Rename the database, as needed
if($restore_from_dbname != $restore_to_dbname){
    echo "Renaming $restore_from_dbname to $restore_to_dbname";
    $result = pg_query($dbconn, "ALTER DATABASE $restore_from_dbname RENAME TO $restore_to_dbname") or die('Could not rename database: ' . pg_last_error());
}

echo "Creating user $restore_to_dbuser";
pg_query($dbconn, "CREATE USER $restore_to_dbuser WITH PASSWORD '$restore_to_pw'");
pg_query($dbconn, "grant all privileges on database $restore_to_dbname to $restore_to_dbuser");

pg_close($dbconn);


$dbconn2 = pg_connect("host=$restore_to_host dbname=$restore_to_dbname user=postgres password=$master_pw") or die('Could not connect: ' . pg_last_error());

echo "Granting privileges to $restore_to_dbuser";
pg_query($dbconn2, "GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO $restore_to_dbuser;");
pg_query($dbconn2, "GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA public TO $restore_to_dbuser;");

// Make sure to turn off Visier FTP Transfer and Report SFTP tasks
pg_query($dbconn2, "UPDATE mdl_local_ftp_transfer_tasks SET active = 0 WHERE id = 1;");
pg_query($dbconn2, "UPDATE mdl_task_scheduled SET disabled = 1 WHERE id > 0;");

pg_close($dbconn2);
