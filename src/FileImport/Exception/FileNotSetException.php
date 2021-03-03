<?php

namespace App\FileImport\Exception;

use Exception;

final class FileNotSetException extends Exception
{
  public function __construct()
  {
    $this->message = 'File is null. Did you forget to call setFile($file) prior to import() ?';
  }
}
