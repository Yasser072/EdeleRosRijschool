<?php
// Inclusie van de databaseverbinding
include 'db.php';

// Controleer of het formulier is verzonden
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Bereid de SQL-query voor om de gebruiker te zoeken
    $sql = "SELECT * FROM gebruikers WHERE username = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$username]);

    // Haal de gebruiker op
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Controleer of de gebruiker bestaat en het wachtwoord correct is
    if ($user && password_verify($password, $user['password'])) {
        echo "Inloggen succesvol! Welkom, " . htmlspecialchars($username) . "!";
    } else {
        echo "Ongeldige gebruikersnaam of wachtwoord.";
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
</head>
<body>
    <div class="container">
        <h1>Inloggen</h1>
        <form method="POST" action="login.php">
            <input type="text" name="username" placeholder="Gebruikersnaam" required>
            <input type="password" name="password" placeholder="Wachtwoord" required>
            <button type="submit">Inloggen</button>
        </form>
    </div>
</body>
</html>
