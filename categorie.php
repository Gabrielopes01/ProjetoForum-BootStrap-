<?php

Use \Classes\PageAdmin;
Use \Classes\User;
Use \Classes\Categorie;

//Home da Parte de categorias
$app->get("/adminCat/search/:num", function($num){

    $page = new PageAdmin([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    User::checkLogin();

    $categorias = Categorie::getCategorie($num);

    $numPags = ceil(count(Categorie::getALLCategorie()) / 10);

    $pages = generatePages($numPags);

    $page->setTpl('categorie',[
        "categorias"=>$categorias,
        "message"=>isset($_SESSION['mensagem'])? $_SESSION['mensagem']:'',
        "filtros"=>['nome' => ""],
        "paginas"=>$pages,
        "numPags"=>$numPags,
        "pagina"=>$num,
        "post"=>0
    ]);

});

//Home da parte de categorias com filtro e paginaçao
$app->get("/adminCat/search/filter/:num", function($num){

    $page = new PageAdmin([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    User::checkLogin();

    $parametros = $_SESSION["parametrosCAT"];

    $resultado = Categorie::filter($parametros);

    $resultadoF = [];

    for ($i= 10 * $num; $i < 10 * ($num + 1); $i++) {
        if (isset($resultado[0][$i])){
            array_push($resultadoF, $resultado[0][$i]);
        }else{
            break;
        }
    }

    $numPags = ceil(count($resultado[0]) / 10);

    $pages = generatePages($numPags);

    $page->setTpl('categorie',[
        "categorias"=>$resultadoF,
        "message"=>isset($_SESSION['mensagem'])? $_SESSION['mensagem']:'',
        "filtros"=>$resultado[1],
        "paginas"=>$pages,
        "numPags"=>$numPags,
        "pagina"=>$num,
        "post"=>0
    ]);

});

//Usando Filtros do POST Filter
$app->post("/adminCat/search/filter/:num", function($num){

    $page = new PageAdmin([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    User::checkLogin();

    $_SESSION["parametrosCAT"] = $_POST;

    $num = 0;

    $resultado = Categorie::filter($_SESSION["parametrosCAT"]);

    $resultadoF = [];


    for ($i = 0; $i < 10; $i++) {
        if (isset($resultado[0][$i])){
            array_push($resultadoF, $resultado[0][$i]);
        }else{
            break;
        }
    }

    $numPags = ceil(count($resultado[0]) / 10);

    $pages = generatePages($numPags);


    $page->setTpl('categorie',[
        "categorias"=>$resultadoF,
        "message"=>isset($_SESSION['mensagem'])? $_SESSION['mensagem']:'',
        "filtros"=>$resultado[1],
        "paginas"=>$pages,
        "numPags"=>$numPags,
        "pagina"=>$num,
        "post"=>0
    ]);

});

//Usando Filtros no Post da Home
$app->post("/adminCat/search/:num", function($num){

    $page = new PageAdmin([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    User::checkLogin();

    $_SESSION["parametrosCAT"] = $_POST;

    $num = 0;

    $resultado = Categorie::filter($_SESSION["parametrosCAT"]);

    $resultadoF = [];

    for ($i= 0; $i < 10; $i++) {
        if (isset($resultado[0][$i])){
            array_push($resultadoF, $resultado[0][$i]);
        }else{
            break;
        }
    }

    $numPags = ceil(count($resultado[0]) / 10);

    $pages = generatePages($numPags);

    $page->setTpl('categorie',[
        "categorias"=>$resultadoF,
        "message"=>isset($_SESSION['mensagem'])? $_SESSION['mensagem']:'',
        "filtros"=>$resultado[1],
        "paginas"=>$pages,
        "numPags"=>$numPags,
        "pagina"=>$num,
        "post"=>1
    ]);

});

//Adicioando um novo usuário
$app->get("/adminCat/add", function(){

    $page = new PageAdmin([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    User::checkLogin();

    $page->setTpl('addCat', [
        "erro"=>isset($_SESSION['mensagem'])? $_SESSION['mensagem']:'',
        "nome"=>isset($_SESSION['paramCat']['nome']) ? $_SESSION['paramCat']['nome'] : ''
    ]);

    $_SESSION['paramCat'] = "";

});


$app->post("/adminCat/add", function(){

    $_SESSION['paramCat'] = $_POST;

    Categorie::addCategorie($_POST);

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
