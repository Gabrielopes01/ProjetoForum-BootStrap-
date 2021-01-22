<?php

Use \Slim\Slim;
Use \Classes\Page;
Use \Classes\PageAdmin;
Use \Classes\Sql;

function formatDate($date){

    $dateC = strtotime($date);
    $newDate = date("d/m/Y",$dateC);

    return $newDate;

}


function getNameCategorie($id){

    $sql = new Sql;

    $resultado = $sql->select("SELECT * FROM Categoria WHERE id = :id", [
        ":id"=>$id
    ]);

    return $resultado[0]["Nome"];

}

function getNameUser($id){

    $sql = new Sql;

    $resultado = $sql->select("SELECT * FROM Usuario WHERE id = :id", [
        ":id"=>$id
    ]);

    return $resultado[0]["Nome"];

}

function removeTags($text){

    $newText = strip_tags($text);
    return $newText;

}


function generatePages($num){

    $pages = [];

    for ($i = 1; $i <= $num; $i++){
        array_push($pages, $i);
    }

    return $pages;

}

?>