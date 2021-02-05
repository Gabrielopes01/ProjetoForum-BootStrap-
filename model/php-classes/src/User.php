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

    public function getUserByEmail($email){

        $sql = new Sql;

        $resultado = $sql->select("SELECT * FROM Usuario WHERE email = :email", [
            ":email"=>$email
        ]);

        return $resultado[0];

    }

    public static function getUsers($num){

        $sql = new Sql();

        $forResult = $num * 10;

        $resultado = $sql->select("SELECT TOP 10 * FROM Usuario WHERE id NOT IN (Select TOP $forResult id From Usuario)", );

        return $resultado;

    }

    public static function getALLUsers(){

        $sql = new Sql();

        $resultado = $sql->select("SELECT * FROM Usuario");

        return $resultado;

    }

    public static function verifyLogin($user, $password){

        $sql = new Sql();

        $resultado = $sql->select("SELECT * FROM Usuario WHERE email = :user", array(
            ":user"=>$user
        ));

        if(count($resultado) > 0){
            if(password_verify($password, $resultado[0]["Senha"])){

                $_SESSION['nome'] = $resultado[0]["Nome"];
                $_SESSION['email']= $resultado[0]["Email"];

                header("Location: /");
                exit;

            } else {
                $_SESSION['mensagem'] = "Usuário e/ou Senha Inválidos";
                header("Location: /0/login");
                exit;
            }
        }

        $_SESSION['mensagem'] = "Usuário e/ou Senha Inválidos";
        header("Location: /0/login");
        exit;

    }

    public static function verifyEmail($email){

        $sql = new Sql();

        $resultado = $sql->select("SELECT * FROM Usuario WHERE email = :email", array(
            ":email"=>$email
        ));

        if(count($resultado) > 0){
            return true;
        }else{
            return false;
        }

    }

    public static function logout(){

        session_destroy();
        header("Location: /");
        exit;

    }

    //Esta função verifica se o usário esta logado para acessar tal pagina
    public static function checkLogin(){
        if(!isset($_SESSION['nome']) || $_SESSION['nome'] === ""){
            $_SESSION['mensagem'] = "Faça o login para acessar a página";
            header("Location: /0/login");
            exit;
        }
    }


    //Esta função ira filtrar os usuarios e exibir a pesquisa
    public static function filter($parametros){

        $sql = new Sql();

        $filtros = ["nome"=>$parametros['nome'], "email"=>$parametros['email'], "data"=>$parametros['data']];
        $select = "SELECT * FROM Usuario WHERE 1 = 1";

        //Verificando se os campos estão definidos e dando valores a eles
        $name = isset($parametros['nome']) && !$parametros['nome'] == ""? $parametros['nome']:"";
        $email = isset($parametros['email']) && !$parametros['email'] == ""? $parametros['email']:"";
        $date = isset($parametros['data']) && !$parametros['data'] == ""? $parametros['data']:"";

        //Verificando se os campos estão preenchidos com parametros de busca
        if($parametros['verBuscaNome'] == 1 && $name != ""){

            $select .= " AND nome LIKE CONCAT('%', '" . $name . "', '%')";

        }

        if($parametros['verBuscaEmail'] == 1 && $email != ""){

            $select .= " AND email LIKE CONCAT('%', '" . $email . "', '%')";

        }

        if($parametros['verBuscaData'] == 1 && $date != ""){

            $select .= " AND SUBSTRING(CONVERT(varchar, data, 103), 0, 11) LIKE CONCAT('%', '" . $date . "', '%')";

        }

        $resultadoF = $sql->select($select);

        return [$resultadoF, $filtros];
    }

    public static function addUser($parametros){

        $sql = new Sql();

        $checkEmail = "/^[a-z0-9.\-\_]+@[a-z0-9.\-\_]+\.(com|br|.com.br|.org|.net)$/i";

        //Verificando se o campo Nome foi preenchido
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
        header("Location: /admin/search/0");
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
        header("Location: /admin/search/0");
        exit;
    }


    public static function deleteUser($id){
            $sql = new Sql();

        $sql->query("DELETE FROM Usuario WHERE Id = :id", array(
            ":id"=>$id
        ));

        $_SESSION['mensagem'] = "Usuário Deletado com Sucesso";
        header("Location: /admin/search/0");
        exit;
    }

    public static function checkPermission($id){

        $sql = new Sql();

        $email = $sql->select("
                SELECT Usuario.Email
                From Noticia
                INNER JOIN Usuario ON Noticia.Id_Usuario_FK = Usuario.Id
                WHERE Noticia.Id = :id
            ", [
                ":id"=>$id
            ]);

        if (!($email[0]["Email"] === $_SESSION["email"])){
            $_SESSION["mensagem"] = "Você não tem permissão para acessar esta notícia";
            header("Location: /adminNews/search/0");
            exit;
        }

    }




}

?>