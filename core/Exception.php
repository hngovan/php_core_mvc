<?php
namespace core;

class Exception extends \Exception
{
  protected $statusCode;

  public function __construct(string $message = "", int $code = 0, int $statusCode = 500, \Throwable $previous = null)
  {
    parent::__construct($message, $code, $previous);
    $this->statusCode = $statusCode;
  }

  public function getStatusCode(): int
  {
    return $this->statusCode;
  }
}
