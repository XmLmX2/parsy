<?php

namespace Parsy\Service;

use DOMDocument;
use DOMElement;
use DOMXPath;
use Exception;
use Parsy\Model\FileJob;
use Parsy\Model\FileJobCollection;

/**
 * A service used for extracting and transforming the HTML data into a collection of FileJob DTOs.
 */
class DataParser
{
    const DEFAULT_FILE = 'data.html';

    /**
     * Look for jobs in required file.
     * @throws Exception
     */
    public function extractJobsFromFile(?string $file = self::DEFAULT_FILE): FileJobCollection
    {
        $fileJobCollection = new FileJobCollection();

        if (!$file) {
            $file = self::DEFAULT_FILE;
        }

        // Load the file
        $filePath = UPLOADS_PATH . 'jobs_data/' . $file;

        if (!file_exists($filePath)) {
            $message = 'The file doesn\'t exist. (' . $filePath . ')';

            Logger::error($message);

            throw new Exception($message);
        }

        $dom = new DOMDocument();
        $dom->loadHTMLFile($filePath);

        Logger::notice('The file has been loaded. (' . $filePath . ')');

        // Search for jobs based on "job" CSS class
        $xpath = new DOMXPath($dom);
        $jobsData = $xpath->query("//div[contains(@class,'job')]");

        foreach ($jobsData as $job) {
            if ($fileJob = $this->transformNodeIntoJobModel($job)) {
                $fileJobCollection->addJob($fileJob);
            }
        }

        return $fileJobCollection;
    }

    /**
     * Parse the job HTML to a FileJob DTO.
     */
    private function transformNodeIntoJobModel(DOMElement $job): ?FileJob
    {
        $jobParser = new JobParser($job);

        if (!$jobParser->hasValidData()) {
            return null;
        }

        return (new FileJob())
            ->setReferenceId($jobParser->extractReferenceId())
            ->setName($jobParser->extractName())
            ->setDescription($jobParser->extractDescription())
            ->setExpirationDate($jobParser->extractExpirationDate())
            ->setOpenings($jobParser->extractOpenings())
            ->setCompany($jobParser->extractCompany())
            ->setProfession($jobParser->extractProfession());
    }
}