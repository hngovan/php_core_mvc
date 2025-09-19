<?php
namespace core;

class Request
{
  private array $routeParams = [];

  public function getMethod(): string
  {
    return strtolower($_SERVER['REQUEST_METHOD']);
  }

  public function getUrl()
  {
    $path = $_SERVER['REQUEST_URI'];
    $position = strpos($path, '?');
    if ($position !== false) {
      $path = substr($path, 0, $position);
    }
    return $path;
  }

  public function getPath(): string
  {
    $path = $_SERVER['REQUEST_URI'] ?? '/';
    $position = strpos($path, '?');
    if ($position !== false) {
      $path = substr($path, 0, $position);
    }
    return $path;
  }

  public function isPost(): bool
  {
    return $this->getMethod() === 'post';
  }

  public function isGet(): bool
  {
    return $this->getMethod() === 'get';
  }

  /**
   * Get raw input (for JSON requests)
   */
  public function getRawBody(): string
  {
    return file_get_contents('php://input');
  }

  /**
   * get JSON data
   */
  public function getJson(): array
  {
    $raw = $this->getRawBody();
    return json_decode($raw, true) ?? [];
  }

  public function getBody(): array
  {
    $data = [];
    if ($this->isGet()) {
      foreach ($_GET as $key => $value) {
        $data[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
      }
    }
    if ($this->isPost()) {
      foreach ($_POST as $key => $value) {
        $data[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
      }
    }
    return $data;

  }

  /**
   * @param $params
   * @return self
   */
  public function setRouteParams($params): static
  {
    $this->routeParams = $params;
    return $this;
  }

  public function getRouteParams()
  {
    return $this->routeParams;
  }

  public function getRouteParam($param, $default = null)
  {
    return $this->routeParams[$param] ?? $default;
  }
}
