<?php

namespace Classes;
require_once("function.php");

Use \Classes\Sql;

class News{
    public function getNewsById($id){

        $sql = new Sql;

        $resultado = $sql->select("
            SELECT Noticia.id AS 'Id', Noticia.titulo AS 'Titulo', Noticia.corpo AS 'Corpo', Noticia.id_categoria AS 'Id_Categoria_FK', Categoria.nome AS 'Categoria', Usuario.nome AS 'Usuario', Noticia.data AS 'Data', Noticia.resumo AS 'Resumo', Noticia.visualizacao AS 'Visualizacao'
            FROM Noticia
            INNER JOIN Categoria ON Noticia.id_categoria = Categoria.id
            INNER JOIN Usuario ON Noticia.id_usuario = Usuario.id
            WHERE Noticia.id = :id", [
            ":id"=>$id
        ]);

        return $resultado[0];

    }

    public static function getNews($num){

        $sql = new Sql();

        $forResult = $num * 10;

        $resultado = $sql->select("
            SELECT TOP 10 Noticia.id AS 'Id', Noticia.titulo AS 'Titulo', Noticia.corpo AS 'Corpo', Categoria.nome AS 'Categoria', Usuario.nome AS 'Usuario', Noticia.data AS 'Data', Noticia.resumo AS 'Resumo', Noticia.visualizacao AS 'Visualizacao'
            FROM Noticia
            INNER JOIN Categoria ON Noticia.id_categoria = Categoria.id
            INNER JOIN Usuario ON Noticia.id_usuario = Usuario.id
            WHERE Noticia.id NOT IN (Select TOP $forResult id From Noticia)
            ");

        return $resultado;

    }

    public static function getALLNews(){

        $sql = new Sql();

        $resultado = $sql->select("
            SELECT Noticia.id AS 'Id', Noticia.titulo AS 'Titulo', Noticia.corpo AS 'Corpo', Categoria.nome AS 'Categoria', Usuario.nome AS 'Usuario', Noticia.data AS 'Data', Noticia.resumo AS 'Resumo', Noticia.imagem AS 'Imagem', Noticia.visualizacao AS 'Visualizacao'
            FROM Noticia
            INNER JOIN Categoria ON Noticia.id_categoria = Categoria.id
            INNER JOIN Usuario ON Noticia.id_usuario = Usuario.id
            ORDER BY Noticia.data DESC");

        return $resultado;

    }

    public static function getTOPNews(){

        $sql = new Sql();

        $resultado = $sql->select("
            SELECT TOP 5 Noticia.id AS 'Id', Noticia.titulo AS 'Titulo', Noticia.corpo AS 'Corpo', Categoria.nome AS 'Categoria', Usuario.nome AS 'Usuario', Noticia.data AS 'Data', Noticia.resumo AS 'Resumo', Noticia.imagem AS 'Imagem', Noticia.visualizacao AS 'Visualizacao'
            FROM Noticia
            INNER JOIN Categoria ON noticia.id_categoria = Categoria.id
            INNER JOIN Usuario ON Noticia.id_usuario = Usuario.id
            ORDER BY Noticia.visualizacao DESC");

        return $resultado;

    }

    public static function addView($num){

        $sql = new Sql();

        $resultado = $sql->select("
            SELECT *
            FROM Acesso
            INNER JOIN Usuario ON Acesso.id_usuario = Usuario.id
            INNER JOIN Noticia ON Acesso.id_noticia = Noticia.id
            WHERE Usuario.email = :email AND Acesso.id_noticia = :id
            ", [
                ":email"=>isset($_SESSION["email"]) ? $_SESSION["email"] : "",
                ":id"=>$num
            ]);


        if(!(count($resultado) > 0) && isset($_SESSION["email"])) {

            $usuario = User::getUserByEmail($_SESSION["email"]);

            $sql->query("INSERT Acesso VALUES (:user, :id)", [
                ":user"=>$usuario["id"],
                ":id"=>$num
            ]);

            $sql->query("UPDATE Noticia SET visualizacao = visualizacao + 1 WHERE id = :id", array(
                ":id"=>$num
            ));
        }


    }

    public static function addNews($parametros){

        $sql = new Sql();

        News::verfiyNewsInfo($parametros, 'add');

        $usuarioID = $sql->select("SELECT Id FROM Usuario WHERE nome = :usuario", array(
            ":usuario"=>$parametros["usuario"]
        ));


        $sql->query("INSERT INTO Noticia (id_categoria, id_usuario, titulo, corpo, resumo, imagem) VALUES (:categoria, :usuario, :titulo, :corpo, :resumo, :imagem)", array(
            ":categoria"=>$parametros["categoria"],
            ":usuario"=>$usuarioID[0]["Id"],
            ":titulo"=>$parametros["titulo"],
            ":corpo"=>$parametros["corpo"],
            ":resumo"=>$parametros["resumo"],
            ":imagem"=>$_SESSION["nomeImagem"]
        ));


        $_SESSION['mensagem'] = "Notícia Criada com Sucesso";
        header("Location: /adminNews/search/0");
        exit;

    }


    public static function editNews($parametros, $id){

        $sql = new Sql();

        News::verfiyNewsInfo($parametros, 'edit', $id);

        $sql->query("UPDATE Noticia SET id_categoria = :categoria, titulo = :titulo, corpo = :corpo, resumo = :resumo WHERE id = :id", array(
            ":categoria"=>$parametros["categoria"],
            ":titulo"=>$parametros["titulo"],
            ":corpo"=>$parametros["corpo"],
            ":resumo"=>$parametros["resumo"],
            ":id"=>$parametros["id"]
        ));

        $_SESSION['mensagem'] = "Noticia Alterada com Sucesso";
        header("Location: /adminNews/search/0");
        exit;
    }

     public static function deleteNews($id){

        $sql = new Sql();

        $sql->query("DELETE FROM Acesso WHERE id_noticia = :id", array(
            ":id"=>$id
        ));

        $sql->query("DELETE FROM Favorito WHERE id_noticia = :id", array(
            ":id"=>$id
        ));

        $sql->query("DELETE FROM Noticia WHERE id = :id", array(
            ":id"=>$id
        ));

        $_SESSION['mensagem'] = "Notícia Deletada com Sucesso";
        header("Location: /adminNews/search/0");
        exit;
    }


     public static function filter($parametros){

        $sql = new Sql();


        $filtros = ["titulo"=>$parametros['titulo'], "categoria"=>$parametros['categoria'], "usuario"=>$parametros['usuario'], "data"=>$parametros['data']];
        $select = "SELECT Noticia.id AS 'Id', Noticia.titulo AS 'Titulo', Noticia.corpo AS 'Corpo', Categoria.nome AS 'Categoria',
                    Usuario.nome AS 'Usuario', Noticia.data AS 'Data', Noticia.visualizacao AS 'Visualizacao'
                    FROM Noticia
                    INNER JOIN Categoria ON Noticia.id_categoria = categoria.id
                    INNER JOIN Usuario ON Noticia.id_usuario = usuario.id
                    WHERE 1 = 1";

        //Verificando se os campos estão definidos e dando valores a eles
        $title = isset($parametros['titulo']) && !$parametros['titulo'] == ""? $parametros['titulo']:"";
        $categorie = isset($parametros['categoria']) && !$parametros['categoria'] == ""? $parametros['categoria']:"";
        $user = isset($parametros['usuario']) && !$parametros['usuario'] == ""? $parametros['usuario']:"";
        $date = isset($parametros['data']) && !$parametros['data'] == ""? $parametros['data']:"";

        //Verificando se os campos estão preenchidos com parametros de busca
        if($parametros['verBuscaTitulo'] == 1 && $title != ""){

                $select .= " AND Noticia.titulo LIKE CONCAT('%', '" . $title . "', '%')";

        }

        if($parametros['verBuscaCategoria'] == 1 && $categorie != ""){

                $select .= " AND Categoria.nome LIKE CONCAT('%', '" . $categorie . "', '%')";

        }

        if($parametros['verBuscaUsuario'] == 1 && $user != ""){

                $select .= " AND Usuario.nome LIKE CONCAT('%', '" . $user . "', '%')";

        }

        if($parametros['verBuscaData'] == 1 && $date != ""){

                $select .= " AND SUBSTRING(CONVERT(varchar, Noticia.data, 103), 0, 11) LIKE CONCAT('%', '" . $date . "', '%')";

        }

        $resultadoF = $sql->select($select);

        return [$resultadoF, $filtros];
    }


    public static function verfiyNewsInfo($parametros, $tipo, $id = 0){

        if($tipo == 'add') {

            if($parametros["titulo"] === ""){
                $_SESSION['mensagem'] = "Campo Titulo Obrigatório";
                header("Location: /adminNews/add");
                exit;
            }

            if($parametros["corpo"] === ""){
                $_SESSION['mensagem'] = "Campo Corpo Obrigatório";
                header("Location: /adminNews/add");
                exit;
            }

            if($parametros["resumo"] === ""){
                $_SESSION['mensagem'] = "Campo Resumo Obrigatório";
                header("Location: /adminNews/add");
                exit;
            }

            if(!isset($parametros["categoria"])){
                $_SESSION['mensagem'] = "Selecione 1 categoria";
                header("Location: /adminNews/add");
                exit;
            }

            if(!verifyImage($_FILES)){
                $_SESSION['mensagem'] = "Formato de Arquivo Inválido";
                header("Location: /adminNews/add");
                exit;
            }

        }


        if($tipo == 'edit'){

            if($parametros["titulo"] === ""){
                $_SESSION['mensagem'] = "Campo Titulo Obrigatório";
                header("Location: /adminNews/edit/$id");
                exit;
            }

            if($parametros["corpo"] === ""){
                $_SESSION['mensagem'] = "Campo Corpo Obrigatório";
                header("Location: /adminNews/edit/$id");
                exit;
            }

            if($parametros["resumo"] === ""){
                $_SESSION['mensagem'] = "Campo Resumo Obrigatório";
                header("Location: /adminNews/edit/$id");
                exit;
            }

        }

    }

}


?>