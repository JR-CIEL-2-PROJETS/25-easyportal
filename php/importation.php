<?php
include 'config.php'; 

// Fonction pour ajouter ou mettre à jour les plaques d'un utilisateur
function ajouterOuMettreAJourPlaques($nom, $prenom, $plaques, $pdo) {
    // Rechercher l'utilisateur dans la base de données
    $sql = "SELECT id FROM utilisateurs WHERE nom = :nom AND prenom = :prenom";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['nom' => $nom, 'prenom' => $prenom]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        $userId = $user['id'];

        // Ajouter ou mettre à jour les plaques
        foreach ($plaques as $plaque) {
            // Vérifier si la plaque existe déjà
            $sql = "SELECT * FROM plaques WHERE numero = :numero AND user_id = :user_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['numero' => $plaque, 'user_id' => $userId]);
            $existingPlaque = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$existingPlaque) {
                // Ajouter la plaque si elle n'existe pas
                $sql = "INSERT INTO plaques (numero, user_id) VALUES (:numero, :user_id)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['numero' => $plaque, 'user_id' => $userId]);
            }
        }
        echo "Plaques ajoutées ou mises à jour pour $nom $prenom.<br>";
    } else {
        echo "Utilisateur $nom $prenom non trouvé.<br>";
    }
}

// Vérifier si un fichier CSV a été téléchargé
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file']['tmp_name'];

    // Ouvrir le fichier CSV
    if (($handle = fopen($file, 'r')) !== FALSE) {
        $headers = fgetcsv($handle); // Lire l'en-tête du fichier CSV
        
        while (($data = fgetcsv($handle)) !== FALSE) {
            // Lignes CSV contenant nom, prénom et plaques
            $nom = $data[0];
            $prenom = $data[1];
            $plaques = array_slice($data, 2); // Toutes les colonnes après nom et prénom

            // Ajouter ou mettre à jour les plaques
            ajouterOuMettreAJourPlaques($nom, $prenom, $plaques, $pdo);
        }
        fclose($handle);
    }
}
?>
