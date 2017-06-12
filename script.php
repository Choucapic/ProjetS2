<?php

session_start();

include_once 'class/webpage.class.php';

if (isset($_GET['type'])) {
    
    $pageName = "get TYPE";
    $error = true;
    $message = 'Problème de paramètre, vous allez être redirigé vers l\'accueil';
    $time = 5;
    $url = 'index';
    
    } else if (isset($_POST['type'])) {
    $pageName = "post TYPE";
    $error = true;
    $message = 'Problème, vous allez être redirigé vers l\'accueil';
    $time = 5;
    $url = 'index';
} else {
    $pageName = "Nothing";
    $error = true;
    $message = 'Problème, vous allez être redirigé vers l\'accueil';
    $time = 5;
    $url = 'index';
}


$errorIcon = $error ? '<i class="fa fa-times fa-5x red-text" aria-hidden="true"></i>' : '<i class="fa fa-check fa-5x green-text" aria-hidden="true"></i>';

$page = new WebPage($pageName);

$page->appendContent(<<<HTML
<div class="container">
<h5 class="center"> {$errorIcon} <br> {$message}</h5>
</div>
HTML
);

header( "refresh:".$time."; url=".$url.".php" );

echo $page->toHTML();