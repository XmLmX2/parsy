<?php

namespace Parsy\Core;

use Parsy\Service\ErrorRedirector;
use PDO;
use PDOException;

class Database
{
    private string $host;
    private int $port;
    private string $name;
    private string $user;
    private string $pass;

    private ?PDO $pdo = null;

    private bool $redirectOnError;

    function __construct(bool $redirectOnError = true)
    {
        $this->redirectOnError = $redirectOnError;
        $this->loadConfig();
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
     * Initialize the PDO object.
     */
    private function initDb(): array
    {
        $response = [
            'status' => false,
            'message' => null
        ];

        try {
            $this->pdo = $this->initPdo();

            $response['status'] = true;

        } catch (\Exception $e) {
            // Create the database if it's missing
            if ($e->getCode() === PDO_EXCEPTION_UNKNOWN_DATABASE_CODE) {
                $response['message'] = 'Missing database! Initialize a new database by accessing: '
                    . WEBROOT . 'init_db.php';
            }

            $response['message'] = $e->getMessage();
        }

        return $response;
    }

    /**
     * Get the PDO object.
     */
    public function get(): ?PDO
    {
        $init = $this->initDb();

        if (empty($init['status']) && $this->redirectOnError) {
            ErrorRedirector::redirect(500,$init['message'] ?? null);
        }

        return $this->pdo;
    }

    /**
     * Initialize the PDO object.
     */
    private function initPdo(bool $requireDatabase = true): PDO
    {
        $dsn = 'mysql:host=' . $this->host . ';port=' . $this->port;

        if ($requireDatabase) {
            $dsn .= ';dbname=' . $this->name;
        }

        $dsn .= ';charset=utf8';

        return new PDO(
            $dsn,
            $this->user, $this->pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
    }

    /**
     * Create database.
     */
    public function createDatabase(): array
    {
        $response = [
            'status' => false,
            'message' => ''
        ];

        try {
            $pdo = $this->initPdo(false);

            $create = $pdo->exec("CREATE DATABASE `" . $this->name . "`");

            if ($create) {
                $response['status'] = true;
                $response['message'] = 'Database "' . $this->name . '" has been created successfully!';
            }
        } catch (PDOException $e) {
            $response['message'] = $e->getMessage();
        }

        return $response;
    }

    /**
     * Get table statements.
     */
    private function getTableStatements(): array
    {
        return [
            'profession' => 'CREATE TABLE profession (
                    id int auto_increment NOT NULL,
                    name varchar(50) NOT NULL UNIQUE,
                    PRIMARY KEY(id)
                )
                ENGINE=InnoDB
                DEFAULT CHARSET=utf8mb4
                COLLATE=utf8mb4_0900_ai_ci;
            ',

            'job' => 'CREATE TABLE job (
                    id int auto_increment NOT NULL,
                    reference_id int NULL,
                    name varchar(50) NOT NULL,
                    description text NOT NULL,
                    expires_at date NOT NULL,
                    openings int NOT NULL,
                    company varchar(30) NOT NULL,
                    profession_id int NOT NULL,
                    CONSTRAINT job_FK FOREIGN KEY (id) REFERENCES profession(id),
                    PRIMARY KEY(id)
                )
                ENGINE=InnoDB
                DEFAULT CHARSET=utf8mb4
                COLLATE=utf8mb4_0900_ai_ci;
            ',
        ];
    }

    /**
     * Create database tables.
     */
    public function createDatabaseTables(): array
    {
        $response = [
            'status' => false,
            'message' => null
        ];

        $pdo = $this->get();

        foreach ($this->getTableStatements() as $statement) {
            try {
                $exec = $pdo->exec($statement);
            } catch (\Exception $e) {
                $response['message'] = $e->getMessage();

                return $response;
            }
        }

        $response['status'] = true;
        $response['message'] = 'The database tables have been added!';

        return $response;
    }
}