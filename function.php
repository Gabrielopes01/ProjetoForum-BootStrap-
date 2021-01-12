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


function getError($error){

    $page = new PageAdmin();

    $page->setTpl('add', [
        "erro"=>$error
    ]);
}


function getSucess($message){

    $page = new PageAdmin();

    $sql = new Sql();

    $resultado = $sql->select("SELECT * FROM Usuario");

    $page->setTpl('home',[
        "usuarios"=>$resultado,
        "message"=>$message
    ]);

}


function selectById($id){

    $sql = new Sql;

    $resultado = $sql->select("SELECT * FROM Usuario WHERE id = :id", [
        ":id"=>$id
    ]);

    return $resultado[0];

}


?>