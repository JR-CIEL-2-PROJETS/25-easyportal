<?php
$servername = "mysql"; 
$username = "user";
$password = "password";
$dbname = "easyportal";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(["success" => false, "message" => "Erreur de connexion à la base de données"]));
}
?>
