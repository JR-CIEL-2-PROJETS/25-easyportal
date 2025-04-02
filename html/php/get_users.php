<?php
header('Content-Type: application/json');

$servername = "51.210.151.13"; // IP de ton serveur OVH
$username = "easyportal2025"; // Ton utilisateur MySQL
$password = "EasyPortal2025!"; // Ton mot de passe MySQL
$dbname = "easyportal2025"; // Le nom de ta base de données

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE role = 'utilisateur'");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($users);
} catch (PDOException $e) {
    echo json_encode(["error" => "Erreur de connexion à la base de données"]);
}
?>
