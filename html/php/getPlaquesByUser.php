<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');
session_start();
require_once 'db.php';


if (isset($_GET['email'])) {
    $email = $_GET['email'];

    // Connexion à la base de données (exemple avec PDO)
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=easy_portal', 'username', 'password');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Requête pour récupérer les plaques de l'utilisateur
        $stmt = $pdo->prepare('SELECT * FROM plaques WHERE email_user = :email');
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $plaques = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($plaques) {
            echo json_encode(['success' => true, 'plaques' => $plaques]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Aucune plaque trouvée pour cet utilisateur.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erreur de base de données : ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Email non fourni.']);
}
?>
