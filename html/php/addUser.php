<?php
header('Content-Type: application/json');
require_once 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $nom = $data['nom'] ?? '';
    $prenom = $data['prenom'] ?? '';
    $email = $data['email'] ?? '';
    $mot_de_passe = password_hash($data['password'] ?? '', PASSWORD_DEFAULT);
    $role = $data['role'] ?? 'utilisateur';
    $date_fin = $data['date_fin'] ?? null;

    $stmt = $pdo->prepare("INSERT INTO users (nom, prenom, email, mot_de_passe, 'role', date_fin) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nom, $prenom, $email, $mot_de_passe, $role, $date_fin]);

    echo json_encode(["success" => true]);
} catch (PDOException $e) {
    echo json_encode(["error" => "Erreur lors de l'ajout de l'utilisateur : " . $e->getMessage()]);
}
?>
