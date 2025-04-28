<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');
session_start();
require_once 'db.php';

// Vérifie si l'utilisateur est connecté et a un rôle autorisé
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'super_admin')) {
    echo json_encode(["success" => false, "message" => "Accès refusé"]);
    exit;
}

try {
    // Préparer la requête pour récupérer les utilisateurs avec les rôles 'utilisateur' et 'utilisateur-intervenant'
    $stmt = $pdo->prepare("SELECT id, prenom, nom, email, `role`, date_fin FROM users WHERE `role` IN ('utilisateur', 'utilisateur-intervenant')");
    $stmt->execute();

    // Récupérer les utilisateurs
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Si des utilisateurs sont trouvés, renvoyer un message de succès et les utilisateurs, sinon renvoyer un message d'erreur
    if (count($users) > 0) {
        echo json_encode(["success" => true, "users" => $users]);
    } else {
        echo json_encode(["success" => false, "message" => "Aucun utilisateur trouvé"]);
    }

} catch (PDOException $e) {
    // Si une erreur se produit dans la récupération des utilisateurs
    echo json_encode(["success" => false, "message" => "Erreur lors de la récupération des utilisateurs: " . $e->getMessage()]);
}
?>
