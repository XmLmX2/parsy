<?php

namespace Parsy\Service;

use DOMDocument;
use DOMElement;
use DOMXPath;
use Parsy\Model\FileJob;

/**
 * A service used for extracting and transforming the HTML data into a collection of FileJob DTOs.
 */
class DataParser
{
    const DEFAULT_FILE = 'data.html';

    public function getData(?string $file = null): array
    {
        return $this->extractJobsFromFile($file);
    }

    /**
     * Look for jobs in required file.
     */
    private function extractJobsFromFile(?string $file = self::DEFAULT_FILE): array
    {
        if (!$file) {
            $file = self::DEFAULT_FILE;
        }

        $jobs = [];

        // Load the file
        $filePath = UPLOADS_PATH . $file;

        $dom = new DOMDocument();
        $dom->loadHTMLFile($filePath);

        Logger::logNotice('The file has been loaded. (' . $filePath . ')');

        // Search for jobs based on "job" CSS class
        $xpath = new DOMXPath($dom);
        $jobsData = $xpath->query("//div[contains(@class,'job')]");

        foreach ($jobsData as $job) {
            if ($fileJob = $this->transformNodeIntoJobModel($job)) {
                $jobs[] = $fileJob;
            }
        }

        return $jobs;
    }

    /**
     * Parse the job HTML to a FileJob DTO.
     */
    private function transformNodeIntoJobModel(DOMElement $job): FileJob
    {
        $jobParser = new JobParser($job);

        return (new FileJob())
            ->setReferenceId($jobParser->extractReferenceId())
            ->setName($jobParser->extractName())
            ->setDescription($jobParser->extractDescription())
            ->setExpiration($jobParser->extractExpirationDate())
            ->setOpenings($jobParser->extractOpenings())
            ->setCompany($jobParser->extractCompany())
            ->setProfession($jobParser->extractProfession());
    }
}