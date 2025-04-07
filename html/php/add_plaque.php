<?php
session_start();

// Paramètres de connexion à la base de données
$servername = "51.210.151.13"; // IP de votre serveur OVH
$username = "easyportal2025"; // Votre utilisateur MySQL
$password = "EasyPortal2025!"; // Votre mot de passe MySQL
$dbname = "easyportal2025"; // Le nom de votre base de données

// Créer une connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifiez la connexion
if ($conn->connect_error) {
    die("Échec de la connexion: " . $conn->connect_error);
}

// Afficher les erreurs pour le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $email = $data['email'];
    $numero = $data['numero'];

    // Récupérer l'utilisateur par email
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        $userId = $user['id'];
        
        // Insérer la nouvelle plaque
        $stmt = $conn->prepare("INSERT INTO plaques (user_id, numero, status) VALUES (?, ?, 'actif')");
        $stmt->bind_param("is", $userId, $numero);
        $success = $stmt->execute();

        if ($success) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'ajout de la plaque']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Utilisateur non trouvé']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
}

// Fermer la connexion
$conn->close();
?>
