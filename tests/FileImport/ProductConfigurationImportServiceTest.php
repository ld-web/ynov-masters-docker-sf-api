<?php

namespace App\Tests\FileImport;

use App\FileImport\Exception\FileNotSetException;
use App\FileImport\Exception\InvalidMimeTypeException;
use App\FileImport\ProductConfigurationImportService;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\File;

class ProductConfigurationImportServiceTest extends TestCase
{
  private $productRepository;
  private $em;
  private $importService;

  protected function setUp(): void
  {
    /** @var ProductRepository&\PHPUnit\Framework\MockObject\MockObject */
    $this->productRepository = $this->createMock(ProductRepository::class);

    /** @var EntityManagerInterface&\PHPUnit\Framework\MockObject\MockObject */
    $this->em = $this->createMock(EntityManagerInterface::class);

    $this->importService = new ProductConfigurationImportService($this->productRepository, $this->em);
  }

  public function testFileIsInitiallyNull(): void
  {
    $this->assertNull($this->importService->getFile(), 'File should be null at import service instantiation');
  }

  public function testInvalidMimeTypeThrowsException(): void
  {
    $invalidFile = new File(__DIR__ . '/../bootstrap.php');
    $this->expectException(InvalidMimeTypeException::class);
    $this->importService->setFile($invalidFile);
  }

  public function testSetValidFileCorrectlySetsFileAttribute(): void
  {
    $importFile = new File(__DIR__ . '/data/data_configurations.csv');

    $this->importService->setFile($importFile);
    $this->assertNotNull($this->importService->getFile(), 'File should not be null after setting');
  }

  public function testImportWithoutFileSetThrowsException(): void
  {
    $this->expectException(FileNotSetException::class);
    $this->importService->import();
  }

  public function testValidFileImportsCorrectNumberOfLines(): void
  {
    $importFile = new File(__DIR__ . '/data/data_configurations.csv');

    $this->importService->setFile($importFile);
    $imported = $this->importService->import();
    $this->assertEquals(34, $imported);
  }

  public function testInvalidColumnNumberImportsNoLine(): void
  {
    $importFile = new File(__DIR__ . '/data/data_configurations_wrong_columns.csv');

    $this->importService->setFile($importFile);
    $imported = $this->importService->import();

    $this->assertEquals(0, $imported);
  }
}
