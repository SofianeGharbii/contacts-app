<?php
// Informations de connexion à MySQL
$servername = "localhost";
$username = "root"; 
$password = ""; 
$database = "contacts_db"; 

try {
    // Connexion à MySQL sans sélectionner de base de données
    $conn = new PDO("mysql:host=$servername", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Créer la base de données si elle n'existe pas
    $sql = "CREATE DATABASE IF NOT EXISTS $database";
    $conn->exec($sql);
    echo "Base de données '$database' créée avec succès.<br>";

    // Se connecter à la base de données créée
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Créer la table 'contacts' si elle n'existe pas
    $sql = "CREATE TABLE IF NOT EXISTS contacts (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        first_name VARCHAR(50) NOT NULL,
        last_name VARCHAR(50) NOT NULL,
        age INT(3) NOT NULL,
        country VARCHAR(50) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        phone_number VARCHAR(15) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $conn->exec($sql);
    echo "Table 'contacts' créée avec succès.<br>";

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}

// Fermer la connexion
$conn = null;
?>
