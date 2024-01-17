<?php

namespace app\controllers;

use core\Controller;

class HomeController extends Controller
{
  public function index()
  {
    return $this->render('home', [
      'name' => 'Dashboard Home',
    ]);
  }
}
