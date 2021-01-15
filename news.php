<?php

Use \Classes\PageAdmin;
Use \Classes\User;
Use \Classes\Categorie;
Use \Classes\News;

//Home da Parte Administrativa
$app->get("/adminNews", function(){

    $page = new PageAdmin([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    User::checkLogin();

    $noticias = News::getNews();

    $usuarios = User::getALLUsers();

    $categorias = Categorie::getALLCategorie();

    $page->setTpl('news',[
        "noticias"=>$noticias,
        "message"=>isset($_SESSION['mensagem'])? $_SESSION['mensagem']:'',
        "filtros"=>['titulo' => "", 'categoria' => "", "usuario"=>"", "data" => ""],
        "categorias"=>$categorias,
        "usuarios"=>$usuarios
    ]);

});

$app->post("/adminNews", function(){

    $page = new PageAdmin([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    User::checkLogin();

    $parametros = $_POST;

    $resultado = News::filter($parametros);

    $page->setTpl('news',[
        "noticias"=>$resultado[0],
        "message"=>isset($_SESSION['mensagem'])? $_SESSION['mensagem']:'',
        "filtros"=>$resultado[1]
    ]);

});


//Adicioando uma nova notícia
$app->get("/adminNews/add", function(){

    $page = new PageAdmin([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    User::checkLogin();

    $usuarios = User::getALLUsers();

    $categorias = Categorie::getALLCategorie();

    $page->setTpl('addNews', [
        "erro"=>isset($_SESSION['mensagem'])? $_SESSION['mensagem']:'',
        "categorias"=>$categorias,
        "usuarios"=>$usuarios
    ]);

});

//Adiconar
$app->post("/adminNews/add", function(){

    $parametros = $_POST;

    News::addNews($parametros);

});

//Editar noticia
$app->get("/adminNews/edit/:id", function($id){

    $page = new PageAdmin([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    User::checkLogin();

    $usuarios = User::getALLUsers();

    $categorias = Categorie::getALLCategorie();

    $news = new News();

    $resultado = $news->getNewsById($id);

    $page->setTpl('editNews', [
        "noticia"=>$resultado,
        "usuarios"=>$usuarios,
        "categorias"=>$categorias,
        "erro"=>isset($_SESSION['mensagem'])? $_SESSION['mensagem']:''
    ]);

});

//Editar notícia
$app->post("/adminNews/edit/:id", function($id){

    $parametros = $_POST;

    News::editNews($parametros, $id);

});

//Deletar Notícia
$app->get("/adminNews/delete/:id", function($id){

    $page = new PageAdmin([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    User::checkLogin();

    $page->setTpl('deleteNews');

});

$app->post("/adminNews/delete/:id", function($id){

    News::deleteNews($id);

});


?>