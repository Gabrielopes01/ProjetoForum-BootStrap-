<?php

namespace Classes;
require_once("function.php");

Use \Classes\Sql;

class News{
    public function getNewsById($id){

        $sql = new Sql;

        $resultado = $sql->select("
            SELECT Noticia.Id AS 'Id', Noticia.Titulo AS 'Titulo', Noticia.Corpo AS 'Corpo', Noticia.Id_Categoria_FK AS 'Id_Categoria_FK', Categoria.Nome AS 'Categoria', Usuario.Nome AS 'Usuario', Noticia.Data AS 'Data', Noticia.Resumo AS 'Resumo', Noticia.Visualizacao AS 'Visualizacao'
            FROM Noticia
            INNER JOIN Categoria ON Noticia.Id_Categoria_FK = Categoria.Id
            INNER JOIN Usuario ON Noticia.Id_Usuario_FK = Usuario.Id
            WHERE Noticia.Id = :id", [
            ":id"=>$id
        ]);

        return $resultado[0];

    }

    public static function getNews($num){

        $sql = new Sql();

        $forResult = $num * 10;

        $resultado = $sql->select("
            SELECT TOP 10 Noticia.Id AS 'Id', Noticia.Titulo AS 'Titulo', Noticia.Corpo AS 'Corpo', Categoria.Nome AS 'Categoria', Usuario.Nome AS 'Usuario', Noticia.Data AS 'Data', Noticia.Resumo AS 'Resumo', Noticia.Visualizacao AS 'Visualizacao'
            FROM Noticia
            INNER JOIN Categoria ON Noticia.Id_Categoria_FK = Categoria.Id
            INNER JOIN Usuario ON Noticia.Id_Usuario_FK = Usuario.Id
            WHERE Noticia.Id NOT IN (Select TOP $forResult Id From Noticia)
            ");

        return $resultado;

    }

    public static function getALLNews(){

        $sql = new Sql();

        $resultado = $sql->select("
            SELECT Noticia.Id AS 'Id', Noticia.Titulo AS 'Titulo', Noticia.Corpo AS 'Corpo', Categoria.Nome AS 'Categoria', Usuario.Nome AS 'Usuario', Noticia.Data AS 'Data', Noticia.Resumo AS 'Resumo', Noticia.Imagem AS 'Imagem', Noticia.Visualizacao AS 'Visualizacao'
            FROM Noticia
            INNER JOIN Categoria ON Noticia.Id_Categoria_FK = Categoria.Id
            INNER JOIN Usuario ON Noticia.Id_Usuario_FK = Usuario.Id
            ORDER BY Noticia.Data DESC");

        return $resultado;

    }

    public static function getTOPNews(){

        $sql = new Sql();

        $resultado = $sql->select("
            SELECT TOP 5 Noticia.Id AS 'Id', Noticia.Titulo AS 'Titulo', Noticia.Corpo AS 'Corpo', Categoria.Nome AS 'Categoria', Usuario.Nome AS 'Usuario', Noticia.Data AS 'Data', Noticia.Resumo AS 'Resumo', Noticia.Imagem AS 'Imagem', Noticia.Visualizacao AS 'Visualizacao'
            FROM Noticia
            INNER JOIN Categoria ON Noticia.Id_Categoria_FK = Categoria.Id
            INNER JOIN Usuario ON Noticia.Id_Usuario_FK = Usuario.Id
            ORDER BY Noticia.Visualizacao DESC");

        return $resultado;

    }

    public static function addView($num){

        $sql = new Sql();

        $resultado = $sql->select("
            SELECT *
            FROM Acesso
            INNER JOIN Usuario ON Acesso.Id_Usuario_FK = Usuario.Id
            INNER JOIN Noticia ON Acesso.Id_Noticia_FK = Noticia.Id
            WHERE Usuario.Email = :email AND Acesso.Id_Noticia_FK = :id
            ", [
                ":email"=>isset($_SESSION["email"]) ? $_SESSION["email"] : "",
                ":id"=>$num
            ]);


        if(!(count($resultado) > 0) && isset($_SESSION["email"])) {

            $usuario = User::getUserByEmail($_SESSION["email"]);

            $sql->query("INSERT Acesso VALUES (:user, :id)", [
                ":user"=>$usuario["Id"],
                ":id"=>$num
            ]);

            $sql->query("UPDATE Noticia SET Visualizacao = Visualizacao + 1 WHERE Id = :id", array(
                ":id"=>$num
            ));
        }


    }

    public static function addNews($parametros){

        $sql = new Sql();

        //Verificando se o campo Titulo foi preenchido
        if($parametros["titulo"] === ""){
            $_SESSION['mensagem'] = "Campo Titulo Obrigatório";
            header("Location: /adminNews/add");
            exit;
        }

        //Verificando se o campo Corpo foi preenchido
        if($parametros["corpo"] === ""){
            $_SESSION['mensagem'] = "Campo Corpo Obrigatório";
            header("Location: /adminNews/add");
            exit;
        }

        //Verificando se o campo Resumo foi preenchido
        if($parametros["resumo"] === ""){
            $_SESSION['mensagem'] = "Campo Resumo Obrigatório";
            header("Location: /adminNews/add");
            exit;
        }

        //Verificando se a categoria foi inserida corretamente
        if(!isset($parametros["categoria"])){
            $_SESSION['mensagem'] = "Selecione 1 categoria";
            header("Location: /adminNews/add");
            exit;
        }

        //Verificando se o tipo de arquivo esta correto, se estiver correto ele salva a imagem no diretorio
        if(!verifyImage($_FILES)){
            $_SESSION['mensagem'] = "Formato de Arquivo Inválido";
            header("Location: /adminNews/add");
            exit;
        }


        $usuarioID = $sql->select("SELECT Id FROM Usuario WHERE Nome = :usuario", array(
            ":usuario"=>$parametros["usuario"]
        ));


        $sql->query("INSERT INTO Noticia (Id_Categoria_FK, Id_Usuario_FK, Titulo, Corpo, Resumo, Imagem) VALUES (:categoria, :usuario, :titulo, :corpo, :resumo, :imagem)", array(
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

        //Verificando se o campo Titulo foi preenchido
        if($parametros["titulo"] === ""){
            $_SESSION['mensagem'] = "Campo Titulo Obrigatório";
            header("Location: /adminNews/edit/$id");
            exit;
        }

        //Verificando se o campo Corpo foi preenchido
        if($parametros["corpo"] === ""){
            $_SESSION['mensagem'] = "Campo Corpo Obrigatório";
            header("Location: /adminNews/edit/$id");
            exit;
        }

        $sql->query("UPDATE Noticia SET Id_Categoria_FK = :categoria, Titulo = :titulo, Corpo = :corpo, Resumo = :resumo WHERE Id = :id", array(
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

        $sql->query("DELETE FROM Noticia WHERE Id = :id", array(
            ":id"=>$id
        ));

        $_SESSION['mensagem'] = "Notícia Deletada com Sucesso";
        header("Location: /adminNews/search/0");
        exit;
    }

     public static function filter($parametros){

        $sql = new Sql();

        $filtros = ["titulo"=>$parametros['titulo'], "categoria"=>$parametros['categoria'], "usuario"=>$parametros['usuario'], "data"=>$parametros['data']];
        $resultadoFiltro = [];

        //Verificando se os campos estão definidos e dando valores a eles
        $title = isset($parametros['titulo']) && !$parametros['titulo'] == ""? $parametros['titulo']:"|";
        $categorie = isset($parametros['categoria']) && !$parametros['categoria'] == ""? $parametros['categoria']:"|";
        $user = isset($parametros['usuario']) && !$parametros['usuario'] == ""? $parametros['usuario']:"|";
        $date = isset($parametros['data']) && !$parametros['data'] == ""? $parametros['data']:"|";

        //Verificando se os 3 campos estão preenchidos com parametros de busca
        if($parametros['verBuscaTitulo'] == 1 && $parametros['verBuscaCategoria'] == 1 && $parametros['verBuscaUsuario'] == 1 && $parametros['verBuscaData'] == 1 && $title != "|" && $categorie != "|" && $user != "|" && $date != "|"){

                $resultadoALL = $sql->select(
                    "
                    SELECT Noticia.Id AS 'Id', Noticia.Titulo AS 'Titulo', Noticia.Corpo AS 'Corpo', Categoria.Nome AS 'Categoria',
                    Usuario.Nome AS 'Usuario', Noticia.Data AS 'Data', Noticia.Visualizacao AS 'Visualizacao'
                    FROM Noticia
                    INNER JOIN Categoria ON Noticia.Id_Categoria_FK = Categoria.Id
                    INNER JOIN Usuario ON Noticia.Id_Usuario_FK = Usuario.Id
                    WHERE Noticia.Titulo LIKE CONCAT('%', :titulo, '%') AND Categoria.Nome LIKE CONCAT('%', :categoria, '%') AND
                    Usuario.Nome LIKE CONCAT('%', :usuario, '%') AND
                    SUBSTRING(CONVERT(varchar, Noticia.Data, 103), 0, 11) LIKE (CONCAT('%', :data, '%'))
                    ", [
                    ":titulo"=>$title,
                    ":categoria"=>$categorie,
                    ":usuario"=>$user,
                    ":data"=>$date
                ]);
                array_push($resultadoFiltro, $resultadoALL);

        } elseif ($title != "|" || $categorie != "|" || $user != "|" || $date != "|") {
                if($parametros['verBuscaTitulo'] == 1 && $title != "|"){
                    //Verificando se o Titulo e Categoria estao preenchidos
                    if($parametros['verBuscaCategoria'] == 1 && $categorie != "|"){
                    $resultadoTC = $sql->select(
                        "
                        SELECT Noticia.Id AS 'Id', Noticia.Titulo AS 'Titulo', Noticia.Corpo AS 'Corpo', Categoria.Nome AS 'Categoria', 
                        Usuario.Nome AS 'Usuario', Noticia.Data AS 'Data', Noticia.Visualizacao AS 'Visualizacao'
                        FROM Noticia
                        INNER JOIN Categoria ON Noticia.Id_Categoria_FK = Categoria.Id
                        INNER JOIN Usuario ON Noticia.Id_Usuario_FK = Usuario.Id
                        WHERE Noticia.Titulo LIKE CONCAT('%', :titulo, '%') AND Categoria.Nome LIKE CONCAT('%', :categoria, '%') 
                        ", [
                        ":titulo"=>$title,
                        ":categoria"=>$categorie
                    ]);
                        array_push($resultadoFiltro, $resultadoTC);

                    //Verificando se o Titulo e Usuario estao preenchidos
                    } elseif ($parametros['verBuscaUsuario'] == 1 && $user != "|") {
                    $resultadoTU = $sql->select(
                        "
                        SELECT Noticia.Id AS 'Id', Noticia.Titulo AS 'Titulo', Noticia.Corpo AS 'Corpo', Categoria.Nome AS 'Categoria', 
                        Usuario.Nome AS 'Usuario', Noticia.Data AS 'Data', Noticia.Visualizacao AS 'Visualizacao'
                        FROM Noticia
                        INNER JOIN Categoria ON Noticia.Id_Categoria_FK = Categoria.Id
                        INNER JOIN Usuario ON Noticia.Id_Usuario_FK = Usuario.Id
                        WHERE Noticia.Titulo LIKE CONCAT('%', :titulo, '%') AND Usuario.Nome LIKE CONCAT('%', :usuario, '%')
                        ", [
                        ":titulo"=>$title,
                        ":usuario"=>$user
                    ]);
                        array_push($resultadoFiltro, $resultadoTU);

                    //Verificando se o Titulo e Data estao preenchidos
                    } elseif ($parametros['verBuscaData'] == 1 && $date != "|") {
                    $resultadoTD = $sql->select(
                        "
                        SELECT Noticia.Id AS 'Id', Noticia.Titulo AS 'Titulo', Noticia.Corpo AS 'Corpo', Categoria.Nome AS 'Categoria', 
                        Usuario.Nome AS 'Usuario', Noticia.Data AS 'Data', Noticia.Visualizacao AS 'Visualizacao'
                        FROM Noticia
                        INNER JOIN Categoria ON Noticia.Id_Categoria_FK = Categoria.Id
                        INNER JOIN Usuario ON Noticia.Id_Usuario_FK = Usuario.Id
                        WHERE Noticia.Titulo LIKE CONCAT('%', :titulo, '%') AND SUBSTRING(CONVERT(varchar, Noticia.Data, 103), 0, 11) LIKE (CONCAT('%', :data, '%'))
                        ", [
                        ":titulo"=>$title,
                        ":data"=>$date
                    ]);
                        array_push($resultadoFiltro, $resultadoTD);

                    } else {
                        //Apenas o Titulo esta preenchido
                    $resultadoT = $sql->select(
                        "
                        SELECT Noticia.Id AS 'Id', Noticia.Titulo AS 'Titulo', Noticia.Corpo AS 'Corpo', Categoria.Nome AS 'Categoria', 
                        Usuario.Nome AS 'Usuario', Noticia.Data AS 'Data', Noticia.Visualizacao AS 'Visualizacao'
                        FROM Noticia
                        INNER JOIN Categoria ON Noticia.Id_Categoria_FK = Categoria.Id
                        INNER JOIN Usuario ON Noticia.Id_Usuario_FK = Usuario.Id
                        WHERE Noticia.Titulo LIKE CONCAT('%', :titulo, '%')
                        ", [
                        ":titulo"=>$title
                    ]);
                        array_push($resultadoFiltro, $resultadoT);
                    }

                } elseif ($parametros['verBuscaCategoria'] == 1 && $categorie != "|") {
                    //Verificando se o Categoria e Usuario estao preenchidos
                    if($parametros['verBuscaUsuario'] == 1 && $user != "|"){
                    $resultadoCU = $sql->select(
                        "
                        SELECT Noticia.Id AS 'Id', Noticia.Titulo AS 'Titulo', Noticia.Corpo AS 'Corpo', Categoria.Nome AS 'Categoria', 
                        Usuario.Nome AS 'Usuario', Noticia.Data AS 'Data', Noticia.Visualizacao AS 'Visualizacao'
                        FROM Noticia
                        INNER JOIN Categoria ON Noticia.Id_Categoria_FK = Categoria.Id
                        INNER JOIN Usuario ON Noticia.Id_Usuario_FK = Usuario.Id
                        WHERE Categoria.Nome LIKE CONCAT('%', :categoria, '%') AND 
                        Usuario.Nome LIKE CONCAT('%', :usuario, '%')
                        ", [
                        ":categoria"=>$categorie,
                        ":usuario"=>$user
                    ]);
                        array_push($resultadoFiltro, $resultadoCU);

                    //Verificando se o Categoria e Data estao preenchidos
                    } elseif ($parametros['verBuscaData'] == 1 && $date != "|") {

                        $resultadoCD = $sql->select(
                        "
                        SELECT Noticia.Id AS 'Id', Noticia.Titulo AS 'Titulo', Noticia.Corpo AS 'Corpo', Categoria.Nome AS 'Categoria', 
                        Usuario.Nome AS 'Usuario', Noticia.Data AS 'Data', Noticia.Visualizacao AS 'Visualizacao'
                        FROM Noticia
                        INNER JOIN Categoria ON Noticia.Id_Categoria_FK = Categoria.Id
                        INNER JOIN Usuario ON Noticia.Id_Usuario_FK = Usuario.Id
                        WHERE Categoria.Nome LIKE CONCAT('%', :categoria, '%') AND
                        SUBSTRING(CONVERT(varchar, Noticia.Data, 103), 0, 11) LIKE (CONCAT('%', :data, '%'))
                        ", [
                        ":categoria"=>$categorie,
                        ":data"=>$date
                    ]);
                        array_push($resultadoFiltro, $resultadoCD);

                    } else {
                        //Apenas a Categoria esta preenchida
                        $resultadoC = $sql->select(
                            "
                            SELECT Noticia.Id AS 'Id', Noticia.Titulo AS 'Titulo', Noticia.Corpo AS 'Corpo', Categoria.Nome AS 'Categoria', 
                            Usuario.Nome AS 'Usuario', Noticia.Data AS 'Data', Noticia.Visualizacao AS 'Visualizacao'
                            FROM Noticia
                            INNER JOIN Categoria ON Noticia.Id_Categoria_FK = Categoria.Id
                            INNER JOIN Usuario ON Noticia.Id_Usuario_FK = Usuario.Id
                            WHERE Categoria.Nome LIKE CONCAT('%', :categoria, '%')
                            ", [
                            ":categoria"=>$categorie
                        ]);
                        array_push($resultadoFiltro, $resultadoC);
                    }

                } elseif ($parametros['verBuscaUsuario'] == 1 && $user != "|") {
                    //Verificando se Usuario e Data estão preenchidos
                    if($parametros['verBuscaData'] == 1 && $date != "|"){
                    $resultadoUD = $sql->select(
                        "
                        SELECT Noticia.Id AS 'Id', Noticia.Titulo AS 'Titulo', Noticia.Corpo AS 'Corpo', Categoria.Nome AS 'Categoria', 
                        Usuario.Nome AS 'Usuario', Noticia.Data AS 'Data', Noticia.Visualizacao AS 'Visualizacao'
                        FROM Noticia
                        INNER JOIN Categoria ON Noticia.Id_Categoria_FK = Categoria.Id
                        INNER JOIN Usuario ON Noticia.Id_Usuario_FK = Usuario.Id
                        WHERE Usuario.Nome LIKE CONCAT('%', :usuario, '%') AND
                        SUBSTRING(CONVERT(varchar, Noticia.Data, 103), 0, 11) LIKE (CONCAT('%', :data, '%'))
                        ", [
                        ":usuario"=>$user,
                        ":data"=>$date
                    ]);
                        array_push($resultadoFiltro, $resultadoUD);
                    } else {
                        //Apenas a Usuario esta preenchido
                        $resultadoU = $sql->select(
                            "
                            SELECT Noticia.Id AS 'Id', Noticia.Titulo AS 'Titulo', Noticia.Corpo AS 'Corpo', Categoria.Nome AS 'Categoria', 
                            Usuario.Nome AS 'Usuario', Noticia.Data AS 'Data', Noticia.Visualizacao AS 'Visualizacao'
                            FROM Noticia
                            INNER JOIN Categoria ON Noticia.Id_Categoria_FK = Categoria.Id
                            INNER JOIN Usuario ON Noticia.Id_Usuario_FK = Usuario.Id
                            WHERE Usuario.Nome LIKE CONCAT('%', :usuario, '%')
                            ", [
                            ":usuario"=>$user
                        ]);
                        array_push($resultadoFiltro, $resultadoU);
                    }

                } else {
                    //Apenas a Data esta preenchido
                    if($parametros['verBuscaData'] == 1 && $date != "|"){
                        $resultadoD = $sql->select(
                            "
                            SELECT Noticia.Id AS 'Id', Noticia.Titulo AS 'Titulo', Noticia.Corpo AS 'Corpo', Categoria.Nome AS 'Categoria', 
                            Usuario.Nome AS 'Usuario', Noticia.Data AS 'Data', Noticia.Visualizacao AS 'Visualizacao'
                            FROM Noticia
                            INNER JOIN Categoria ON Noticia.Id_Categoria_FK = Categoria.Id
                            INNER JOIN Usuario ON Noticia.Id_Usuario_FK = Usuario.Id
                            WHERE SUBSTRING(CONVERT(varchar, Noticia.Data, 103), 0, 11) LIKE (CONCAT('%', :data, '%'))
                            ", [
                            ":data"=>$date
                        ]);
                        array_push($resultadoFiltro, $resultadoD);
                    }
                }
            } else {
                //Nenhum dos 4 estao preenchidos
                $resultado2 = $sql->select(
                    "
                    SELECT TOP 10 Noticia.Id AS 'Id', Noticia.Titulo AS 'Titulo', Noticia.Corpo AS 'Corpo', Categoria.Nome AS 'Categoria', Usuario.Nome AS 'Usuario', Noticia.Data AS 'Data', Noticia.Visualizacao AS 'Visualizacao'
                    FROM Noticia
                    INNER JOIN Categoria ON Noticia.Id_Categoria_FK = Categoria.Id
                    INNER JOIN Usuario ON Noticia.Id_Usuario_FK = Usuario.Id
                    ");
                $resultadoFiltro[0] = $resultado2;
            }

            $resultadoFinal = isset($resultadoFiltro[0])?$resultadoFiltro[0]:[];

            return [$resultadoFinal, $filtros];
    }

}


?>