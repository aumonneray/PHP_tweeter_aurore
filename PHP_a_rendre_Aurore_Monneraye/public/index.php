<?php


namespace web;


define('DS', DIRECTORY_SEPARATOR);
//define('ROOT', dirname(__FILE__) . DS . '..' . DS);
define('ROOT', dirname(__FILE__) );

use app\src\AutoLoader;

require_once __DIR__ . '/../app/src/AutoLoader.php';
AutoLoader::register();

var_dump(DS);
var_dump(ROOT);
$app = require_once __DIR__ . '/../app/bootstrap.php';
$app->run();