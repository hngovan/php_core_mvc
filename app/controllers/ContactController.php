<?php

namespace app\controllers;

use core\Controller;

class ContactController extends Controller
{
  public function index()
  {
    return $this->render('contact', [
      'name' => 'Contact Form'
    ]);
  }

  public function handleContacts()
  {
  }
}
