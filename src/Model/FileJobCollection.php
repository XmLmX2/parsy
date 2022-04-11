<?php
/**
 * User: Marius Mertoiu
 * Date: 11/04/2022 08:19
 * Email: marius.mertoiu@gmail.com
 */

namespace Parsy\Model;

/**
 * This model stores a collection (array) of FileJobs.
 */
class FileJobCollection
{
    /**
     * @var FileJob[]
     */
    private array $jobs = [];

    public function getJobs(): array
    {
        return $this->jobs;
    }

    public function addJob(FileJob $job): void
    {
        $this->jobs[] = $job;
    }

    public function countJobs(): int
    {
        return count($this->getJobs());
    }

    public function isJobInCollection(array $dbJob): bool
    {
        foreach ($this->getJobs() as $job) {
            if ($dbJob['name'] == $job->getName() &&
                $dbJob['description'] == $job->getDescription() &&
                $dbJob['company_name'] == $job->getCompany() &&
                $dbJob['profession_name'] == $job->getProfession()
            ) {
                return true;
            }
        }

        return false;
    }
}