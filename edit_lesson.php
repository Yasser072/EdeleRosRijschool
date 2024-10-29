<?php
session_start();
include 'db.php';

// Controleer of de instructeur is ingelogd
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'docent') {
    echo "Toegang geweigerd. Alleen docenten kunnen lessen bewerken.";
    exit();
}

// Haal de les op die bewerkt moet worden
if (isset($_GET['id'])) {
    $lessonId = $_GET['id'];
    $sql = "SELECT * FROM lessen WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$lessonId]);
    $lesson = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$lesson) {
        echo "Les niet gevonden.";
        exit();
    }
} else {
    echo "Geen les ID opgegeven.";
    exit();
}

// Update les
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $datum = $_POST['datum'];
    $tijd = $_POST['tijd'];
    $titel = $_POST['titel'];
    $omschrijving = $_POST['omschrijving'];

    $updateSql = "UPDATE lessen SET datum = ?, tijd = ?, titel = ?, omschrijving = ? WHERE id = ?";
    $updateStmt = $db->prepare($updateSql);
    
    if ($updateStmt->execute([$datum, $tijd, $titel, $omschrijving, $lessonId])) {
        $successMessage = "Les succesvol bijgewerkt!";
    } else {
        $errorMessage = "Fout bij het bijwerken van de les.";
    }
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
    <div class="edit-lesson-container">
        <h1>Les Bewerken</h1>

        <?php if (!empty($successMessage)): ?>
            <p class="success-message"><?= htmlspecialchars($successMessage) ?></p>
        <?php endif; ?>
        <?php if (!empty($errorMessage)): ?>
            <p class="error-message"><?= htmlspecialchars($errorMessage) ?></p>
        <?php endif; ?>

        <form method="POST" action="edit_lesson.php?id=<?= $lessonId ?>">
            <input type="date" name="datum" value="<?= htmlspecialchars($lesson['datum']) ?>" required>
            <input type="time" name="tijd" value="<?= htmlspecialchars($lesson['tijd']) ?>" required>
            <input type="text" name="titel" placeholder="Titel van de Les" value="<?= htmlspecialchars($lesson['titel']) ?>" required>
            <textarea name="omschrijving" rows="4" placeholder="Omschrijving van de Les" required><?= htmlspecialchars($lesson['omschrijving']) ?></textarea>
            <button type="submit">Bijwerken</button>
            <a href="dashboard.php" class="home-button"><i class="fas fa-home"></i> Terug naar Dashboard</a>
        </form>
    </div>
</body>
</html>
