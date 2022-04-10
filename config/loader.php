<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config/config.php';

/**
 * Credentials file load.
 * We load a credentials.local.php if it exists and stop loading the credentials.php file.
 *
 * WARNING :: Take note that if the credentials.local.php file is loaded all constants from credentials.php file
 * should be defined in it, otherwise they won't exist.
 *
 */
require_once file_exists(CONFIG_PATH.'credentials.local.php') ?
    CONFIG_PATH.'credentials.local.php' :
    CONFIG_PATH.'credentials.php';

require_once 'vendor/autoload.php';