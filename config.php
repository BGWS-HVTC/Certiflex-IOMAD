<?php
///////////////////////////////////////////////////////////////////////////
//                                                                       //
// Moodle configuration file                                             //
//                                                                       //
// This file should be renamed "config.php" in the top-level directory   //
//                                                                       //
///////////////////////////////////////////////////////////////////////////
//                                                                       //
// NOTICE OF COPYRIGHT                                                   //
//                                                                       //
// Moodle - Modular Object-Oriented Dynamic Learning Environment         //
//          http://moodle.org                                            //
//                                                                       //
// Copyright (C) 1999 onwards  Martin Dougiamas  http://moodle.com       //
//                                                                       //
// This program is free software; you can redistribute it and/or modify  //
// it under the terms of the GNU General Public License as published by  //
// the Free Software Foundation; either version 3 of the License, or     //
// (at your option) any later version.                                   //
//                                                                       //
// This program is distributed in the hope that it will be useful,       //
// but WITHOUT ANY WARRANTY; without even the implied warranty of        //
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         //
// GNU General Public License for more details:                          //
//                                                                       //
//          http://www.gnu.org/copyleft/gpl.html                         //
//                                                                       //
///////////////////////////////////////////////////////////////////////////
unset($CFG);  // Ignore this line
global $CFG;  // This is necessary here for PHPUnit execution
$CFG = new stdClass();

//=========================================================================
// 1. DATABASE SETUP
//=========================================================================

$CFG->dbtype    = 'pgsql';      // 'pgsql', 'mariadb', 'mysqli', 'auroramysql', 'sqlsrv' or 'oci'
$CFG->dblibrary = 'native';     // 'native' only at the moment
$CFG->dbhost    = $_SERVER['DB_HOST'];;  // eg 'localhost' or 'db.isp.com' or IP
$CFG->dbname    = $_SERVER['DB_NAME'];;     // database name, eg moodle
$CFG->dbuser    = $_SERVER['DB_USER'];;   // your database username
$CFG->dbpass    = $_SERVER['DB_PASSWORD'];;   // your database password
$CFG->prefix    = $_SERVER['DB_PREFIX'];       // prefix to use for all table names
$CFG->dboptions = array(
    'dbpersist' => false,
    'dbsocket'  => false,
    'dbport'    => '5432',
    'dbhandlesoptions' => false,
    'dbcollation' => 'utf8mb4_unicode_ci',
    'connecttimeout' => null,
);

//=========================================================================
// 2. WEB SITE LOCATION
//=========================================================================
$CFG->wwwroot   = $_SERVER['WWW_ROOT'];
$CFG->dirroot = "/var/www/certiflex-latest/src";

//=========================================================================
// 3. DATA FILES LOCATION
//=========================================================================
$CFG->dataroot  = $_SERVER['DATA_ROOT'];
$CFG->routerconfigured = false;
$CFG->reverseproxy = $_SERVER['REVERSE_PROXY'] == 'true';

//=========================================================================
// 4. DATA FILES PERMISSIONS
//=========================================================================
$CFG->directorypermissions = 02777;


//=========================================================================
// 5. ADMIN DIRECTORY LOCATION  (deprecated)
//=========================================================================
$CFG->admin = 'admin';


//=========================================================================
// 6. OTHER MISCELLANEOUS SETTINGS (ignore these for new installations)
//=========================================================================
//   Redis session handler (requires redis server and redis extension):
if ($_SERVER['CACHE_HOST']) {
    $CFG->session_handler_class = '\core\session\redis';
    $CFG->session_redis_host = $_SERVER['CACHE_HOST'];
    if ($_SERVER['CACHE_TLS'] == "true")
        $CFG->session_redis_encrypt = ['verify_peer' => false, 'verify_peer_name' => false];
}

$CFG->sslproxy = $_SERVER['SSL_PROXY'] == 'true';

//=========================================================================
// 7. SETTINGS FOR DEVELOPMENT SERVERS - not intended for production use!!!
//=========================================================================
//
// Force a debugging mode regardless the settings in the site administration
// @error_reporting(E_ALL | E_STRICT); // NOT FOR PRODUCTION SERVERS!
// @ini_set('display_errors', '1');    // NOT FOR PRODUCTION SERVERS!
// $CFG->debug = (E_ALL | E_STRICT);   // === DEBUG_DEVELOPER - NOT FOR PRODUCTION SERVERS!
// $CFG->debugdisplay = 1;             // NOT FOR PRODUCTION SERVERS!

//=========================================================================
// 8. FORCED SETTINGS
//=========================================================================
//=========================================================================
// 9. PHPUNIT SUPPORT
//=========================================================================
//=========================================================================
// 10. SECRET PASSWORD SALT
//=========================================================================
//=========================================================================
// 11. BEHAT SUPPORT
//=========================================================================
//=========================================================================
// 12. DEVELOPER DATA GENERATOR
//=========================================================================
//=========================================================================
// 13. SYSTEM PATHS (You need to set following, depending on your system)
//=========================================================================
//=========================================================================
// 14. ALTERNATIVE FILE SYSTEM SETTINGS
//=========================================================================
//=========================================================================
// 15. CAMPAIGN CONTENT
//=========================================================================
//=========================================================================
// 16. ALTERNATIVE CACHE CONFIG SETTINGS
//=========================================================================
//=========================================================================
// 17. SCHEDULED TASK OVERRIDES
//=========================================================================
//=========================================================================
// 18. SITE ADMIN PRESETS
//=========================================================================
//=========================================================================
// 19. SERVICES AND SUPPORT CONTENT
//=========================================================================
$CFG->showservicesandsupportcontent = false;
//=========================================================================
// 20. NON HTTP ONLY COOKIES
//=========================================================================
//=========================================================================
// 21. SECRET PASSWORD PEPPER
//=========================================================================
//=========================================================================
// ALL DONE!  To continue installation, visit your main page with a browser
//=========================================================================

require_once(__DIR__ . '/lib/setup.php'); // Do not edit

// There is no php closing tag in this file,
// it is intentional because it prevents trailing whitespace problems!
