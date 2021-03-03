<?php

namespace App\FileImport\Exception;

use Exception;

final class InvalidMimeTypeException extends Exception
{
  public function __construct()
  {
    $this->message = "Given file has invalid mime type";
  }
}
