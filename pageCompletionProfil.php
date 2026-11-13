<?php
session_start();
require_once 'include.php';

$success = (isset($_GET['inscription']) && $_GET['inscription'] === 'reussie');
$statut = $_SESSION['statut'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $telephone = $_POST['telephone'];
    $adresse = $_POST['adresse'];
    $domaine = $_POST['domaine'];
    $nbEmployes = $_POST['nbEmployes'];
    
    $pdo = new PDO("mysql:host={$GLOBALS['config']['bd']['host']};dbname={$GLOBALS['config']['bd']['name']}", $GLOBALS['config']['bd']['user'], $GLOBALS['config']['bd']['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        if ($statut === 'etudiant') {
            $sql = $pdo->prepare("UPDATE Etudiant SET nom = ?, prenom = ?, adresse = ?, numTel = ? WHERE email = ?");
            $sql->execute([$nom, $prenom, $adresse, $telephone, $_SESSION['login']]);
        }
        elseif ($statut === 'entreprise') {
            $sql = $pdo->prepare("UPDATE Entreprise SET nom = ?, domaine = ?, nbEmployes = ? WHERE email = ?");
            $sql->execute([$nom, $domaine, $nbEmployes, $_SESSION['login']]);
        }
            

            header('Location: pageCompletionProfil.php?inscription=reussie');
            exit();

    $success = true;
}

echo $twig->render('pageCompletionProfil.html.twig', [
    'success' => $success,
    'statut' => $statut
]);
?>
