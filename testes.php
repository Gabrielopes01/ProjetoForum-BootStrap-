<?php

Use \Classes\Page;
Use \Novo\Teste;  //Os namespaces precisam ser definidos no composer
Use \Classes\News;

require_once('function.php');

$app->get("/0/testes", function(){

    $page = new Page([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    $noticias = News::getALLNews();

    foreach ($noticias as $linha=>$coluna) {

        $images = imageExists($noticias[$linha]["Imagem"]);

        if($images) {
            echo $noticias[$linha]["Imagem"];
        }
    }

    $page->setTpl("testes", [
        "erro"=>isset($_SESSION["mensagem"]) ? $_SESSION["mensagem"] : "",
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

});


$app->post("/0/testes", function(){

    $images = imageExists("image");

    var_dump($images);

    Teste::say();

    var_dump($_POST["data"]);
    exit;

    $_SESSION["mensagem"] = "Acessou o Post em ".$_POST["data"];

    header("Location: /0/testes");
    exit;

});
