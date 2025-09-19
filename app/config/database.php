<?php
return [
  'dsn' => $_ENV['DB_DSN'],
  'user' => $_ENV['DB_USER'],
  'password' => $_ENV['DB_PASSWORD'],
  // 'options' => [
  //   PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  //   PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
  //   PDO::ATTR_EMULATE_PREPARES => false,
  // ]
];
