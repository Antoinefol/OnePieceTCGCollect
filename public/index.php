<?php

use OnePieceTCGCollect\Autoloader;
use OnePieceTCGCollect\src\Core\Router;
use OnePieceTCGCollect\src\Core\EnvLoader;

include '../Autoloader.php';
Autoloader::register();
EnvLoader::load();
$route = new Router();

$route->routes();