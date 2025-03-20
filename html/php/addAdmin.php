<?php
header('Content-Type: application/json');

// Connexion à la base de données
require_once 'db.php'; // Assure-toi que ce fichier contient la connexion à la base de données

// Récupérer et décoder les données envoyées
$data = json_decode(file_get_contents("php://input"), true);

// Vérifier que toutes les données nécessaires sont présentes et non vides
$prenom = isset($data['prenom']) ? trim($data['prenom']) : '';
$nom = isset($data['nom']) ? trim($data['nom']) : '';
$email = isset($data['email']) ? trim($data['email']) : '';
$password = isset($data['mot_de_passe']) ? trim($data['mot_de_passe']) : '';

// Validation des données
if (empty($prenom) || empty($nom) || empty($email) || empty($password)) {
    echo json_encode(["success" => false, "message" => "Tous les champs sont obligatoires."]);
    exit;
}

// Vérification si l'email existe déjà
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    echo json_encode(["success" => false, "message" => "Cet email est déjà utilisé."]);
    exit;
}

// Insertion de l'administrateur dans la base de données
try {
    $stmt = $pdo->prepare("INSERT INTO users (prenom, nom, email, mot_de_passe, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$prenom, $nom, $email, $password, 'admin']); // Le rôle par défaut est 'admin'

    // Si l'insertion est réussie, renvoyer un message de succès
    echo json_encode(["success" => true, "message" => "L'administrateur a été ajouté avec succès."]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Erreur lors de l'ajout de l'administrateur."]);
}
?>
