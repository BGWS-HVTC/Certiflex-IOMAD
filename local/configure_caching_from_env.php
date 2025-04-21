<?php

const CLI_SCRIPT = true;

use core_cache\config_writer;
use core_cache\factory;
use core_cache\store;

require_once("/var/www/certiflex-latest/src/config.php");

$store_name = "BGWS-redis";
print("Checking existing stores...");
// Get list of stores
$factory = factory::instance();
$config = $factory->create_config_instance();
$stores = $config->get_all_stores();

$writer = config_writer::instance();

// Check if store exists
if (!array_key_exists($store_name, $stores)) {
    mtrace("Store not found.");
    mtrace("Creating store...");
    // If not, let's create it.
    $configuration = [
        "server" => $_SERVER['CACHE_HOST'],
        "encryption" => $_SERVER['CACHE_TLS'] == "true",
    ];
    try {
        $writer->add_store_instance($store_name, "redis", $configuration);
    } catch (\core\exception\moodle_exception $exception) {
        print($exception->getMessage());
    }
    mtrace ("Store created.");

} else {
    mtrace("Store already exists.");
}
mtrace("Updating application cache to use store...");
try {
    $writer->set_mode_mappings([
        store::MODE_APPLICATION => [$store_name],
        store::MODE_SESSION => [$store_name],
    ]);
} catch (\core\exception\moodle_exception $exception) {
    print($exception->getMessage());
}
mtrace("Mappings updated. Cache configured.");
