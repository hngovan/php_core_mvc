<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use core\Application;

use app\controllers\ContactController;
use app\controllers\HomeController;
use app\controllers\AuthController;
use app\controllers\PostsController;

// load .env
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

// load config form file config.php
$config = require_once dirname(__DIR__) . '/app/config/app.php';

// pass config to application
$app = new Application(dirname(__DIR__), $config);

$app->on(Application::EVENT_BEFORE_REQUEST, function () {
  // echo "Before request from second installation";
});

// routes

$app->router->get('/register', [AuthController::class, 'register']);
$app->router->post('/register', [AuthController::class, 'register']);
$app->router->get('/login', [AuthController::class, 'login']);
$app->router->post('/login', [AuthController::class, 'login']);
$app->router->get('/logout', [AuthController::class, 'logout']);
$app->router->get('/profile', [AuthController::class, 'profile']);

$app->router->get('/', [HomeController::class, 'index']);

$app->router->get('/posts', [PostsController::class, 'index']);
$app->router->get('/posts/create', [PostsController::class, 'create']);
$app->router->post('/posts/create', [PostsController::class, 'create']);
$app->router->get('/posts/{id:\d+}', [PostsController::class, 'view']);
$app->router->get('/posts/{id:\d+}/edit', [PostsController::class, 'edit']);
$app->router->post('/posts/{id:\d+}/edit', [PostsController::class, 'edit']);
$app->router->post('/posts/{id:\d+}/delete', [PostsController::class, 'delete']);

$app->router->get('/contact', [ContactController::class, 'index']);
$app->router->post('/contact', [ContactController::class, 'handleContacts']);


$app->run();
