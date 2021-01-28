<?php

Use \Classes\Favorite;
require_once("function.php");

$app->get("/:pag/favorite/:id", function($pag, $id){

    $ver = Favorite::verifyFavorite($id);

    if($ver == 1){
        Favorite::removeFavorite($id, $pag);
    } else {
        Favorite::addFavorites($id, $pag);
    }

});


?>