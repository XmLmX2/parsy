<?php
/**
 * User: Marius Mertoiu
 * Date: 11/04/2022 08:53
 * Email: marius.mertoiu@gmail.com
 */

namespace Parsy\Repository;

class CompanyRepository extends BaseRepository
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
                FROM company
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

        $sql = "INSERT INTO company (name) VALUES (:name)";

        $prepare = [
            'name' => $name,
        ];

        $statement = $this->db->prepare($sql);
        $execute = $statement->execute($prepare);

        if ($execute) {
            $response['status'] = true;
            $response['insert_id'] = $this->db->lastInsertId();
            $response['message'] = 'A new company with ID ' . $response['insert_id'] . ' has been added.';

            return $response;
        }

        $response['message'] = 'An error occurred while adding the company to the database.';

        return $response;
    }

    /**
     * Search by name and return the ID of a company from the database or create it and return the new ID.
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