<?php
session_start();

require_once("vendor/autoload.php");
require_once("function.php");

Use \Slim\Slim;


$app = new Slim();

$app->config('debug', true);

require_once("home.php");
require_once("admin.php");
require_once("login.php");
require_once("categorie.php");
require_once("news.php");

$app->run();



?>