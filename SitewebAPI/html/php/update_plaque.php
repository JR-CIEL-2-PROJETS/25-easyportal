<?php
session_start();
header('Content-Type: application/json');

$servername = "51.210.151.13";
$username = "easyportal2025";
$password = "EasyPortal2025!";
$dbname = "easyportal2025";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Connexion échouée: ' . $conn->connect_error]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'] ?? null;
$numero = $data['numero'] ?? null;
$statut = $data['statut'] ?? null;

if (!$id || !$numero || !$statut) {
    echo json_encode(['success' => false, 'message' => 'Paramètres manquants']);
    exit;
}

$stmt = $conn->prepare("UPDATE plaques SET numero = ?, statut = ? WHERE id = ?");
$stmt->bind_param("ssi", $numero, $statut, $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Plaque mise à jour']);
} else {
    echo json_encode(['success' => false, 'message' => 'Erreur de mise à jour: ' . $conn->error]);
}

$conn->close();
?>