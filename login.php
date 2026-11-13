<?php
require_once 'include.php';

$email = $_POST['login'] ?? '';
$erreur = null;

$pdo = new PDO("mysql:host={$GLOBALS['config']['bd']['host']};dbname={$GLOBALS['config']['bd']['name']}", $GLOBALS['config']['bd']['user'], $GLOBALS['config']['bd']['password']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SESSION['statut'] === 'etudiant') {
    $verifEmail = $pdo->prepare("SELECT password FROM Etudiant WHERE email = ?");
} elseif ($_SESSION['statut'] === 'entreprise') {
    $verifEmail = $pdo->prepare("SELECT password FROM Entreprise WHERE email = ?");
}
$verifEmail->execute([$email]);
$user = $verifEmail->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    $erreur = "Compte inexistant";
} else {
    $mdpCrypte = $user['password'];
    $mdpSaisie = $_POST['pwd'];

    $salt = '$2y$07$usesomesillystringforsalt$';
    $passwordCrypte = crypt($mdpSaisie, $salt);
    if ($mdpCrypte == $passwordCrypte) {
        $_SESSION['login'] = $email;
        header('Location: index.php');

    } else {
        $erreur = "Mot de passe incorrect";
    }
}

if ($erreur) {
    echo $twig->render('pageConnexion.html.twig', ['erreur' => $erreur]);
}

?>