<?php

Use \Classes\Page;
Use \Classes\News;

//Home de Site
$app->get("/", function(){

    header("Location: /0");
    exit;

});

$app->get("/:num", function($num){

    $page = new Page([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    $noticias = News::getALLNews();

    $resultado = [];

    for ($i = 24 * $num; $i < 24 * ($num + 1); $i++) {
        if($i == count($noticias) || $i > count($noticias)){
            break;
        } else {
            array_push($resultado, $noticias[$i]);
        }
    }


    $page->setTpl('home', [
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:'',
        "noticias"=>$resultado
    ]);

});

?>