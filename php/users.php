<?php
// Informations de connexion à la base de données
$host = '127.0.0.1'; // Adresse du serveur
$dbname = 'Easy-portal'; // Nom de la base de données
$username = 'root'; // Nom d'utilisateur MySQL
$password = ''; // Mot de passe MySQL

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
    if (isset($_POST['pseudo'], $_POST['prenom'], $_POST['nom'], $_POST['plaques'], $_POST['date'])) {
        // Récupération des données du formulaire
        $pseudo = htmlspecialchars(trim($_POST['pseudo']));
        $prenom = htmlspecialchars(trim($_POST['prenom']));
        $nom = htmlspecialchars(trim($_POST['nom']));
        $plaques = htmlspecialchars(trim($_POST['plaques']));
        $date = $_POST['date'];

        // Validation simple des plaques (par exemple, format alphanumérique)
        if (!preg_match("/^[A-Za-z0-9-]+$/", $plaques)) {
            echo "Erreur : Format des plaques d'immatriculation invalide.";
            exit;
        }

        // Préparation de la requête SQL
        $sql = "INSERT INTO users (pseudo, prénom, nom, plaques, date) VALUES (:pseudo, :prenom, :nom, :plaques, :date)";

        try {
            $stmt = $pdo->prepare($sql);
            // Exécution de la requête avec les données du formulaire
            $stmt->execute([
                ':pseudo' => $pseudo,
                ':prenom' => $prenom,
                ':nom' => $nom,
                ':plaques' => $plaques,
                ':date' => $date,
            ]);

            echo "Données insérées avec succès !";
        } catch (PDOException $e) {
            echo "Erreur lors de l'insertion des données : " . $e->getMessage();
        }
    } else {
        echo "Erreur : Veuillez remplir tous les champs.";
    }
}
?>
