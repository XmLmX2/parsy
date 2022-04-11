<?php
/**
 * User: Marius Mertoiu
 * Date: 10/04/2022 17:53
 * Email: marius.mertoiu@gmail.com
 */

namespace Parsy\Repository;

class ProfessionRepository extends BaseRepository
{
    public function findBy(array $criteria)
    {
        $where = '';
        $prepare = [];

        if ($criteria['name']) {
            $where .= ' AND name = :name';
            $prepare['name'] = trim($criteria['name']);
        }

        $sql = "SELECT
                    id,
                    name
                FROM profession
                WHERE 1
                " . $where . "
                ORDER BY name ASC";
        $statement = $this->db->prepare($sql);
        $statement->execute($prepare);

        return $statement->fetchAll();
    }
}