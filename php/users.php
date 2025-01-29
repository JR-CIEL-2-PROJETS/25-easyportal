<?php

include 'config.php';

try {
    // Connexion à la base de données avec PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Vérification si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérification si toutes les données sont présentes
    if (isset($_POST['nom'], $_POST['prenom'], $_POST['email'], $_POST['mot_de_passe'])) {
        // Récupération et validation des données du formulaire
        $nom = htmlspecialchars(trim($_POST['nom']));
        $prenom = htmlspecialchars(trim($_POST['prenom']));
        $email = htmlspecialchars(trim($_POST['email']));
        $mot_de_passe = htmlspecialchars(trim($_POST['mot_de_passe']));

        // Validation de l'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Erreur : Format d'email invalide.";
            exit;
        }

        // Hachage du mot de passe pour plus de sécurité
        $mot_de_passe_hache = password_hash($mot_de_passe, PASSWORD_DEFAULT);

        // Préparation de la requête SQL (la colonne "date" est gérée par la base de données)
        $sql = "INSERT INTO users (nom, prénom, email, mot_de_passe) VALUES (:nom, :prenom, :email, :mot_de_passe)";

        try {
            $stmt = $pdo->prepare($sql);
            // Exécution de la requête avec les données du formulaire
            $stmt->execute([
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':email' => $email,
                ':mot_de_passe' => $mot_de_passe_hache,
            ]);

            echo "Utilisateur ajouté avec succès !";
        } catch (PDOException $e) {
            echo "Erreur lors de l'insertion des données : " . $e->getMessage();
        }
    } else {
        echo "Erreur : Veuillez remplir tous les champs.";
    }
}
?>
