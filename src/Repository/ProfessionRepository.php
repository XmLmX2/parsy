<?php
/**
 * User: Marius Mertoiu
 * Date: 10/04/2022 17:53
 * Email: marius.mertoiu@gmail.com
 */

namespace Parsy\Repository;

class ProfessionRepository extends BaseRepository
{
    public function findBy(array $criteria, bool $fetchOnlyOneResult = false)
    {
        $where = '';
        $prepare = [];

        if (!empty($criteria['id'])) {
            $where .= ' AND id = :id';
            $prepare['id'] = (int) $criteria['id'];
        }

        if (!empty($criteria['name'])) {
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

        return $fetchOnlyOneResult ? $statement->fetch() : $statement->fetchAll();
    }

    public function findOneBy(array $criteria)
    {
        return $this->findBy($criteria, true);
    }

    public function insert(string $name): array
    {
        $response = [
            'status' => false,
            'message' => null
        ];

        $sql = "INSERT INTO profession (name) VALUES (:name)";

        $prepare = [
            'name' => $name,
        ];

        $statement = $this->db->prepare($sql);
        $execute = $statement->execute($prepare);

        if ($execute) {
            $response['status'] = true;
            $response['insert_id'] = $this->db->lastInsertId();
            $response['message'] = 'A new profession with ID ' . $response['insert_id'] . ' has been added.';

            return $response;
        }

        $response['message'] = 'An error occurred while adding the profession to the database.';

        return $response;
    }

    /**
     * Search by name and return the ID of a profession from the database or create it and return the new ID.
     */
    public function getOrCreateByName(string $name)
    {
        $exists = $this->findOneBy([
            'name' => $name
        ]);

        if (!empty($exists['id'])) {
            return $exists['id'];
        }

        $create = $this->insert($name);

        return $create['insert_id'] ?? null;
    }
}