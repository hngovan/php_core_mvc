<?php


require_once __DIR__ . '/vendor/autoload.php';

use core\Application;
use Dotenv\Dotenv;

// load .env
$dotenv = Dotenv::createImmutable(dirname(__FILE__));
$dotenv->load();

// load config form file config.php
$config = require_once dirname(__FILE__) . '/app/config/app.php';

// pass config to application
$app = new Application(dirname(__FILE__), $config);

$app->db->applyMigrations();
