<?php
define('CLI_SCRIPT', true);
require '/var/www/totara_latest/web/config.php';
require '/var/www/totara-latest/web/blocks/awesome/lib.php'; 
require '/var/www/totara-latest/web/blocks/banner/lib.php';
$OLD_DOMAIN='compassmylms.com';
$NEW_DOMAIN=$CFG->wwwroot;
block_awesome_global_db_replace('www.'.$OLD_DOMAIN, $NEW_DOMAIN);
block_banner_global_db_replace('www.'.$OLD_DOMAIN, $NEW_DOMAIN);
block_awesome_global_db_replace($OLD_DOMAIN, $NEW_DOMAIN);
block_banner_global_db_replace($OLD_DOMAIN, $NEW_DOMAIN);
