<?php

namespace Classes;

Use \Classes\Sql;

class Comments{

    public static function getALLcomments(){

        $sql = new Sql();

        $resultado = $sql->select("
            SELECT Comentario.id AS 'id', Comentario.descricao AS 'descricao', Comentario.data AS 'data', Usuario.nome AS 'nome', Usuario.email AS 'email', Noticia.id AS 'noticia' 
            FROM Comentario
            INNER JOIN Usuario ON Comentario.id_usuario = Usuario.id
            INNER JOIN Noticia ON Comentario.id_noticia = Noticia.id
            ORDER BY Noticia.data DESC
        ");

        return $resultado;

    }

    public static function addComment($comment, $user, $news){

        $sql = new Sql();

        $sql->query("INSERT INTO Comentario VALUES (:descr, :user, :news, DEFAULT)", [
            ":descr"=>$comment,
            ":user"=>$user,
            ":news"=>$news
        ]);

        header("Location: /news/$news");
        exit;

    }

}