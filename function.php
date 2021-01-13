<?php

Use \Slim\Slim;
Use \Classes\Page;
Use \Classes\PageAdmin;
Use \Classes\Sql;

function formatDate($date){

    $dateC = strtotime($date);
    $newDate = date("d/m/Y",$dateC);

    return $newDate;

}






?>