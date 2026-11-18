<?php
<<<<<<< HEAD
session_start();
=======

>>>>>>> f7a915528b33229e4e791d7aae2bcad90bef6ba3

if (isset($_GET['retour'])) {
    unset($_SESSION['statut']);
    header('Location: pageCreationDeCompte.php');
    exit();
}

require_once 'include.php';

$erreur = null;
$statut = $_POST['statut'] ?? $_SESSION['statut'] ?? null;

if ($statut !== null) {
    $_SESSION['statut'] = $statut;
}

echo $twig->render('pageCreationDeCompte.html.twig',
['erreur' => $erreur, 
'statut' => $statut]);
?>
