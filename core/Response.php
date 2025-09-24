<?php
namespace core;

class Response
{
  protected int $statusCode = 200;

  public function setStatusCode(int $code): void
  {
    $this->statusCode = $code;
    http_response_code($code);
  }

  public function getStatusCode(): int
  {
    return $this->statusCode;
  }

  public function redirect($url)
  {
    header("Location: $url");
  }
}
