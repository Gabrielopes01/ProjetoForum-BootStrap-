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

    var_dump($_POST["data"]);
    exit;

    $_SESSION["mensagem"] = "Acessou o Post em ".$_POST["data"];

    header("Location: /0/testes");
    exit;

});


?>