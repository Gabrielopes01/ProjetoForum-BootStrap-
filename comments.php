<?php

Use \Classes\Comments;

$app->get('/news/:num/addComment/:user', function($num, $user) {

    $comment = $_GET['comentario'];

    Comments::addComment($comment, $user, $num);

});