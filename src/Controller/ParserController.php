<?php
/**
 * User: Marius Mertoiu
 * Date: 10/04/2022 19:38
 * Email: marius.mertoiu@gmail.com
 */

namespace Parsy\Controller;

use Parsy\Service\DataParser;
use Parsy\Service\JobManager;

class ParserController
{
    /**
     * Parse the jobs from the file and save them to the database.
     */
    public function parse(?string $fileName): array
    {
        $response = [];

        // Parse and store the jobs from the file into an FileJobCollection object
        try {
            $parser = new DataParser();
            $fileJobCollection = $parser->extractJobsFromFile($fileName);

            $response['parse'] = [
                'status' => $fileJobCollection->getJobs(),
                'message' => $fileJobCollection->countJobs() . ' jobs have been parsed'
            ];
        } catch (\Exception $e) {
            $response['parse'] = [
                'status' => false,
                'message' => $e->getMessage()
            ];

            return $response;
        }

        // Add the jobs to the database
        $jobAdder = new JobManager();
        $response['db_sync'] = $jobAdder->syncJobs($fileJobCollection);

        return $response;
    }
}