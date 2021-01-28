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

?>