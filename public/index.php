<?php
define('ROOT', dirname(__DIR__));
define('PERPAGE',5);
define('BASE_URL','http://localhost/silex/public');
date_default_timezone_set('Asia/Ho_Chi_Minh');
use Symfony\Component\HttpFoundation\Request;

require ROOT . '/app/app.php';
require ROOT . '/app/routes.php';
Request::enableHttpMethodParameterOverride();

$app->run();