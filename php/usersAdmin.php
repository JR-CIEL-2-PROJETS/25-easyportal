<?php
include 'config.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (isset($data['identifiant'], $data['motdepasse'])) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE identifiant = :identifiant");
        $stmt->execute([':identifiant' => $data['identifiant']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($data['motdepasse'], $user['motdepasse'])) {
            echo json_encode(['status' => 'success', 'message' => 'Connexion réussie.', 'user_id' => $user['id']]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Identifiant ou mot de passe incorrect.']);
        }
    }
}
?>
