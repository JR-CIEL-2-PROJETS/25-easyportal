<?php
// config.php

$host = 'mysql'; // Adresse du serveur (Docker)
$dbname = 'EasyPortal'; // Nom de la base de données
$username = 'root'; // Nom d'utilisateur MySQL
$password = 'root'; // Mot de passe MySQL

// Connexion PDO avec options de sécurité
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Active les exceptions pour les erreurs SQL
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Mode de récupération par défaut (tableaux associatifs)
        PDO::ATTR_EMULATE_PREPARES => false // Désactive l'émulation des requêtes préparées pour plus de sécurité
    ]);
} catch (PDOException $e) {
    error_log("Erreur de connexion à la base de données : " . $e->getMessage()); // Log l'erreur
    die("Une erreur est survenue, veuillez contacter l'administrateur."); // Message générique en prod
}
?>
