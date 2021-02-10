<?php

Use \Classes\Page;
Use \Classes\News;


$app->get("/news/:num", function($num){

    $page = new Page([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    News::addView($num);

    require_once("pageNews.php");

});
