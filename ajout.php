<?php

include_once 'class/webpage.class.php';
include_once 'class/mypdo.include.php';

session_start();

$p = new WebPage('Ajout dans le Catalogue');

if (isset($_SESSION['login'])) {

  // Pour les marques
  $stmt = myPDO::getInstance()->prepare(<<<SQL
          SELECT idM, nomM
          FROM marque
SQL
);
      $stmt->execute(array()) ;
      $marques = $stmt->fetchAll();
  $marquesHTML = "";
  foreach ($marques as $marque) {
  $marquesHTML .= "<option value=\"". $marque['idM'] ."\">". $marque['nomM'] ."</option>";
  }

  // Pour les Types de matériel
  $stmt = myPDO::getInstance()->prepare(<<<SQL
          SELECT idT, nomT
          FROM type
SQL
);
      $stmt->execute(array()) ;
      $types = $stmt->fetchAll();
  $typesHTML = "";
  foreach ($types as $type) {
  $typesHTML .= "<option value=\"". $type['idT'] ."\">". $type['nomT'] ."</option>";
  }

  // Pour les Caractéristiques
  $stmt = myPDO::getInstance()->prepare(<<<SQL
          SELECT idC, nomC
          FROM caracteristique
SQL
);
      $stmt->execute(array()) ;
      $caracs = $stmt->fetchAll();
  $caracsHTML = "";
  foreach ($caracs as $carac) {
  $caracsHTML .= "<option value=\"". $carac['idC'] ."\">". $carac['nomC'] ."</option>";
  }



  $p->appendContent(<<<HTML
  <div class="container">
    <h4 class="center"> Ajout dans le Catalogue </h4>
    <br>
  <p class="center"> Veuillez sélectionner ce que vous souhaitez ajouter </p>

  <form id="ajoutCatalogue" method="post" name="ajoutCatalogue" action="script.php" class="col s12">
  <div class="row">
  <p class="center">
    <input class="with-gap radioAjout" name="table" type="radio" id="materiel" value="materiel" checked/>
    <label for="materiel">Matériel</label>

    <input class="with-gap radioAjout" name="table" type="radio" id="type" value="type" />
    <label for="type">Type de Matériel</label>

    <input class="with-gap radioAjout" name="table" type="radio" id="marque" value="marque" />
    <label for="marque">Marque</label>

    <input class="with-gap radioAjout" name="table" type="radio" id="caracteristique" value="caracteristique"/>
    <label for="caracteristique">Caractéristique</label>
    </p>

    <div id="ajoutMarqueDiv" class="input-field" name="marque">
      <select id="ajoutMarqueSelect" name="marque">
        {$marquesHTML}
        </select>
        <label for="marque">Marque</label>
    </div>

    <div id="ajoutTypeDiv" class="input-field" name="typeM">
      <select id="ajoutTypeSelect" name="typeM">
        {$typesHTML}
        </select>
        <label for="typeM">Type de Matériel</label>
    </div>

      <div id="nomDiv" class="input-field" hidden>
        <input id="nomInput" type="text" class="validate" name="nom" required disabled>
        <label for="nom">Nom</label>
        </div>

        <div id="refDiv" class="input-field col m6 s12">
        <input id="refInput" type="text" class="validate" name="ref" required>
        <label for="ref">Référence</label>
        </div>

        <div id="prixDiv" class="input-field col m6 s12">
        <input id="prixInput" type="text" class="validate" name="prix" required>
        <label for="prix">Prix</label>
        </div>
        </div>
        <div id="caracteristiques">
          <hr>
          <p class="center">Caractéristiques</p>
          <div id="champsCarac">
            <div class="row">
              <div id="ajoutCaracDiv" class="input-field col s6" name="carac[]">
                <select id="ajoutCaracSelect1" name="carac[]">
                  {$caracsHTML}
                </select>
                <label for="ajoutCaracSelect">Caractéristique</label>
              </div>
              <div class="input-field col s6">
                <input id="ajoutCaracInput1" type="text" class="validate" name="valeurCarac[]">
                <label for="valeurCarac[]">Valeur</label>
              </div>
            </div>
          </div>
          <a id="buttonAddCarac" class="btn">Ajouter une Caractéristique</a>
        </div>
   <input type="hidden" name="type" value="ajout"/>
   <div class="right btn-auth">
   <button class="btn white black-text waves-effect waves-light" type="submit" name="submit">Ajouter
   <i class="material-icons right">send</i>
   </button>
   </div>
   </form>
HTML
);

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
