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

    }

}

?>