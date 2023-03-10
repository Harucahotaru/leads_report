<?php

require_once __DIR__ . '/vendor/autoload.php';

$controller = $_GET['controller'];
$action = $_GET['action'];

require_once 'src/controllers/' . $controller.'.php';
$controller = '\app\controllers\\' . $controller;
$instance = new $controller();
$instance->$action();