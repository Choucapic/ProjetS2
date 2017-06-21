<?php

include_once 'class/webpage.class.php';
include_once 'class/mypdo.include.php';

session_start();

$p = new WebPage('Modification de Membre');

if (isset($_SESSION['login'])) {
  if ($_SESSION['grade'] == 'Administrateur') {
    if (isset($_GET['idP'])) {

  // Pour Trouver le membre
  $stmt = myPDO::getInstance()->prepare(<<<SQL
          SELECT *
          FROM personne
          WHERE idP = {$_GET['idP']}
SQL
);
  $stmt->execute(array());
  if (($object = $stmt->fetch()) !== false) {
    $infos = $object ;

    // Pour mettre un bon format pour la date d'ajout
    setlocale(LC_ALL, 'fra', 'fr_FR.UTF-8');
    $dateAjout = strftime("%d %B %Y", strtotime($infos['datAjout']));

    // Pour savoir quel grade sélectionner dans le select
    $selectedAdmin = $infos['grade'] == 'Administrateur' ? 'selected' : '';
    $selectedMembre = $infos['grade'] == 'Membre' ? 'selected' : '';

    // Pour savoir quel sexe sélectionner dans le select
    $selectedHomme = $infos['sexe'] == 'Homme' ? 'selected' : '';
    $selectedFemme = $infos['sexe'] == 'Femme' ? 'selected' : '';

    // Pour ne pas afficher le bouton 'supprimer' si c'est notre propre compte
    if ($_GET['idP'] != $_SESSION['idP']) {
      $deleteButton = <<<HTML
                      <a class="btn red darken-3 waves-effect waves-light" id="delMembreButton" onclick="if (confirm('Voulez vous vraiment supprimer ce Membre ?')) window.location.href='script.php?type=delMembre&idP={$_GET['idP']}';" name="delete">
                        Supprimer <i class="material-icons right">clear</i>
     </a>
HTML
;
      $dateDepartPicker = <<<HTML
      <br>
      <p class="center">Si l'utilisateur est désormais parti et que vous souhaitez clôturer son compte, veuillez spécifier une date de départ :</p>
      <div class="col m4 s0"></div>
      <div class="input-field col s4">
          <input id="datDepart" type="date" class="datepicker" name="datDepart" value="{$infos['datDepart']}">
          <label for="datDepart">Date de Départ</label>
        </div>
        <div class="col m4 s0"></div>
HTML;
    } else {
      $deleteButton = '';
      $dateDepartPicker = '';
    }

    $p->appendContent(<<<HTML
    <div class="container">
      <h4 class="center"> Modification de Membre </h4>
      <br>
      <form id="modifyMembre" method="post" name="modifyMembre" action="script.php" class="col s12">
      <div class="row">
        <div class="input-field" name="grade">
				 	<select id="insMembreSelectGrade" name="grade">
					 	<option value="Administrateur" {$selectedAdmin}>Administrateur</option>
					 	<option value="Membre" {$selectedMembre}>Membre</option>
						</select>
						<label for="grade">Type de Membre</label>
				</div>

        <div class="input-field col m6 s12">
          <input id="login" type="text" class="validate" name="login" value="{$infos['login']}" required>
          <label for="login">Nom d'utilisateur</label>
          </div>

          <div class="input-field col m6 s12">
          <input id="password" type="password" class="validate" name="password">
          <label for="password">Mot de passe (laisser vide pour ne pas le changer)</label>
          </div>

          <div class="input-field col m6 s12">
            <input id="nomP" type="text" class="validate" name="nomP" value="{$infos['nomP']}" required>
            <label for="nomP">Nom</label>
            </div>

            <div class="input-field col m6 s12">
            <input id="prenom" type="text" class="validate" name="prenom" value="{$infos['prenom']}" required>
            <label for="prenom">Prénom</label>
            </div>

          <div class="input-field col m6 s12">
              <input id="datns" type="date" class="datepicker" name="datns" value="{$infos['datns']}" required>
              <label for="datns">Date de Naissance</label>
            </div>

              <div class="input-field col m6 s12">
              <input id="adr" type="text" class="validate" name="adr" value="{$infos['adr']}">
              <label for="adr">Adresse</label>
              </div>

              <div class="input-field col m6 s12">
                <input id="cp" type="text" class="validate" name="cp" value="{$infos['cp']}">
                <label for="cp">Code Postal</label>
                </div>

                <div class="input-field col m6 s12">
                <input id="ville" type="text" class="validate" name="ville" value="{$infos['ville']}">
                <label for="ville">Ville</label>
                </div>

                <div class="input-field col m6 s12">
                  <select id="insMembreSelectSexe" name="sexe">
                  <option value="Homme" {$selectedHomme}>Homme</option>
                  <option value="Femme" {$selectedFemme}>Femme</option>
                  </select>
                  <label for="sexe">Sexe</label>
                  </div>

                  <div class="input-field col m6 s12">
                  <input id="tel" type="tel" maxlength="10" pattern="[0-9]{10}" class="validate" name="tel" value="{$infos['tel']}">
                  <label for="tel">Téléphone</label>
                  </div>

                  <p class="center">Date d'ajout de cet utilisateur : {$dateAjout} </p>
                  {$dateDepartPicker}
       </div>
       <input type="hidden" name="type" value="modifyMembre"/>
       <input type="hidden" name="idP" value="{$_GET['idP']}"/>
       <div class="right btn-auth">
         {$deleteButton}
       <button class="btn white black-text waves-effect waves-light" type="submit" name="submit">Modifier
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
    <h5 class="center"> <i class="fa fa-times fa-5x red-text" aria-hidden="true"></i> <br> L'id ne correspond à aucun Membre</h5>
    </div>
HTML
    );

    header( "refresh:5; url=index.php" );
}
  } else {
$p->appendContent(<<<HTML
<div class="container">
<h5 class="center"> <i class="fa fa-times fa-5x red-text" aria-hidden="true"></i> <br> L'id ne correspond à aucun Membre</h5>
</div>
HTML
);

header( "refresh:5; url=index.php" );
}
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
