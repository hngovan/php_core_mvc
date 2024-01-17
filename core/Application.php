<?php

namespace core;

/**
 * Class Application
 * 
 * @author hngovan
 * @package namespace app\core;
 */
class Application
{
  public static string $ROOT_DIR;
  public static Application $app;
  public Router $router;
  public Request $request;
  public Response $response;
  public function __construct($rootPath)
  {
    self::$ROOT_DIR = $rootPath;
    self::$app = $this;
    $this->request = new Request();
    $this->response = new Response();
    $this->router = new Router($this->request, $this->response);
  }

  public function run()
  {
    echo $this->router->resolve();
  }
}