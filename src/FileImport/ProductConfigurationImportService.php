<?php

namespace App\FileImport;

use App\Entity\CircProductConfiguration;
use App\Entity\Product;
use App\Entity\RectProductConfiguration;
use App\FileImport\Exception\FileNotSetException;
use App\FileImport\Exception\InvalidMimeTypeException;
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

  private const AUTHORIZED_MIME_TYPES = [
    'text/csv',
    'text/plain',
    'text/x-comma-separated-values',
    'text/x-csv',
    'application/csv'
  ];

  public function __construct(ProductRepository $productRepository, EntityManagerInterface $em)
  {
    $this->productRepository = $productRepository;
    $this->em = $em;
  }

  public function import(): int
  {
    $imported = 0;

    if ($this->file === null) {
      throw new FileNotSetException();
    }

    foreach ($this->file as $line) {
      $shape = $line[0];
      if (count($line) !== $this->nbColumns || ($shape !== "Rectangulaire" && $shape !== "Circulaire")) {
        continue; // wrong column number or unknown shape : just ignore this line
      }

      // --- Product
      $product = $this->getOrCreateProduct($line[1]);

      // --- Configuration
      if ($shape === "Rectangulaire") {
        $configuration = new RectProductConfiguration();
        $configuration
          ->setWidth($line[2])
          ->setHeight($line[3])
          ->setThickness($line[4])
          ->setDepth(floatval($line[5]))
          ->setDB10(floatval($line[7]))
          ->setDB5(floatval($line[8]))
          ->setDB2(floatval($line[9]))
          ->setDB1(floatval($line[10]))
          ->setProduct($product);
      } elseif ($shape === "Circulaire") {
        $configuration = new CircProductConfiguration();
        $configuration
          ->setDiameter(floatval($line[6]))
          ->setDepth(floatval($line[5]))
          ->setDB10(floatval($line[7]))
          ->setDB5(floatval($line[8]))
          ->setDB2(floatval($line[9]))
          ->setDB1(floatval($line[10]))
          ->setProduct($product);
      }

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
}
