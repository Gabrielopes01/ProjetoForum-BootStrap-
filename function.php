<?php


function formatDate($date){

    $dateC = strtotime($date);
    $newDate = date("d/m/Y",$dateC);

    return $newDate;

}



?>