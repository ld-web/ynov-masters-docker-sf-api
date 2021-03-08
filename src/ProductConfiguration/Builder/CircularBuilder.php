<?php

namespace App\ProductConfiguration\Builder;

use App\Entity\CircProductConfiguration;

class CircularBuilder extends AbstractBuilder
{
  /** @var CircProductConfiguration */
  protected $productState;

  public function stepSpecificAttributes(array $data)
  {
    $this->productState->setDiameter(intval($data[6]));
  }

  public function reset(): void
  {
    $this->productState = new CircProductConfiguration();
  }
}
