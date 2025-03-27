<?php
header('Content-Type: application/json');

require_once 'login.php'; // Connexion à la BDD

$data = json_decode(file_get_contents("php://input"), true);
$adminId = $data['id'] ?? null;

// Vérifier que l'ID est un entier
if (!$adminId || !is_numeric($adminId)) {
    echo json_encode(["success" => false, "message" => "ID invalide ou manquant"]);
    exit;
}

try {
    // Vérifier si l'utilisateur existe et son rôle
    $stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
    $stmt->execute([$adminId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(["success" => false, "message" => "Administrateur non trouvé"]);
        exit;
    }

    if ($user['role'] === 'super_admin') {
        echo json_encode(["success" => false, "message" => "Impossible de supprimer un super admin"]);
        exit;
    }

    // Supprimer l'admin
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$adminId]);

    // Vérifier si la suppression a bien eu lieu
    echo json_encode(["success" => true, "message" => "Administrateur supprimé avec succès"]);
exit;


} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Erreur lors de la suppression : " . $e->getMessage()]);
}
?>
