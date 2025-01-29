<?php
// Inclure la config pour la connexion à la base de données
include '../php/config.php';

// Fonction pour vérifier si une plaque existe déjà
function plaqueExiste($plaque, $pdo) {
    $sql = "SELECT COUNT(*) FROM plaques WHERE numero = :plaque";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['plaque' => $plaque]);
    return $stmt->fetchColumn() > 0;
}

// Fonction pour ajouter une plaque
function ajouterPlaque($numero, $statut = 'actif', $pdo) {
    if (!plaqueExiste($numero, $pdo)) {
        $sql = "INSERT INTO plaques (numero, statut) VALUES (:numero, :statut)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['numero' => $numero, 'statut' => $statut]);
        return "Plaque ajoutée avec succès.";
    }
    return "La plaque existe déjà.";
}

// Fonction pour modifier une plaque
function modifierPlaque($id, $statut, $pdo) {
    $sql = "UPDATE plaques SET statut = :statut WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['statut' => $statut, 'id' => $id]);
    return "Plaque modifiée avec succès.";
}

// Fonction pour supprimer une plaque
function supprimerPlaque($id, $pdo) {
    $sql = "DELETE FROM plaques WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    return "Plaque supprimée avec succès.";
}
?>
