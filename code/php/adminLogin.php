<?php
include 'config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email'], $_POST['mot_de_passe'])) {
        echo json_encode(['message' => verifierConnexion($_POST['email'], $_POST['mot_de_passe'], $pdo)]);
    } else {
        echo json_encode(['error' => 'encore un flop']);
    }
}

/**
 * Vérifier la connexion d'un utilisateur Admin
 */
function verifierConnexion($email, $mot_de_passe, $pdo) {
    $query = $pdo->prepare("SELECT * FROM AdminUsers WHERE email = ?");
    $query->execute([$email]);
    $admin = $query->fetch();

    if ($admin && password_verify($mot_de_passe, $admin['mot_de_passe'])) {
        return "Connexion réussie. Bienvenue, admin !";
    }
    return "Échec de la connexion. Email ou mot de passe incorrect.";
}
?>
