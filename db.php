<?php
$servername = "localhost";
$username = "root";
$password = ""; // Leeg wachtwoord voor XAMPP standaard root-gebruiker
$dbname = "edelerosrijschool"; // De juiste naam van je database

try {
    $db = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Verbindingsfout: " . $e->getMessage();
    exit();
}

?>
