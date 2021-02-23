<?php

Use \Classes\Comments;
Use \Classes\PageAdmin;
Use \Classes\User;

$app->get('/news/:num/addComment/:user', function($num, $user) {

    $comment = $_GET['comentario'];

    Comments::addComment($comment, $user, $num);

});


//Home da Parte de comentarios
$app->get("/adminCom/search/:num", function($num){

    $page = new PageAdmin([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    User::checkLogin();

    $comentarios = Comments::getComments($num);

    $usuarios = User::getALLUsers();

    $numPags = ceil(count(Comments::getALLcomments()) / 10);

    $pages = generatePages($numPags);

    $page->setTpl('comment',[
        "comentarios"=>$comentarios,
        "message"=>isset($_SESSION['mensagem'])? $_SESSION['mensagem']:'',
        "filtros"=>['desc' => "", "data" => "", "nome" => ""],
        "usuario"=>$_SESSION['nome'],
        "paginas"=>$pages,
        "numPags"=>$numPags,
        "pagina"=>$num,
        "post"=>0
    ]);

});

//Home da parte de comentarios com filtro e paginaçao
$app->get("/adminCom/search/filter/:num", function($num){

    $page = new PageAdmin([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    User::checkLogin();

    $parametros = $_SESSION["parametrosCom"];

    $resultado = Comments::filter($parametros);

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

    $page->setTpl('comment',[
        "comentarios"=>$resultadoF,
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
$app->post("/adminCom/search/filter/:num", function($num){

    $page = new PageAdmin([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    User::checkLogin();

    $_SESSION["parametrosCom"] = $_POST;

    $num = 0;

    $resultado = Comments::filter($_SESSION["parametrosCom"]);

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


    $page->setTpl('comment',[
        "comentarios"=>$resultadoF,
        "message"=>isset($_SESSION['mensagem'])? $_SESSION['mensagem']:'',
        "filtros"=>$resultado[1],
        "usuario"=>$_SESSION['nome'],
        "paginas"=>$pages,
        "numPags"=>$numPags,
        "pagina"=>$num,
        "post"=>0
    ]);

});


//Usando Filtros do POST da home
$app->post("/adminCom/search/:num", function($num){

    $page = new PageAdmin([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    User::checkLogin();

    $_SESSION["parametrosCom"] = $_POST;

    $num = 0;

    $resultado = Comments::filter($_SESSION["parametrosCom"]);

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


    $page->setTpl('comment',[
        "comentarios"=>$resultadoF,
        "message"=>isset($_SESSION['mensagem'])? $_SESSION['mensagem']:'',
        "filtros"=>$resultado[1],
        "paginas"=>$pages,
        "numPags"=>$numPags,
        "pagina"=>$num,
        "post"=>1
    ]);

});


//Editar comentário
$app->get("/adminCom/edit/:id", function($id){

    $page = new PageAdmin([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    User::checkLogin();

    $comentario = Comments::getCommentById($id);

    $page->setTpl('editCom', [
        "comentario"=>$comentario,
        "erro"=>isset($_SESSION['mensagem'])? $_SESSION['mensagem']:'',
        "usuario"=>$_SESSION['nome']
    ]);

});


//Editar notícia
$app->post("/adminCom/edit/:id", function($id){

    $parametros = $_POST;

    Comments::editComment($parametros, $id);

});

//Deletar Notícia
$app->get("/adminCom/delete/:id", function($id){

    $page = new PageAdmin([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    User::checkLogin();

    $page->setTpl('deleteCom');

});

$app->post("/adminCom/delete/:id", function($id){

    Comments::deleteComment($id);

});