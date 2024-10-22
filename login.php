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
    <title>Inloggen</title>
</head>
<body>
    <h1>Inloggen</h1>
    <form method="post">
        <label for="gebruikersnaam">Gebruikersnaam:</label>
        <input type="text" id="gebruikersnaam" name="gebruikersnaam" required><br>
        
        <label for="wachtwoord">Wachtwoord:</label>
        <input type="password" id="wachtwoord" name="wachtwoord" required><br>
        
        <input type="submit" value="Inloggen">
    </form>
    <?php if (isset($error)) echo "<p>$error</p>"; ?>
</body>
</html>
