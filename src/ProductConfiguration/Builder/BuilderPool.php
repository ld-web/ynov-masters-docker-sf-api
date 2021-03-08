<?php

namespace App\ProductConfiguration\Builder;

class BuilderPool
{
  private array $pool = [];

  public function get(string $class): AbstractBuilder
  {
    //TODO: Check $class is_subclass_of AbstractBuilder::class
    if (!array_key_exists($class, $this->pool)) {
      $this->pool[$class] = new $class();
    }
    return $this->pool[$class];
  }
}
