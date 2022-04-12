<?php
/**
 * User: Marius Mertoiu
 * Date: 11/04/2022 20:47
 * Email: marius.mertoiu@gmail.com
 */

use Parsy\Service\DataParser;
use PHPUnit\Framework\TestCase;

class DataParserTest extends TestCase
{
    public function testFileDoesNotExists(): void
    {
        $dataParser = new DataParser();

        $this->expectException(Exception::class);

        $randomFileName = uniqid();

        $dataParser->extractJobsFromFile($randomFileName);
    }

    public function testIfFileExistsAndReturnsAFileJobCollection(): void
    {
        $dataParser = new DataParser();

        $fileJobCollection = $dataParser->extractJobsFromFile('data.html');

        $this->assertInstanceOf('Parsy\Model\FileJobCollection', $fileJobCollection);
    }

    public function testIfAValidFileReturnsJobs(): void
    {
        $dataParser = new DataParser();

        $fileJobCollection = $dataParser->extractJobsFromFile('data.html');

        $this->assertNotEmpty($fileJobCollection->countJobs());
    }
}