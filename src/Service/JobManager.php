<?php
/**
 * User: Marius Mertoiu
 * Date: 11/04/2022 07:26
 * Email: marius.mertoiu@gmail.com
 */

namespace Parsy\Service;

use Exception;
use Parsy\Model\FileJob;
use Parsy\Model\FileJobCollection;
use Parsy\Repository\JobRepository;
use Parsy\Repository\CompanyRepository;
use Parsy\Repository\ProfessionRepository;

class JobManager
{
    public function syncJobs(FileJobCollection $jobCollection): array
    {
        $response = [
            'total_jobs' => $jobCollection->countJobs(),
        ];

        // Delete existing jobs in the DB that are missing from the file
        $response['deleted'] = $this->cleanup($jobCollection);

        $syncCollection = $this->syncCollectionOfJobs($jobCollection);

        $response['added'] = $syncCollection['added'] ?? 0;
        $response['updated'] = $syncCollection['updated'] ?? 0;
        $response['errors'] = $syncCollection['errors'];

        return $response;
    }

    /**
     * Add a collection of jobs into the database.
     */
    private function syncCollectionOfJobs(FileJobCollection $jobCollection): array
    {
        $response = [
            'status' => false,
            'updated' => 0,
            'added' => 0,
            'errors' => []
        ];

        if (empty($jobCollection->getJobs())) {
            $response['message'] = 'Invalid jobs data.';

            return $response;
        }

        $jobRepository = new JobRepository();

        // Loop and sync the jobs
        foreach ($jobCollection->getJobs() as $fileJob) {
            $dbJob = $jobRepository->findOneBy([
                'name' => $fileJob->getName(),
                'description' => $fileJob->getDescription(),
                'company_name' => $fileJob->getCompany(),
                'profession_name' => $fileJob->getProfession(),
            ]);

            if (!empty($dbJob)) {
                $job = $this->updateJob((int) $dbJob['id'], $fileJob);
            } else {
                $job = $this->addJob($fileJob);
            }

            if (empty($job['status'])) {
                $response['errors'] = $job['message'] ?? 'An error occurred while ' .
                    (!empty($dbJob) ? 'updating' : 'adding') . ' the job to the database.' .
                    ' (Name: ' . $fileJob->getName() . ')';
            } else {
                // Increment the corresponding counter
                $response[!empty($dbJob) ? 'updated' : 'added']++;
            }
        }

        $response['status'] = empty($response['errors']);

        return $response;
    }

    /**
     * Add the job to the database.
     */
    private function addJob(FileJob $job): array
    {
        $response = [
            'status' => false,
            'message' => null
        ];

        // Get company ID
        try {
            $companyId = $this->getCompanyId($job);
        } catch (Exception $e) {
            $response['message'] = $e->getMessage();

            return $response;
        }

        // Get profession ID
        try {
            $professionId = $this->getProfessionId($job);
        } catch (Exception $e) {
            $response['message'] = $e->getMessage();

            return $response;
        }

        $jobRepository = new JobRepository();

        $create = $jobRepository->insert(
            $job->getReferenceId(),
            $job->getName(),
            $job->getDescription(),
            $job->getExpirationDate(),
            $job->getOpenings(),
            $companyId,
            $professionId
        );

        if (!empty($create['status'])) {
            $message = 'A new job has been added! (ID: ' . $create['insert_id'] . ' / Name: ' . $job->getName() . ')';

            Logger::success($message);

            $response['status'] = true;
        } else {
            $message = $create['message'] ?? 'An error occurred while adding the job to the database.' .
                ' (Name: ' . $job->getName() . ')';

            Logger::error($message);
        }

        $response['message'] = $message;

        return $response;
    }

    /**
     * Add the job to the database.
     */
    private function updateJob(int $dbJobId, FileJob $job): array
    {
        $response = [
            'status' => false,
            'message' => null
        ];

        $jobRepository = new JobRepository();

        $update = $jobRepository->update(
            $dbJobId,
            $job->getReferenceId(),
            $job->getExpirationDate(),
            $job->getOpenings()
        );

        if (!empty($update['status'])) {
            $message = 'The job has been updated! (ID: ' . $dbJobId . ' / Name: ' . $job->getName() . ')';

            Logger::success($message);

            $response['status'] = true;
        } else {
            $message = $update['message'] ?? 'An error occurred while adding the job to the database.' .
                ' (Name: ' . $job->getName() . ')';

            Logger::error($message);
        }

        $response['message'] = $message;

        return $response;
    }

    /**
     * Get profession ID based on profession name.
     *
     * @throws Exception
     */
    private function getProfessionId(FileJob $job)
    {
        $professionRepository = new ProfessionRepository();

        $professionId = $professionRepository->getOrCreateByName($job->getProfession());

        if (!$professionId) {
            $message = 'An error occurred while trying to retrieve the profession ID for "' .
                $job->getProfession() . '".';

            Logger::error($message);

            throw new Exception($message);
        }

        return $professionId;
    }

    /**
     * Get company ID based on company name.
     *
     * @throws Exception
     */
    private function getCompanyId(FileJob $job)
    {
        $companyRepository = new CompanyRepository();

        $companyId = $companyRepository->getOrCreateByName($job->getCompany());

        if (!$companyId) {
            $message = 'An error occurred while trying to retrieve the company ID for "' .
                $job->getCompany() . '".';

            Logger::error($message);

            throw new Exception($message);
        }

        return $companyId;
    }

    /**
     * Delete the existing rows from the database if they are missing from the file.
     */
    private function cleanup(FileJobCollection $jobCollection): int
    {
        $deleted = 0;

        $jobRepository = new JobRepository();

        foreach ($jobRepository->findAll() as $dbJob) {
            $isInCollection = $jobCollection->isJobInCollection($dbJob);

            if (!$isInCollection) {
                $delete = $jobRepository->delete($dbJob['id']);

                if ($delete) {
                    $deleted++;
                }
            }
        }

        return $deleted;
    }
}