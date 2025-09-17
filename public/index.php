<?php

use OnePieceTCGCollect\Autoloader;
use OnePieceTCGCollect\src\Core\Router;

include '../Autoloader.php';
Autoloader::register();

$route = new Router();

$route->routes();