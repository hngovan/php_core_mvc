<?php

namespace core;

class router
{
  public Request $request;
  public Response $response;
  protected array $routes = [];
  /**
   * routes constructor
   * 
   * @param \core\Request $request
   * @param \core\Response $response
   */
  public function __construct(Request $request, Response $response)
  {
    $this->request = $request;
    $this->response = $response;
  }

  public function get(string $path, $callback)
  {
    $this->routes['get'][$path] = $callback;
  }

  public function post(string $url, $callback)
  {
    $this->routes['post'][$url] = $callback;
  }

  public function resolve()
  {
    $path = $this->request->getPath();
    $method = $this->request->getMethod();
    $callback = $this->routes[$method][$path] ?? false;

    if (!$callback) {
      $this->response->statusCode(404);
      return $this->renderView('_404');
    }

    if (is_string($callback)) {
      return $this->renderView($callback);
    }

    if (is_array($callback)) {
      $callback[0] = new $callback[0]();
    }

    return call_user_func($callback);
  }

  public function renderView($view, $params = [])
  {
    $layoutContent = $this->layoutContent();
    $viewContent = $this->renderOnlyView($view, $params);
    return str_replace('{{ content }}', $viewContent, $layoutContent);
  }

  public function renderContent($viewContent, $params = [])
  {
    $layoutContent = $this->layoutContent();
    return str_replace('{{ content }}', $viewContent, $layoutContent);
  }

  protected function layoutContent()
  {
    ob_start();
    include_once Application::$ROOT_DIR . "/app/views/layouts/main.php";
    return ob_get_clean();
  }

  protected function renderOnlyView($view, $params = [])
  {
    foreach ($params as $key => $value) {
      $$key = $value;
    }
    ob_start();
    include_once Application::$ROOT_DIR . "/app/views/$view.php";
    return ob_get_clean();
  }
}