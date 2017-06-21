<?php

include_once 'class/webpage.class.php';
include_once 'class/mypdo.include.php';

session_start();

$p = new WebPage('Catalogue');

// Pour récupérer les types de matériels
$stmt = myPDO::getInstance()->prepare(<<<SQL
        SELECT idT, nomT
        FROM type
        ORDER BY nomT
SQL
);
$stmt->execute();
$types = $stmt->fetchAll();

$HTML = '<ul id="typeMat" class="collapsible" data-collapsible="expandable">';
    foreach ($types as $type) {
      $HTML .= '<li>
        <div class="collapsible-header waves-effect">
        ' . $type['nomT'] .'
        </div>
        <div class="collapsible-body grey memberCollapse">';
      // Pour les matériels en fonction du type
      $stmt = myPDO::getInstance()->prepare(<<<SQL
              SELECT ref, prix, nomM
              FROM materiel, marque
              WHERE idT = {$type['idT']} AND materiel.idM = marque.idM
SQL
);
      $stmt->execute(array()) ;
      $materiels = array();
      while (($object = $stmt->fetch()) !== false) {
        $materiels[] = $object ;
      }
      foreach ($materiels as $materiel) {
        if (isset($_SESSION['login'])) {
          $modifyButton = '<a class="black waves-effect waves-light btn right" href="modifyMateriel.php?ref='. $materiel['ref'] .'" style="margin-right: 10px; margin-top: 45px;"><i class="material-icons left">mode_edit</i>Modifier</a>';
        } else {
          $modifyButton = '';
        }
      $HTML .= '<div class="row materiel"><div class="col m6 s6"> <p style="padding-top: 15px;"> Référence : '.  $materiel['ref'] .'<br> Prix : '. $materiel['prix'] .'€<br> Marque : ' . $materiel['nomM'] . '</p> </div> <div class="col m6 s6"><a class="white waves-effect waves-light btn right black-text" href="seeMateriel.php?ref='. $materiel['ref'] .'" style="margin-right: 10px; margin-top: 45px;"><i class="material-icons left">search</i>Voir</a>'. $modifyButton .'</div></div><hr>';
      }
    $HTML .= "</div> </li>";
    }

    $HTML .= '</ul>';

    // Pour récupérer les marques de matériels
    $stmt = myPDO::getInstance()->prepare(<<<SQL
            SELECT idM, nomM
            FROM marque
            ORDER BY nomM
SQL
);
    $stmt->execute();
    $marques = $stmt->fetchAll();

    $HTML .= '<ul id="marqueMat" class="collapsible" data-collapsible="expandable" hidden>';
        foreach ($marques as $marque) {
          $HTML .= '<li>
            <div class="collapsible-header waves-effect">
            ' . $marque['nomM'] .'
            </div>
            <div class="collapsible-body grey memberCollapse">';
          // Pour les matériels en fonction de la marque
          $stmt = myPDO::getInstance()->prepare(<<<SQL
                  SELECT ref, prix, nomT
                  FROM materiel, type
                  WHERE idM = {$marque['idM']} AND materiel.idT = type.idT
SQL
);
          $stmt->execute(array()) ;
          $materiels = array();
          while (($object = $stmt->fetch()) !== false) {
            $materiels[] = $object ;
          }
          foreach ($materiels as $materiel) {
            if (isset($_SESSION['login'])) {
              $modifyButton = '<a class="black waves-effect waves-light btn right" href="modifyMateriel.php?ref='. $materiel['ref'] .'" style="margin-right: 10px; margin-top: 45px;"><i class="material-icons left">mode_edit</i>Modifier</a>';
            } else {
              $modifyButton = '';
            }
          $HTML .= '<div class="row materiel"><div class="col m6 s6"> <p style="padding-top: 15px;"> Référence : '.  $materiel['ref'] .'<br> Prix : '. $materiel['prix'] .'€<br> Type de matériel : ' . $materiel['nomT'] . '</p> </div> <div class="col m6 s6"><a class="white waves-effect waves-light btn right black-text" href="seeMateriel.php?ref='. $materiel['ref'] .'" style="margin-right: 10px; margin-top: 45px;"><i class="material-icons left">search</i>Voir</a>'. $modifyButton .'</div></div><hr>';
          }
        $HTML .= "</div> </li>";
        }

    $HTML .= '</ul>';

    // Pour les matériels dans l'ordre alphabéthique

    $lettres = ['a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'];

    $HTML .= '<ul id="lettreMat" class="collapsible" data-collapsible="expandable" hidden>';
        foreach ($lettres as $lettre) {
          $HTML .= '<li>
            <div class="collapsible-header waves-effect">
            ' . strtoupper($lettre) .'
            </div>
            <div class="collapsible-body grey memberCollapse">';
          // Pour les matériels en fonction de la lettre
          $stmt = myPDO::getInstance()->prepare(<<<SQL
                  SELECT ref, prix, nomT, nomM
                  FROM materiel, type, marque
                  WHERE LOWER(ref) LIKE '{$lettre}%' AND materiel.idT = type.idT AND materiel.idM = marque.idM
SQL
);
          $stmt->execute(array()) ;
          $materiels = array();
          while (($object = $stmt->fetch()) !== false) {
            $materiels[] = $object ;
          }
          foreach ($materiels as $materiel) {
            if (isset($_SESSION['login'])) {
              $modifyButton = '<a class="black waves-effect waves-light btn right" href="modifyMateriel.php?ref='. $materiel['ref'] .'" style="margin-right: 10px; margin-top: 60px;"><i class="material-icons left">mode_edit</i>Modifier</a>';
            } else {
              $modifyButton = '';
            }
          $HTML .= '<div class="row materiel"><div class="col m6 s6"> <p style="padding-top: 15px;"> Référence : '.  $materiel['ref'] .'<br> Prix : '. $materiel['prix'] .'€<br> Type de matériel : ' . $materiel['nomT'] . '<br> Marque : ' . $materiel['nomM'] . '</p> </div> <div class="col m6 s6"><a class="white waves-effect waves-light btn right black-text" href="seeMateriel.php?ref='. $materiel['ref'] .'" style="margin-right: 10px; margin-top: 60px;"><i class="material-icons left">search</i>Voir</a>'. $modifyButton .'</div></div><hr>';
          }
        $HTML .= "</div> </li>";
        }

        // Pour tout ce qui n'est pas une lettre
        $HTML .= '<li>
          <div class="collapsible-header waves-effect">
          Autres
          </div>
          <div class="collapsible-body grey memberCollapse">';

        $stmt = myPDO::getInstance()->prepare(<<<SQL
                SELECT ref, prix, nomT, nomM
                FROM materiel, type, marque
                WHERE ref REGEXP '^[^A-Za-z]' AND materiel.idT = type.idT AND materiel.idM = marque.idM
SQL
);
        $stmt->execute(array()) ;
        $materiels = array();
        while (($object = $stmt->fetch()) !== false) {
          $materiels[] = $object ;
        }
        foreach ($materiels as $materiel) {
          if (isset($_SESSION['login'])) {
            $modifyButton = '<a class="black waves-effect waves-light btn right" href="modifyMateriel.php?ref='. $materiel['ref'] .'" style="margin-right: 10px; margin-top: 60px;"><i class="material-icons left">mode_edit</i>Modifier</a>';
          } else {
            $modifyButton = '';
          }
        $HTML .= '<div class="row materiel"><div class="col m6 s6"> <p style="padding-top: 15px;"> Référence : '.  $materiel['ref'] .'<br> Prix : '. $materiel['prix'] .'€<br> Type de matériel : ' . $materiel['nomT'] . '<br> Marque : ' . $materiel['nomM'] . '</p> </div> <div class="col m6 s6"><a class="white waves-effect waves-light btn right black-text" href="seeMateriel.php?ref='. $materiel['ref'] .'" style="margin-right: 10px; margin-top: 60px;"><i class="material-icons left">search</i>Voir</a>'. $modifyButton .'</div></div><hr>';
        }
      $HTML .= "</div> </li>";

    $HTML .= '</ul>';

    $p->appendContent(<<<HTML
    <div class="container">
    <h4 class="center"> Catalogue </h4>
    <br>
  <p class="center"> Veuillez sélectionner le type de classement des matériels : </p>
  <div class="row">
  <p class="center">
    <input class="with-gap radioCatalogue" name="search" type="radio" id="typeM" value="typeM" checked/>
    <label for="typeM">par Type de Matériel</label>

    <input class="with-gap radioCatalogue" name="search" type="radio" id="marque" value="marque" />
    <label for="marque">par Marque</label>

    <input class="with-gap radioCatalogue" name="search" type="radio" id="alpha" value="alpha" />
    <label for="alpha">par ordre Alphabétique</label>
    </p>
  </div>
    <br>
    {$HTML}
    </div>
HTML
);

echo $p->toHTML();
