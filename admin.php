<?php

Use \Classes\PageAdmin;
Use \Classes\User;

//Home da Parte Administrativa
$app->get("/admin/search/:num", function($num){

    $page = new PageAdmin([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:'',
    ]);

    User::checkLogin();

    $usuarios = User::getUsers($num);

    $numPags = ceil(count(User::getALLUsers()) / 10);

    $pages = generatePages($numPags);

    $page->setTpl('home',[
        "usuarios"=>$usuarios,
        "message"=>isset($_SESSION['mensagem'])? $_SESSION['mensagem']:'',
        "filtros"=>['nome' => "", 'email' => "", 'data' => ""],
        "paginas"=>$pages,
        "numPags"=>$numPags,
        "pagina"=>$num
    ]);

});

//Usando Filtros
$app->post("/admin/search/:num", function($num){

    $page = new PageAdmin([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    User::checkLogin();

    $parametros = $_POST;

    $resultado = User::filter($parametros);

    $page->setTpl('home',[
        "usuarios"=>$resultado[0],
        "message"=>isset($_SESSION['mensagem'])? $_SESSION['mensagem']:'',
        "filtros"=>$resultado[1]
    ]);

});

//Adicioando um novo usuário
$app->get("/admin/add", function(){

    $page = new PageAdmin([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    User::checkLogin();

    $page->setTpl('add', [
        "erro"=>isset($_SESSION['mensagem'])? $_SESSION['mensagem']:''
    ]);

});

//Adiconar
$app->post("/admin/add", function(){

    $parametros = $_POST;

    User::addUser($parametros);

});

//Editar usuário
$app->get("/admin/edit/:id", function($id){

    $page = new PageAdmin([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    User::checkLogin();

    $user = new User();

    $resultado = $user->getUserById($id);

    $page->setTpl('edit', [
        "usuario"=>$resultado,
        "erro"=>isset($_SESSION['mensagem'])? $_SESSION['mensagem']:''
    ]);

});

$app->post("/admin/edit/:id", function($id){

    $parametros = $_POST;

    User::editUser($parametros, $id);

});

//Deletar Usuário
$app->get("/admin/delete/:id", function($id){

    $page = new PageAdmin([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    User::checkLogin();

    $page->setTpl('delete');

});

$app->post("/admin/delete/:id", function($id){

    User::deleteUser($id);

});

?>