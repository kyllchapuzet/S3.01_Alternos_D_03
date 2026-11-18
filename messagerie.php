<?php
require_once 'include.php';

$user_email = "ton_mail@exemple.fr";

$pdo = new PDO(
    "mysql:host=" . $GLOBALS['config']['bd']['host'] . ";dbname=" . $GLOBALS['config']['bd']['name'] . ";charset=utf8mb4",
    $GLOBALS['config']['bd']['user'],
    $GLOBALS['config']['bd']['password'],
);

//entreprise qui a envoyé ou reçu un message avec l'utilisateur
$sql = $pdo->prepare("
    SELECT e.email, e.nom
    FROM Entreprise e
    JOIN Message m
      ON e.email = m.emailExpediteur OR e.email = m.emailDestinataire
    WHERE (m.emailExpediteur = ? OR m.emailDestinataire = ?)
      AND e.email != ?
    GROUP BY e.email, e.nom
");

$sql->execute([$user_email, $user_email, $user_email]);
$conversations = $sql->fetchAll(PDO::FETCH_ASSOC);

// Récupération de l'email choisi par l'utilisateur
$email_choisi = $_GET['email'] ?? ($conversations[0]['email'] ?? null);

// Récupération du nom de l'entreprise choisie
$destinataire_nom = null;
foreach ($conversations as $conv) {
    if ($conv['email'] === $email_choisi) {
        $destinataire_nom = $conv['nom'];
    }
}

// Récupération des messages échangés avec l'entreprise choisie
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

    $sql = $pdo->prepare("
        INSERT INTO Message (emailExpediteur, emailDestinataire, Contenu)
        VALUES (?, ?, ?)
    ");
    $sql->execute([$user_email, $email_destinataire, $message]);

    header('Location: messagerie.php?email=' . $email_destinataire);
    exit;
}

echo $twig->render('messagerie.html.twig', [
    'conversations'    => $conversations,
    'email_choisi'     => $email_choisi,
    'destinataire_nom' => $destinataire_nom,
    'messages'         => $messages,
    'user_email'       => $user_email,
]);