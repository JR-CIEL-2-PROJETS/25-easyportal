<?php
header('Content-Type: application/json');

$servername = "mysql";
$username = "user";
$password = "password";
$dbname = "easyportal";

try {
    // Connexion à la base de données
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupération des logs avec les informations de l'utilisateur
    $stmt = $pdo->prepare("
        SELECT u.email, l.date_entree, l.plaque, l.action
        FROM logs l
        JOIN users u ON l.user_id = u.id
        ORDER BY l.date_entree DESC
    ");
    $stmt->execute();

    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($logs);

} catch (PDOException $e) {
    echo json_encode(["error" => "Erreur de connexion à la base de données : " . $e->getMessage()]);
}
?>
