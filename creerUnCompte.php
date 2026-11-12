<?php
require_once 'include.php';
session_start();

$erreur = null;

    $email = $_POST['login'];
    $password = $_POST['pwd'];
    $passwordConfirm = $_POST['pwdConfirm'];
    $_SESSION['login'] = $email;

    if ($password !== $passwordConfirm) 
    {
        $erreur = "Les mots de passe ne sont pas identiques.";
    } 
    else 
    {

        $pdo = new PDO("mysql:host={$GLOBALS['config']['bd']['host']};dbname={$GLOBALS['config']['bd']['name']}", $GLOBALS['config']['bd']['user'], $GLOBALS['config']['bd']['password']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        $verifEmail = $pdo->prepare("SELECT * FROM Etudiant WHERE email = ?");
        $verifEmail->execute([$email]);

        if ($verifEmail->fetch()) {
            $erreur = "Un compte avec cette adresse e-mail existe déjà.";
        } 
        else {
        $salt = '$2y$07$usesomesillystringforsalt$';
        $passwordCrypte = crypt($password, $salt);

        
            $sql = $pdo->prepare("INSERT INTO Etudiant(email, password) VALUES (?, ?)");
            $sql->execute([$email, $passwordCrypte]);


            header('Location: pageCompletionProfil.php');
            exit();
        }
    }


echo $twig->render('pageCreationDeCompte.html.twig', [
    'erreur' => $erreur
]);

?>