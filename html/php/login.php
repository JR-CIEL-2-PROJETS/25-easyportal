<?php
header('Content-Type: application/json');
session_start();
$_SESSION['user_name'] = $user['prenom'] . ' ' . $user['nom'];
error_log("Session mise à jour: " . $_SESSION['user_name']); // Log pour vérifier



$servername = "51.210.151.13"; // IP de ton serveur OVH
$username = "easyportal2025"; // Ton utilisateur MySQL
$password = "EasyPortal2025!"; // Ton mot de passe MySQL
$dbname = "easyportal2025"; // Le nom de ta base de données

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer les données du POST
    $data = json_decode(file_get_contents("php://input"), true);
    $email = $data['email'] ?? '';
    $mot_de_passe = $data['password'] ?? '';

    // Préparer la requête pour vérifier l'utilisateur
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Vérifier si le mot de passe est correct
        if (password_verify($mot_de_passe, $user['mot_de_passe'])) {
            // Authentifier l'utilisateur
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['user_name'] = $user['prenom'] . ' ' . $user['nom'];
            
            echo json_encode(["success" => true, "role" => $user['role']]);
        } else {
            echo json_encode(["success" => false, "message" => "Email ou mot de passe incorrect"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Email ou mot de passe incorrect"]);
    }
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Erreur de connexion à la base de données"]);
}
?>
