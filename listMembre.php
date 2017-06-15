<?php

include_once 'class/webpage.class.php';
include_once 'class/mypdo.include.php';

session_start();

$p = new WebPage('Liste des Membres');

if (isset($_SESSION['login'])) {
  if ($_SESSION['grade'] == 'Administrateur') {

    // Pour récupérer les Grades
		$stmt = myPDO::getInstance()->prepare("SHOW COLUMNS FROM personne WHERE Field = 'grade'" );
			$stmt->execute() ;
			$object = $stmt->fetch();
		  preg_match("/^enum\(\'(.*)\'\)$/", $object['Type'], $matches);
		  $grades = explode("','", $matches[1]);
			sort($grades);

$HTML = '<ul class="collapsible" data-collapsible="expandable">';
    foreach ($grades as $grade) {
      $HTML .= '<li>
        <div class="collapsible-header waves-effect">
        ' . $grade .'s
        </div>
        <div class="collapsible-body grey memberCollapse">';
      // For Membre en fonction du type
      $stmt = myPDO::getInstance()->prepare(<<<SQL
              SELECT id, nom, prenom, login
              FROM personne
              WHERE grade = '{$grade}'
SQL
);
      $stmt->execute(array()) ;
      $membres = array();
      while (($object = $stmt->fetch()) !== false) {
        $membres[] = $object ;
      }
      foreach ($membres as $membre) {
      $HTML .= '<div class="row member"><div class="col m6 s6"> <p style="padding-top: 15px;"> Nom : '.  $membre['nom'] . ' ' . $membre['prenom'] .  '<br> Login : '. $membre['login'] .'</p> </div> <div class="col m6 s6"><a class="black waves-effect waves-light btn right" href="modifyMembre.php?id='. $membre['id'] .'" style="margin-right: 10px; margin-top: 35px;"><i class="material-icons left">mode_edit</i>Modifier</a></div></div><hr>';
      }
    $HTML .= "</div> </li>";
    }
    $HTML .= '</ul>';

    $p->appendContent(<<<HTML
    <div class="container">
    <h4 class="center"> Liste des Membres </h4>
    <br>
    {$HTML}
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
