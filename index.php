<?php
session_start();
mb_internal_encoding("UTF-8");

require_once("vendor/autoload.php");  //Carrega as dependências do projeto
require_once("function.php");

Use \Slim\Slim;


$app = new Slim();

$app->config('debug', true);

require_once("home.php");
require_once("admin.php");
require_once("login.php");
require_once("categorie.php");
require_once("news.php");
require_once("favorite.php");
require_once("homeNews.php");
require_once("comments.php");
//require_once("testes.php");

$app->run();
