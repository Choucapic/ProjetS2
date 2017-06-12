<?php

include_once 'class/webpage.class.php';
    
$p = new WebPage('index');

$p->appendContent(<<<HTML
    <div class="container">
    <h4 class="center">Bienvenue sur le site de gestion de stock Informatique</h4>
    
    <form id="connect" method="post" name="authentification" action="script.php" class="col s12">
    <div class="row">
      <div class="input-field col m6 s12">
        <i class="prefix fa fa-at"></i>
        <input id="login" type="email" class="validate" name="login" required>
        <label for="login">Adresse Mail</label>
        </div>

        <div class="input-field col m6 s12">
        <i class="prefix fa fa-lock"></i>
        <input id="password" type="password" class="validate" name="password" required>
        <label for="password">Mot de passe</label>
        </div>
     </div>
     <input type="hidden" name="type" value="connection"/>
     <div class="center btn-a
     uth">
     <button class="btn white black-text waves-effect waves-light" type="submit" name="submit">Se connecter
     <i class="material-icons right">send</i>
     </button>
     </div>
     </form>
    
    <div class="carousel">
    <a class="carousel-item" href="#one!"><img src="http://lorempixel.com/250/250/nature/1"></a>
    <a class="carousel-item" href="#two!"><img src="http://lorempixel.com/250/250/nature/2"></a>
    <a class="carousel-item" href="#three!"><img src="http://lorempixel.com/250/250/nature/3"></a>
    <a class="carousel-item" href="#four!"><img src="http://lorempixel.com/250/250/nature/4"></a>
    <a class="carousel-item" href="#five!"><img src="http://lorempixel.com/250/250/nature/5"></a>
  </div>
  </div>
HTML
);

echo $p->toHTML();