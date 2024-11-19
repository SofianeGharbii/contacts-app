<?php
$servername = "localhost";
$username = "root"; // Remplacez par vos identifiants
$password = "";
$dbname = "contacts_db";

try {
    // Connexion avec PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Définir le mode d'erreur de PDO
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Échec de la connexion : " . $e->getMessage();
}
?>
