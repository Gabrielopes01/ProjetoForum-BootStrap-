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

//Home da parte administrativa com filtro e paginaçao
$app->get("/admin/search/filter/:num", function($num){

    $page = new PageAdmin([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    User::checkLogin();

    $parametros = $_SESSION["parametros"];

    $resultado = User::filter($parametros,$num);

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

    $page->setTpl('homeFilter',[
        "usuarios"=>$resultadoF,
        "message"=>isset($_SESSION['mensagem'])? $_SESSION['mensagem']:'',
        "filtros"=>$resultado[1],
        "paginas"=>$pages,
        "numPags"=>$numPags,
        "pagina"=>$num,
        "post"=>0
    ]);

});

//Usando Filtros
$app->post("/admin/search/filter/:num", function($num){

    $page = new PageAdmin([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    User::checkLogin();

    $_SESSION["parametros"] = $_POST;

    $resultado = User::filter($_SESSION["parametros"],$num);

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

    $num = 0;


    $page->setTpl('homeFilter',[
        "usuarios"=>$resultadoF,
        "message"=>isset($_SESSION['mensagem'])? $_SESSION['mensagem']:'',
        "filtros"=>$resultado[1],
        "paginas"=>$pages,
        "numPags"=>$numPags,
        "pagina"=>$num,
        "post"=>0
    ]);

});

//Usando Filtros
$app->post("/admin/search/:num", function($num){

    $page = new PageAdmin([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    User::checkLogin();

    $_SESSION["parametros"] = $_POST;

    $resultado = User::filter($_SESSION["parametros"],$num);

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

    $num = 0;


    $page->setTpl('homeFilter',[
        "usuarios"=>$resultadoF,
        "message"=>isset($_SESSION['mensagem'])? $_SESSION['mensagem']:'',
        "filtros"=>$resultado[1],
        "paginas"=>$pages,
        "numPags"=>$numPags,
        "pagina"=>$num,
        "post"=>1
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