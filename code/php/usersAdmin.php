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
        if (isset($data['email'], $data['mot_de_passe'])) {
            $is_super_admin = isset($data['is_super_admin']) ? $data['is_super_admin'] : 0;

            // Empêcher la création de plusieurs super-utilisateurs
            if ($is_super_admin == 1) {
                $check_super_admin = $pdo->query("SELECT COUNT(*) FROM AdminUsers WHERE is_super_admin = 1")->fetchColumn();
                if ($check_super_admin > 0) {
                    echo json_encode(['error' => 'Un super-utilisateur existe déjà.']);
                    exit;
                }
            }

            echo json_encode(['message' => ajouterAdminUser($data['email'], $data['mot_de_passe'], $is_super_admin, $pdo)]);
        } else {
            echo json_encode(['error' => 'Email ou mot de passe manquant.']);
        }
        break;

    case 'DELETE':
        if ($id) {
            // Vérifier si l'utilisateur est un super-utilisateur
            $query = $pdo->prepare("SELECT is_super_admin FROM AdminUsers WHERE id = ?");
            $query->execute([$id]);
            $result = $query->fetch();

            if ($result && $result['is_super_admin'] == 1) {
                echo json_encode(['error' => 'Le super-utilisateur ne peut pas être supprimé.']);
                exit;
            }

            echo json_encode(['message' => supprimerAdminUser($id, $pdo)]);
        } else {
            echo json_encode(['error' => 'ID manquant.']);
        }
        break;

    default:
        echo json_encode(['error' => 'Méthode non autorisée.']);
        break;
}

/**
 * Ajouter un utilisateur Admin
 */
function ajouterAdminUser($email, $mot_de_passe, $is_super_admin, $pdo) {
    $hashed_password = password_hash($mot_de_passe, PASSWORD_BCRYPT);

    $query = $pdo->prepare("INSERT INTO AdminUsers (email, mot_de_passe, is_super_admin) VALUES (?, ?, ?)");
    if ($query->execute([$email, $hashed_password, $is_super_admin])) {
        return "Utilisateur ajouté avec succès.";
    }
    return "Erreur lors de l'ajout de l'utilisateur.";
}

/**
 * Supprimer un utilisateur Admin
 */
function supprimerAdminUser($id, $pdo) {
    $query = $pdo->prepare("DELETE FROM AdminUsers WHERE id = ?");
    if ($query->execute([$id])) {
        return "Administrateur supprimé avec succès.";
    }
    return "Cet Administrateur ne peut pas etre supprimé.";
}
?>
