<?php

namespace App\ProductConfiguration\Builder;

use App\Entity\ProductConfiguration;

abstract class AbstractBuilder
{
  /** @var ProductConfiguration */
  protected $productState;

  //TODO: DTO pour l'import de données de configuration
  public function stepGenericAttributes(array $data)
  {
    $this->productState->setDb1(floatval($data[10]));
    $this->productState->setDb2(floatval($data[9]));
    $this->productState->setDb5(floatval($data[8]));
    $this->productState->setDb10(floatval($data[7]));
    $this->productState->setDepth(intval($data[5]));
  }

  //TODO: DTO pour l'import de données de configuration
  abstract public function stepSpecificAttributes(array $data);

  public function getProductConfiguration(): ProductConfiguration
  {
    return $this->productState;
  }

  abstract public function reset(): void;
}
