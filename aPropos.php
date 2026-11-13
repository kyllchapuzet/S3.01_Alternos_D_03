<?php
require_once 'include.php';


$equipe = [
    ['nom' => 'Maxime Hild',      'role' => 'Scrum Master',        'qualites' => ['Perspicace', 'Résilient', 'Entreprenant']],
    ['nom' => 'Clément Baroan',   'role' => 'Product Owner',       'qualites' => ['Réaliste', 'Déterminé', 'Sérieux']],
    ['nom' => 'Kyllian Chapuzet', 'role' => 'Développeur',         'qualites' => ['Leadership', 'Méthodique', 'Flexible']],
    ['nom' => 'Etor Cesat',       'role' => 'Développeur',         'qualites' => ['Esprit d\'équipe', 'Jovial', 'Rigoureux']],
    ['nom' => 'Johan Dumas',      'role' => 'Développeur',         'qualites' => ['À l\'écoute', 'Disponible', 'Collaboratif']],
    ['nom' => 'Cyprien Guillot',  'role' => 'Développeur',         'qualites' => ['Franc', 'Studieux', 'Créatif']],
];


$fonctionnalites = [
    [
        'titre' => 'Mise en relation intelligente',
        'texte' => 'Grâce aux données saisies à l\'inscription, notre algorithme propose aux entreprises uniquement les profils susceptibles de les intéresser, et inversement aux étudiants en recherche d\'alternance ou de stage.',
    ],
    [
        'titre' => 'Carte interactive des logements',
        'texte' => 'Pour chaque offre, une carte affiche les logements disponibles à proximité grâce à une API. L\'objectif : supprimer la peur géographique qui pousse de nombreux étudiants à refuser une offre.',
    ],
    [
        'titre' => 'Aides financières',
        'texte' => 'Simulation de bourses, aides au logement, aides à la mobilité : depuis une offre ou son profil, chaque candidat sait à quelles aides il est éligible.',
    ],
    [
        'titre' => 'Soirées étudiantes',
        'texte' => 'Une API dédiée aux soirées étudiantes aide les nouveaux arrivants à s\'intégrer dans leur ville et à lutter contre l\'isolement social.',
    ],
    [
        'titre' => 'Messagerie en direct',
        'texte' => 'Comme sur un réseau social professionnel, étudiants et recruteurs s\'échangent des offres, des CV et discutent en direct.',
    ],
];

echo $twig->render('aPropos.thml.twig', [
    'equipe'          => $equipe,
    'fonctionnalites' => $fonctionnalites,
]);