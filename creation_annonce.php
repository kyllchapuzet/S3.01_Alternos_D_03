<?php
require_once 'include.php';

$erreur = null;   // définie dès le départ pour éviter le warning
$succes = null;

$contratsValides = ['Alternance', 'Stage', 'CDI', 'CDD'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre          = trim($_POST['titre'] ?? '');
    $description    = trim($_POST['description'] ?? '');
    $salaire        = trim($_POST['salaire'] ?? '');
    $typeDeContrat  = trim($_POST['typeDeContrat'] ?? '');
    $mailEntreprise = $_SESSION['login'] ?? null;

    if (empty($mailEntreprise)) {
        $erreur = "Vous devez être connecté en tant qu'entreprise pour publier une annonce.";
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO Annonce (titre, description, salaire, typeDeContrat, estDisponible, mailEntreprise)
            VALUES (:titre, :description, :salaire, :typeDeContrat, 1, :mailEntreprise)
        ");
        $resultat = $stmt->execute([
            ':titre'          => $titre,
            ':description'    => $description,
            ':salaire'        => round( $salaire, 2),
            ':typeDeContrat'  => $typeDeContrat,
            ':mailEntreprise' => $mailEntreprise,
        ]);

        if ($resultat) {
            $succes = "Votre annonce a bien été publiée !";
        } else {
            $erreur = "Une erreur est survenue lors de l'enregistrement de l'annonce.";
        }
    }
}

echo $twig->render('creation_annonce.html.twig', [
    'erreur' => $erreur,
    'succes' => $succes,
]);