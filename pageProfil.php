<?php

require_once 'include.php';

if (!isset($_SESSION['login'])) {
    header('Location: pageConnexion.php');
    exit;
}

$modif = $_GET['modif'] ?? null;
$success = $_GET['success'] ?? null;

$pdo = new PDO(
    "mysql:host={$GLOBALS['config']['bd']['host']};dbname={$GLOBALS['config']['bd']['name']}",
    $GLOBALS['config']['bd']['user'],
    $GLOBALS['config']['bd']['password']
);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $champ = $_POST['champ'] ?? null;
    $valeur = trim($_POST['valeur'] ?? '');

    $champsAutorises = [
        'nom' => 'nom',
        'prenom' => 'prenom',
        'email' => 'email',
        'numTel' => 'numTel',
        'adresse' => 'adresse'
    ];

    if ($champ !== null && isset($champsAutorises[$champ]) && $valeur !== '') {

        $sql = $pdo->prepare("UPDATE Etudiant SET {$champ} = :valeur WHERE email = :mail");
        $sql->execute([
            ':valeur' => $valeur,
            ':mail' => $_SESSION['login']
        ]);

        if ($champ === 'email') {
            $_SESSION['login'] = $valeur;
        }
    }

    header('Location: pageProfil.php?success=1');
    exit;
}


$sql = $pdo->prepare("SELECT * FROM Etudiant WHERE email = :mail");
$sql->execute([':mail' => $_SESSION['login']]);
$user = $sql->fetch(PDO::FETCH_ASSOC);

echo $twig->render('pageProfil.html.twig', [
    'user' => $user,
    'modif' => $modif,
    'success' => $success
]);
?>