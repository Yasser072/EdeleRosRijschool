<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $naam = $_POST['naam'];
    $email = $_POST['email'];
    $gebruikersnaam = $_POST['gebruikersnaam'];
    $wachtwoord = password_hash($_POST['wachtwoord'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO klanten (naam, email, gebruikersnaam, wachtwoord) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$naam, $email, $gebruikersnaam, $wachtwoord])) {
        $_SESSION['success'] = "Registratie succesvol! Je kunt nu inloggen.";
        header("Location: login.php");
        exit;
    } else {
        $error = "Er is een fout opgetreden tijdens registratie.";
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Registreren</title>
</head>
<body>
    <h1>Registreren</h1>
    <form method="post">
        <label for="naam">Naam:</label>
        <input type="text" id="naam" name="naam" required><br>
        
        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required><br>
        
        <label for="gebruikersnaam">Gebruikersnaam:</label>
        <input type="text" id="gebruikersnaam" name="gebruikersnaam" required><br>
        
        <label for="wachtwoord">Wachtwoord:</label>
        <input type="password" id="wachtwoord" name="wachtwoord" required><br>
        
        <input type="submit" value="Registreren">
    </form>
    <?php if (isset($error)) echo "<p>$error</p>"; ?>
</body>
</html>
