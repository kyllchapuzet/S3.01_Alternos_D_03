<?php
require_once 'include.php';

$successMessage = null;

if ( isset($_GET['inscription']) && $_GET['inscription'] === 'reussie') {
    $successMessage = 'Compte créé avec succès';
}

echo $twig->render('page_connexion.html.twig', [
    'successMessage' => $successMessage,
]);

 ?>