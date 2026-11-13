<?php

session_start();

require_once 'include.php';

if (!isset($_SESSION['login'])) {
    header('Location: pageConnexion.php');
    exit;
}

$pdo = new PDO(
    "mysql:host={$GLOBALS['config']['bd']['host']};dbname={$GLOBALS['config']['bd']['name']}", 
    $GLOBALS['config']['bd']['user'], 
    $GLOBALS['config']['bd']['password']
);

$sql = $pdo->prepare("SELECT * FROM Etudiant WHERE email = :mail");
$sql->execute([':mail' => $_SESSION['login']]);
$user = $sql->fetch(PDO::FETCH_ASSOC);

echo $twig->render('pageProfil.html.twig', [
    'user' => $user,
]);
?>