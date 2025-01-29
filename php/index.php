<?php
include 'config.php';
include '../Plaque/fonctions.php'; 

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? $_GET['id'] : null;

switch ($method) {
    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data['numero'])) {
            echo json_encode(['message' => ajouterPlaque($data['numero'], 'actif', $pdo)]);
        } elseif (isset($data['id']) && isset($data['statut'])) {
            echo json_encode(['message' => modifierPlaque($data['id'], $data['statut'], $pdo)]);
        } else {
            echo json_encode(['error' => 'Données manquantes pour l\'ajout ou la modification']);
        }
        break;

    case 'DELETE':
        if ($id) {
            echo json_encode(['message' => supprimerPlaque($id, $pdo)]);
        } else {
            echo json_encode(['error' => 'ID manquant']);
        }
        break;

    default:
        echo json_encode(['error' => 'Méthode non autorisée']);
        break;
}
?>
