<?php
session_start();
header('Content-Type: application/json');

// Connexion à la base
$servername = "51.210.151.13";
$username = "easyportal2025";
$password = "EasyPortal2025!";
$dbname = "easyportal2025";

// Connexion MySQLi
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Erreur de connexion: ' . $conn->connect_error]);
    exit;
}

// Récupère l'email transmis en GET
$email = $_GET['email'] ?? null;
if (!$email) {
    echo json_encode(['success' => false, 'message' => "Paramètre 'email' manquant."]);
    exit;
}

// Vérifie que l'utilisateur est admin ou super_admin
$stmt = $conn->prepare("SELECT role FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user || ($user['role'] !== 'admin' && $user['role'] !== 'super_admin')) {
    echo json_encode(['success' => false, 'message' => "Accès refusé."]);
    exit;
}

// Récupère les logs
$query = "
    SELECT users.email, logs.date_entree, logs.plaque, logs.action
    FROM logs
    INNER JOIN users ON logs.user_id = users.id
    ORDER BY logs.date_entree DESC
";
$logResult = $conn->query($query);

$logs = [];
if ($logResult && $logResult->num_rows > 0) {
    while ($row = $logResult->fetch_assoc()) {
        $logs[] = $row;
    }
    echo json_encode(['success' => true, 'logs' => $logs]);
} else {
    echo json_encode(['success' => false, 'message' => 'Aucun log trouvé.']);
}

$conn->close();