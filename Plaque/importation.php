<?php
include '../php/config.php'; 
include '../Plaque/fonctions.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file']['tmp_name'];

    if (($handle = fopen($file, 'r')) !== FALSE) {
        $headers = fgetcsv($handle);
        
        while (($data = fgetcsv($handle)) !== FALSE) {
            $nom = $data[0];
            $prenom = $data[1];
            $plaques = array_slice($data, 2);
            ajouterOuMettreAJourPlaques($nom, $prenom, $plaques, $pdo);
        }
        fclose($handle);
    }
}
?>
