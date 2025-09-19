<?php

namespace app\models;

use core\Model;

class User extends Model
{
  public int $id = 0;
  public string $first_name = '';
  public string $last_name = '';
  public string $email = '';
  public string $password = '';
  public string $confirm_password = '';
  public int $status = 0;
  public string $created_at = '';

  public static function tableName(): string
  {
    return 'users';
  }

  public static function primaryKey()
  {
    return 'id';
  }

  public function rules(): array
  {
    return [
      'first_name' => [self::RULE_REQUIRED],
      'last_name' => [self::RULE_REQUIRED],
      'email' => [
        self::RULE_REQUIRED,
        self::RULE_EMAIL,
        [
          self::RULE_UNIQUE,
          'class' => self::class
        ]
      ],
      'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 8]],
      'confirm_password' => [self::RULE_REQUIRED, [self::RULE_MATCH, 'match' => 'password']],
    ];
  }

  public function save(): bool|string
  {
    $this->password = password_hash($this->password, PASSWORD_DEFAULT);

    $data = [
      'first_name' => $this->first_name,
      'last_name' => $this->last_name,
      'email' => $this->email,
      'password' => $this->password,
    ];

    return parent::create($data);
  }

  public function findByEmail(string $email): mixed
  {
    return $this->findOne(['email' => $email]);
  }

  public static function findById($where): mixed
  {
    $instance = new static();
    return $instance->findOne($where);
  }

  public function validatePassword($password): bool
  {
    return password_verify($this->password, $password);
  }

  public function getDisplayName(): string
  {
    return $this->first_name . ' ' . $this->last_name;
  }
}
