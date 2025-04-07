<?php
ob_start(); // Éviter tout output avant le JSON
header('Content-Type: application/json');
header("Cache-Control: no-cache, no-store, must-revalidate"); // Évite le cache
header("Pragma: no-cache");
header("Expires: 0");

session_start();

$servername = "51.210.151.13";
$username = "easyportal2025";
$password = "EasyPortal2025!";
$dbname = "easyportal2025";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $data = json_decode(file_get_contents("php://input"), true);
    $email = $data['email'] ?? '';
    $mot_de_passe = $data['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($mot_de_passe, $user['mot_de_passe'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['user_name'] = $user['prenom'] . ' ' . $user['nom'];
        error_log("Session mise à jour: " . $_SESSION['user_name']);

        // Répondre avec succès
        echo json_encode(["success" => true, "role" => $user['role']]);
    } else {
        // Répondre avec une erreur
        echo json_encode(["success" => false, "message" => "Email ou mot de passe incorrect"]);
    }
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Erreur de connexion à la base de données"]);
}
?>
