<?php
namespace app\exceptions;

use core\Exception;

class ForbiddenException extends Exception
{
  public function __construct(string $message = "Forbidden", \Throwable $previous = null)
  {
    parent::__construct($message, 0, 403, $previous);
  }
}
