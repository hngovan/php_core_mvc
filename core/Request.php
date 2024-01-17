<?php
namespace app\core;

class Request
{
  private array $routeParams = [];
  
  public function getMethod()
  {
    return strtolower($_SERVER['REQUEST_METHOD']);
  }

  public function getPath()
  {
    $path = $_SERVER['REQUEST_URI'] ?? '/';
    $position = strpos($path, '?');
    if ($position !== false) {
      $path = substr($path, 0, $position);
    }
    return $path;
  }
}