<?php
    Use \Classes\News;

    require_once("function.php");

    $noticia = News::getNewsById($num);

    echo "<br><br><br><br><br>";

    echo "<h2 id='titleNews'; align='left' style='font-family: 'Trebuchet MS', sans-serif;'>". $noticia['Titulo']. "</h2>";

    isInFavorite($num);

    if($_SESSION['favorito'] == 1){
        echo "<a href='/0/favoriteOne/".$num."' class='btn btn-warning' id='favoriteButton' style='border-radius: 30px; color: black;'><i class='fas fa-star'></i></a>";
    } else if ($_SESSION['favorito'] == 0){
        echo "<a href='/0/favoriteOne/".$num."' class='btn btn-warning' id='favoriteButton' style='border-radius: 30px; color: white;'><i class='fas fa-star'></i></a>";
    } else {
        echo "<a href='#' id='favoriteButton' class='btn btn-secondary disabled' style='border-radius: 30px;'><i class='fas fa-star'></i></a>";
    }

    echo "<div class='row' style='height: 100%''>";
    echo "<small class='text-muted' style='text-align: right;'>Publicado em: ". formatDate($noticia['Data']). " por <strong>". $noticia['Usuario']."</strong> <i class='fas fa-eye'></i> ".$noticia["Visualizacao"]. "</small>";
    echo "<hr>";
    echo "<div class='col-12 col-md-10 offset-md-1'>";
    echo "<p class='text-muted' style='text-align: left;'>".$noticia['Resumo']."</p>";
    echo "<div id='newsText'>".$noticia["Corpo"]."</div>";
    echo "</div> </div>";
    echo "<br><br><br>";


