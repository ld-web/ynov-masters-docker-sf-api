<?php

namespace App\FileImport;

use App\Entity\Product;
use App\FileImport\Exception\FileNotSetException;
use App\FileImport\Exception\InvalidMimeTypeException;
use App\Product\Shape;
use App\ProductConfiguration\Builder\AbstractBuilder;
use App\ProductConfiguration\Builder\BuilderDirector;
use App\ProductConfiguration\Builder\BuilderPool;
use App\ProductConfiguration\Builder\CircularBuilder;
use App\ProductConfiguration\Builder\RectangularBuilder;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use SplFileObject;
use Symfony\Component\HttpFoundation\File\File;

class ProductConfigurationImportService
{
  private $productRepository;
  private $em;
  private $file = null;
  private $nbColumns = 11;
  private $separator = ';';
  private $products = []; // Product objects pool
  private $director;
  private $builderPool;

  private const AUTHORIZED_MIME_TYPES = [
    'text/csv',
    'text/plain',
    'text/x-comma-separated-values',
    'text/x-csv',
    'application/csv'
  ];

  public function __construct(
    ProductRepository $productRepository,
    EntityManagerInterface $em,
    BuilderDirector $director,
    BuilderPool $builderPool
  ) {
    $this->productRepository = $productRepository;
    $this->em = $em;
    $this->director = $director;
    $this->builderPool = $builderPool;
  }

  public function import(): int
  {
    $imported = 0;

    if ($this->file === null) {
      throw new FileNotSetException();
    }

    foreach ($this->file as $line) {
      if (count($line) !== $this->nbColumns) {
        continue;
      }

      $shape = Shape::getShape($line[0]);
      if ($shape === null) {
        continue; // wrong column number or unknown shape : just ignore this line
      }

      // --- Product
      $product = $this->getOrCreateProduct($line[1]);

      // --- Configuration
      $builder = $this->selectBuilder($shape);
      $this->director->setBuilder($builder);
      $configuration = $this->director->make($line);
      $configuration->setProduct($product);

      $this->em->persist($configuration);
      $imported++;
    }

    $this->em->flush();

    return $imported;
  }

  public function getFile(): ?SplFileObject
  {
    return $this->file;
  }

  /**
   * Sets the file pointer to the internal file attribute
   *
   * @param File|null $file
   * @return void
   * @throws InvalidMimeTypeException
   */
  public function setFile(?File $file)
  {
    if (!$this->isValid($file)) {
      throw new InvalidMimeTypeException();
    }

    $this->file = $file->openFile();
    $this->file->setFlags(SplFileObject::READ_CSV);
    $this->file->setCsvControl($this->separator);

    return $this;
  }

  private function isValid(File $file): bool
  {
    $mimeType = $file->getMimeType();

    return in_array($mimeType, self::AUTHORIZED_MIME_TYPES);
  }

  /**
   * Tries to get the product from the object pool or the database.
   * If none is returned, it creates a product and puts it in the object pool
   *
   * @param string $name
   * @return Product
   */
  private function getOrCreateProduct(string $name): Product
  {
    if (!isset($this->products[$name])) {
      $product = $this->productRepository->findOneBy(['name' => $name]);
      if ($product === null) {
        $product = new Product();
        $product->setName($name);
        $this->em->persist($product);
        $this->products[$name] = $product;
      }
    } else {
      $product = $this->products[$name];
    }

    return $product;
  }

  private function selectBuilder(int $shape): AbstractBuilder
  {
    switch ($shape) {
      case Shape::CIRCULAR:
        return $this->builderPool->get(CircularBuilder::class);
      case Shape::RECTANGULAR:
        return $this->builderPool->get(RectangularBuilder::class);
      default:
        throw new \LogicException("Unsupported row format");
    }
  }
}
