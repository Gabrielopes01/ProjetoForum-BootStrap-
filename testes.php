<?php

Use \Classes\Page;

$app->get("/0/testes", function(){

    $page = new Page([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);


    $page->setTpl("testes", [
        "erro"=>isset($_SESSION["mensagem"]) ? $_SESSION["mensagem"] : "",
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

});


$app->post("/0/testes", function(){

    $_SESSION["mensagem"] = "Acessou o Post";

    header("Location: /0/testes");
    exit;

});


?>