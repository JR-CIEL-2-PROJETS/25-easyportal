<?php
session_start();

// Paramètres de connexion à la base de données
$servername = "51.210.151.13"; // IP de ton serveur MySQL
$username = "easyportal2025"; // Ton utilisateur MySQL
$password = "EasyPortal2025!"; // Ton mot de passe MySQL
$dbname = "easyportal2025"; // Le nom de ta base de données

// Créer une connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifie la connexion
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Échec de la connexion: ' . $conn->connect_error]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $plaqueId = $data['id'];
    $status = $data['status'];

    // Vérifie que l'ID et le statut sont fournis
    if (!$plaqueId || !$status) {
        echo json_encode(['success' => false, 'message' => 'ID de plaque ou statut non fournis.']);
        exit;
    }

    // Prépare et exécute la mise à jour
    $stmt = $conn->prepare("UPDATE plaques SET statut = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $plaqueId);
    $success = $stmt->execute();

    if ($success) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour de l\'état: ' . $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
}

// Fermer la connexion
$conn->close();
?>
