<?php

include_once 'class/webpage.class.php';
include_once 'class/mypdo.include.php';

session_start();

$p = new WebPage('Création de Membre');

if (isset($_SESSION['login'])) {
  if ($_SESSION['grade'] == 'Administrateur') {

    $p->appendContent(<<<HTML
    <div class="container">
      <h4 class="center"> Inscription de Membre </h4>
      <br>
      <form id="createMembre" method="post" name="createMembre" action="script.php" class="col s12">
      <div class="row">
        <div class="input-field" name="grade">
				 	<select id="insMembreSelect" name="grade">
					 	<option value="Administrateur">Administrateur</option>
					 	<option value="Membre" selected>Membre</option>
						</select>
						<label for="grade">Type de Membre</label>
				</div>

        <div class="input-field col m6 s12">
          <input id="login" type="text" class="validate" name="login" required>
          <label for="login">Nom d'utilisateur</label>
          </div>

          <div class="input-field col m6 s12">
          <input id="password" type="password" class="validate" name="password" required>
          <label for="password">Mot de passe</label>
          </div>

          <div class="input-field col m6 s12">
            <input id="nom" type="text" class="validate" name="nom" required>
            <label for="nom">Nom</label>
            </div>

            <div class="input-field col m6 s12">
            <input id="prenom" type="text" class="validate" name="prenom" required>
            <label for="prenom">Prénom</label>
            </div>
       </div>
       <input type="hidden" name="type" value="createMembre"/>
       <div class="right btn-auth">
       <button class="btn white black-text waves-effect waves-light" type="submit" name="submit">Créer
       <i class="material-icons right">send</i>
       </button>
       </div>
       </form>
    </div>
HTML
);

  } else {
    $p->appendContent(<<<HTML
    <div class="container">
    <h5 class="center"> <i class="fa fa-times fa-5x red-text" aria-hidden="true"></i> <br> Vous n'avez pas les droits requis, vous allez être redirigé vers l'accueil</h5>
    </div>
HTML
);

    header( "refresh:3; url=index.php" );
  }
} else {
  $p->appendContent(<<<HTML
  <div class="container">
  <h5 class="center"> <i class="fa fa-times fa-5x red-text" aria-hidden="true"></i> <br> Vous n'êtes pas connecté, vous allez être redirigé vers l'accueil</h5>
  </div>
HTML
);

  header( "refresh:3; url=index.php" );
}

echo $p->toHTML();
