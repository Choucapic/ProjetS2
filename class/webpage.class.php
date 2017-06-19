<?php
class WebPage {
    /**
     * @var string Texte compris entre <head> et </head>
     */
    private $head  = null ;
    /**
     * @var string Texte compris entre <title> et </title>
     */
    private $title = null ;
    /**
     * @var string Texte compris entre <body> et </body>
     */
    private $body  = null ;
    /**
     * Constructeur
     * @param string $title Titre de la page
     */
    public function __construct($title=null) {
        $this->setTitle($title) ;
    }
    /**
     * Retourner le contenu de $this->body
     *
     * @return string
     */
    public function body() {
        return $this->body ;
    }
    /**
     * Retourner le contenu de $this->head
     *
     * @return string
     */
    public function head() {
        return $this->head ;
    }
    /**
     * Donner la derni�re modification du script principal
     * @link http://php.net/manual/en/function.getlastmod.php
     * @link http://php.net/manual/en/function.strftime.php
     *
     * @return string
     */
    public function getLastModification() {
        return strftime("Derni�re modification de cette page le %d/%m/%Y � %Hh%M", getlastmod()) ;
    }
    /**
     * Prot�ger les caract�res sp�ciaux pouvant d�grader la page Web
     * @see http://php.net/manual/en/function.htmlentities.php
     * @param string $string La cha�ne � prot�ger
     *
     * @return string La cha�ne prot�g�e
     */
    public static function escapeString($string) {
        return htmlentities($string, ENT_QUOTES|ENT_HTML5, "utf-8") ;
    }
    /**
     * Affecter le titre de la page
     * @param string $title Le titre
     */
    public function setTitle($title) {
        $this->title = $title ;
    }
    /**
     * Ajouter un contenu dans head
     * @param string $content Le contenu � ajouter
     *
     * @return void
     */
    public function appendToHead($content) {
        $this->head .= $content ;
    }
    /**
     * Ajouter un contenu CSS dans head
     * @param string $css Le contenu CSS � ajouter
     *
     * @return void
     */
    public function appendCss($css) {
        $this->appendToHead(<<<HTML
    <style type='text/css'>
    $css
    </style>
HTML
) ;
    }
    /**
     * Ajouter l'URL d'un script CSS dans head
     * @param string $url L'URL du script CSS
     *
     * @return void
     */
    public function appendCssUrl($url) {
        $this->appendToHead(<<<HTML
    <link rel="stylesheet" type="text/css" href="{$url}">
HTML
) ;
    }
    /**
     * Ajouter un contenu JavaScript dans head
     * @param string $js Le contenu JavaScript � ajouter
     *
     * @return void
     */
    public function appendJs($js) {
        $this->appendToHead(<<<HTML
    <script type='text/javascript'>
    $js
    </script>
HTML
) ;
    }
    /**
     * Ajouter l'URL d'un script JavaScript dans head
     * @param string $url L'URL du script JavaScript
     *
     * @return void
     */
    public function appendJsUrl($url) {
        $this->appendToHead(<<<HTML
    <script type='text/javascript' src='$url'></script>
HTML
) ;
    }
    /**
     * Ajouter un contenu dans body
     * @param string $content Le contenu � ajouter
     *
     * @return void
     */
    public function appendContent($content) {
        $this->body .= $content ;
    }
    /**
     * Produire la page Web compl�te
     *
     * @return string
     * @throws Exception si title n'est pas d�fini
     */
    public function toHTML($isConnected = false) {
        if (is_null($this->title)) {
            throw new Exception(__CLASS__ . ": title not set") ;
        }

        $isConnected = isset($_SESSION['login']) && $_SESSION['login'] != '';

        $nav = '';
        $navMobile = '';
        if ($isConnected) {
            $nav = '<li><a class="red-text" href="script.php?type=disconnection">Déconnexion</a></li>
                    <li><a href="ajout.php">Ajout</a></li>';
            $navMobile = $nav;

                    if ($_SESSION['grade'] == 'Administrateur') {
                      $navMobile .= '<ul class="collapsible collapsible-accordion" style="padding-left:15px;">
                                           <li>
                                          <a class="collapsible-header">Administration</a>
                                          <div class="collapsible-body grey lighten-3">
                                            <ul>
                                              <li><a href="createMembre.php">Créer un membre</a></li>
                                              <li><a href="listMembre.php">Liste des membres</a></li>
                                            </ul>
                                          </div>
                                          </li>
                                          </ul> ';
                      $nav .= '<li><a class="dropdown-button" href="#!" data-beloworigin="true" data-activates="dropAdmin">Administration<i class="material-icons right">arrow_drop_down</i></a></li>
                      <ul id="dropAdmin" class="dropdown-content grey darken-3">
                        <li><a class="white-text" href="createMembre.php">Créer un membre</a></li>
                        <li><a class="white-text" href="listMembre.php">Liste des membres</a></li>
                      </ul>';

                    }
        }


        return <<<HTML
<!doctype html>
<html lang="fr">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>{$this->title}</title>
        <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

        <!-- Compiled and minified CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/css/materialize.min.css">

        <!-- Compiled and minified JavaScript -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/js/materialize.min.js"></script>

        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

        <link rel="stylesheet" href="index.css">
        <script src="index.js"></script>
{$this->head()}
    </head>
    <body>
    <nav>
    <div class="nav-wrapper grey darken-3">
      <a href="index.php" class="brand-logo">IUT'Elec</a>
      <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="fa fa-2x fa-bars" aria-hidden="true"></i></a>
      <ul class="right hide-on-med-and-down">
        <li><a href="index.php">Accueil</a></li>
        <li><a href="catalogue.php">Catalogue</a></li>
        {$nav}
      </ul>
      <ul class="side-nav" id="mobile-demo">
        <li><a href="index.php">Accueil</a></li>
        <li><a href="catalogue.php">Catalogue</a></li>
        {$navMobile}
      </ul>
    </div>
  </nav>

{$this->body()}
    </body>
</html>
HTML;
    }
}
