<?php
require_once 'db.php'; // Connexion à la base de données

header("Content-Type: application/json");

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
    // Convertir la date du format 'YYYY-MM-DDTHH:MM' vers 'YYYY-MM-DD HH:MM:SS.000000'
    // La chaîne "T" doit être remplacée par un espace et on ajoute les secondes et microsecondes
    $date_fin = str_replace("T", " ", $date_fin) . ":00.000000";
}

// Vérifier si la date est valide
if ($date_fin !== null && strtotime($date_fin) === false) {
    echo json_encode(["success" => false, "message" => "Date invalide."]);
    exit;
}

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
