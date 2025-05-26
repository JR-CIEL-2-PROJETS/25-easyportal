<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');
session_start();
require_once 'db.php';

// Vérifie si l'utilisateur est connecté et a un rôle autorisé
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'super_admin')) {
    echo json_encode(["success" => false, "message" => "Accès refusé"]);
    exit;
}

try {
    // Requête pour récupérer les logs avec les emails des utilisateurs
    $stmt = $pdo->prepare("
        SELECT users.email, logs.date_entree, logs.plaque, logs.action
        FROM logs 
        INNER JOIN users ON logs.user_id = users.id
        ORDER BY logs.date_entree DESC
    ");
    $stmt->execute();
    
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($logs) > 0) {
        echo json_encode(["success" => true, "logs" => $logs]);
    } else {
        echo json_encode(["success" => false, "message" => "Aucun log trouvé"]);
    }

} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Erreur lors de la récupération des logs: " . $e->getMessage()]);
}
?>
