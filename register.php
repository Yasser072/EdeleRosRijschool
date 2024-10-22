<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aanmelden</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>
<body>
    <div class="container">
    <a href="index.php" class="home-button"><i class="fas fa-home"></i> Home</a>

        <h1>Aanmelden</h1>
        <form method="POST" action="register.php">
            <input type="text" id="username" name="username" placeholder="Gebruikersnaam" required>
            <input type="email" id="email" name="email" placeholder="E-mailadres" required>
            <input type="password" id="password" name="password" placeholder="Wachtwoord" required>
            <button type="submit">Aanmelden</button>
        </form>
    </div>
</body>
</html>

<?php
// Maak verbinding met de database
$servername = "localhost";
$username = "root"; // Gebruik root als standaard XAMPP-gebruiker
$password = ""; // Leeg wachtwoord voor XAMPP standaard root-gebruiker
$dbname = "EdeleRosRijschool"; // De naam van de database

// Maak verbinding
$conn = new mysqli($servername, $username, $password, $dbname);

// Controleer de verbinding
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hash het wachtwoord en sla op in een variabele
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL-insert statement, automatisch rol 'leerling' toekennen
    $sql = "INSERT INTO gebruikers (username, email, password, role) VALUES (?, ?, ?, 'leerling')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $hashed_password); // Gebruik de variabele

    if ($stmt->execute()) {
        echo "<p class='success-message'>Aanmelding succesvol!</p>";
    } else {
        echo "<p class='error-message'>Error: " . $stmt->error . "</p>";
    }
    

    $stmt->close();
}