<?php
header('Content-Type: application/json');
session_start();
require_once 'db.php';

// Vérification des données reçues
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['nom'], $data['prenom'], $data['email'], $data['password'], $data['role'])) {
    echo json_encode(["success" => false, "message" => "Données manquantes"]);
    exit;
}

$nom = trim($data['nom']);
$prenom = trim($data['prenom']);
$email = trim($data['email']);
$password = trim($data['password']); 
$role = trim($data['role']);

try {
    $stmt = $pdo->prepare("
        INSERT INTO users (prenom, nom, email, role, mot_de_passe)
        VALUES (:prenom, :nom, :email, :role, :mot_de_passe)
    ");

    $stmt->bindParam(':prenom', $prenom);
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':mot_de_passe', $password);

    $stmt->execute();

    echo json_encode(["success" => true, "message" => "Utilisateur ajouté avec succès"]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Erreur lors de l'ajout de l'utilisateur : " . $e->getMessage()]);
}
?>
