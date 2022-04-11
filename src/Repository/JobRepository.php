<?php
/**
 * User: Marius Mertoiu
 * Date: 11/04/2022 07:14
 * Email: marius.mertoiu@gmail.com
 */

namespace Parsy\Repository;

use DateTime;

class JobRepository extends BaseRepository
{
    /**
     * Insert method.
     */
    public function insert(
        ?int $referenceId,
        string $name,
        string $description,
        DateTime $expiresAt,
        int $openings,
        int $companyId,
        int $professionId
    ): array {
        $response = [
            'status' => false,
            'message' => null
        ];

        // Check if the profession exists
        $professionRepository = new ProfessionRepository();
        $profession = $professionRepository->findOneBy([
            'id' => $professionId
        ]);

        if (empty($profession)) {
            $response['message'] = 'There is no profession with ID ' . $professionId . '.';
            return $response;
        }

        $sql = "INSERT INTO job (reference_id, name, description, expires_at, openings, company_id, profession_id)
            VALUES (:reference_id, :name, :description, :expires_at, :openings, :company_id, :profession_id)";

        $prepare = [
            'reference_id' => $referenceId,
            'name' => $name,
            'description' => $description,
            'expires_at' => $expiresAt->format('Y-m-d'),
            'openings' => $openings,
            'company_id' => $companyId,
            'profession_id' => $professionId
        ];

        $statement = $this->db->prepare($sql);
        $execute = $statement->execute($prepare);

        if ($execute) {
            $response['status'] = true;
            $response['insert_id'] = $this->db->lastInsertId();
            $response['message'] = 'A new job with ID ' . $response['insert_id'] . ' has been added.';

            return $response;
        }

        $response['message'] = 'An error occurred while adding the job to the database.';

        return $response;
    }

    /**
     * Returns multiple rows based on criteria.
     */
    public function findBy(array $criteria = [], bool $fetchOnlyOneResult = false)
    {
        $where = '';
        $prepare = [];

        if (!empty($criteria['id'])) {
            $where .= ' AND j.id = :id';
            $prepare['id'] = (int) $criteria['id'];
        }

        if (!empty($criteria['reference_id'])) {
            $where .= ' AND j.reference_id = :reference_id';
            $prepare['reference_id'] = (int) $criteria['reference_id'];
        }

        if (!empty($criteria['name'])) {
            $where .= ' AND j.name = :name';
            $prepare['name'] = trim($criteria['name']);
        }

        if (!empty($criteria['description'])) {
            $where .= ' AND j.description = :description';
            $prepare['description'] = trim($criteria['description']);
        }

        if (!empty($criteria['expires_at'])) {
            $where .= ' AND j.expires_at = :expires_at';
            $prepare['expires_at'] = $criteria['expires_at'];
        }

        if (!empty($criteria['openings'])) {
            $where .= ' AND j.openings = :openings';
            $prepare['openings'] = (int) $criteria['openings'];
        }

        if (!empty($criteria['company_id'])) {
            $where .= ' AND j.company_id = :company_id';
            $prepare['company_id'] = (int) $criteria['company_id'];
        }

        if (!empty($criteria['company_name'])) {
            $where .= ' AND c.name = :company_name';
            $prepare['company_name'] = trim($criteria['company_name']);
        }

        if (!empty($criteria['profession_id'])) {
            $where .= ' AND j.profession_id = :profession_id';
            $prepare['profession_id'] = (int) $criteria['profession_id'];
        }

        if (!empty($criteria['profession_name'])) {
            $where .= ' AND p.name = :profession_name';
            $prepare['profession_name'] = trim($criteria['profession_name']);
        }

        $sql = "SELECT
                    j.id,
                    j.reference_id,
                    j.name,
                    j.description,
                    j.expires_at,
                    j.openings,
                    j.company_id,    
                    j.profession_id,
                    c.name AS company_name,
                    p.name AS profession_name
                FROM job j
                LEFT JOIN profession p ON p.id = j.profession_id
                LEFT JOIN company c ON c.id = j.company_id
                WHERE 1
                " . $where . "
                ORDER BY j.name ASC";

        $statement = $this->db->prepare($sql);
        $statement->execute($prepare);

        return $fetchOnlyOneResult ? $statement->fetch() : $statement->fetchAll();
    }

    /**
     * Returns a single row based on criteria.
     */
    public function findOneBy(array $criteria)
    {
        return $this->findBy($criteria, true);
    }

    /**
     * Returns all rows.
     */
    public function findAll()
    {
        return $this->findBy();
    }

    /**
     * Delete a job from the database.
     */
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM job WHERE id = :id LIMIT 1";

        $statement = $this->db->prepare($sql);

        return $statement->execute([
            'id' => $id
        ]);
    }

    /**
     * Update a job in the database.
     *
     * We allow changes only on reference ID, expiration date and openings because the other fields are used for
     * identification. A job is unique only if it has the same name, description, company and profession.
     */
    public function update(
        int $id,
        ?int $referenceId,
        DateTime $expiresAt,
        int $openings
    ): array {
        $sql = "UPDATE job SET
                    reference_id = :reference_id,
                    expires_at = :expires_at,
                    openings = :openings
                WHERE id = :id
                LIMIT 1";

        $prepare = [
            'id' => $id,
            'reference_id' => $referenceId,
            'expires_at' => $expiresAt->format('Y-m-d'),
            'openings' => $openings
        ];

        $statement = $this->db->prepare($sql);
        $execute = $statement->execute($prepare);

        if ($execute) {
            $response['status'] = true;
            $response['message'] = 'The job has been updated. (ID: ' . $id . ')';

            return $response;
        }

        $response['message'] = 'An error occurred while updating the job.';

        return $response;
    }
}