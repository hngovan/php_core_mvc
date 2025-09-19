<?php

namespace core;

use core\Middleware;

class Controller
{
    public string $layout = 'main';
    public string $action = '';

    /**
     * @var \core\Middleware[]
     */
    protected array $middlewares = [];

    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    public function render($view, $params = []): string
    {
        return Application::$app->router->renderView($view, $params);
    }

    public function registerMiddleware(Middleware $middleware)
    {
        $this->middlewares[] = $middleware;
    }

    /**
     * @return \core\Middleware[]
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }
}
