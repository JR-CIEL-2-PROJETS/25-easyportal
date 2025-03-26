<?php
header('Content-Type: application/json');
require_once 'db.php';

try {
    // Connexion à la base de données
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Requête SQL pour récupérer les utilisateurs ayant les rôles "utilisateur" ou "utilisateur-intervenant"
    $stmt = $pdo->prepare("SELECT id, prenom, nom, email, 'role', date_fin FROM users WHERE 'role' IN ('utilisateur', 'utilisateur-intervenant')");
    $stmt->execute();

    // Récupération des résultats
    $users = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $users[] = $row;
    }

    // Retourne les utilisateurs sous forme de JSON
    echo json_encode($users);

} catch (PDOException $e) {
    // Gestion des erreurs
    echo json_encode(["error" => "Erreur lors de la récupération des utilisateurs : " . $e->getMessage()]);
}
?>
