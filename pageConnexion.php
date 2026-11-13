<?php
require_once 'include.php';

if (isset($_GET['retour'])) {
    unset($_SESSION['statut']);
    header('Location: pageConnexion.php');
    exit();
}

$successMessage = null;

$statut = $_POST['statut'] ?? $_SESSION['statut'] ?? null;
if ($statut !== null) {
    $_SESSION['statut'] = $statut;
}

if ( isset($_GET['inscription']) && $_GET['inscription'] === 'reussie') {
    $successMessage = 'Compte créé avec succès';
}

echo $twig->render('pageConnexion.html.twig', [
    'successMessage' => $successMessage,
    'statut' => $statut
]);




?>