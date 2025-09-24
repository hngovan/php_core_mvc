<?php

namespace core;

use app\exceptions\NotFoundException;

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

  public function getRouteMap($method): array
  {
    return $this->routes[$method] ?? [];
  }

  public function getCallback(): mixed
  {
    $method = $this->request->getMethod();
    $url = $this->request->getUrl();

    // Trim slashes
    $url = trim($url, '/');

    // Get all routes for current request method
    $routes = $this->getRouteMap($method);

    $routeParams = false;

    // Start iterating registered routes
    foreach ($routes as $route => $callback) {
      // Trim slashes
      $route = trim($route, '/');
      $routeNames = [];

      if (!$route) {
        continue;
      }

      // Find all route names from route and save in $routeNames
      if (preg_match_all('/\{(\w+)(:[^}]+)?}/', $route, $matches)) {
        $routeNames = $matches[1];
      }

      // Convert route name into regex pattern
      $routeRegex = "@^" . preg_replace_callback('/\{\w+(:([^}]+))?}/', fn($m) => isset($m[2]) ? "({$m[2]})" : '(\w+)', $route) . "$@";

      // Test and match current route against $routeRegex
      if (preg_match_all($routeRegex, $url, $valueMatches)) {
        $values = [];
        for ($i = 1; $i < count($valueMatches); $i++) {
          $values[] = $valueMatches[$i][0];
        }
        $routeParams = array_combine($routeNames, $values);

        $this->request->setRouteParams($routeParams);
        return $callback;
      }
    }

    return false;
  }

  public function resolve(): mixed
  {
    $url = $this->request->getUrl();
    $method = $this->request->getMethod();

    // First try exact match
    $callback = $this->routes[$method][$url] ?? false;

    // If no exact match, try dynamic route matching
    if ($callback === false) {
      $callback = $this->getCallback();
    }

    // If still no route found, throw 404
    if ($callback === false) {
      throw new NotFoundException();
    }

    if (is_string($callback)) {
      return $this->renderView($callback);
    }

    if (is_array($callback)) {
      /**
       * @var $controller \core\Controller
       */
      $controller = new $callback[0];
      $controller->action = $callback[1];
      Application::$app->controller = $controller;

      $middlewares = $controller->getMiddlewares();
      foreach ($middlewares as $middleware) {
        $middleware->execute();
      }

      $callback[0] = $controller;
    }

    return call_user_func($callback, $this->request, $this->response);
  }

  public function renderView($view, $params = []): array|string
  {
    return Application::$app->view->renderView($view, $params);
  }

  protected function renderOnlyView($view, $params = []): bool|string
  {
    return Application::$app->view->renderViewOnly($view, $params);
  }
}
