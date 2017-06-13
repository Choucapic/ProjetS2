<?php

include_once 'class/webpage.class.php';
include_once 'class/mypdo.include.php';

session_start();

$p = new WebPage('Modification de Membre');

if (isset($_SESSION['login'])) {
  if ($_SESSION['grade'] == 'Administrateur') {
    if (isset($_GET['id'])) {

  // Pour Trouver le membre
  $stmt = myPDO::getInstance()->prepare(<<<SQL
          SELECT id, login, nom, prenom, grade
          FROM personne
          WHERE id = {$_GET['id']}
SQL
);
  $stmt->execute(array());
  if (($object = $stmt->fetch()) !== false) {
    $infos = $object ;

    // Pour savoir quel grade sélectionner dans le select
    $selectedAdmin = $infos['grade'] == 'Administrateur' ? 'selected' : '';
    $selectedMembre = $infos['grade'] == 'Membre' ? 'selected' : '';

    // Pour ne pas afficher le bouton 'supprimer' si c'est notre propre compte
    if ($_GET['id'] != $_SESSION['id']) {
      $deleteButton = <<<HTML
                      <a class="btn red darken-3 waves-effect waves-light" id="delMembreButton" onclick="if (confirm('Voulez vous vraiment supprimer ce Membre ?')) window.location.href='script.php?type=delMembre&id={$_GET['id']}';" name="delete">
                        Supprimer <i class="material-icons right">clear</i>
     </a>
HTML
;
    } else {
      $deleteButton = '';
    }

    $p->appendContent(<<<HTML
    <div class="container">
      <h4 class="center"> Modification de Membre </h4>
      <br>
      <form id="createMembre" method="post" name="modifyMembre" action="script.php" class="col s12">
      <div class="row">
        <div class="input-field" name="grade">
				 	<select id="insMembreSelect" name="grade">
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
            <input id="nom" type="text" class="validate" name="nom" value="{$infos['nom']}" required>
            <label for="nom">Nom</label>
            </div>

            <div class="input-field col m6 s12">
            <input id="prenom" type="text" class="validate" name="prenom" value="{$infos['prenom']}" required>
            <label for="prenom">Prénom</label>
            </div>
       </div>
       <input type="hidden" name="type" value="modifyMembre"/>
       <input type="hidden" name="id" value="{$_GET['id']}"/>
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
