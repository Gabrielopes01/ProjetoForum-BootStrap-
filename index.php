<?php

require_once("vendor/autoload.php");

Use \Slim\Slim;
Use \Classes\Page;

$app = new Slim();

$app->config('debug', true);

$app->get("/", function(){

    $page = new Page();

    phpinfo();



});

$app->run();



?>