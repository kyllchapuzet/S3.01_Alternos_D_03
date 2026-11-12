<?php
require_once 'include.php';

session_start();

$login_valide = $GLOBALS['config']['default-user']['mail'];
$pwd_valide = $GLOBALS['config']['default-user']['password'];

// on teste si nos variables sont définies
if (isset($_POST['login']) && isset($_POST['pwd'])) {
    // on vérifie les informations saisies
    if ($login_valide == $_POST['login'] && $pwd_valide == $_POST['pwd']) {
        session_start ();
        // on enregistre les paramètres de notre visiteur comme variables de session ($login et $pwd) (
        $_SESSION['login'] = $_POST['login'];
        $_SESSION['pwd'] = $_POST['pwd'];
        // on redirige notre visiteur vers une page de notre section membre
        header ('location: index.php');
    }
    else {
        echo '<body onLoad="alert(\'Membre non reconnu...\')">';
        // puis on le redirige vers la page d'accueil
        echo '<meta http-equiv="refresh" content="0;URL=index.php">';
    }
} else {
    echo 'Les variables du formulaire ne sont pas déclarées.';
}
?>