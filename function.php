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


function getError($error, $tpl = 'add'){

    $page = new PageAdmin();

    $page->setTpl($tpl, [
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


function login($name){

    $page = new Page();

    $page->setTpl('home', [
        "nome"=>$name
    ]);

}




?>