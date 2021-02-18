<?php
    Use \Classes\News;

    require_once("function.php");

    $noticia = News::getNewsById($num);

    echo "<br><br><br><br><br>";

    echo "<h2 id='titleNews' align='left' width='100%' class='home-title'><span>". $noticia['Titulo']. "</span></h2>";

    isInFavorite($num);

    echo "<p id='$num' style='float: right;'>";
    if($_SESSION['favorito'] == 1){
        echo "<a onclick='favButtonC($num)' class='btn btn-warning favButton' style='border-radius: 30px; color: black;'><i class='fas fa-star'></i></a>";
    } else if ($_SESSION['favorito'] == 0){
        echo "<a onclick='favButtonC($num)' class='btn btn-warning favButton' style='border-radius: 30px; color: white;'><i class='fas fa-star'></i></a>";
    }
    echo "</p>";

    echo "<div class='row' style='height: 100%''>";
    echo "<hr>";
    echo "<div class='col-12 col-md-10 offset-md-1'>";
    echo "<p style='text-align: left; color:#cccccc; font-size:15px'>".$noticia['Resumo']."</p>";
    echo "<hr>";
    echo "<div id='newsText' style='text-shadow: 0px 1px 0px black; background-color: #d9d9d9; color: black; padding: 20px;'>".$noticia["Corpo"]."</div>";
    echo "<small class='text-muted' style='float: right;'>Publicado em: ". formatDate($noticia['Data']). " por <strong>". $noticia['Usuario']."</strong> <i class='fas fa-eye'></i> ".$noticia["Visualizacao"]. "</small>";
    echo "</div> </div>";
    echo "<br><br><br><br><br>";



echo "<script>";
echo "function favButtonC(id) {

  console.log(id);

  var xhttp = new XMLHttpRequest();

  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById(id).innerHTML =    //Troque por innerHtml para que ele possa ler o HTML da página e exibi-la
      this.responseText;  //getAllResponseHeaders(); - Exibe os parametros do cabeçalho do arquivo
    }
  };

  xhttp.open('POST', '/0/favorite/' + id , true);
  xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xhttp.send('id='+id);
}";

echo "</script>";