<?php

if (isset($_SERVER['HTTP_HOST']) && isset($_SERVER['PHP_SELF'])) {
    define("WEBROOT", 'https://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/');
} else {
    define("WEBROOT", '/');
}

const CONFIG_PATH = ROOT . 'config/';
const UPLOADS_PATH = ROOT . 'public/uploads/';

/**
 * The PDO exception code returned when the database is missing. Used to  automatically create the database.
 */
const PDO_EXCEPTION_UNKNOWN_DATABASE_CODE = 1049;