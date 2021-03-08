<?php

namespace App\Product;

class Shape
{
  const RECTANGULAR = 0;
  const CIRCULAR = 1;

  private const TEXT_SHAPES = [
    "rectangulaire" => self::RECTANGULAR,
    "circulaire" => self::CIRCULAR
  ];

  public static function getShape(string $name): ?int
  {
    $search = strtolower($name);

    if (!array_key_exists($search, self::TEXT_SHAPES)) {
      return null;
    }

    return self::TEXT_SHAPES[$search];
  }
}
