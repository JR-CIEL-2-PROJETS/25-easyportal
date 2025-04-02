<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');
session_start();
require_once 'db.php';

try {
    $pdo = new PDO('mysql:host=localhost;dbname=easy_portal', 'username', 'password');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Requête pour récupérer toutes les plaques avec les informations des utilisateurs
    $stmt = $pdo->prepare('SELECT u.nom, u.prenom, p.plaque FROM users u JOIN plaques p ON u.email = p.email_user');
    $stmt->execute();

    $plaques = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'plaques' => $plaques]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur de base de données : ' . $e->getMessage()]);
}
?>
