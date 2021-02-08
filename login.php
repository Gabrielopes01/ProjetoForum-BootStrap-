<?php

Use \Classes\PageAdmin;
Use \Classes\User;

$app->get("/0/login", function(){

    $page = new PageAdmin([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    $page->setTpl('login', [
        'erro'=>isset($_SESSION['mensagem'])? $_SESSION['mensagem']:''
    ]);

});

$app->post("/0/login", function(){

    User::verifyLogin($_POST["email"],$_POST["senha"]);

});

$app->get("/0/logout", function(){

    User::logout();

});

$app->get("/0/signin", function(){

    $page = new PageAdmin([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    $page->setTpl('signin', [
        'erro'=>isset($_SESSION['mensagem'])? $_SESSION['mensagem']:'',
        "nome"=>isset($_SESSION['paramUser']['nome']) ? $_SESSION['paramUser']['nome'] : '',
        "email"=>isset($_SESSION['paramUser']['email']) ? $_SESSION['paramUser']['email'] : ''
    ]);

    $_SESSION['paramUser'] = "";

});

$app->post("/0/signin", function(){

    $_SESSION['paramUser'] = $_POST;

    User::addUser($_POST, "/0/login", "/0/signin");

});

?>