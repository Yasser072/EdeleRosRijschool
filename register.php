
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
        <h1>Aanmelden</h1>
        <form>
            <input type="text" id="username" name="username" placeholder="Gebruikersnaam" required>
            <input type="email" id="email" name="email" placeholder="E-mailadres" required> <!-- E-mail veld toegevoegd -->
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
if ($conn->connect_error) {
    die("Verbinding mislukt: " . $conn->connect_error);
}

// Verwerken van de aanmelding
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // SQL-insert statement
    $sql = "INSERT INTO gebruikers (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, password_hash($password, PASSWORD_DEFAULT));

    if ($stmt->execute()) {
        echo "Aanmelding succesvol!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
