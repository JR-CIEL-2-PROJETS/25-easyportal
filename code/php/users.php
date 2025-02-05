<?php
include 'config.php';  // Inclure la configuration de la base de données

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nom'], $_POST['prenom'], $_POST['email'], $_POST['mot_de_passe'])) {
        
        // Sécurisation des entrées
        $nom = htmlspecialchars(trim($_POST['nom']));
        $prenom = htmlspecialchars(trim($_POST['prenom']));
        $email = htmlspecialchars(trim($_POST['email']));
        $mot_de_passe = htmlspecialchars(trim($_POST['mot_de_passe'])); // On ne hache plus le mot de passe

        // Validation de l'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Erreur : Format d'email invalide.";
            exit;
        }

        // Requête SQL pour insérer les données de l'utilisateur
        $sql = "INSERT INTO users (nom, prenom, email, mot_de_passe) VALUES (:nom, :prenom, :email, :mot_de_passe)";
        
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':email' => $email,
                ':mot_de_passe' => $mot_de_passe,  // On insère directement le mot de passe en clair
            ]);

            echo "Utilisateur ajouté avec succès !";
        } catch (PDOException $e) {
            echo "Erreur lors de l'insertion : " . $e->getMessage();
        }
    } else {
        echo "Erreur : Veuillez remplir tous les champs.";
    }
}
?>
