<?php

require dirname(__DIR__) . '/vendor/autoload.php';

$whoops = new Whoops\Run();
$whoops->register();
$whoops->pushHandler(new Whoops\Handler\PrettyPageHandler());

require_once('../App/Routes.php');