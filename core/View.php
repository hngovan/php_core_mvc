<?php
namespace core;

class View
{
  public string $title = '';

  public function renderView($view, array $params): array|string
  {

    if (isset($params['title'])) {
      $this->title = $params['title'];
    }

    $layoutName = Application::$app->layout;
    if (Application::$app->controller) {
      $layoutName = Application::$app->controller->layout;
    }

    $viewContent = $this->renderViewOnly($view, $params);

    ob_start();
    include_once Application::$ROOT_DIR . "/app/views/layouts/$layoutName.php";
    $layoutContent = ob_get_clean();

    return str_replace('{{ content }}', $viewContent, $layoutContent);

  }

  public function renderComponent($componentName, array $params = []): bool|string
  {
    extract($params);
    ob_start();
    include_once Application::$ROOT_DIR . "/app/views/components/{$componentName}.php";
    return ob_get_clean();
  }

  public function renderViewOnly($view, array $params): bool|string
  {
    foreach ($params as $key => $value) {
      $$key = $value;
    }
    ob_start();
    include_once Application::$ROOT_DIR . "/app/views/$view.php";
    return ob_get_clean();
  }
}
