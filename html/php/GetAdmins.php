<?php
header('Content-Type: application/json');
require 'login.php'; // Fichier de connexion à la base de données

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SELECT id, nom, prenom, email, role FROM users WHERE role IN ('admin', 'super_admin')");
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["success" => true, "admins" => $admins]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Erreur de connexion à la base de données"]);
}
?>
