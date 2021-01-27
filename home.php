<?php

Use \Classes\Page;
Use \Classes\News;

//Home de Site
$app->get("/", function(){

    $page = new Page([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    $noticias = News::getALLNews();

    $page->setTpl('home', [
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:'',
        "noticias"=>$noticias
    ]);

});

?>