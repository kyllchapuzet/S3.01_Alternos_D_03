<?php
require_once 'include.php';

$user_email = "alalisse@caks";

$pdo = new PDO(
    "mysql:host=" . $GLOBALS['config']['bd']['host'] . ";dbname=" . $GLOBALS['config']['bd']['name'] . ";charset=utf8mb4",
    $GLOBALS['config']['bd']['user'],
    $GLOBALS['config']['bd']['password'],
);

// tous les messages envoyés ou reçus par l'utilisateur 
$sql = $pdo->prepare("
    SELECT emailExpediteur, emailDestinataire
    FROM Message
    WHERE emailExpediteur = ? OR emailDestinataire = ?
    ORDER BY dateEnvoi DESC
");
$sql->execute([$user_email, $user_email]);
$lignes = $sql->fetchAll(PDO::FETCH_ASSOC); // cree tableau associatif

// personnes  avec qui l'utilisateur a discuté
$conversations = [];
foreach ($lignes as $ligne) {

    // l'autre personne de la conversation
    if ($ligne['emailExpediteur'] === $user_email) {
        $autre = $ligne['emailDestinataire']; // emmail message envoyé
    } else {
        $autre = $ligne['emailExpediteur']; //  emmail message recu
    }

    //verif c'est une entreprise
    $req = $pdo->prepare("SELECT nom FROM Entreprise WHERE email = ?");
    $req->execute([$autre]);
    $entreprise = $req->fetch(PDO::FETCH_ASSOC);

    //verif c'est un étudiant
    $nomEtudiant = null;
    if (!$entreprise) {
        $req = $pdo->prepare("SELECT prenom, nom FROM Etudiant WHERE email = ?");
        $req->execute([$autre]);
        $etudiant = $req->fetch(PDO::FETCH_ASSOC);

        if ($etudiant) {
            $nomEtudiant = $etudiant['prenom'] . ' ' . $etudiant['nom'];
        }
    }

    //Détermine le nom final à afficher
    if ($entreprise) {
        $nom = $entreprise['nom'];
    } elseif ($nomEtudiant) {
        $nom = $nomEtudiant;
    } else {
        $nom = $autre; // ni entreprise ni étudiant : on affiche l'adresse
    }

    $conversations[$autre] = ['email' => $autre, 'nom' => $nom];
}

// Récupération de l'email choisi par l'utilisateur
$email_choisi = $_GET['email'] ?? null;

// Récupération du nom de la personne choisie
$destinataire_nom = null;
foreach ($conversations as $conv) {
    if ($conv['email'] === $email_choisi) {
        $destinataire_nom = $conv['nom'];
    }
}

// Récupération des messages échangés avec la personne choisie
$sql = $pdo->prepare("
    SELECT emailExpediteur, emailDestinataire, Contenu, dateEnvoi
    FROM Message
    WHERE (emailExpediteur = ? AND emailDestinataire = ?)
       OR (emailExpediteur = ? AND emailDestinataire = ?)
    ORDER BY dateEnvoi ASC
");
$sql->execute([$user_email, $email_choisi, $email_choisi, $user_email]);
$messages = $sql->fetchAll(PDO::FETCH_ASSOC);

// Envoi d'un message 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email_destinataire = $_POST['email_destinataire'] ?? null;
    $message            = trim($_POST['message'] ?? '');

    if ($email_destinataire && $message !== '') {
        $sql = $pdo->prepare("
            INSERT INTO Message (emailExpediteur, emailDestinataire, Contenu)
            VALUES (?, ?, ?)
        ");
        $sql->execute([$user_email, $email_destinataire, $message]);
    }

    header('Location: messagerie.php?email=' . urlencode($email_destinataire ?? ''));
    exit;
}

echo $twig->render('messagerie.html.twig', [
    'conversations'    => $conversations,
    'email_choisi'     => $email_choisi,
    'destinataire_nom' => $destinataire_nom,
    'messages'         => $messages,
    'user_email'       => $user_email,
]);