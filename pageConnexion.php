<?php
require_once 'include.php';

$successMessage = null;

if ( isset($_GET['inscription']) && $_GET['inscription'] === 'reussie') {
    $successMessage = 'Compte créé avec succès';
}

echo $twig->render('pageConnexion.html.twig', [
    'successMessage' => $successMessage,
]);

 ?>