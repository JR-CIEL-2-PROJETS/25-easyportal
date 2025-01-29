<?php

Include 'config.php';

try {
    // Connexion à la base de données avec PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(['status' => 'error', 'message' => "Erreur de connexion : " . $e->getMessage()]));
}

// Vérification si la requête est de type POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données envoyées via POST
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['identifiant']) && isset($data['motdepasse'])) {
        $identifiant = $data['identifiant'];
        $motdepasse = $data['motdepasse'];

        // Requête SQL pour rechercher l'utilisateur
        $sql = "SELECT * FROM users WHERE identifiant = :identifiant";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':identifiant' => $identifiant]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Vérification du mot de passe
            if (password_verify($motdepasse, $user['motdepasse'])) {
                // Connexion réussie
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Connexion réussie.',
                    'user_id' => $user['id'],
                    'identifiant' => $user['identifiant']
                ]);
                exit;
            } else {
                // Mot de passe incorrect
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Mot de passe incorrect.'
                ]);
                exit;
            }
        } else {
            // Utilisateur non trouvé
            echo json_encode([
                'status' => 'error',
                'message' => 'Identifiant introuvable.'
            ]);
            exit;
        }
    } else {
        // Données manquantes dans la requête
        echo json_encode([
            'status' => 'error',
            'message' => 'Les champs identifiant et mot de passe sont obligatoires.'
        ]);
        exit;
    }
} else {
    // Mauvaise méthode HTTP
    echo json_encode([
        'status' => 'error',
        'message' => 'Méthode non autorisée.'
    ]);
    exit;
}
