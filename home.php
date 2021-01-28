<?php

Use \Classes\Page;
Use \Classes\News;
Use \Classes\User;
Use \Classes\Favorite;

//Home de Site
$app->get("/", function(){

    header("Location: /0");
    exit;

});

$app->get("/:num", function($num){

    $page = new Page([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    $noticias = News::getALLNews();

    $resultado = [];
    $numPags = (int) ceil(count($noticias)/24);
    $paginas = generatePag($numPags);

    for ($i = 24 * $num; $i < 24 * ($num + 1); $i++) {
        if($i == count($noticias) || $i > count($noticias)){
            break;
        } else {
            array_push($resultado, $noticias[$i]);
        }
    }


    $page->setTpl('home', [
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:'',
        "noticias"=>$resultado,
        "pagina"=>$num,
        "paginas"=>$paginas,
        "numPags"=>$numPags
    ]);

});

?>