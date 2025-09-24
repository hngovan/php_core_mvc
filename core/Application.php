<?php

namespace core;

/**
 * Class Application
 * 
 * @package namespace app\core;
 */
class Application
{
  public const string EVENT_BEFORE_REQUEST = 'beforeRequest';
  public const string EVENT_AFTER_REQUEST = 'afterRequest';
  protected array $eventListeners = [];

  public static string $ROOT_DIR;
  public static Application $app;
  public string $userClass;
  public string $layout = 'main';
  public Router $router;
  public Request $request;
  public Session $session;
  public Response $response;
  public ?Controller $controller = null;
  public Database $db;
  public View $view;
  public $user;

  public function __construct($rootPath, $config)
  {
    $this->user = null;
    $this->userClass = $config['userClass'];
    self::$ROOT_DIR = $rootPath;
    self::$app = $this;
    $this->request = new Request();
    $this->response = new Response();
    $this->router = new Router($this->request, $this->response);
    $this->db = new Database($config['db']);
    $this->session = new Session();
    $this->view = new View();

    $userId = Application::$app->session->get('user');
    if ($userId) {
      $key = $this->userClass::primaryKey();

      // Create instance and call instance method
      $userInstance = new $this->userClass();
      $this->user = $userInstance->findOne([$key => $userId]);
    }
  }

  public function isGuest(): bool
  {
    return !self::$app->user;
  }

  public function login($user): bool
  {
    $this->user = $user;
    $className = get_class($user);
    $primaryKey = $className::primaryKey();
    $value = $user->{$primaryKey};
    $this->session->set('user', $value);

    return true;
  }

  public function logout()
  {
    $this->session->remove('user');
  }

  public function run()
  {
    $this->triggerEvent(self::EVENT_BEFORE_REQUEST);
    try {
      echo $this->router->resolve();
    } catch (\core\Exception $e) {
      $this->controller = new Controller();  // Ensure controller is set
      $this->controller->setLayout('error');
      echo $this->router->renderView('/errors/_error', [
        'exception' => $e,
        'statusCode' => $e->getStatusCode()
      ]);
    }
  }

  /**
   * Trigger event
   */
  public function triggerEvent($eventName)
  {
    $callbacks = $this->eventListeners[$eventName] ?? [];
    foreach ($callbacks as $callback) {
      call_user_func($callback);
    }
  }

  public function on($eventName, $callback)
  {
    $this->eventListeners[$eventName][] = $callback;
  }
}
