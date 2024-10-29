<?php
session_start();
include 'db.php';

// Controleer of de instructeur is ingelogd
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    echo "Toegang geweigerd. Alleen admin kan leerlingen beheren.";
    exit();
}

$successMessage = ''; // Melding variabele
$errorMessage = ''; // Foutmelding variabele

// Voeg leerling toe
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_student'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Controleer of de gebruiker al bestaat
    $checkSql = "SELECT * FROM gebruikers WHERE username = ? OR email = ?";
    $checkStmt = $db->prepare($checkSql);
    $checkStmt->execute([$username, $email]);

    if ($checkStmt->rowCount() > 0) {
        $errorMessage = "Gebruikersnaam of e-mailadres is al in gebruik.";
    } else {
        $insertSql = "INSERT INTO gebruikers (username, email, password, role) VALUES (?, ?, ?, 'leerling')";
        $insertStmt = $db->prepare($insertSql);
        if ($insertStmt->execute([$username, $email, $hashed_password])) {
            $successMessage = "Leerling succesvol toegevoegd!";
        } else {
            $errorMessage = "Fout bij het toevoegen van leerling.";
        }
    }
}

// Verwijder leerling
if (isset($_POST['delete_id'])) {
    $deleteId = $_POST['delete_id'];
    $sql = "DELETE FROM gebruikers WHERE id = ?";
    $stmt = $db->prepare($sql);
    
    if ($stmt->execute([$deleteId])) {
        $successMessage = "Leerling succesvol verwijderd!";
    } else {
        $errorMessage = "Fout bij het verwijderen van leerling.";
    }
}

// Haal leerlingen op
$sql = "SELECT * FROM gebruikers WHERE role = 'leerling'";
$stmt = $db->prepare($sql);
$stmt->execute();
$leerlingen = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beheer Leerlingen</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container manage-students">
        <h1>Beheer Leerlingen</h1>

        <?php if (!empty($successMessage)): ?>
            <p class="success-message"><?= htmlspecialchars($successMessage) ?></p>
        <?php endif; ?>
        <?php if (!empty($errorMessage)): ?>
            <p class="error-message"><?= htmlspecialchars($errorMessage) ?></p>
        <?php endif; ?>

        <h2>Voeg Leerling Toe</h2>
        <form method="POST" action="manage_students.php">
            <input type="text" name="username" placeholder="Gebruikersnaam" required>
            <input type="email" name="email" placeholder="E-mailadres" required>
            <input type="password" name="password" placeholder="Wachtwoord" required>
            <button type="submit" name="add_student">Voeg Leerling Toe</button>
        </form>

        <h2>Huidige Leerlingen</h2>
        <table>
            <thead>
                <tr>
                    <th>Naam</th>
                    <th>Email</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($leerlingen as $leerling): ?>
                    <tr>
                        <td><?= htmlspecialchars($leerling['username']) ?></td>
                        <td><?= htmlspecialchars($leerling['email']) ?></td>
                        <td>
                            <form method="POST" action="manage_students.php" style="display:inline;">
                                <input type="hidden" name="delete_id" value="<?= $leerling['id'] ?>">
                                <button type="submit" class="delete-button">Verwijderen</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="admin_dashboard.php" class="home-button">Terug naar Dashboard</a>
    </div>
</body>
</html>
