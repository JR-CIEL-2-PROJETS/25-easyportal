<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $file = $_FILES['file']['tmp_name'];
    $handle = fopen($file, "r");

    $servername = "mysql";
    $username = "user";
    $password = "password";
    $dbname = "easyportal";

    try {
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $stmt = $pdo->prepare("INSERT INTO plaques (numero) VALUES (?)");
            $stmt->execute([$data[0]]);
        }

        fclose($handle);
        echo json_encode(["success" => true]);
    } catch (PDOException $e) {
        echo json_encode(["error" => "Erreur de connexion à la base de données"]);
    }
}
?>
