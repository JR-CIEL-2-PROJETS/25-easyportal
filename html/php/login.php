<?php
header('Content-Type: application/json');
session_start();

$servername = "mysql";
$username = "user";
$password = "password";
$dbname = "easyportal";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $data = json_decode(file_get_contents("php://input"), true);
    $email = $data['email'] ?? '';
    $mot_de_passe = $data['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $mot_de_passe === $user['mot_de_passe']) {
        if ($user['role'] === 'admin' || $user['role'] === 'super_admin') {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            echo json_encode(["success" => true, "role" => $user['role']]);
        } else {
            echo json_encode(["success" => false, "message" => "Accès refusé : vous devez être administrateur."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Email ou mot de passe incorrect"]);
    }
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Erreur de connexion à la base de données"]);
}
?>
