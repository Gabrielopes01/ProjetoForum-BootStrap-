<?php

namespace Classes;

Use \Classes\Sql;
Use \Classes\User;

class Favorite{

    public static function getALLFavorites(){

        $sql = new Sql();

        $resultado = $sql->select("
            SELECT Noticia.Imagem AS 'Imagem', Noticia.Id AS 'Id', Noticia.Titulo AS 'Titulo', Noticia.Resumo AS 'Resumo',
            Noticia.Data AS 'Data', Usuario.Nome AS 'Usuario', Noticia.Corpo AS 'Corpo'
            FROM Favorito
            INNER JOIN Usuario ON Favorito.Id_Usuario_FK = Usuario.Id
            INNER JOIN Noticia ON Favorito.Id_Noticia_FK = Noticia.Id
            ORDER BY Noticia.Data
            ",);

        return $resultado;

    }

    public static function verifyFavorite($id){

        if(isset($_SESSION["email"]) && $_SESSION["email"] != ""){

            $sql = new Sql();

            $resultado = $sql->select("
                SELECT Usuario.Nome AS 'Nome', Noticia.Titulo AS 'Titulo'
                FROM Favorito
                INNER JOIN Usuario ON Favorito.Id_Usuario_FK = Usuario.Id
                INNER JOIN Noticia ON Favorito.Id_Noticia_FK = Noticia.Id
                WHERE Usuario.Email = :email AND Favorito.Id_Noticia_FK = :id
                ", [
                    ":email"=>$_SESSION["email"],
                    ":id"=>$id
                ]);

            if (count($resultado) > 0) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return 2;
        }


    }

    public static function addFavorites($id){

        $sql = new Sql();

        $usuario = User::getUserByEmail($_SESSION["email"]);

        $sql->query("
            INSERT Favorito
            Values (:user, :id)
            ", [
                ":user"=>$usuario["Id"],
                ":id"=>$id
            ]);


    }


    public static function removeFavorite($id){

        $sql = new Sql();

        $usuario = User::getUserByEmail($_SESSION["email"]);

        $sql->query("
            DELETE FROM Favorito
            WHERE Id_Noticia_FK = :id AND Id_Usuario_FK = :user
            ", [
                ":id"=>$id,
                ":user"=>$usuario["Id"]
            ]);



    }


//Fim da Classe
}


?>