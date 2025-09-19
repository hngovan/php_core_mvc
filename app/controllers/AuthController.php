<?php

namespace app\controllers;

use app\middleware\AuthMiddleware;
use app\models\Login;
use app\models\User;
use core\Controller;
use core\Request;
use core\Application;
use core\Response;

class AuthController extends Controller
{
  public function __construct()
  {
    $this->registerMiddleware(new AuthMiddleware(['profile']));
  }

  public function register(Request $request): string
  {
    $user = new User();
    if ($request->isPost()) {
      $user->loadData($request->getBody());
      if ($user->validate() && $user->save()) {
        Application::$app->session->setFlash('success', 'Registration successful!');
        Application::$app->response->redirect('/login');
        return 'Register Successfully!';
      }
    }

    $this->setLayout('auth');

    return $this->render('auth/register', [
      'title' => 'Register',
      'model' => $user
    ]);
  }

  public function login(Request $request): string
  {
    $loginForm = new Login();

    if ($request->isPost()) {
      $loginForm->loadData($request->getBody());

      if ($loginForm->validate() && $loginForm->login()) {
        Application::$app->response->redirect('/');
        return 'Login Successful!';
      }
    }

    $this->setLayout('auth');

    return $this->render('auth/login', [
      'title' => 'Login',
      'model' => $loginForm
    ]);
  }

  public function logout(Request $request, Response $response)
  {
    Application::$app->logout();
    $response->redirect('/login');
  }

  public function profile()
  {
    return $this->render('auth/profile', [
      'title' => 'Profile'
    ]);
  }
}
