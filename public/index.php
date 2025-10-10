<?php
require '../core/Autoloader.php';

use Core\Autoloader;

Autoloader::register();

require '../config/config.php';
require '../config/routes.php';
require '../config/database.php';
require '../core/utilities/utilities.php';

use Core\Router;
use Core\Request;
use Core\SessionManager;

// TODO CSRF
SessionManager::start();

$request = new Request();

$router = new Router($request);
$router->dispatch();
