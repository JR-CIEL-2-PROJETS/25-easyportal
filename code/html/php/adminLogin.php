<?php
include 'config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Activer les erreurs PDO pour voir les problèmes SQL
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des valeurs envoyées
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;
    $mot_de_passe = isset($_POST['mot_de_passe']) ? trim($_POST['mot_de_passe']) : null;

    if (!empty($email) && !empty($mot_de_passe)) {
        echo json_encode(['message' => verifierConnexion($email, $mot_de_passe, $pdo)]);
    } else {
        echo json_encode(['error' => 'Email ou mot de passe manquant.']);
    }
} else {
    echo json_encode(['error' => 'Méthode non autorisée.']);
}

/**
 * Vérifier la connexion d'un utilisateur Admin
 */
function verifierConnexion($email, $mot_de_passe, $pdo) {
    // Vérifier si l'email existe en base
    $query = $pdo->prepare("SELECT * FROM AdminUsers WHERE email = ?");
    $query->execute([$email]);
    $admin = $query->fetch();

    if (!$admin) {
        return "Échec : Email non trouvé.";
    }

    // Comparaison simple des mots de passe
    if ($mot_de_passe === $admin['mot_de_passe']) {
        return "Connexion réussie !";
    } else {
        return "Mot de passe incorrect.";
    }
}
?>
