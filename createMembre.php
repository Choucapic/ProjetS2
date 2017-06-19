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
				 	<select id="insMembreSelectGrade" name="grade">
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
            <input id="nomP" type="text" class="validate" name="nomP" required>
            <label for="nomP">Nom</label>
            </div>

            <div class="input-field col m6 s12">
            <input id="prenom" type="text" class="validate" name="prenom" required>
            <label for="prenom">Prénom</label>
            </div>

            <div class="input-field col m6 s12">
                <input id="datns" type="date" class="datepicker" name="datns" required>
                <label for="datns">Date de Naissance</label>
              </div>

                <div class="input-field col m6 s12">
                <input id="adr" type="text" class="validate" name="adr">
                <label for="adr">Adresse</label>
                </div>

                <div class="input-field col m6 s12">
                  <input id="cp" type="text" class="validate" name="cp">
                  <label for="cp">Code Postal</label>
                  </div>

                  <div class="input-field col m6 s12">
                  <input id="ville" type="text" class="validate" name="ville">
                  <label for="ville">Ville</label>
                  </div>

                  <div class="input-field col m6 s12">
                    <select id="insMembreSelectSexe" name="sexe">
                    <option value="Homme">Homme</option>
                    <option value="Femme">Femme</option>
                    </select>
                    <label for="sexe">Sexe</label>
                    </div>

                    <div class="input-field col m6 s12">
                    <input id="tel" type="tel" maxlength="10" pattern="[0-9]{10}" class="validate" name="tel">
                    <label for="tel">Téléphone</label>
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
