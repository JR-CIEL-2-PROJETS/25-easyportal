<?php
require_once 'db.php'; // Connexion à la base de données

header("Content-Type: application/json");

// Lire les données JSON envoyées
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["id"]) || !isset($data["date_fin"])) {
    echo json_encode(["success" => false, "message" => "Données invalides."]);
    exit;
}

$id = intval($data["id"]);
$date_fin = $data["date_fin"];

// Si la date est vide ou NULL, on la définit comme NULL dans la base de données
if ($date_fin === null || $date_fin === "") {
    $date_fin = null;
} else {
    // Vérifier si la date est valide au format 'YYYY-MM-DD'
    $date_obj = DateTime::createFromFormat('Y-m-d', $date_fin);
    if (!$date_obj || $date_obj->format('Y-m-d') !== $date_fin) {
        echo json_encode(["success" => false, "message" => "Date invalide."]);
        exit;
    }
}

// Préparer la requête de mise à jour
$sql = "UPDATE utilisateurs SET date_fin = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $date_fin, $id);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Erreur lors de la mise à jour."]);
}

$stmt->close();
$conn->close();
?>
