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


function verifyImage($archive){

    $permitido = array("png", "jpg", "jpeg");
    $extensao = pathinfo($archive["imagem"]["name"], PATHINFO_EXTENSION);

    if (in_array($extensao, $permitido)){

        $pasta = "res/site/images/";
        $temporario = $archive["imagem"]["tmp_name"];
        $novoNome = uniqid().".$extensao";

        $_SESSION["nomeImagem"] = $novoNome;

        move_uploaded_file($temporario, $pasta.$novoNome);

        return true;

    } else {
        return false;
    }

}


function generatePag($num){

    $pags = [];

    for ($i=1; $i < $num; $i++) {
        array_push($pags, $i);
    }

    return $pags;

}


?>