<?php
header('Content-Type: application/json');
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'super_admin')) {
    echo json_encode(["success" => false, "message" => "Accès refusé"]);
    exit;
}

try {
    $stmt = $pdo->query("SELECT id, prenom, nom, email, `role` FROM users WHERE `role` IN ('admin', 'super_admin')");
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["success" => true, "admins" => $admins]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Erreur lors de la récupération des administrateurs"]);
}
?>
