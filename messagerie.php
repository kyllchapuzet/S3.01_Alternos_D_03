<?php
require_once 'include.php';

$user_mail = $_SESSION['login'] ?? null;

$pdo = new PDO(
    "mysql:host=" . $GLOBALS['config']['bd']['host'] . ";dbname=" . $GLOBALS['config']['bd']['name'] . ";charset=utf8mb4",
    $GLOBALS['config']['bd']['user'],
    $GLOBALS['config']['bd']['password'],
);

//entreprise qui a envoyé ou reçu un message avec l'utilisateur
$sql = $pdo->prepare("
    SELECT e.mail, e.nom
    FROM Entreprise e
    JOIN Message m
      ON e.mail = m.mailExpediteur OR e.mail = m.mailDestinataire
    WHERE (m.mailExpediteur = ? OR m.mailDestinataire = ?)
      AND e.mail != ?
    GROUP BY e.mail, e.nom
");
$sql->execute([$user_mail, $user_mail, $user_mail]);
$conversations = $sql->fetchAll(PDO::FETCH_ASSOC);

// Récupération du mail choisi par l'utilisateur
$mail_choisi = $_GET['mail'] ?? null;

// Récupération du nom de l'entreprise choisie
$destinataire_nom = null;
foreach ($conversations as $conv) {
    if ($conv['mail'] === $mail_choisi) {
        $destinataire_nom = $conv['nom'];
    }
}

// Récupération des messages échangés avec l'entreprise choisie


    $sql = $pdo->prepare("
        SELECT mailExpediteur, mailDestinataire, Contenu, dateEnvoi
        FROM Message
        WHERE (mailExpediteur = ? AND mailDestinataire = ?)
           OR (mailExpediteur = ? AND mailDestinataire = ?)
        ORDER BY dateEnvoi ASC
    ");
    $sql->execute([$user_mail, $mail_choisi, $mail_choisi, $user_mail]);
    $messages = $sql->fetchAll(PDO::FETCH_ASSOC);


echo $twig->render('messagerie.html.twig', [
    'conversations'    => $conversations,
    'mail_choisi'      => $mail_choisi,
    'destinataire_nom' => $destinataire_nom,
    'messages'         => $messages,
    'user_mail'        => $user_mail,
]);