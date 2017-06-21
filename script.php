<?php

session_start();

include_once 'class/webpage.class.php';
include_once 'class/mypdo.include.php';

// Spécifier le temps de redirection en seconde : (Pour lire le message)
$redirectGood = 3;
$redirectError = 5;

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
      $time = $redirectGood;
      $url = 'index';
      break;
    /* -------------------- Supression de Membre -------------------- */
    case 'delMembre' :
    $pageName = 'Supression de Membre';
    if (isset($_SESSION['login'])) {
      if ($_SESSION['grade'] == 'Administrateur') {
        if (isset($_GET['idP'])) {
          if ($_GET['idP'] != '') {
            $stmt = myPDO::getInstance()->prepare(<<<SQL
                   DELETE FROM `personne`
                   WHERE idP = {$_GET['idP']}
SQL
               ) ;
            $stmt->execute();
            $error = false;
            $message = 'Le membre a bien été supprimé, vous allez être redirigé automatiquement';
            $time = $redirectGood;
            $url = 'listMembre';
          } else {
            $error = true;
            $message = 'Certains paramètres sont vides, vous allez être redirigé automatiquement';
            $time = $redirectError;
            $url = 'listMembre';
          }
        } else {
          $error = true;
          $message = 'Problème de paramètres, vous allez être redirigé automatiquement';
          $time = $redirectError;
          $url = 'listMembre';
        }
      } else {
        $error = true;
        $message = 'Vous n\'avez pas les droits requis, vous allez être redirigé vers l\'accueil';
        $time = $redirectError;
        $url = 'index';
      }
    } else {
      $error = true;
      $message = 'Vous n\'êtes pas connecté, vous allez être redirigé vers l\'accueil';
      $time = $redirectError;
      $url = 'index';
    }
      break;
    /* -------------------- Supression de Matériel -------------------- */
    case 'delMateriel' :
    $pageName = 'Supression de Matériel';
    if (isset($_SESSION['login'])) {
      if (isset($_GET['ref'])) {
        if ($_GET['ref'] != '') {
          $stmt = myPDO::getInstance()->prepare(<<<SQL
                 DELETE FROM `materiel`
                 WHERE ref = '{$_GET['ref']}'
SQL
             ) ;
          $stmt->execute();

          // Ne pas oublier de rincer les caractéristiques correspondantes
          $stmt = myPDO::getInstance()->prepare(<<<SQL
                                DELETE FROM `valeurcaracteristique`
                                WHERE ref = '{$_GET['ref']}'
SQL
) ;
          $stmt->execute();
          
          $error = false;
          $message = 'Le matériel a bien été supprimé, vous allez être redirigé automatiquement';
          $time = $redirectGood;
          $url = 'catalogue';
        } else {
          $error = true;
          $message = 'Problème de paramètres, vous allez être redirigé automatiquement';
          $time = $redirectError;
          $url = 'catalogue';
        }
      } else {
        $error = true;
        $message = 'Problème de paramètres, vous allez être redirigé automatiquement';
        $time = $redirectError;
        $url = 'catalogue';
      }
    } else {
      $error = true;
      $message = 'Vous n\'êtes pas connecté, vous allez être redirigé vers l\'accueil';
      $time = $redirectError;
      $url = 'index';
    }
      break;
    default :
      $pageName = "Erreur GET";
      $error = true;
      $message = 'Un problème inconnu est survenu, vous allez être redirigé vers l\'accueil';
      $time = $redirectError;
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
      $time=$redirectGood;
    } else if (isset($_POST['submit'])) {
      $login  = (isset($_POST['login'])) ? htmlentities(trim($_POST['login'])) : '';
      $password   = (isset($_POST['password'])) ? sha1(htmlentities(trim($_POST['password'])))   : '';

      if (($login != '') && ($password != '')) {

        $pdo = myPDO::getInstance();
        $stmt = $pdo->prepare(<<<SQL
                              SELECT idP, login, nomP, prenom, grade, datDepart
                              FROM personne
                              WHERE login = "{$login}" AND mdp = "{$password}"
SQL
                              ) ;

        $stmt->execute();
        if (($result = $stmt->fetch()) !== false) {
          if ($result['datDepart'] == null) {
          $_SESSION['idP'] = $result['idP'];
          $_SESSION['login'] = $result['login'];
          $_SESSION['nomP'] = $result['nomP'];
          $_SESSION['prenom'] = $result['prenom'];
          $_SESSION['grade'] = $result['grade'];

          $url="index";
          $error = false;
          $message='Vous êtes bien connecté, vous allez être redirigé vers l\'accueil';
          $time=$redirectGood;
        } else {
          $url="index";
          $error = true;
          $message = 'Votre compte a été clôturé, vous ne pouvez pas vous connecter';
          $time=$redirectError;
        }
        } else {
          $url="index";
          $error = true;
          $message = 'Login ou de mot de passe incorrect <br> Vous allez être redirigé automatiquement';
          $time=$redirectError;
        }

      } else {
        $error = true;
        $url="index";
        $message = 'Login ou mot de passe vide <br> Vous allez être redirigé automatiquement';
        $time=$redirectError;
      }
    }
    break;
  /* -------------------- Création de Membre -------------------- */
  case 'createMembre' :
    $pageName = 'Création de Membre';
    if (isset($_SESSION['login'])) {
      if ($_SESSION['grade'] == 'Administrateur') {
        if (isset($_POST['login']) && isset($_POST['password']) && isset($_POST['grade']) && isset($_POST['nomP']) && isset($_POST['prenom']) && isset($_POST['datns']) && isset($_POST['sexe'])) {
          if ($_POST['login'] != '' && $_POST['password'] != '' && $_POST['grade'] != '' && $_POST['nomP'] != '' && $_POST['prenom'] != '' && $_POST['datns'] != '' && $_POST['sexe'] != '') {
            $login = htmlentities(trim($_POST['login']));
            $password = sha1(htmlentities(trim($_POST['password'])));
            $nom = htmlentities(trim($_POST['nomP']));
            $prenom =  htmlentities(trim($_POST['prenom']));

            $champs = '';
            $values = '';

            if (isset($_POST['adr'])) {
              if ($_POST['adr'] != '') {
                $adr = htmlentities(trim($_POST['adr']));
                $champs .= ', `adr`';
                $values .= ', \''. $adr .'\'';
              }
            }

            if (isset($_POST['cp'])) {
              if ($_POST['cp'] != '') {
                $cp = htmlentities(trim($_POST['cp']));
                $champs .= ', `cp`';
                $values .= ', \''. $cp .'\'';
              }
            }

            if (isset($_POST['ville'])) {
              if ($_POST['ville'] != '') {
                $ville = htmlentities(trim($_POST['ville']));
                $champs .= ', `ville`';
                $values .= ', \''. $ville .'\'';
              }
            }

            if (isset($_POST['tel'])) {
              if ($_POST['tel'] != '') {
                $tel = htmlentities(trim($_POST['tel']));
                $champs .= ', `tel`';
                $values .= ', \''. $tel .'\'';
              }
            }

            $now = date("d/m/y");

            $pdo = myPDO::getInstance();
            $stmt = $pdo->prepare(<<<SQL
                                  INSERT INTO personne (`login`, `mdp`, `grade`, `nomP`, `prenom`, `datns`, `sexe`, `datAjout`{$champs}) VALUES ('{$login}', '{$password}', '{$_POST['grade']}', '{$nom}', '{$prenom}', STR_TO_DATE('{$_POST['datns']}', '%Y-%m-%d'), '{$_POST['sexe']}', STR_TO_DATE('{$now}', '%d/%m/%Y'){$values});
SQL
) ;

            $stmt->execute();
            $error = false;
            $message = 'Le membre ' . $nom . ' ' . $prenom . ' a bien été créé, vous allez être redirigé vers l\'accueil' ;
            $time = $redirectGood;
            $url = 'index';

          } else {
            $error = true;
            $message = 'Certains paramètres sont vides, vous allez être redirigé automatiquement';
            $time = $redirectError;
            $url = 'createMembre';
          }
        } else {
          $error = true;
          $message = 'Problème de paramètres, vous allez être redirigé automatiquement';
          $time = $redirectError;
          $url = 'createMembre';
        }
      } else {
        $error = true;
        $message = 'Vous n\'avez pas les droits requis, vous allez être redirigé vers l\'accueil';
        $time = $redirectError;
        $url = 'index';
      }
    } else {
      $error = true;
      $message = 'Vous n\'êtes pas connecté, vous allez être redirigé vers l\'accueil';
      $time = $redirectError;
      $url = 'index';
    }
    break;
  /* -------------------- Modification de Membre -------------------- */
  case 'modifyMembre' :
    $pageName = 'Modification de Membre';
    if (isset($_SESSION['login'])) {
      if ($_SESSION['grade'] == 'Administrateur') {
        if (isset($_POST['idP']) && isset($_POST['login']) && isset($_POST['nomP']) && isset($_POST['prenom']) && isset($_POST['grade']) && isset($_POST['datns']) && isset($_POST['sexe'])) {
          if ($_POST['idP'] != '' && $_POST['login'] != '' && $_POST['nomP'] != '' && $_POST['prenom'] != '' && $_POST['grade'] != '' && $_POST['datns'] != '' && $_POST['sexe'] != '') {

            $login = htmlentities(trim($_POST['login']));
            $nom = htmlentities(trim($_POST['nomP']));
            $prenom =  htmlentities(trim($_POST['prenom']));

            $set = '';

            if (isset($_POST['password']) && $_POST['password'] != '') {
              $password = sha1(htmlentities(trim($_POST['password'])));
              $set .= ', mdp = \'' . $password . '\'';
            }

            if (isset($_POST['adr'])) {
              if ($_POST['adr'] != '') {
                $adr = htmlentities(trim($_POST['adr']));
                $set .= ', adr = \'' . $adr . '\'';
              }
            }

            if (isset($_POST['cp'])) {
              if ($_POST['cp'] != '') {
                $cp = htmlentities(trim($_POST['cp']));
                $set .= ', cp = \'' . $cp . '\'';
              }
            }

            if (isset($_POST['ville'])) {
              if ($_POST['ville'] != '') {
                $ville = htmlentities(trim($_POST['ville']));
                $set .= ', ville = \'' . $ville . '\'';
              }
            }

            if (isset($_POST['tel'])) {
              if ($_POST['tel'] != '') {
                $tel = htmlentities(trim($_POST['tel']));
                $set .= ', tel = \'' . $tel . '\'';
              }
            }

            if (isset($_POST['datDepart'])) {
              if ($_POST['datDepart'] != '') {
                $set .= ", datDepart = STR_TO_DATE('" . $_POST['datDepart'] ."', '%Y-%m-%d')";
              }
            }


            $stmt = myPDO::getInstance()->prepare(<<<SQL
                UPDATE personne
                SET login = '{$login}', nomP = '{$nom}', prenom = '{$prenom}', grade = '{$_POST['grade']}', datns = STR_TO_DATE('{$_POST['datns']}', '%Y-%m-%d'){$set}
                WHERE idP = {$_POST['idP']}
SQL
);
          $stmt->execute();
          $error = false;
          $url="index";
          $message = 'Le Membre a bien été modifié, <br> Vous allez être redirigé vers l\'accueil';
          $time=$redirectGood;

          } else {
            $error = true;
            $message = 'Certains paramètres sont vides, vous allez être redirigé automatiquement';
            $time = $redirectError;
            $url = 'listMembre';
          }
        } else {
          $error = true;
          $message = 'Problème de paramètres, vous allez être redirigé automatiquement';
          $time = $redirectError;
          $url = 'listMembre';
        }
      } else {
        $error = true;
        $message = 'Vous n\'avez pas les droits requis, vous allez être redirigé vers l\'accueil';
        $time = $redirectError;
        $url = 'index';
      }
    } else {
      $error = true;
      $message = 'Vous n\'êtes pas conencté, vous allez être redirigé vers l\'accueil';
      $time = $redirectError;
      $url = 'index';
    }
    break;
  /* -------------------- Ajout d'un item -------------------- */
  case 'ajout' :
    $pageName = "Ajout d'un item dans le catalogue";
    if (isset($_SESSION['login'])) {
    if (isset($_POST['table']) && $_POST['table'] != '') {
      switch ($_POST['table']) {
        case 'materiel' :
          if (isset($_POST['ref']) && isset($_POST['prix']) && isset($_POST['marque']) && isset($_POST['typeM']) && isset($_POST['carac']) && isset($_POST['valeurCarac'])) {
            if ($_POST['ref'] != '' && $_POST['prix'] != '' && $_POST['marque'] != '' && $_POST['typeM'] != '') {
              $pdo = myPDO::getInstance();
              $stmt = $pdo->prepare(<<<SQL
                                    INSERT INTO `materiel` VALUES ('{$_POST['ref']}', {$_POST['prix']}, '{$_POST['marque']}', '{$_POST['typeM']}', {$_SESSION['idP']});
SQL
) ;
              $stmt->execute();
              for ($i = 0; $i < count($_POST['carac']); $i++) {
                if ($_POST['valeurCarac'][$i] != '') {
                  $pdo = myPDO::getInstance();
                  $stmt = $pdo->prepare(<<<SQL
                                        INSERT INTO `valeurcaracteristique` VALUES ('{$_POST['carac'][$i]}', '{$_POST['ref']}', '{$_POST['valeurCarac'][$i]}');
SQL
) ;
                  $stmt->execute();
                }
              }

              $error = false;
              $message = 'Le matériel "' . $_POST['ref'] . '" a bien été ajouté dans le catalogue';
              $time = $redirectGood;
              $url = 'ajout';
            } else {
              $error = true;
              $message = 'Problème de paramètres, vous allez être redirigé automatiquement';
              $time = $redirectError;
              $url = 'ajout';
            }
          } else {
            $error = true;
            $message = 'Certains paramètres sont vides, vous allez être redirigé automatiquement';
            $time = $redirectError;
            $url = 'ajout';
          }
          break;
        case 'type' :
        case 'marque' :
        case 'caracteristique' :
        if (isset($_POST['nom']) && $_POST['nom'] != '') {
          $pdo = myPDO::getInstance();
          $stmt = $pdo->prepare(<<<SQL
                                INSERT INTO `{$_POST['table']}` (`nom`) VALUES ('{$_POST['nom']}');
SQL
) ;
          $stmt->execute();
          $error = false;
          $message = 'L\'item "' . $_POST['table'] . '" a bien été ajouté dans le catalogue';
          $time = $redirectGood;
          $url = 'ajout';
        } else {
          $error = true;
          $message = 'Problème de paramètres, vous allez être redirigé automatiquement';
          $time = $redirectError;
          $url = 'ajout';
        }
          break;
      }
    } else {
      $error = true;
      $message = 'Problème de paramètres, vous allez être redirigé automatiquement';
      $time = $redirectError;
      $url = 'ajout';
    }
  } else {
    $error = true;
    $message = 'Vous n\'êtes pas connecté, vous allez être redirigé automatiquement';
    $time = $redirectError;
    $url = 'index';
  }
    break;
  /* -------------------- Modification de Matériel -------------------- */
  case 'modifyMateriel' :
    $pageName = 'Modification de matériel';
    if (isset($_SESSION['login'])) {
      if (isset($_POST['oldRef']) && isset($_POST['ref']) && isset($_POST['prix']) && isset($_POST['marque']) && isset($_POST['typeM']) && isset($_POST['carac']) && isset($_POST['valeurCarac'])) {
        if ($_POST['oldRef'] != '' && $_POST['ref'] != '' && $_POST['prix'] != '' && $_POST['marque'] != '' && $_POST['typeM'] != '') {
          $pdo = myPDO::getInstance();
          $stmt = $pdo->prepare(<<<SQL
                                UPDATE `materiel`
                                SET ref = '{$_POST['ref']}', prix = {$_POST['prix']}, idM = '{$_POST['marque']}', idT = '{$_POST['typeM']}'
                                WHERE ref = '{$_POST['oldRef']}';
SQL
) ;
          $stmt->execute();

          // On rince les anciennes caractéristiques
          $stmt = $pdo->prepare(<<<SQL
                                DELETE FROM `valeurcaracteristique`
                                WHERE ref = '{$_POST['oldRef']}';
SQL
) ;
          // On ajoute les nouvelles (ou les même si elles n'ont pas changé)
          $stmt->execute();
          for ($i = 0; $i < count($_POST['carac']); $i++) {
            if ($_POST['valeurCarac'][$i] != '') {
              $pdo = myPDO::getInstance();
              $stmt = $pdo->prepare(<<<SQL
                                    INSERT INTO `valeurcaracteristique` VALUES ('{$_POST['carac'][$i]}', '{$_POST['ref']}', '{$_POST['valeurCarac'][$i]}');
SQL
) ;
              $stmt->execute();
            }
          }

          $error = false;
          $message = 'Le matériel "' . $_POST['ref'] . '" a bien été modifié dans le catalogue';
          $time = $redirectGood;
          $url = 'catalogue';
        } else {
          $error = true;
          $message = 'Problème de paramètres, vous allez être redirigé automatiquement';
          $time = $redirectError;
          $url = 'catalogue';
        }
      } else {
        $error = true;
        $message = 'Certains paramètres sont vides, vous allez être redirigé automatiquement';
        $time = $redirectError;
        $url = 'catalogue';
      }
    } else {
      $error = true;
      $message = 'Vous n\'êtes pas connecté, vous allez être redirigé automatiquement';
      $time = $redirectError;
      $url = 'index';
    }
    break;
  default :
    $pageName = "Erreur POST";
    $error = true;
    $message = 'Un problème inconnu est survenu, vous allez être redirigé vers l\'accueil';
    $time = $redirectError;
    $url = 'index';
  }
} else {
    $pageName = "Nothing";
    $error = true;
    $message = 'Il n\'y a rien à traiter, vous allez être redirigé vers l\'accueil';
    $time = $redirectError;
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
