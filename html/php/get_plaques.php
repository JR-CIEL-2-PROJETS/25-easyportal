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
    $email = $data['email'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM plaques WHERE user_id = (SELECT id FROM users WHERE email = ?)");
    $stmt->execute([$email]);
    $plaques = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($plaques);
} catch (PDOException $e) {
    echo json_encode(["error" => "Erreur de connexion à la base de données"]);
}
?>
