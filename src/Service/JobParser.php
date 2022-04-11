<?php
/**
 * User: Marius Mertoiu
 * Date: 10/04/2022 20:16
 * Email: marius.mertoiu@gmail.com
 */

namespace Parsy\Service;

use DateTime;
use DOMElement;
use Exception;

class JobParser
{
    private DOMElement $job;

    private const EXPIRATION_CODE = 'expiration';
    private const OPENINGS_CODE = 'oppenings'; // Here is a typo, it should be openings
    private const COMPANY_CODE = 'company';
    private const PROFESSION_CODE = 'profession';

    public function __construct(DOMElement $job)
    {
        $this->job = $job;

        Logger::logNotice('Job parsing has started. (' . $this->logIdentifier() . ')');
    }

    /**
     * Extract an identifier used in logs. Try to use the reference ID (data-id attribute), if it fails go to
     * job name, if that fails too, go to job description.
     */
    private function logIdentifier(): ?string
    {
        // Reference ID
        $identifier = $this->extractReferenceId() ? 'ID: ' . $this->extractReferenceId() : null;

        // Name
        if (!$identifier) {
            $identifier = $this->extractName() ? 'Name: ' . $this->extractName() : null;
        }

        // Description
        if (!$identifier) {
            $identifier = $this->extractName() ? 'Description: ' . $this->extractDescription() : null;
        }

        return $identifier;
    }

    public function extractReferenceId(): int
    {
        $id = (int) $this->job->getAttribute('data-id');

        if (empty($id)) {
            Logger::logError('Unable to extract the reference ID. (' . $this->logIdentifier() . ')');
        }

        return $id;
    }

    public function extractName(): ?string
    {
        foreach ($this->job->getElementsByTagName('h2') as $h2) {
            // Return the first H2 (that's the title)
            return (string) $h2->nodeValue;
        }

        Logger::logError('Unable to extract the name. (' . $this->logIdentifier() . ')');

        return null;
    }

    public function extractDescription(): ?string
    {
        foreach ($this->job->getElementsByTagName('p') as $paragraph) {
            // Return the first paragraph (that's the description)
            return (string) $paragraph->nodeValue;
        }

        Logger::logError('Unable to extract the description. (' . $this->logIdentifier() . ')');

        return null;
    }

    /**
     * Extract and group the information from the table into an associative array.
     */
    private function groupTableElementsAsAnAssociativeArray(): array
    {
        $data = [];

        $table = null;

        foreach ($this->job->getElementsByTagName('table') as $item) {
            $table = $item;
        }

        foreach ($table->getElementsByTagName('tr') as $tableRow) {
            $key = strtolower(trim($tableRow->firstElementChild->textContent));
            $value = trim($tableRow->lastElementChild->textContent);

            $data[$key] = $value;
        }

        return $data;
    }

    public function extractExpirationDate(): ?DateTime
    {
        $tableData = $this->groupTableElementsAsAnAssociativeArray();

        try {
            $expirationDate = $tableData[self::EXPIRATION_CODE];

            if (empty($expirationDate)) {
                Logger::logError('Expiration date was not found in the file. (' . $this->logIdentifier() . ')');
            }

            return $expirationDate ? new DateTime($tableData[self::EXPIRATION_CODE]) : null;
        } catch (Exception $e) {
            if (empty($expirationDate)) {
                Logger::logError('Expiration date could not be converted to DateTime. (' . $this->logIdentifier() . ')');
            }

            return null;
        }
    }

    public function extractOpenings(): ?int
    {
        $tableData = $this->groupTableElementsAsAnAssociativeArray();

        $openings = $tableData[self::OPENINGS_CODE] ? (int) $tableData[self::OPENINGS_CODE] : null;

        if (!$openings) {
            Logger::logError('Openings not found in the file. (' . $this->logIdentifier() . ')');
        }

        return $openings;
    }

    public function extractCompany(): ?string
    {
        $tableData = $this->groupTableElementsAsAnAssociativeArray();

        $company = $tableData[self::COMPANY_CODE] ?? null;

        if (empty($company)) {
            Logger::logError('Company not found in the file. (' . $this->logIdentifier() . ')');
        }

        return $company;
    }

    public function extractProfession(): ?string
    {
        $tableData = $this->groupTableElementsAsAnAssociativeArray();

        $profession = $tableData[self::PROFESSION_CODE] ?? null;

        if (empty($company)) {
            Logger::logError('Profession not found in the file. (' . $this->logIdentifier() . ')');
        }

        return $profession;
    }
}