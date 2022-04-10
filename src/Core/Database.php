<?php

namespace Parsy\Core;

use PDO;

class Database
{
    private string $host;
    private int $port;
    private string $name;
    private string $user;
    private string $pass;

    private ?PDO $pdo = null;

    function __construct()
    {
        $this->loadConfig();

        // Initialize the connexion
        if (!$this->isInit()) {
            $this->initDb();
        }
    }

    /**
     * Load config properties.
     */
    private function loadConfig()
    {
        $this->host = defined('DB_HOST') ? DB_HOST : null;
        $this->port = defined('DB_PORT') ? DB_PORT : null;
        $this->name = defined('DB_NAME') ? DB_NAME : null;
        $this->user = defined('DB_USER') ? DB_USER : null;
        $this->pass = defined('DB_PASS') ? DB_PASS : null;
    }

    /**
     * Check if there is an instance of the class.
     */
    private function isInit(): bool
    {
        return $this->pdo instanceof PDO;
    }

    /**
     * Initialize the PDO object.
     */
    private function initDb()
    {
        $this->pdo = new PDO(
            'mysql:host='.$this->host.';port='.$this->port.';dbname='.$this->name.';charset=utf8',
            $this->user, $this->pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
    }
}