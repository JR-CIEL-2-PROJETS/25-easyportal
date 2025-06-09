<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

$servername = "51.210.151.13";
$username = "easyportal2025";
$password = "EasyPortal2025!";
$dbname = "easyportal2025";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $role = $_GET['role'] ?? 'admin';

    if ($role === 'super_admin') {
        // Le super admin voit tout
        $stmt = $pdo->prepare("SELECT id, nom, prenom, email, role FROM users");
    } else {
        // Admin et autres rôles voient uniquement les utilisateurs simples
        $stmt = $pdo->prepare("SELECT id, nom, prenom, email, role FROM users WHERE role = 'utilisateur'");
    }

    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["success" => true, "data" => $users]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "error" => "Erreur de connexion : " . $e->getMessage()]);
}
?>