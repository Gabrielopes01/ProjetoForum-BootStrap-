<?php

Use \Classes\Page;


$app->get("/news/:num", function($num){

    $page = new Page([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);



    require_once("pageNews.php");

});


?>