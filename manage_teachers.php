<?php
session_start();
include 'db.php'; // Verbind met de database

// Controleer of de admin is ingelogd
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    echo "Toegang geweigerd. Alleen admins kunnen docenten beheren.";
    exit();
}

// Verwijder docent
if (isset($_POST['delete_id'])) {
    $deleteId = $_POST['delete_id'];
    $sql = "DELETE FROM gebruikers WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$deleteId]);
    header("Location: manage_teachers.php"); // Redirect na verwijderen
    exit();
}

// Voeg docent toe
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_teacher'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hash het wachtwoord
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL-insert statement
    $sql = "INSERT INTO gebruikers (username, email, password, role) VALUES (?, ?, ?, 'docent')";
    $stmt = $db->prepare($sql);
    $stmt->execute([$username, $email, $hashed_password]);
}

// Haal alle docenten op
$sql = "SELECT * FROM gebruikers WHERE role = 'docent'";
$stmt = $db->prepare($sql);
$stmt->execute();
$docenten = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beheer Docenten</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container manage-students">
        <h1>Beheer Docenten</h1>

        <!-- Terug naar Admin Dashboard Knop -->
        <a href="admin_dashboard.php" class="home-button">Terug naar Admin Dashboard</a>

        <!-- Voeg Docent Toe Sectie -->
        <h2 style="background-color: #4CAF50; color: white; padding: 10px;">Voeg Docent Toe</h2>
        <form method="POST" action="manage_teachers.php">
            <input type="text" name="username" placeholder="Gebruikersnaam" required>
            <input type="email" name="email" placeholder="E-mailadres" required>
            <input type="password" name="password" placeholder="Wachtwoord" required>
            <button type="submit" name="add_teacher">Voeg Docent Toe</button>
        </form>

        <!-- Lijst met Docenten -->
        <table>
            <thead>
                <tr>
                    <th>Naam</th>
                    <th>E-mail</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($docenten as $docent): ?>
                    <tr>
                        <td><?= htmlspecialchars($docent['username']) ?></td>
                        <td><?= htmlspecialchars($docent['email']) ?></td>
                        <td>
                            <form method="POST" action="manage_teachers.php" style="display:inline;">
                                <input type="hidden" name="delete_id" value="<?= $docent['id'] ?>">
                                <button type="submit" class="delete-button">Verwijderen</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
