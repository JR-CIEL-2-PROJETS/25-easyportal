<?php
header('Content-Type: application/json');

require_once 'login.php'; // Connexion à la BDD

$data = json_decode(file_get_contents("php://input"), true);
$UserId = $data['id'] ?? null;

// Vérifier que l'ID est un entier
if (!$UserId || !is_numeric($UserId)) {
    echo json_encode(["success" => false, "message" => "ID invalide ou manquant"]);
    exit;
}

try {
    // Vérifier si l'utilisateur existe et son rôle
    $stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
    $stmt->execute([$UserId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(["success" => false, "message" => "Utilisateur non trouvé"]);
        exit;
    }
    // Supprimer l'Utilisateur
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$UserId]);

    // Vérifier si la suppression a bien eu lieu
    if ($stmt->rowCount() > 0) {
        echo json_encode(["success" => true, "message" => "Utilisateur supprimé avec succès"]);
    } else {
        echo json_encode(["success" => false, "message" => "Aucune ligne supprimée, l'Utilisateur peut ne pas exister"]);
    }

} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Erreur lors de la suppression : " . $e->getMessage()]);
}
?>
