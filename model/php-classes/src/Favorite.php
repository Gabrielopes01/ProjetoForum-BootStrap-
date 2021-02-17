<?php

namespace Classes;

Use \Classes\Sql;
Use \Classes\User;

class Favorite{

    public static function getALLFavorites(){

        $sql = new Sql();

        $resultado = $sql->select("
            SELECT Noticia.imagem AS 'Imagem', Noticia.id AS 'Id', Noticia.titulo AS 'Titulo', Noticia.resumo AS 'Resumo',
            Noticia.data AS 'Data', Usuario.nome AS 'Usuario', Noticia.corpo AS 'Corpo'
            FROM Favorito
            INNER JOIN Usuario ON Favorito.id_usuario = Usuario.id
            INNER JOIN Noticia ON Favorito.id_noticia = Noticia.id
            ORDER BY Noticia.data
            ",);

        return $resultado;

    }

    public static function verifyFavorite($id){

        if(isset($_SESSION["email"]) && $_SESSION["email"] != ""){

            $sql = new Sql();

            $resultado = $sql->select("
                SELECT Usuario.nome AS 'Nome', Noticia.titulo AS 'Titulo'
                FROM Favorito
                INNER JOIN Usuario ON Favorito.id_usuario = Usuario.id
                INNER JOIN Noticia ON Favorito.id_noticia = Noticia.id
                WHERE Usuario.email = :email AND Favorito.id_noticia = :id
                ", [
                    ":email"=>$_SESSION["email"],
                    ":id"=>$id
                ]);

            if (count($resultado) > 0) {
                return 1;
            }
            return 0;

        }

        return 2;

    }

    public static function addFavorites($id){

        $sql = new Sql();

        $usuario = User::getUserByEmail($_SESSION["email"]);

        $sql->query("
            INSERT Favorito
            Values (:user, :id)
            ", [
                ":user"=>$usuario["id"],
                ":id"=>$id
            ]);


    }


    public static function removeFavorite($id){

        $sql = new Sql();

        $usuario = User::getUserByEmail($_SESSION["email"]);

        $sql->query("
            DELETE FROM Favorito
            WHERE id_noticia = :id AND id_usuario = :user
            ", [
                ":id"=>$id,
                ":user"=>$usuario["id"]
            ]);

    }


//Fim da Classe
}

