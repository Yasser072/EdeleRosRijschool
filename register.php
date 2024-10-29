<?php
session_start();
include 'db.php'; // Verbind met de database

$successMessage = ''; // Melding variabele
$errorMessage = ''; // Foutmelding variabele

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Controleer of de gebruikersnaam al bestaat
    $stmt = $db->prepare("SELECT * FROM gebruikers WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    if ($stmt->rowCount() > 0) {
        $errorMessage = "Gebruikersnaam of e-mailadres is al in gebruik.";
    } else {
        // Hash het wachtwoord
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // SQL-insert statement
        $sql = "INSERT INTO gebruikers (username, email, password, role) VALUES (?, ?, ?, 'leerling')";
        $stmt = $db->prepare($sql);

        try {
            $stmt->execute([$username, $email, $hashed_password]);
            // Redirect met succesmelding
            header("Location: login.php?register_success=1"); // Redirect naar login met succesmelding
            exit();
        } catch (PDOException $e) {
            $errorMessage = "Error bij aanmelding: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aanmelden</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <a href="index.php" class="home-button"><i class="fas fa-home"></i> Home</a>

        <h1>Aanmelden</h1>
        <?php if (!empty($errorMessage)): ?>
            <p class="error-message"><?= htmlspecialchars($errorMessage) ?></p>
        <?php endif; ?>
        <form method="POST" action="register.php">
            <input type="text" id="username" name="username" placeholder="Gebruikersnaam" required>
            <input type="email" id="email" name="email" placeholder="E-mailadres" required>
            <input type="password" id="password" name="password" placeholder="Wachtwoord" required>
            <button type="submit">Aanmelden</button>
        </form>
    </div>
</body>
</html>
