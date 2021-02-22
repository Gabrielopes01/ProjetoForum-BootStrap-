<?php
    Use \Classes\News;
    Use \Classes\User;
    Use \Classes\Comments;

    require_once("function.php");

    $noticia = News::getNewsById($num);
    $comments = Comments::getALLcomments();

    if (isset($_SESSION['email'])){
        $email = $_SESSION['email'];
        $usuario = User::getUserByEmail($email);
    } else {
        $email = 'null';
        $usuario = [];
    }

    //Página
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

    echo "<div class='row' style='height: 100%'>";
    echo "<hr>";
    echo "<div class='col-12 col-md-10 offset-md-1'>";
    echo "<p style='text-align: left; color:#cccccc; font-size:15px'>".$noticia['Resumo']."</p>";
    echo "<hr>";
    echo "<div id='newsText' style='text-shadow: 0px 1px 0px black; background-color: #d9d9d9; color: black; padding: 20px;'>".$noticia["Corpo"]."</div>";
    echo "<small class='text-muted' style='float: right;'>Publicado em ". formatDate($noticia['Data']). " por <strong>". $noticia['Usuario']."</strong> <i class='fas fa-eye'></i> ".$noticia["Visualizacao"]. "</small>";
    echo "</div>";
    echo "</div>";
    echo "<div class='row' style='margin-top: 100px'> <hr style='color:white'>";
    echo "<div class='col-md-5 offset-md-1'>
    <p id='comText'>Deixe seu Comentário:</p>
    </div>
    <div class='col-md-5 d-none d-sm-block'>
        <p id='comText'>Comentários</p>
    </div>
    <div class='col-10 col-md-5 offset-md-1 offset-1'>";
    if($email == 'null'){
        echo "<p style='color:grey'>Você presica ter uma conta e estar conectado para comentar</p>";
    } else {
        echo "<form method='GET' action='/news/$num/addComment/".$usuario['id']."'>";
        echo "<textarea id='comments' name='comentario' rows='4' class='form-control' placeholder='Insira sua Mensagem...' style='margin-top:10px; width:100%;'></textarea>";
        echo "<button type='submit' class='btn btn-success' style='float: right; margin-top: 5px; width:100%'>Enviar</a>";
        echo "</form>";
    }
    echo "</div>";
    echo "<div class=' col-md-4 offset-md-1 offset-1 d-none d-sm-block' style='height: 260px; width:600px; overflow:auto; float:right; margin-left: 10px'>";
    foreach ($comments as $comment) {
            if ($comment['noticia'] == $num){
                echo "<img class='rounded-circle' src='../res/defaults/user.png' style='float:left; height: 50px; margin:10px'>";
                echo "<div style='background-color: #d9d9d9; border-radius: 0px 10px 10px 10px; padding: 6px; border: 3px solid black; margin-bottom:5px'>";
                    echo "<p class='text-muted' style='float:right; font-size:12px; margin-right: 5px'>".formatDate($comment['data'])."</p>";
                    echo "<h5>".$comment['nome']."</h5>";
                    echo "<p class='text-muted' style='font-size:12px'>".$comment['email']."</p>";
                    echo "<p style='margin-left: 20px'> - ".$comment['descricao']."</p>";
                echo "</div>";
            }
    }
    echo "</div>";
    echo "<input type='hidden' name='usuario-co' id='usuario-co' value='".$email."'>";
    echo "</div>";
    echo "<br><br><br><br><br>";


echo "<script>";
echo "function favButtonC(id) {

  console.log(id);

  $.post('/0/favorite/' + id)
    .done(function(data) {
      $('#'+id).html(data);
    })
    .fail(function(){
      $('#'+id).html('Erro');
    });
}";
echo "</script>";