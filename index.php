<?php

require_once("vendor/autoload.php");
require_once("function.php");

Use \Slim\Slim;
Use \Classes\Page;
Use \Classes\PageAdmin;
Use \Classes\Sql;
Use \Classes\User;

$app = new Slim();

$app->config('debug', true);

//Home de Site
$app->get("/", function(){

    $page = new Page();

    $page->setTpl('home');

});

//Home da Parte Administrativa
$app->get("/admin", function(){

    $page = new PageAdmin();

    $sql = new Sql();

    $resultado = $sql->select("SELECT TOP 10 * FROM Usuario");

    $page->setTpl('home',[
        "usuarios"=>$resultado,
        "message"=>""
    ]);

});

//Adicioando um novo usuário
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

    if($_POST["senha"] !== $_POST["csenha"]){
        getError("Senhas não Conferem");
        exit;
    }

    if(User::verifyEmail($_POST["email"])){
        getError("Email já Cadastrado");
        exit;
    }

    $sql->query("INSERT INTO Usuario (Nome, Email, Senha) VALUES (:nome,:email,:senha)", array(
        ":nome"=>$_POST["nome"],
        ":email"=>$_POST["email"],
        ":senha"=>password_hash($_POST["senha"], PASSWORD_DEFAULT)
    ));


    getSucess("Usuário Cadastrado com Sucesso");
    exit;

});

//Mensagens na Tela
$app->get("/admin/:message", function($message){

    $page = new PageAdmin();

    $sql = new Sql();

    $resultado = $sql->select("SELECT TOP 10 * FROM Usuario");

    $page->setTpl('home',[
        "usuarios"=>$resultado,
        "message"=>$message
    ]);

});

//Editar usuário
$app->get("/admin/edit/:id", function($id){

    $page = new PageAdmin();

    $user = new User();

    $resultado = $user->getUserById($id);

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

//    getSucess("Usuário Alterado com Sucesso");
    header("Location: /admin/Usuário Alterado com Sucesso");
    exit;

});

//Deletar Usuário
$app->get("/admin/delete/:id", function($id){

    $page = new PageAdmin();

    $page->setTpl('delete');

});

$app->post("/admin/delete/:id", function($id){

    $sql = new Sql();

    $sql->query("DELETE FROM Usuario WHERE Id = :id", array(
        ":id"=>$id
    ));

//    getSucess("Usuário Deletado com Sucesso");
    header("Location: /admin/Usuário Deletado com Sucesso");
    exit;

});


$app->get("/login", function(){

    $page = new PageAdmin();

    $page->setTpl('login', [
        'erro'=>""
    ]);

});

$app->post("/login", function(){

    User::verifyLogin($_POST["email"],$_POST["senha"]);

});

$app->run();



?>