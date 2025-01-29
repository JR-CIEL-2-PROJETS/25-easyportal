<?php
include '../php/config.php'; 
include '../Plaque/fonctions.php'; 

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="plaques.csv"');

$output = fopen('php://output', 'w');

fputcsv($output, ['Plaques autorisées']);
$plaquesAutorisees = exporterPlaquesAutorisees($pdo);
foreach ($plaquesAutorisees as $plaque) {
    fputcsv($output, [$plaque['nom'], $plaque['prenom'], $plaque['numero']]);
}

fputcsv($output, []);
fputcsv($output, ['Plaques non autorisées']);
$plaquesNonAutorisees = exporterPlaquesNonAutorisees($pdo);
foreach ($plaquesNonAutorisees as $plaque) {
    fputcsv($output, [$plaque['numero']]);
}

fclose($output);
?>
