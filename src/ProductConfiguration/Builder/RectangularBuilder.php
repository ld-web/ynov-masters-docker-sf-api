<?php

namespace App\ProductConfiguration\Builder;

use App\Entity\RectProductConfiguration;

class RectangularBuilder extends AbstractBuilder
{
  /** @var RectProductConfiguration */
  protected $productState;

  public function stepSpecificAttributes(array $data)
  {
    $this->productState->setWidth(intval($data[2]));
    $this->productState->setHeight(intval($data[3]));
    $this->productState->setThickness(intval($data[4]));
  }

  public function reset(): void
  {
    $this->productState = new RectProductConfiguration();
  }
}
