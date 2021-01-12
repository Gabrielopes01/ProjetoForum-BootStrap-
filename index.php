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
        "usuarios"=>$resultado,
        "message"=>""
    ]);

});


$app->get("/admin/add", function(){

    $page = new PageAdmin();

    $page->setTpl('add', [
        "erro"=>""
    ]);

});

$app->post("/admin/add", function(){

    $sql = new Sql();

    $checkEmail = "/^[a-z0-9.\-\_]+@[a-z0-9.\-\_]+\.(com|br|.com.br|.org|.net)$/i";

    if (!preg_match($checkEmail, $_POST["email"])) {
        getError("Email Invalido");
        exit;
    }

    $sql->query("INSERT INTO Usuario (Nome, Email, Senha) VALUES (:nome,:email,:senha)", array(
        ":nome"=>$_POST["nome"],
        ":email"=>$_POST["email"],
        ":senha"=>$_POST["senha"]
    ));


    getSucess("Usuário Cadastrado com Sucesso");
    exit;

});


$app->get("/admin/edit/:id", function($id){

    $page = new PageAdmin();

    $resultado = selectById($id);


    $page->setTpl('edit', [
        "usuario"=>$resultado
    ]);

});

$app->post("/admin/edit/:id", function($id){

    $sql = new Sql();


    $sql->query("UPDATE Usuario SET Nome = :nome, Email = :email WHERE Id = :id", array(
        ":nome"=>$_POST["nome"],
        ":email"=>$_POST["email"],
        ":id"=>$_POST["id"]
    ));

    getSucess("Usuário Alterado com Sucesso");
    exit;

});

$app->run();



?>