<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Ajoutez ceci si nécessaire pour les requêtes CORS

$servername = "51.210.151.13";
$username = "easyportal2025";
$password = "EasyPortal2025!";
$dbname = "easyportal2025";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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
