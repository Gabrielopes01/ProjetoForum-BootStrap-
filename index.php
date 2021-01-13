<?php
session_start();



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

    $page->setTpl('home', [
    "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

});

//Home da Parte Administrativa
$app->get("/admin", function(){

    $page = new PageAdmin();

    $sql = new Sql();

    $resultado = $sql->select("SELECT TOP 10 * FROM Usuario");

    $page->setTpl('home',[
        "usuarios"=>$resultado,
        "message"=>isset($_SESSION['mensagem'])? $_SESSION['mensagem']:''
    ]);

});

//Adicioando um novo usuário
$app->get("/admin/add", function(){

    $page = new PageAdmin();

    $page->setTpl('add', [
        "erro"=>isset($_SESSION['mensagem'])? $_SESSION['mensagem']:''
    ]);

});


$app->post("/admin/add", function(){

    $sql = new Sql();

    $checkEmail = "/^[a-z0-9.\-\_]+@[a-z0-9.\-\_]+\.(com|br|.com.br|.org|.net)$/i";

    if (!preg_match($checkEmail, $_POST["email"])) {
        $_SESSION['mensagem'] = "Email Inválido";
        header("Location: /admin/add");
        exit;
    }

    if($_POST["senha"] !== $_POST["csenha"]){
        $_SESSION['mensagem'] = "Senhas não Conferem";
        header("Location: /admin/add");
        exit;
    }

    if(User::verifyEmail($_POST["email"])){
        $_SESSION['mensagem'] = "Email ja Cadastrado";
        header("Location: /admin/add");
        exit;
    }

    $sql->query("INSERT INTO Usuario (Nome, Email, Senha) VALUES (:nome,:email,:senha)", array(
        ":nome"=>$_POST["nome"],
        ":email"=>$_POST["email"],
        ":senha"=>password_hash($_POST["senha"], PASSWORD_DEFAULT)
    ));


    $_SESSION['mensagem'] = "Usuário Cadastrado com Sucesso";
    header("Location: /admin");
    exit;

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

    $_SESSION['mensagem'] = "Usuário Alterado com Sucessoo";
    header("Location: /admin");
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

    $_SESSION['mensagem'] = "Usuário Deletado com Sucesso";
    header("Location: /admin");
    exit;

});


$app->get("/login", function(){

    $page = new PageAdmin();

    $page->setTpl('login', [
        'erro'=>isset($_SESSION['mensagem'])? $_SESSION['mensagem']:''
    ]);

});

$app->post("/login", function(){

    User::verifyLogin($_POST["email"],$_POST["senha"]);


});

$app->get("/logout", function(){

    User::logout();

    header("Location: /");
    exit;

});

$app->run();



?>