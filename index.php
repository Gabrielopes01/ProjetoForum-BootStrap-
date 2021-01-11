<?php

require_once("vendor/autoload.php");

Use \Slim\Slim;
Use \Classes\Page;
Use \Classes\PageAdmin;

$app = new Slim();

$app->config('debug', true);

$app->get("/", function(){

    $page = new Page();

    $page->setTpl('home', [
        "nome"=>"Gabriel"
    ]);

});


$app->get("/admin", function(){

    $page = new PageAdmin();

    $page->setTpl('home');

});

$app->run();



?>