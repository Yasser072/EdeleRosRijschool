<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gebruikersnaam = $_POST['gebruikersnaam'];
    $wachtwoord = $_POST['wachtwoord'];

    $stmt = $pdo->prepare("SELECT * FROM klanten WHERE gebruikersnaam = ?");
    $stmt->execute([$gebruikersnaam]);
    $klant = $stmt->fetch();

    if ($klant && password_verify($wachtwoord, $klant['wachtwoord'])) {
        $_SESSION['klant_id'] = $klant['id'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Ongeldige gebruikersnaam of wachtwoord.";
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
        <form>
            <input type="text" id="username" name="username" placeholder="Gebruikersnaam" required>
            <input type="password" id="password" name="password" placeholder="Wachtwoord" required>
            <button type="submit">Inloggen</button>
        </form>
    </div>
</body>
</html>
