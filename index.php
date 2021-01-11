<?php

require_once("vendor/autoload.php");

Use \Slim\Slim;

$app = new Slim();

$app->config('debug', true);

$app->get("/", function(){

    phpinfo();

});

$app->run();



?>