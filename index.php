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

    $resultado = $sql->select("SELECT * FROM Usuario");

    $page->setTpl('home',[
        "usuarios"=>$resultado
    ]);

});


$app->get("/admin/add", function(){

    $page = new PageAdmin();

    $page->setTpl('add');

});

$app->post("/admin/add", function(){

    $sql = new Sql();


    $sql->query("INSERT INTO Usuario (Nome, Email, Senha) VALUES (:nome,:email,:senha)", array(
        ":nome"=>$_POST["nome"],
        ":email"=>$_POST["email"],
        ":senha"=>$_POST["senha"]
    ));

    header("Location: /admin");
    exit;

});

$app->run();



?>