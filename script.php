<?php

session_start();

include_once 'class/webpage.class.php';
include_once 'class/mypdo.include.php';

if (isset($_GET['type'])) {
  switch ($_GET['type']) {
    /* -------------------- Deconnexion -------------------- */
    case 'disconnection' :
      $pageName = 'Déconnexion';
      if (isset($_SESSION['login'])) {
        $error = false;
        $message = 'Vous vous êtes bien déconnecté, vous allez être redirigé vers l\'accueil';

        $_SESSION = array();
        session_destroy();
      } else {
        $error = true;
        $message = 'Vous n\'êtes pas connecté, vous allez être redirigé vers l\'accueil';
      }
      $time = 3;
      $url = 'index';
      break;
    /* -------------------- Supression de Membre -------------------- */
    case 'delMembre' :
    $pageName = 'Supression de Membre';
    if (isset($_SESSION['login'])) {
      if ($_SESSION['grade'] == 'Administrateur') {
        if (isset($_GET['id'])) {
          if ($_GET['id'] != '') {
            $stmt = myPDO::getInstance()->prepare(<<<SQL
                   DELETE FROM `personne`
                   WHERE id = {$_GET['id']}
SQL
               ) ;
            $stmt->execute();
            $error = false;
            $message = 'Le membre a bien été supprimé, vous allez être redirigé automatiquement';
            $time = 5;
            $url = 'listMembre';
          } else {
            $error = true;
            $message = 'Certains paramètres sont vides, vous allez être redirigé automatiquement';
            $time = 5;
            $url = 'listMembre';
          }
        } else {
          $error = true;
          $message = 'Problème de paramètres, vous allez être redirigé automatiquement';
          $time = 5;
          $url = 'listMembre';
        }
      } else {
        $error = true;
        $message = 'Vous n\'avez pas les droits requis, vous allez être redirigé vers l\'accueil';
        $time = 5;
        $url = 'index';
      }
    } else {
      $error = true;
      $message = 'Vous n\'êtes pas connecté, vous allez être redirigé vers l\'accueil';
      $time = 5;
      $url = 'index';
    }
      break;
    default :
      $pageName = "Erreur GET";
      $error = true;
      $message = 'Un problème inconnu est survenu, vous allez être redirigé vers l\'accueil';
      $time = 5;
      $url = 'index';
  }
} else if (isset($_POST['type'])) {
  switch ($_POST['type']) {
  /* -------------------- Connection -------------------- */
  case 'connection' :
    $pageName = "Connexion";
    if (isset($_SESSION['login'])) {
      $url="index";
      $error = true;
      $message="Vous êtes déjà connecté";
      $time=3;
    } else if (isset($_POST['submit'])) {
      $login  = (isset($_POST['login'])) ? htmlentities(trim($_POST['login'])) : '';
      $password   = (isset($_POST['password'])) ? sha1(htmlentities(trim($_POST['password'])))   : '';

      if (($login != '') && ($password != '')) {

        $pdo = myPDO::getInstance();
        $stmt = $pdo->prepare(<<<SQL
                              SELECT id, login, nom, prenom, grade
                              FROM personne
                              WHERE login = "{$login}" AND mdp = "{$password}"
SQL
                              ) ;

        $stmt->execute();
        if (($result = $stmt->fetch()) !== false) {
          $_SESSION['id'] = $result['id'];
          $_SESSION['login'] = $result['login'];
          $_SESSION['nom'] = $result['nom'];
          $_SESSION['prenom'] = $result['prenom'];
          $_SESSION['grade'] = $result['grade'];

          $url="index";
          $error = false;
          $message='Vous êtes bien connecté, vous allez être redirigé vers l\'accueil';
          $time=3;
        } else {
          $url="index";
          $error = true;
          $message = 'Login ou de mot de passe incorrect <br> Vous allez être redirigé automatiquement';
          $time=5;
        }

      } else {
        $error = true;
        $url="index";
        $message = 'Login ou mot de passe vide <br> Vous allez être redirigé automatiquement';
        $time=5;
      }
    }
    break;
  /* -------------------- Création de Membre -------------------- */
  case 'createMembre' :
    $pageName = 'Création de Membre';
    if (isset($_SESSION['login'])) {
      if ($_SESSION['grade'] == 'Administrateur') {
        if (isset($_POST['login']) && isset($_POST['password']) && isset($_POST['grade']) && isset($_POST['nom']) && isset($_POST['prenom'])) {
          if ($_POST['login'] != '' && $_POST['password'] != '' && $_POST['grade'] != '' && $_POST['nom'] != '' && $_POST['prenom'] != '') {
            $login = htmlentities(trim($_POST['login']));
            $password = sha1(htmlentities(trim($_POST['password'])));
            $nom = htmlentities(trim($_POST['nom']));
            $prenom =  htmlentities(trim($_POST['prenom']));

            $pdo = myPDO::getInstance();
            $stmt = $pdo->prepare(<<<SQL
                                  INSERT INTO personne (`login`, `mdp`, `grade`, `nom`, `prenom`) VALUES ('{$login}', '{$password}', '{$_POST['grade']}', '{$nom}', '{$prenom}');
SQL
) ;

            $stmt->execute();
            $error = false;
            $message = 'Le membre ' . $nom . ' ' . $prenom . ' a bien été créé, vous allez être redirigé vers l\'accueil' ;
            $time = 3;
            $url = 'index';

          } else {
            $error = true;
            $message = 'Certains paramètres sont vides, vous allez être redirigé automatiquement';
            $time = 5;
            $url = 'createMembre';
          }
        } else {
          $error = true;
          $message = 'Problème de paramètres, vous allez être redirigé automatiquement';
          $time = 5;
          $url = 'createMembre';
        }
      } else {
        $error = true;
        $message = 'Vous n\'avez pas les droits requis, vous allez être redirigé vers l\'accueil';
        $time = 5;
        $url = 'index';
      }
    } else {
      $error = true;
      $message = 'Vous n\'êtes pas connecté, vous allez être redirigé vers l\'accueil';
      $time = 5;
      $url = 'index';
    }
    break;
  /* -------------------- Modification de Membre -------------------- */
  case 'modifyMembre' :
    $pageName = 'Modification de Membre';
    if (isset($_SESSION['login'])) {
      if ($_SESSION['grade'] == 'Administrateur') {
        if (isset($_POST['id']) && isset($_POST['login']) && isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['grade'])) {
          if ($_POST['id'] != '' && $_POST['login'] != '' && $_POST['nom'] != '' && $_POST['prenom'] != '' && $_POST['grade'] != '') {

            $login = htmlentities(trim($_POST['login']));
            $nom = htmlentities(trim($_POST['nom']));
            $prenom =  htmlentities(trim($_POST['prenom']));

            $setPassword = '';
            if (isset($_POST['password']) && $_POST['password'] != '') {
              $password = sha1(htmlentities(trim($_POST['password'])));
              $setPassword = ', mdp = \'' . $password . '\'';
            }


            $stmt = myPDO::getInstance()->prepare(<<<SQL
                UPDATE personne
                SET login = '{$login}', nom = '{$nom}', prenom = '{$prenom}', grade = '{$_POST['grade']}'{$setPassword}
                WHERE id = {$_POST['id']}
SQL
);
          $stmt->execute();
          $error = false;
          $url="index";
          $message = 'Le Membre a bien été modifié, <br> Vous allez être redirigé vers l\'accueil';
          $time=3;

          } else {
            $error = true;
            $message = 'Certains paramètres sont vides, vous allez être redirigé automatiquement';
            $time = 5;
            $url = 'listMembre';
          }
        } else {
          $error = true;
          $message = 'Problème de paramètres, vous allez être redirigé automatiquement';
          $time = 5;
          $url = 'listMembre';
        }
      } else {
        $error = true;
        $message = 'Vous n\'avez pas les droits requis, vous allez être redirigé vers l\'accueil';
        $time = 5;
        $url = 'index';
      }
    } else {
      $error = true;
      $message = 'Vous n\'êtes pas conencté, vous allez être redirigé vers l\'accueil';
      $time = 5;
      $url = 'index';
    }
    break;
  default :
    $pageName = "Erreur POST";
    $error = true;
    $message = 'Un problème inconnu est survenu, vous allez être redirigé vers l\'accueil';
    $time = 5;
    $url = 'index';
  }
} else {
    $pageName = "Nothing";
    $error = true;
    $message = 'Il n\'y a rien à traiter, vous allez être redirigé vers l\'accueil';
    $time = 5;
    $url = 'index';
}


$errorIcon = $error ? '<i class="fa fa-times fa-5x red-text" aria-hidden="true"></i>' : '<i class="fa fa-check fa-5x green-text" aria-hidden="true"></i>';

$page = new WebPage($pageName);

$page->appendContent(<<<HTML
<div class="container">
<h5 class="center"> {$errorIcon} <br> {$message}</h5>
</div>
HTML
);

header( "refresh:".$time."; url=".$url.".php" );

echo $page->toHTML();
