<?php
namespace app\exceptions;

use core\Exception;

class NotFoundException extends Exception
{
  public function __construct(string $message = "Page not found", \Throwable $previous = null)
  {
    parent::__construct($message, 0, 404, $previous);
  }
}
