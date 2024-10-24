<?php
session_start(); // Start de sessie
include 'db.php'; // Verbind met de database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Zoek de gebruiker in de database
    $sql = "SELECT * FROM gebruikers WHERE username = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$username]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Controleer of de gebruiker bestaat en of het wachtwoord klopt
    if ($user && password_verify($password, $user['password'])) {
        // Sla de gebruikersinformatie op in de sessie
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Stuur door naar het dashboard
        header('Location: dashboard.php');
        exit();
    } else {
        echo "<p class='error-message'>Ongeldige gebruikersnaam of wachtwoord.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inloggen</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>
<body>
    <div class="container">
    <a href="index.php" class="home-button"><i class="fas fa-home"></i> Home</a>
        <h1>Inloggen</h1>
        <form method="POST" action="login.php">
            <input type="text" name="username" placeholder="Gebruikersnaam" required>
            <input type="password" name="password" placeholder="Wachtwoord" required>
            <button type="submit">Inloggen</button>
        </form>
    </div>
</body>
</html>
