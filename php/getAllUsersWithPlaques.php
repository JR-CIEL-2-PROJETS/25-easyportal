<?php
header('Content-Type: application/json');

$servername = "51.210.151.13";
$username = "easyportal2025";
$password = "EasyPortal2025!";
$dbname = "easyportal2025";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Erreur connexion: ' . $conn->connect_error]);
    exit;
}

$sql = "
    SELECT 
        users.id AS user_id,
        users.nom,
        users.prenom,
        users.email,
        users.role,
        plaques.id AS plaque_id,
        plaques.numero,
        plaques.statut
    FROM users
    LEFT JOIN plaques ON plaques.user_id = users.id
    WHERE users.role IN ('utilisateur', 'admin')
    ORDER BY users.nom ASC
";

$result = $conn->query($sql);

if ($result) {
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode(['success' => true, 'users' => $data]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erreur SQL: ' . $conn->error]);
}

$conn->close();
?>
