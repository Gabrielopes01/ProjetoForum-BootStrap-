<?php

Use \Classes\PageAdmin;
Use \Classes\User;
Use \Classes\Categorie;
Use \Classes\News;

//Home da Parte de noticias
$app->get("/adminNews/search/:num", function($num){

    $page = new PageAdmin([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    User::checkLogin();

    $noticias = News::getNews($num);

    $usuarios = User::getALLUsers();

    $categorias = Categorie::getALLCategorie();

    $numPags = ceil(count(News::getALLNews()) / 10);

    $pages = generatePages($numPags);

    $page->setTpl('news',[
        "noticias"=>$noticias,
        "message"=>isset($_SESSION['mensagem'])? $_SESSION['mensagem']:'',
        "filtros"=>['titulo' => "", 'categoria' => "", "usuario"=>"", "data" => ""],
        "categorias"=>$categorias,
        "usuarios"=>$usuarios,
        "usuario"=>$_SESSION['nome'],
        "paginas"=>$pages,
        "numPags"=>$numPags,
        "pagina"=>$num,
        "post"=>0
    ]);

});

//Home da parte de noticias com filtro e paginaçao
$app->get("/adminNews/search/filter/:num", function($num){

    $page = new PageAdmin([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    User::checkLogin();

    $parametros = $_SESSION["parametrosNEW"];

    $resultado = News::filter($parametros);

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

    $page->setTpl('news',[
        "noticias"=>$resultadoF,
        "message"=>isset($_SESSION['mensagem'])? $_SESSION['mensagem']:'',
        "filtros"=>$resultado[1],
        "usuario"=>$_SESSION['nome'],
        "paginas"=>$pages,
        "numPags"=>$numPags,
        "pagina"=>$num,
        "post"=>0
    ]);

});

//Usando Filtros do POST Filter
$app->post("/adminNews/search/filter/:num", function($num){

    $page = new PageAdmin([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    User::checkLogin();

    $_SESSION["parametrosNEW"] = $_POST;

    $num = 0;

    $resultado = News::filter($_SESSION["parametrosNEW"]);

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


    $page->setTpl('news',[
        "noticias"=>$resultadoF,
        "message"=>isset($_SESSION['mensagem'])? $_SESSION['mensagem']:'',
        "filtros"=>$resultado[1],
        "usuario"=>$_SESSION['nome'],
        "paginas"=>$pages,
        "numPags"=>$numPags,
        "pagina"=>$num,
        "post"=>0
    ]);

});

//Usando Filros do POST da Home
$app->post("/adminNews/search/:num", function($num){

    $page = new PageAdmin([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    User::checkLogin();

    $_SESSION["parametrosNEW"] = $_POST;

    $num = 0;

    $resultado = News::filter($_SESSION["parametrosNEW"]);

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

    $page->setTpl('news',[
        "noticias"=>$resultadoF,
        "message"=>isset($_SESSION['mensagem'])? $_SESSION['mensagem']:'',
        "filtros"=>$resultado[1],
        "usuario"=>$_SESSION['nome'],
        "paginas"=>$pages,
        "numPags"=>$numPags,
        "pagina"=>$num,
        "post"=>1
    ]);

});


//Adicioando uma nova notícia
$app->get("/adminNews/add", function(){

    $page = new PageAdmin([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    User::checkLogin();

    $categorias = Categorie::getALLCategorie();

    $page->setTpl('addNews', [
        "erro"=>isset($_SESSION['mensagem'])? $_SESSION['mensagem']:'',
        "categorias"=>$categorias,
        "usuario"=>$_SESSION['nome'],
        "titulo"=>isset($_SESSION['paramNews']['titulo']) ? $_SESSION['paramNews']['titulo'] : "",
        "corpo"=>isset($_SESSION['paramNews']['corpo']) ? $_SESSION['paramNews']['corpo'] : "",
        "resumo"=>isset($_SESSION['paramNews']['resumo']) ? $_SESSION['paramNews']['resumo'] : ""
    ]);

    $_SESSION['paramNews'] = "";

});

//Adiconar
$app->post("/adminNews/add", function(){

    $_SESSION['paramNews'] = $_POST;

    News::addNews($_POST, "/adminNews/search/0");

});

//Editar noticia
$app->get("/adminNews/edit/:id", function($id){

    $page = new PageAdmin([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    User::checkLogin();

    User::checkPermission($id);

    $usuarios = User::getALLUsers();

    $categorias = Categorie::getALLCategorie();

    $news = new News();

    $resultado = $news->getNewsById($id);

    $page->setTpl('editNews', [
        "noticia"=>$resultado,
        "usuarios"=>$usuarios,
        "categorias"=>$categorias,
        "erro"=>isset($_SESSION['mensagem'])? $_SESSION['mensagem']:'',
        "usuario"=>$_SESSION['nome']
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

    User::checkPermission($id);

    $page->setTpl('deleteNews');

});

$app->post("/adminNews/delete/:id", function($id){

    News::deleteNews($id);

});


$app->get("/0/publish", function(){

    $page = new PageAdmin([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    User::checkLogin();

    $categorias = Categorie::getALLCategorie();

    $page->setTpl('publish', [
        "erro"=>isset($_SESSION['mensagem'])? $_SESSION['mensagem']:'',
        "categorias"=>$categorias,
        "usuario"=>$_SESSION['nome'],
        "titulo"=>isset($_SESSION['paramNews']['titulo']) ? $_SESSION['paramNews']['titulo'] : "",
        "corpo"=>isset($_SESSION['paramNews']['corpo']) ? $_SESSION['paramNews']['corpo'] : "",
        "resumo"=>isset($_SESSION['paramNews']['resumo']) ? $_SESSION['paramNews']['resumo'] : ""
    ]);

    $_SESSION['paramNews'] = "";

});

$app->post("/0/publish", function(){

    $_SESSION['paramNews'] = $_POST;

    News::addNews($_POST, "/");

});
