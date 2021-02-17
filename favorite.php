<?php

Use \Classes\Favorite;
Use \Classes\Page;
require_once("function.php");

/*
$app->get("/:pag/favorite/:id", function($pag, $id){

    $ver = Favorite::verifyFavorite($id);

    if($ver == 1){
        Favorite::removeFavorite($id);
        header("Location: /$pag");
        exit;
    } else {
        Favorite::addFavorites($id);
        header("Location: /$pag");
        exit;
    }

});

$app->get("/:pag/favoriteOne/:id", function($pag, $id){

    $ver = Favorite::verifyFavorite($id);

    if($ver == 1){
        Favorite::removeFavorite($id);
        header("Location: /news/$id");
        exit;
    } else {
        Favorite::addFavorites($id);
        header("Location: /news/$id");
        exit;
    }

});

$app->get("/:pag/favoriteRemove/:id", function($pag, $id){

    Favorite::removeFavorite($id, $pag);

    header("Location: /$pag/myFavorites");
    exit;

});
*/

$app->get("/:pag/myFavorites", function($pag){

    $page = new Page([
        "nome"=>isset($_SESSION['nome'])? $_SESSION['nome']:''
    ]);

    $favoritos = Favorite::getALLFavorites();

    $resultado = [];
    $numPags = (int) ceil(count($favoritos)/16);
    $paginas = generatePages($numPags);

    for ($i = 16 * $pag; $i < 16 * ($pag + 1); $i++) {
        if($i == count($favoritos) || $i > count($favoritos)){
            break;
        } else {
            array_push($resultado, $favoritos[$i]);
        }
    }

    $page->setTpl("favorites", [
        "favoritos"=>$resultado,
        "pagina"=>$pag,
        "paginas"=>$paginas,
        "numPags"=>$numPags
    ]);

});

