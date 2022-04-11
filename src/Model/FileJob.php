<?php
/**
 * User: Marius Mertoiu
 * Date: 10/04/2022 19:52
 * Email: marius.mertoiu@gmail.com
 */

namespace Parsy\Model;

use DateTime;

/**
 * A DTO that stores the parsed data of a job.
 */
class FileJob
{
    private ?int $referenceId;

    private string $name;

    private string $description;

    private DateTime $expirationDate;

    private int $openings;

    private string $company;

    private string $profession;

    public function getReferenceId(): int
    {
        return $this->referenceId;
    }

    public function setReferenceId(?int $referenceId): self
    {
        $this->referenceId = $referenceId;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getExpirationDate(): DateTime
    {
        return $this->expirationDate;
    }

    public function setExpirationDate(DateTime $expirationDate): self
    {
        $this->expirationDate = $expirationDate;

        return $this;
    }

    public function getOpenings(): int
    {
        return $this->openings;
    }

    public function setOpenings(int $openings): self
    {
        $this->openings = $openings;

        return $this;
    }

    public function getCompany(): string
    {
        return $this->company;
    }

    public function setCompany(string $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getProfession(): string
    {
        return $this->profession;
    }

    public function setProfession(string $profession): self
    {
        $this->profession = $profession;

        return $this;
    }
}