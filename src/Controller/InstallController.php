<?php
/**
 * User: Marius Mertoiu
 * Date: 10/04/2022 19:34
 * Email: marius.mertoiu@gmail.com
 */

namespace Parsy\Controller;

use Parsy\Core\Database;

class InstallController
{
    public function install(): array
    {
        $responses = [
            'status' => true,
            'info' => []
        ];

        $database = new Database(false);

        // Create database
        $responses['info']['create_db'] = $database->createDatabase();

        if (empty($responses['info']['create_db']['status'])) {
            $responses['status'] = false;
        }

        // Create database tables
        $responses['info']['create_tables'] = $database->createDatabaseTables();

        if (empty($responses['info']['create_tables']['status'])) {
            $responses['status'] = false;
        }

        return $responses;
    }
}