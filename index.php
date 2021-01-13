<?php
session_start();



require_once("vendor/autoload.php");
require_once("function.php");

Use \Slim\Slim;
Use \Classes\Page;
Use \Classes\PageAdmin;
Use \Classes\Sql;
Use \Classes\User;


$app = new Slim();

$app->config('debug', true);

//Home de Site
$app->get("/", function(){

    $page = new Page([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    $page->setTpl('home', [
    "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

});

//Home da Parte Administrativa
$app->get("/admin", function(){

    $page = new PageAdmin([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    User::checkLogin();

    $sql = new Sql();

    $resultado = $sql->select("SELECT TOP 10 * FROM Usuario");

    $page->setTpl('home',[
        "usuarios"=>$resultado,
        "message"=>isset($_SESSION['mensagem'])? $_SESSION['mensagem']:'',
        "filtros"=>['nome' => "", 'email' => "", 'data' => ""]
    ]);

});

//Usando Filtros
$app->post("/admin", function(){

    $page = new PageAdmin([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    User::checkLogin();

    $sql = new Sql();

    $resultado = $sql->select("SELECT TOP 10 * FROM Usuario");

    $filtros = ["nome"=>$_POST['nome'], "email"=>$_POST['email'], "data"=>$_POST['data']];
    $resultadoFiltro = [];

    foreach ($resultado as $num => $linha) {

        $name = isset($_POST['nome']) && !$_POST['nome'] == ""? $_POST['nome']:"|";
        $email = isset($_POST['email']) && !$_POST['email'] == ""? $_POST['email']:"|";
        $data = isset($_POST['data']) && !$_POST['data'] == ""? $_POST['data']:"|";

        if(strpos(strtolower($linha['Nome']), strtolower($name)) !== false &&
            strpos(strtolower($linha['Email']), strtolower($email)) !== false &&
            strpos(formatDate(substr($linha['Data'], 0,10)), strtolower($data)) !== false){

            array_push($resultadoFiltro, $resultado[$num]);
            break;

        }else{
                if(strpos(strtolower($linha['Nome']), strtolower($name)) !== false && strpos(strtolower($linha['Email']), strtolower($email)) !== false ||
                strpos(strtolower($linha['Nome']), strtolower($name)) !== false && strpos(formatDate(substr($linha['Data'], 0,10)), strtolower($data)) !== false ||
                strpos(strtolower($linha['Email']), strtolower($email)) !== false && strpos(formatDate(substr($linha['Data'], 0,10)), strtolower($data)) !== false){
                array_push($resultadoFiltro, $resultado[$num]);
            }else{
                foreach ($linha as $coluna => $valor) {
                    //Verificando se o Nome confere a busca
                    $name = isset($_POST['nome']) && !$_POST['nome'] == ""? $_POST['nome']:"|";
                    $email = isset($_POST['email']) && !$_POST['email'] == ""? $_POST['email']:"|";
                    $data = isset($_POST['data']) && !$_POST['data'] == ""? $_POST['data']:"|";

                    if($_POST['nome'] !== ""){
                        if ($coluna === "Nome" && strpos(strtolower($valor), strtolower($name)) !== false) {
                            array_push($resultadoFiltro, $resultado[$num]);
                        }
                    }

                    if($_POST['email'] !== ""){
                        if ($coluna === "Email" && strpos(strtolower($valor), strtolower($email)) !== false) {
                            array_push($resultadoFiltro, $resultado[$num]);
                        }
                    }

                    if($_POST['data'] !== ""){
                        if ($coluna === "Data" && strpos(formatDate(substr($valor, 0,10)), strtolower($data)) !== false) {
                        array_push($resultadoFiltro, $resultado[$num]);
                        }
                    }

                }
            }

        }




    }

    $page->setTpl('home',[
        "usuarios"=>$resultadoFiltro,
        "message"=>isset($_SESSION['mensagem'])? $_SESSION['mensagem']:'',
        "filtros"=>$filtros
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


$app->post("/admin/add", function(){

    $sql = new Sql();

    $checkEmail = "/^[a-z0-9.\-\_]+@[a-z0-9.\-\_]+\.(com|br|.com.br|.org|.net)$/i";

    //Verifocando se o campo Nome foi preenchido
    if($_POST["nome"] === ""){
        $_SESSION['mensagem'] = "Campo Nome Obrigatório";
        header("Location: /admin/add");
        exit;
    }

    //Verificando se o Email esta correto
    if (!preg_match($checkEmail, $_POST["email"])) {
        $_SESSION['mensagem'] = "Email Inválido";
        header("Location: /admin/add");
        exit;
    }

    //Verificando se o Email ja esta cadastrado
    if(User::verifyEmail($_POST["email"])){
        $_SESSION['mensagem'] = "Email ja Cadastrado";
        header("Location: /admin/add");
        exit;
    }

    //Verificando se o campo Senha foi preenchido
    if($_POST["senha"] === ""){
        $_SESSION['mensagem'] = "Campo Senha Obrigatório";
        header("Location: /admin/add");
        exit;
    }

    //Verificando se a Senha bate com o Confirmar Senha
    if($_POST["senha"] !== $_POST["csenha"]){
        $_SESSION['mensagem'] = "Senhas não Conferem";
        header("Location: /admin/add");
        exit;
    }


    $sql->query("INSERT INTO Usuario (Nome, Email, Senha) VALUES (:nome,:email,:senha)", array(
        ":nome"=>$_POST["nome"],
        ":email"=>$_POST["email"],
        ":senha"=>password_hash($_POST["senha"], PASSWORD_DEFAULT)
    ));


    $_SESSION['mensagem'] = "Usuário Cadastrado com Sucesso";
    header("Location: /admin");
    exit;

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

    $sql = new Sql();

    $checkEmail = "/^[a-z0-9.\-\_]+@[a-z0-9.\-\_]+\.(com|br|.com.br|.org|.net)$/i";

    //Verifocando se o campo Nome foi preenchido
    if($_POST["nome"] === ""){
        $_SESSION['mensagem'] = "Campo Nome Obrigatório";
        header("Location: /admin/edit/$id");
        exit;
    }

    //Verificando se o Email esta correto
    if (!preg_match($checkEmail, $_POST["email"])) {
        $_SESSION['mensagem'] = "Email Inválido";
        header("Location: /admin/edit/$id");
        exit;
    }

    //Verificando se o Email ja esta cadastrado
    if(User::verifyEmail($_POST["email"])){
        $_SESSION['mensagem'] = "Email ja Cadastrado";
        header("Location: /admin/edit/$id");
        exit;
    }


    $sql->query("UPDATE Usuario SET Nome = :nome, Email = :email WHERE Id = :id", array(
        ":nome"=>$_POST["nome"],
        ":email"=>$_POST["email"],
        ":id"=>$_POST["id"]
    ));

    $_SESSION['mensagem'] = "Usuário Alterado com Sucessoo";
    header("Location: /admin");
    exit;

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

    $sql = new Sql();

    $sql->query("DELETE FROM Usuario WHERE Id = :id", array(
        ":id"=>$id
    ));

    $_SESSION['mensagem'] = "Usuário Deletado com Sucesso";
    header("Location: /admin");
    exit;

});


$app->get("/login", function(){

    $page = new PageAdmin([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    $page->setTpl('login', [
        'erro'=>isset($_SESSION['mensagem'])? $_SESSION['mensagem']:''
    ]);

});

$app->post("/login", function(){

    User::verifyLogin($_POST["email"],$_POST["senha"]);


});

$app->get("/logout", function(){

    User::logout();

    header("Location: /");
    exit;

});

$app->run();



?>