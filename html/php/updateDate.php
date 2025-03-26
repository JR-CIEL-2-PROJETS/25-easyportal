<?php
header('Content-Type: application/json');
session_start();
require_once 'db.php';

// Vérifie si l'utilisateur est connecté et a un rôle autorisé
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'super_admin')) {
    echo json_encode(["success" => false, "message" => "Accès refusé"]);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$userId = $data['userId'];
$date = $data['date'];

// Validation de la date (optionnel, mais recommandé)
if (!strtotime($date)) {
    echo json_encode(["success" => false, "message" => "Date invalide"]);
    exit;
}

try {
    // Préparer la requête pour mettre à jour la date_fin de l'utilisateur
    $stmt = $pdo->prepare("UPDATE users SET date_fin = :date WHERE id = :id AND role = 'utilisateur-intervenant'");
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':id', $userId);
    $stmt->execute();

    // Vérifie si l'utilisateur a été mis à jour
    if ($stmt->rowCount() > 0) {
        echo json_encode(["success" => true, "message" => "Date mise à jour avec succès"]);
    } else {
        echo json_encode(["success" => false, "message" => "L'utilisateur n'a pas été trouvé ou n'est pas un utilisateur-intervenant"]);
    }

} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Erreur lors de la mise à jour de la date: " . $e->getMessage()]);
}
?>
