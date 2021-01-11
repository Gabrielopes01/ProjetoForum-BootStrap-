<?php

require_once("vendor/autoload.php");
require_once("function.php");

Use \Slim\Slim;
Use \Classes\Page;
Use \Classes\PageAdmin;
Use \Classes\Sql;

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

    $sql = new Sql();

    $resultado = $sql->select("SELECT Nome,Email,Data FROM Usuario");

    $page->setTpl('home',[
        "usuarios"=>$resultado
    ]);

});

$app->run();



?>