<?php

namespace App\Controller;

use App\FileImport\ProductConfigurationImportService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductConfigurationController extends AbstractController
{
  /**
   * @Route("/products/configurations/import", name="product_configuration_import", methods={"POST"})
   */
  public function index(Request $request, ProductConfigurationImportService $importService): Response
  {
    $file = $request->files->get('file');

    try {
      if ($file === null) {
        throw new BadRequestException('File is required');
      }
      $importService->setFile($file);
      $imported = $importService->import();
    } catch (Exception $e) {
      return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
    }

    return $this->json(['imported' => $imported]);
  }
}
