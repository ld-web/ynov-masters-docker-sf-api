<?php

namespace App\ProductConfiguration\Builder;

use App\Entity\ProductConfiguration;

class BuilderDirector
{
  private ?AbstractBuilder $builder = null;

  public function setBuilder(AbstractBuilder $builder): void
  {
    $this->builder = $builder;
  }

  public function make(array $productConfigurationData): ?ProductConfiguration
  {
    $this->builder->reset();
    $this->builder->stepGenericAttributes($productConfigurationData);
    $this->builder->stepSpecificAttributes($productConfigurationData);

    return $this->builder->getProductConfiguration();
  }
}
