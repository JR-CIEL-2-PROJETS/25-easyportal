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

if (isset($_GET['email'])) {
    $email = $_GET['email'];

    // Préparez votre requête SQL pour obtenir les plaques de l'utilisateur
    $stmt = $conn->prepare("SELECT users.nom, users.prenom, plaques.numero, plaques.status, plaques.id 
                             FROM users 
                             JOIN plaques ON users.id = plaques.user_id 
                             WHERE users.email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    $plaques = [];
    while ($row = $result->fetch_assoc()) {
        $plaques[] = $row;
    }

    // Retournez les données au format JSON
    echo json_encode(['success' => true, 'plaques' => $plaques]);
} else {
    echo json_encode(['success' => false, 'message' => 'Email non fourni']);
}

// Fermer la connexion
$conn->close();
?>
