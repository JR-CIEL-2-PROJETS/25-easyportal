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
        } else {
            echo json_encode(['error' => 'Numéro de plaque manquant']);
        }
        break;

    case 'PUT':
        if ($id) {
            $data = json_decode(file_get_contents("php://input"), true);
            if (isset($data['statut'])) {
                echo json_encode(['message' => modifierPlaque($id, $data['statut'], $pdo)]);
            } else {
                echo json_encode(['error' => 'Statut manquant']);
            }
        } else {
            echo json_encode(['error' => 'ID manquant']);
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
