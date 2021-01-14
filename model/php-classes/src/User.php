<?php

namespace Classes;
require_once("function.php");

Use \Classes\Sql;

class User{

    public function getUserById($id){

    $sql = new Sql;

    $resultado = $sql->select("SELECT * FROM Usuario WHERE id = :id", [
        ":id"=>$id
    ]);

    return $resultado[0];

    }

    public static function getUsers(){

        $sql = new Sql();

        $resultado = $sql->select("SELECT TOP 10 * FROM Usuario");

        return $resultado;

    }

    public static function verifyLogin($user, $password){

        $sql = new Sql();

        $resultado = $sql->select("SELECT * FROM Usuario WHERE Email = :user", array(
            ":user"=>$user
        ));

        if(count($resultado) > 0){
            if(password_verify($password, $resultado[0]["Senha"])){

                $_SESSION['nome'] = $resultado[0]["Nome"];

                header("Location: /");
                exit;

            } else {
                $_SESSION['mensagem'] = "Usuário e/ou Senha Inválidos";
                header("Location: /login");
                exit;
            }
        }

    }

    public static function verifyEmail($email){

        $sql = new Sql();

        $resultado = $sql->select("SELECT * FROM Usuario WHERE Email = :email", array(
            ":email"=>$email
        ));

        if(count($resultado) > 0){
            return true;
        }else{
            return false;
        }

    }

    public static function logout(){

        $_SESSION['nome'] = "";
        header("Location: /");
        exit;

    }

    //Esta função verifica se o usário esta logado para acessar tal pagina
    public static function checkLogin(){
        if(!isset($_SESSION['nome']) || $_SESSION['nome'] === ""){
            $_SESSION['mensagem'] = "Faça o login para acessar a página";
            header("Location: /login");
            exit;
        }
    }


    //Esta função ira filtrar os usuarios e exibir a pesquisa
    public static function filter($parametros){

        $sql = new Sql();

        $filtros = ["nome"=>$parametros['nome'], "email"=>$parametros['email'], "data"=>$parametros['data']];
        $resultadoFiltro = [];

        //Verificando se os campos estão definidos e dando valores a eles
        $name = isset($parametros['nome']) && !$parametros['nome'] == ""? $parametros['nome']:"|";
        $email = isset($parametros['email']) && !$parametros['email'] == ""? $parametros['email']:"|";
        $data = isset($parametros['data']) && !$parametros['data'] == ""? $parametros['data']:"|";

        //Verificando se os 3 campos estão preenchidos com parametros de busca
        if($parametros['verBuscaNome'] == 1 && $parametros['verBuscaEmail'] == 1 && $parametros['verBuscaData'] == 1 && $name != "|" && $email != "|" && $data != "|"){
            if($parametros['nome'] !== "" && $parametros['email'] !== "" && $parametros['data'] !== ""){
                    $resultadoALL = $sql->select("SELECT * FROM Usuario WHERE Nome LIKE CONCAT('%', :nome, '%') AND Email LIKE CONCAT('%', :email, '%') AND SUBSTRING(CONVERT(varchar, Data, 103), 0, 11) LIKE (CONCAT('%', :data, '%'))", [
                        ":nome"=>$name,
                        ":email"=>$email,
                        ":data"=>$data
                    ]);
                    array_push($resultadoFiltro, $resultadoALL);
            }

            } elseif ($name != "|" || $email != "|" || $data != "|") {
                if($parametros['verBuscaNome'] == 1 && $name != "|"){
                    //Verificando se o Nome e Email estao preenchidos
                    if($parametros['verBuscaEmail'] == 1 && $email != "|"){
                    $resultadoNE = $sql->select("SELECT * FROM Usuario WHERE Nome LIKE CONCAT('%', :nome, '%') AND Email LIKE CONCAT('%', :email, '%')", [
                        ":nome"=>$name,
                        ":email"=>$email
                    ]);
                        array_push($resultadoFiltro, $resultadoNE);

                    //Verificando se o Nome e Data estao preenchidos
                    } elseif ($parametros['verBuscaData'] == 1 && $data != "|") {
                    $resultadoND = $sql->select("SELECT * FROM Usuario WHERE Nome LIKE CONCAT('%', :nome, '%') AND SUBSTRING(CONVERT(varchar, Data, 103), 0, 11) LIKE (CONCAT('%', :data, '%'))", [
                        ":nome"=>$name,
                        ":data"=>$data
                    ]);
                        array_push($resultadoFiltro, $resultadoND);
                    } else {
                        //Apenas o Nome esta preenchido
                    $resultadoN = $sql->select("SELECT * FROM Usuario WHERE Nome LIKE CONCAT('%', :nome, '%')", [
                        ":nome"=>$name
                    ]);
                        array_push($resultadoFiltro, $resultadoN);
                    }

                } elseif ($parametros['verBuscaEmail'] == 1 && $email != "|") {
                    //Verificando se o Email e Data estao preenchidos
                    if($parametros['verBuscaData'] == 1 && $data != "|"){
                    $resultadoED = $sql->select("SELECT * FROM Usuario WHERE Email LIKE CONCAT('%', :email, '%') AND SUBSTRING(CONVERT(varchar, Data, 103), 0, 11) LIKE (CONCAT('%', :data, '%'))", [
                        ":email"=>$email,
                        ":data"=>$data
                    ]);
                        array_push($resultadoFiltro, $resultadoED);
                    } else {
                        //Apenas o Email esta preenchido
                        $resultadoE = $sql->select("SELECT * FROM Usuario WHERE Email LIKE CONCAT('%', :email, '%')", [
                            ":email"=>$email
                        ]);
                        array_push($resultadoFiltro, $resultadoE);
                    }
                } else {
                    //Apenas a Data esta preenchido
                    if($parametros['verBuscaData'] == 1 && $data != "|"){
                        $resultadoD = $sql->select("SELECT * FROM Usuario WHERE SUBSTRING(CONVERT(varchar, Data, 103), 0, 11) LIKE (CONCAT('%', :data, '%'))", [
                            ":data"=>$data
                        ]);
                        array_push($resultadoFiltro, $resultadoD);
                    }
                }
            } else {
                //Nenhum dos 3 estao preenchidos
                $resultado2 = $sql->select("SELECT TOP 10 * FROM Usuario");
                $resultadoFiltro[0] = $resultado2;
            }

            return [$resultadoFiltro[0], $filtros];
    }

    public static function addUser($parametros){

        $sql = new Sql();

        $checkEmail = "/^[a-z0-9.\-\_]+@[a-z0-9.\-\_]+\.(com|br|.com.br|.org|.net)$/i";

        //Verifocando se o campo Nome foi preenchido
        if($parametros["nome"] === ""){
            $_SESSION['mensagem'] = "Campo Nome Obrigatório";
            header("Location: /admin/add");
            exit;
        }

        //Verificando se o Email esta correto
        if (!preg_match($checkEmail, $parametros["email"])) {
            $_SESSION['mensagem'] = "Email Inválido";
            header("Location: /admin/add");
            exit;
        }

        //Verificando se o Email ja esta cadastrado
        if(User::verifyEmail($parametros["email"])){
            $_SESSION['mensagem'] = "Email ja Cadastrado";
            header("Location: /admin/add");
            exit;
        }

        //Verificando se o campo Senha foi preenchido
        if($parametros["senha"] === ""){
            $_SESSION['mensagem'] = "Campo Senha Obrigatório";
            header("Location: /admin/add");
            exit;
        }

        //Verificando se a Senha bate com o Confirmar Senha
        if($parametros["senha"] !== $parametros["csenha"]){
            $_SESSION['mensagem'] = "Senhas não Conferem";
            header("Location: /admin/add");
            exit;
        }


        $sql->query("INSERT INTO Usuario (Nome, Email, Senha) VALUES (:nome,:email,:senha)", array(
            ":nome"=>$parametros["nome"],
            ":email"=>$parametros["email"],
            ":senha"=>password_hash($parametros["senha"], PASSWORD_DEFAULT)
        ));


        $_SESSION['mensagem'] = "Usuário Cadastrado com Sucesso";
        header("Location: /admin");
        exit;

    }


    public static function editUser($parametros, $id){

        $sql = new Sql();

        $checkEmail = "/^[a-z0-9.\-\_]+@[a-z0-9.\-\_]+\.(com|br|.com.br|.org|.net)$/i";

        //Verificando se o campo Nome foi preenchido
        if($parametros["nome"] === ""){
            $_SESSION['mensagem'] = "Campo Nome Obrigatório";
            header("Location: /admin/edit/$id");
            exit;
        }

        //Verificando se o Email esta correto
        if (!preg_match($checkEmail, $parametros["email"])) {
            $_SESSION['mensagem'] = "Email Inválido";
            header("Location: /admin/edit/$id");
            exit;
        }


        $sql->query("UPDATE Usuario SET Nome = :nome, Email = :email WHERE Id = :id", array(
            ":nome"=>$parametros["nome"],
            ":email"=>$parametros["email"],
            ":id"=>$parametros["id"]
        ));

        $_SESSION['mensagem'] = "Usuário Alterado com Sucessoo";
        header("Location: /admin");
        exit;
    }


    public static function deleteUser($id){
            $sql = new Sql();

        $sql->query("DELETE FROM Usuario WHERE Id = :id", array(
            ":id"=>$id
        ));

        $_SESSION['mensagem'] = "Usuário Deletado com Sucesso";
        header("Location: /admin");
        exit;
    }




}

?>