<?php

include_once 'class/webpage.class.php';
include_once 'class/mypdo.include.php';

session_start();

$p = new WebPage('Matériel');

if (isset($_GET['ref'])) {
  if ($_GET['ref'] != '') {

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

      // Pour Trouver les caractéristiques
      $stmt = myPDO::getInstance()->prepare(<<<SQL
              SELECT nomC, valeur
              FROM valeurcaracteristique, caracteristique
              WHERE ref = '{$_GET['ref']}' AND id = idC
SQL
);
$stmt->execute(array());
$caracHTML = '';
while (($object = $stmt->fetch()) !== false) {
  $caracHTML .= '<div class="col xl3 m6 s12">
    <h5 class="center"><u>'. $object['nomC'] .' :</u> '. $object['valeur'] . (($object['nomC'] == 'Poids') ? 'g' : '') .' </h5>
  </div>';
}
      $p->setTitle('Matériel ' . $infos['ref']);

      $p->appendContent(<<<HTML
      <div class="container">
        <br>
        <h4 class="center"> Informations du le matériel {$infos['ref']} </h4>
        <br>
        <br>
        <div class="row">
          <div class="col xl3 m6 s12">
            <h5 class="center"><u>Référence :</u> {$infos['ref']}</h5>
          </div>

          <div class="col xl3 m6 s12">
            <h5 class="center"><u>Prix :</u> {$infos['prix']}€</h5>
          </div>

          <div class="col xl3 m6 s12">
            <h5 class="center"><u>Marque :</u> {$infos['nomM']}</h5>
          </div>

          <div class="col xl3 m6 s12">
            <h5 class="center"><u>Type de Matériel :</u> {$infos['nomT']}</h5>
          </div>
        </div>
          <hr>

          <h5 class="center"> Caractéristiques : </h5>
          <div class="row">
          {$caracHTML}
        </div>
          <hr>

          <div class="row">
          <div class="col s12">
            <h5 class="center"><u>Ajouté par :</u> {$infos['nomP']} {$infos['prenom']}</h5>
          </div>
        </div>
      </div>
HTML
);

} else {
  $p->appendContent(<<<HTML
  <div class="container">
  <h5 class="center"> <i class="fa fa-times fa-5x red-text" aria-hidden="true"></i> <br> La référence ne correspond à aucun Matériel</h5>
  </div>
HTML
  );

  header( "refresh:5; url=index.php" );
}
  } else {
    $p->appendContent(<<<HTML
    <div class="container">
    <h5 class="center"> <i class="fa fa-times fa-5x red-text" aria-hidden="true"></i> <br> La référence ne correspond à aucun Matériel</h5>
    </div>
HTML
    );

    header( "refresh:5; url=index.php" );
}
  } else {
$p->appendContent(<<<HTML
<div class="container">
<h5 class="center"> <i class="fa fa-times fa-5x red-text" aria-hidden="true"></i> <br> La référence ne correspond à aucun Matériel</h5>
</div>
HTML
);

header( "refresh:5; url=index.php" );
}

echo $p->toHTML();
