<?php
namespace app\models;

use core\Application;
use core\Model;

class Login extends User
{
  public string $email = '';
  public string $password = '';

  public function rules(): array
  {
    return [
      'email' => [self::RULE_REQUIRED],
      'password' => [self::RULE_REQUIRED],
    ];
  }

  public function login(): bool
  {
    $user = $this->findByEmail($this->email);

    if (!$user) {
      $this->addError('email', 'User does not exist with this email address');
      return false;
    }

    if (!$this->validatePassword($user->password)) {
      $this->addError('password', 'Password is incorrect');
      return false;
    }

    return Application::$app->login($user);
  }
}
