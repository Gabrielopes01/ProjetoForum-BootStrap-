<?php

Use \Classes\PageAdmin;
Use \Classes\User;
Use \Classes\Categorie;

//Home da Parte Administrativa
$app->get("/adminCat", function(){

    $page = new PageAdmin([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    User::checkLogin();

    $categorias = Categorie::getCategorie();

    $page->setTpl('categorie',[
        "categorias"=>$categorias,
        "message"=>isset($_SESSION['mensagem'])? $_SESSION['mensagem']:'',
        "filtros"=>['nome' => ""]
    ]);

});

//Usando Filtros
$app->post("/adminCat", function(){

    $page = new PageAdmin([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    User::checkLogin();

    $parametros = $_POST;

    $resultado = Categorie::filter($parametros);

    $page->setTpl('categorie',[
        "categorias"=>$resultado[0],
        "message"=>isset($_SESSION['mensagem'])? $_SESSION['mensagem']:'',
        "filtros"=>$resultado[1]
    ]);

});

//Adicioando um novo usuário
$app->get("/adminCat/add", function(){

    $page = new PageAdmin([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    User::checkLogin();

    $page->setTpl('addCat', [
        "erro"=>isset($_SESSION['mensagem'])? $_SESSION['mensagem']:''
    ]);

});


$app->post("/adminCat/add", function(){

    $parametros = $_POST;

    Categorie::addCategorie($parametros);

});

//Editar usuário
$app->get("/adminCat/edit/:id", function($id){

    $page = new PageAdmin([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    User::checkLogin();

    $categorie = new Categorie();

    $resultado = $categorie->getCategorieById($id);

    $page->setTpl('editCat', [
        "categoria"=>$resultado,
        "erro"=>isset($_SESSION['mensagem'])? $_SESSION['mensagem']:''
    ]);

});

$app->post("/adminCat/edit/:id", function($id){

    $parametros = $_POST;

    Categorie::editCategorie($parametros, $id);

});

//Deletar Usuário
$app->get("/adminCat/delete/:id", function($id){

    $page = new PageAdmin([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    User::checkLogin();

    $page->setTpl('deleteCat');

});

$app->post("/adminCat/delete/:id", function($id){

    Categorie::deleteCategorie($id);

});

?>