<?php
/**
 * User: Marius Mertoiu
 * Date: 10/04/2022 17:52
 * Email: marius.mertoiu@gmail.com
 */

namespace Parsy\Repository;

use Parsy\Core\Database;
use PDO;

abstract class BaseRepository
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = (new Database())->get();
    }
}