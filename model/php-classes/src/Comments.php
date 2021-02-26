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
            ORDER BY Noticia.data ASC
        ");

        return $resultado;

    }

    public static function getCommentById($id){

        $sql = new Sql();

        $resultado = $sql->select("
            SELECT Comentario.id AS 'id', Comentario.descricao AS 'descricao', Comentario.data AS 'data', Usuario.nome AS 'nome', Usuario.email AS 'email', Noticia.id AS 'noticia'
            FROM Comentario
            INNER JOIN Usuario ON Comentario.id_usuario = Usuario.id
            INNER JOIN Noticia ON Comentario.id_noticia = Noticia.id
            WHERE Comentario.id = $id
        ");

        return $resultado[0];

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

    public static function editComment($parametros, $id){

        $sql = new Sql();

        $sql->query("UPDATE Comentario SET descricao = :descricao WHERE id = :id", array(
            ":descricao"=>$parametros["desc"],
            ":id"=>$id
        ));

        $_SESSION['mensagem'] = "Comentário Alterado com Sucesso";
        header("Location: /adminCom/search/0");
        exit;
    }

    public static function deleteComment($id){

        $sql = new Sql();

        $sql->query("DELETE FROM Comentario WHERE id = :id", array(
            ":id"=>$id
        ));

        $_SESSION['mensagem'] = "Comentário Deletado com Sucesso";
        header("Location: /adminCom/search/0");
        exit;
    }

    public static function getComments($num){

        $sql = new Sql();

        $forResult = $num * 10;

        $resultado = $sql->select("
            SELECT TOP 10 Comentario.id AS 'id', Comentario.descricao AS 'descricao', Noticia.titulo AS 'noticia', Usuario.nome AS 'usuario', Usuario.email AS 'email', Comentario.data AS 'data'
            FROM Comentario
            INNER JOIN Noticia ON Comentario.id_noticia = Noticia.id
            INNER JOIN Usuario ON Comentario.id_usuario = Usuario.id
            WHERE Comentario.id NOT IN (Select TOP $forResult id From Comentario)
            ");

        return $resultado;

    }


    public static function filter($parametros){

        $sql = new Sql();


        $filtros = ["desc"=>$parametros['desc'], "nome"=>$parametros['nome'], "data"=>$parametros['data']];
        $select = "SELECT Comentario.id AS 'id', Comentario.descricao AS 'descricao', Comentario.data AS 'data', Usuario.nome AS 'usuario', Usuario.email AS 'email', Noticia.titulo AS 'noticia' 
                    FROM Comentario
                    INNER JOIN Usuario ON Comentario.id_usuario = Usuario.id
                    INNER JOIN Noticia ON Comentario.id_noticia = Noticia.id
                    WHERE 1 = 1";

        //Verificando se os campos estão definidos e dando valores a eles
        $desc = isset($parametros['desc']) && !$parametros['desc'] == ""? $parametros['desc']:"";
        $user = isset($parametros['nome']) && !$parametros['nome'] == ""? $parametros['nome']:"";
        $date = isset($parametros['data']) && !$parametros['data'] == ""? $parametros['data']:"";

        //Verificando se os campos estão preenchidos com parametros de busca
        if($parametros['verBuscaDesc'] == 1 && $desc != ""){
                $select .= " AND Comentario.descricao LIKE CONCAT('%', '" . $desc . "', '%')";
        }

        if($parametros['verBuscaNome'] == 1 && $user != ""){
                $select .= " AND Usuario.nome LIKE CONCAT('%', '" . $user . "', '%')";
        }

        if($parametros['verBuscaData'] == 1 && $date != ""){
                $select .= " AND SUBSTRING(CONVERT(varchar, Comentario.data, 103), 0, 11) LIKE CONCAT('%', '" . $date . "', '%')";
        }

        $resultadoF = $sql->select($select);

        return [$resultadoF, $filtros];

    }

}