<?php

include 'gestion_plaque.php';
include 'config.php';

// Définir les en-têtes pour permettre les requêtes cross-origin (CORS) et JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Récupérer la méthode de la requête HTTP (POST, GET, PUT, DELETE)
$method = $_SERVER['REQUEST_METHOD'];

// Récupérer l'ID à partir de l'URL (si disponible)
$id = isset($_GET['id']) ? $_GET['id'] : null;

// Traitement en fonction de la méthode HTTP
switch ($method) {
    case 'POST':
        // Ajouter une plaque
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['numero'])) {
            $numero = $data['numero'];
            $statut = isset($data['statut']) ? $data['statut'] : 'actif';
            echo json_encode(['message' => ajouterPlaque($numero, $statut)]);
        } else {
            echo json_encode(['error' => 'Numéro de plaque manquant']);
        }
        break;

    case 'PUT':
        // Modifier une plaque (par exemple, changer le statut)
        if ($id) {
            $data = json_decode(file_get_contents("php://input"), true);
            if (isset($data['statut'])) {
                echo json_encode(['message' => modifierPlaque($id, $data['statut'])]);
            } else {
                echo json_encode(['error' => 'Statut manquant']);
            }
        } else {
            echo json_encode(['error' => 'ID manquant']);
        }
        break;

    case 'DELETE':
        // Supprimer une plaque
        if ($id) {
            echo json_encode(['message' => supprimerPlaque($id)]);
        } else {
            echo json_encode(['error' => 'ID manquant']);
        }
        break;

    case 'GET':
        // Obtenir les informations d'une plaque
        if ($id) {
            $plaque = obtenirPlaque($id);
            if ($plaque) {
                echo json_encode($plaque);
            } else {
                echo json_encode(['error' => 'Plaque non trouvée']);
            }
        } else {
            echo json_encode(['error' => 'ID manquant']);
        }
        break;

    default:
        echo json_encode(['error' => 'Méthode non autorisée']);
        break;
}
?>
