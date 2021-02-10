<?php

namespace Classes;
require_once("function.php");

Use \Classes\Sql;

class Categorie {

    public function getCategorieById($id){

        $sql = new Sql;

        $resultado = $sql->select("SELECT * FROM Categoria WHERE id = :id", [
            ":id"=>$id
        ]);

        return $resultado[0];

    }


    public static function getCategorie($num){

        $sql = new Sql();

        $forResult = $num * 10;

        $resultado = $sql->select("SELECT TOP 10 * FROM Categoria WHERE id NOT IN (Select TOP $forResult id From Categoria)");

        return $resultado;

    }

    public static function getALLCategorie(){

        $sql = new Sql();

        $resultado = $sql->select("SELECT * FROM Categoria");

        return $resultado;

    }

    public static function verifyCategorie($category){

        $sql = new Sql();

        $resultado = $sql->select("SELECT * FROM Categoria WHERE nome = :nome", array(
            ":nome"=>$category
        ));

        if(count($resultado) > 0){
            return true;
        }

        return false;


    }


    public static function addCategorie($parametros){

        $sql = new Sql();

        Categorie::verifyCategorieInfo($parametros, 'add');

        $sql->query("INSERT INTO Categoria VALUES (:nome)", array(
            ":nome"=>$parametros["nome"],
        ));


        $_SESSION['mensagem'] = "Categoria Cadastrada com Sucesso";
        header("Location: /adminCat/search/0");
        exit;

    }

    public static function editCategorie($parametros, $id){

        $sql = new Sql();

        Categorie::verifyCategorieInfo($parametros, 'edit', $id);

        $sql->query("UPDATE Categoria SET nome = :nome WHERE id = :id", array(
            ":nome"=>$parametros["nome"],
            ":id"=>$parametros["id"]
        ));

        $_SESSION['mensagem'] = "Categoria Alterado com Sucessoo";
        header("Location: /adminCat/search/0");
        exit;
    }

    public static function deleteCategorie($id){
        $sql = new Sql();

        $sql->query("DELETE FROM Categoria WHERE id = :id", array(
            ":id"=>$id
        ));

        $_SESSION['mensagem'] = "Categoria Deletada com Sucesso";
        header("Location: /adminCat/search/0");
        exit;
    }

    public static function filter($parametros){

        $sql = new Sql();

        $filtros = ["nome"=>$parametros['nome']];
        //Clausula de SQL padrão
        $select = "SELECT * FROM Categoria WHERE 1 = 1";

        $name = isset($parametros['nome']) && !$parametros['nome'] == ""? $parametros['nome']:"";

        if($parametros['verBuscaNome'] == 1 && $name != ""){
            $select .= " AND nome LIKE CONCAT('%', '" . $name . "', '%')";
        }

        $resultadoF = $sql->select($select);

        return [$resultadoF, $filtros];
    }

    public static function verifyCategorieInfo($parametros, $tipo, $id = 0) {


        if($tipo == 'edit') {

            if($parametros["nome"] === ""){
                $_SESSION['mensagem'] = "Campo Nome Obrigatório";
                header("Location: /adminCat/edit/$id");
                exit;
            }

            if(Categorie::verifyCategorie($parametros["nome"])){
                $_SESSION['mensagem'] = "Categoria ja Cadastrada";
                header("Location: /adminCat/edit/$id");
                exit;
            }

        }

        if($tipo == 'add'){

            if($parametros["nome"] === ""){
                $_SESSION['mensagem'] = "Campo Nome Obrigatório";
                header('Location: /adminCat/add');
                exit;
            }

            if(Categorie::verifyCategorie($parametros["nome"])){
                $_SESSION['mensagem'] = "Categoria ja Cadastrada";
                header('Location: /adminCat/add');
                exit;
            }

        }

    }


}
