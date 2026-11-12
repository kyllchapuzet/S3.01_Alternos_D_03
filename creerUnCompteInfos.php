<?php
require_once 'include.php';

$erreur = null;

    $email = $_POST['login'];
    $password = $_POST['pwd'];
    $passwordConfirm = $_POST['pwdConfirm'];


    if ($password !== $passwordConfirm) 
    {
        $erreur = "Les mots de passe ne sont pas identiques.";
    } 
    else 
    {

        $pdo = new PDO('mysql:host=lakartxela.iutbayonne.univ-pau.fr;dbname=mhild001_bd;charset=utf8', 'mhild001_bd', 'mhild001_bd');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $salt = '$2y$07$usesomesillystringforsalt$';
        $passwordCrypte = crypt($password, $salt);

        
            $sql = $pdo->prepare("INSERT INTO Etudiant(email, password) VALUES (?, ?)");
            $sql->execute([$email, $passwordCrypte]);

            header('Location: page_connexion.php?inscription=reussie');
            exit();
        }



echo $twig->render('page_creationDeCompte.html.twig', [
    'erreur' => $erreur
]);

?>