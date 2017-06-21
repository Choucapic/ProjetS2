<?php

include_once 'class/webpage.class.php';
include_once 'class/mypdo.include.php';

session_start();

$p = new WebPage('Modification de Matériel');

if (isset($_SESSION['login'])) {
    if (isset($_GET['ref'])) {

      // Pour Trouver le matériel
      $stmt = myPDO::getInstance()->prepare(<<<SQL
              SELECT ref, prix, nomM, nomT, nomP, prenom
              FROM materiel, marque, type, personne
              WHERE ref = '{$_GET['ref']}' AND materiel.idM = marque.idM AND materiel.idT = type.idT AND materiel.idP = personne.idP
SQL
);
  $stmt->execute(array());
  if (($object = $stmt->fetch()) !== false) {
    $infos = $object ;

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
    $marquesHTML .= "<option value=\"". $marque['idM'] ."\"". (($marque['nomM'] == $infos['nomM']) ? "selected" : "") .">". $marque['nomM'] ."</option>";
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
    $typesHTML .= "<option value=\"". $type['idT'] ."\"". (($type['nomT'] == $infos['nomT']) ? "selected" : "") .">". $type['nomT'] ."</option>";
    }

    // Pour les Caractéristiques
    $stmt = myPDO::getInstance()->prepare(<<<SQL
            SELECT idC, nomC
            FROM caracteristique
SQL
);
        $stmt->execute(array()) ;
        $caracs = $stmt->fetchAll();

    // Pour récupérer les caractéristiques du matériel
    $stmt = myPDO::getInstance()->prepare(<<<SQL
            SELECT nomC, valeur
            FROM valeurcaracteristique, caracteristique
            WHERE ref = '{$_GET['ref']}' AND id = idC
SQL
);
$stmt->execute(array());
$counter = 1;
$caracsHTML = '';
$nameCarac = array();
while (($object = $stmt->fetch()) !== false) {
  $optionsCarac = '';
  foreach ($caracs as $carac) {
  $optionsCarac .= "<option value=\"". $carac['idC'] ."\"". (($object['nomC'] == $carac['nomC']) ? "selected" : "") . ((in_array($carac['nomC'], $nameCarac)) ? "disabled" : "") .">". $carac['nomC'] ."</option>";
  }
array_push($nameCarac, $object['nomC']);

  $caracsHTML .= <<<HTML
  <div class="row">
  <div id="ajoutCaracDiv" class="input-field col s6" name="carac[]">
    <select id="ajoutCaracSelect{$counter}" name="carac[]">
      {$optionsCarac}
    </select>
    <label for="ajoutCaracSelect">Caractéristique</label>
  </div>
  <div class="input-field col s6">
    <input id="ajoutCaracInput1" type="text" class="validate" name="valeurCarac[]" value="{$object['valeur']}">
    <label for="valeurCarac[]">Valeur</label>
  </div>
</div>
HTML
;
}

    $p->appendContent(<<<HTML
    <div class="container">
      <h4 class="center"> Modification de Matériel </h4>
      <br>
      <form id="modifyMateriel" method="post" name="modifyMateriel" action="script.php" class="col s12">
      <div class="row">
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

        <div id="refDiv" class="input-field col m6 s12">
        <input id="refInput" type="text" class="validate" name="ref" value="{$infos['ref']}" required>
        <label for="ref">Référence</label>
        </div>

        <div id="prixDiv" class="input-field col m6 s12">
        <input id="prixInput" type="text" class="validate" name="prix" value="{$infos['prix']}" required>
        <label for="prix">Prix</label>
        </div>
        </div>
        <div id="caracteristiques">
          <hr>
          <p class="center">Caractéristiques</p>
          <div id="champsCarac">
              {$caracsHTML}
          </div>
          <a id="buttonAddCarac" class="btn">Ajouter une Caractéristique</a>

       </div>
       <input type="hidden" name="type" value="modifyMateriel"/>
       <input type="hidden" name="oldRef" value="{$_GET['ref']}"/>
       <div class="right btn-auth">
         <a class="btn red darken-3 waves-effect waves-light" id="delMaterielButton" onclick="if (confirm('Voulez vous vraiment supprimer ce Matériel ?')) window.location.href='script.php?type=delMateriel&ref={$_GET['ref']}';" name="delete">
           Supprimer <i class="material-icons right">clear</i></a>
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
<h5 class="center"> <i class="fa fa-times fa-5x red-text" aria-hidden="true"></i> <br> La référence ne correspond à aucun matériel</h5>
</div>
HTML
);

header( "refresh:5; url=index.php" );
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
