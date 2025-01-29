<?php

include '../php/config.php'; 
include '../Plaque/fonctions.php'; 

function ajouterPlaque($numero, $statut = 'actif') {
    global $pdo;
    $sql = "INSERT INTO plaques (numero, statut) VALUES (:numero, :statut)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':numero' => $numero, ':statut' => $statut]);
    return "Plaque ajoutée avec succès.";
}

function modifierPlaque($id, $statut) {
    global $pdo;
    $sql = "UPDATE plaques SET statut = :statut WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':statut' => $statut, ':id' => $id]);
    return "Plaque modifiée avec succès.";
}

function supprimerPlaque($id) {
    global $pdo;
    $sql = "DELETE FROM plaques WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    return "Plaque supprimée avec succès.";
}

function bloquerPlaque($id) {
    return modifierPlaque($id, 'bloqué');
}

function obtenirPlaque($id) {
    global $pdo;
    $sql = "SELECT * FROM plaques WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

?>
