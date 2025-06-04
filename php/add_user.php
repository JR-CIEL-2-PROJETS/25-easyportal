<?php
session_start();
header('Content-Type: application/json');

// Connexion à la base
$servername = "51.210.151.13";
$username = "easyportal2025";
$password = "EasyPortal2025!";
$dbname = "easyportal2025";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Erreur connexion: ' . $conn->connect_error]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$email = $data['email'] ?? '';
$numero = $data['numero'] ?? '';
$statut = $data['statut'] ?? '';

if (!$email || !$numero || !$statut) {
    echo json_encode(['success' => false, 'message' => 'Données manquantes']);
    exit;
}

// Récupère l'ID de l'utilisateur
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    $userId = $user['id'];

    // Vérifie s'il a déjà 5 plaques
    $countStmt = $conn->prepare("SELECT COUNT(*) AS count FROM plaques WHERE user_id = ?");
    $countStmt->bind_param("i", $userId);
    $countStmt->execute();
    $countResult = $countStmt->get_result();
    $count = $countResult->fetch_assoc()['count'];

    if ($count >= 5) {
        echo json_encode(['success' => false, 'message' => 'Nombre maximal de plaques atteint (5).']);
    } else {
        $insert = $conn->prepare("INSERT INTO plaques (user_id, numero, statut) VALUES (?, ?, ?)");
        $insert->bind_param("iss", $userId, $numero, $statut);
        if ($insert->execute()) {
            echo json_encode(['success' => true, 'message' => 'Plaque ajoutée.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur insertion: ' . $insert->error]);
        }
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Utilisateur non trouvé']);
}

$conn->close();
?>
