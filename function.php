<?php

Use \Slim\Slim;
Use \Classes\Page;
Use \Classes\PageAdmin;
Use \Classes\Sql;
Use \Classes\Favorite;

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

    return $resultado[0]["nome"];

}

function getNameUser($id){

    $sql = new Sql;

    $resultado = $sql->select("SELECT * FROM Usuario WHERE id = :id", [
        ":id"=>$id
    ]);

    return $resultado[0]["nome"];

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


function isInFavorite($id){

    $favorito = Favorite::verifyFavorite($id);

    if($favorito == 1){
       $_SESSION["favorito"] = 1;
    } else if ($favorito == 0) {
        $_SESSION["favorito"] = 0;
    } else {
        $_SESSION["favorito"] = 2;
    }

}


function imageExists($image){

    $images = scandir("res/site/images");

    if (in_array($image, $images)){
        return $image;
    }

    return "../defaults/default.jpg";

}


function lenghtStr($str){

    return strlen($str);

}


function getAutor($id) {

    $sql = new Sql();

    $nome = $sql->select("SELECT Usuario.nome from Noticia INNER JOIN Usuario ON Noticia.id_usuario = Usuario.id WHERE Noticia.Id = :id", [
        ':id'=>$id
    ]);

    return $nome[0]["nome"];
}
