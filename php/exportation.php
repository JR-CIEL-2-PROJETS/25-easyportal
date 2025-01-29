<?php
include 'config.php'; // Inclure la config de la base de données

// Exporter les plaques autorisées (celles qui existent dans la base de données)
function exporterPlaquesAutorisees($pdo) {
    $sql = "SELECT p.numero, u.nom, u.prenom FROM plaques p JOIN utilisateurs u ON p.user_id = u.id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $plaques = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $plaques;
}

// Exporter les plaques non autorisées (non présentes dans la base de données)
function exporterPlaquesNonAutorisees($pdo) {
    $sql = "SELECT p.numero FROM plaques p LEFT JOIN utilisateurs u ON p.user_id = u.id WHERE u.id IS NULL";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $plaques = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $plaques;
}

// Définir l'en-tête pour le fichier CSV
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="plaques.csv"');

// Ouvrir le flux de sortie
$output = fopen('php://output', 'w');

// Exporter les plaques autorisées
$plaquesAutorisees = exporterPlaquesAutorisees($pdo);
fputcsv($output, ['Plaques autorisées']);
foreach ($plaquesAutorisees as $plaque) {
    fputcsv($output, [$plaque['nom'], $plaque['prenom'], $plaque['numero']]);
}

// Exporter les plaques non autorisées
fputcsv($output, []); // Laisser une ligne vide
fputcsv($output, ['Plaques non autorisées']);
$plaquesNonAutorisees = exporterPlaquesNonAutorisees($pdo);
foreach ($plaquesNonAutorisees as $plaque) {
    fputcsv($output, [$plaque['numero']]);
}

// Fermer le flux
fclose($output);
?>
