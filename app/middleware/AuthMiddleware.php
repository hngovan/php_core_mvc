<?php
namespace app\middleware;

use app\exceptions\ForbiddenException;
use core\Application;
use core\Middleware;


class AuthMiddleware extends Middleware
{
  protected array $actions = [];

  public function __construct($actions = [])
  {
    $this->actions = $actions;
  }

  public function execute(): void
  {
    if (!Application::$app->isGuest()) {
      if (empty($this->actions) || in_array(Application::$app->controller->action, $this->actions)) {
        throw new ForbiddenException();
      }
    }
  }
}
