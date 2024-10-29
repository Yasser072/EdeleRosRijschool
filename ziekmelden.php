<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Verbind met de database
include 'db.php';

$successMessage = '';
$errorMessage = '';

// Haal de gegevens van de ingelogde gebruiker
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM gebruikers WHERE id = ?";
$stmt = $db->prepare($sql);
$stmt->execute([$user_id]);
$gebruiker = $stmt->fetch(PDO::FETCH_ASSOC);

// Verwerk de ziekmelding
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reden = $_POST['reden']; // Reden van ziekmelding
    $datum = $_POST['datum']; // Gekozen datum

    // Voeg de ziekmelding toe aan de database
    $sql = "INSERT INTO ziekmeldingen (user_id, reden, datum) VALUES (?, ?, ?)";
    $stmt = $db->prepare($sql);

    if ($stmt->execute([$user_id, $reden, $datum])) {
        $successMessage = "Ziekmelding succesvol ingediend!";

        // Hier moet je de docent-ID ophalen. 
        // Bijvoorbeeld, je zou de relatie kunnen hebben in een aparte tabel.
        // Stel dat je een `les_leerlingen` tabel hebt die de relatie tussen leerlingen en docenten vastlegt.
        
        $docentSql = "SELECT d.id FROM les_leerlingen ll 
                      JOIN lessen l ON ll.les_id = l.id 
                      JOIN gebruikers d ON l.instructeur_id = d.id 
                      WHERE ll.leerling_id = ?";
        $docentStmt = $db->prepare($docentSql);
        $docentStmt->execute([$user_id]);
        $docent = $docentStmt->fetch(PDO::FETCH_ASSOC);

        if ($docent) {
            // Voeg een notificatie toe voor de docent
            $notifySql = "INSERT INTO notificaties (user_id, message) VALUES (?, ?)";
            $notifyStmt = $db->prepare($notifySql);
            $notifyStmt->execute([$docent['id'], "Leerling " . htmlspecialchars($gebruiker['username']) . " heeft zich ziek gemeld op $datum."]);
        }
    } else {
        $errorMessage = "Er is een fout opgetreden bij het indienen van de ziekmelding.";
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ziekmelden</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container ziekmelden">
        <h1>Ziekmelden</h1>
        
        <?php if ($successMessage): ?>
            <p class="success-message"><?= htmlspecialchars($successMessage) ?></p>
        <?php endif; ?>
        <?php if ($errorMessage): ?>
            <p class="error-message"><?= htmlspecialchars($errorMessage) ?></p>
        <?php endif; ?>

        <form method="POST" action="ziekmelden.php">
            <label for="name">Naam:</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($gebruiker['username']) ?>" readonly>

            <label for="email">E-mailadres:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($gebruiker['email']) ?>" readonly>

            <label for="datum">Datum van ziekmelding:</label>
            <input type="date" id="datum" name="datum" required>

            <label for="reden">Reden van ziekmelding:</label>
            <textarea id="reden" name="reden" rows="4" required></textarea>

            <button type="submit">Ziekmelden</button>
        </form>

        <a href="dashboard.php" class="home-button">Terug naar Dashboard</a>
    </div>
</body>
</html>
