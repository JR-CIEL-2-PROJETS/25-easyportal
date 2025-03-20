<?php
header('Content-Type: application/json');

$servername = "mysql";
$username = "user";
$password = "password";
$dbname = "easyportal";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $data = json_decode(file_get_contents("php://input"), true);
    $nom = $data['nom'] ?? '';
    $prenom = $data['prenom'] ?? '';
    $email = $data['email'] ?? '';
    $mot_de_passe = password_hash($data['password'] ?? '', PASSWORD_DEFAULT);
    $role = $data['role'] ?? 'utilisateur';

    $stmt = $pdo->prepare("INSERT INTO users (nom, prenom, email, mot_de_passe, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$nom, $prenom, $email, $mot_de_passe, $role]);

    echo json_encode(["success" => true]);
} catch (PDOException $e) {
    echo json_encode(["error" => "Erreur de connexion à la base de données"]);
}
?>
