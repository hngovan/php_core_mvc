<?php

namespace app\controllers;

use app\middleware\AuthMiddleware;
use core\Controller;

class HomeController extends Controller
{
  public function __construct()
  {
    $this->registerMiddleware(new AuthMiddleware());
  }

  public function index()
  {
    return $this->render('home', [
      'title' => 'Dashboard',
    ]);
  }
}
