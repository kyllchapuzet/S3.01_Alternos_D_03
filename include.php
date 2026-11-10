<?php
    //Ajout de l'autoload de composer
    require_once 'vendor/autoload.php';


    //Import de la classe Yaml (nécessite l'installation de symfony/yaml via Composer) -->
    use Symfony\Component\Yaml\Yaml;

    // Charger le fichier de configuration YAML 
    $configFile = __DIR__ . '/config/config.yml';

    if (!file_exists($configFile)) {
        throw new Exception("Le fichier de configuration {$configFile} est introuvable.");
    }

    // Parser le fichier YAML pour le transformer en tableau associatif PHP
    $config = Yaml::parseFile($configFile);

    // Définir $config comme une variable globale pour que les paramètres 
    // de l'application soient accessibles partout dans ton code
    $GLOBALS['config'] = $config;



    //Ajout de l'initialisation de twig
    require_once 'config/twig.php';


?>