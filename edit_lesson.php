<?php
session_start();
include 'db.php';

// Controleer of de instructeur is ingelogd
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'docent') {
    echo "Toegang geweigerd. Alleen docenten kunnen lessen bewerken.";
    exit();
}

// Lesgegevens ophalen
if (isset($_GET['id'])) {
    $les_id = $_GET['id'];

    // Haal de les op basis van het ID
    $sql = "SELECT * FROM lessen WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$les_id]);
    $les = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$les) {
        echo "Les niet gevonden.";
        exit();
    }
}

// Bijwerken van de les
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $datum = $_POST['datum'];
    $tijd = $_POST['tijd'];
    $titel = $_POST['titel'];
    $omschrijving = $_POST['omschrijving'];

    // Update de les in de database
    $sql = "UPDATE lessen SET datum = ?, tijd = ?, titel = ?, omschrijving = ? WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$datum, $tijd, $titel, $omschrijving, $les_id]);

    header("Location: manage_lessons.php"); // Redirect na bijwerken
    exit();
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Les Bewerken</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Les Bewerken</h1>
        <form method="POST" action="edit_lesson.php?id=<?= $les_id ?>">
            <label for="datum">Datum:</label>
            <input type="date" id="datum" name="datum" value="<?= htmlspecialchars($les['datum']) ?>" required>

            <label for="tijd">Tijd:</label>
            <input type="time" id="tijd" name="tijd" value="<?= htmlspecialchars($les['tijd']) ?>" required>

            <label for="titel">Titel van de Les:</label>
            <input type="text" id="titel" name="titel" value="<?= htmlspecialchars($les['titel']) ?>" required>

            <label for="omschrijving">Omschrijving van de Les:</label>
            <textarea id="omschrijving" name="omschrijving" rows="4" required><?= htmlspecialchars($les['omschrijving']) ?></textarea>

            <button type="submit">Bijwerken</button>
            <a href="manage_lessons.php" class="home-button"><i class="fas fa-home"></i> Terug</a>
        </form>
    </div>
</body>
</html>
