<?php
require_once 'include.php';

$erreur = null;

echo $twig->render('pageCreationDeCompte.html.twig', ['erreur' => $erreur]);
?>