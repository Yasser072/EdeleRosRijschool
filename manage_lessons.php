<?php
session_start();
include 'db.php';

// Controleer of de instructeur is ingelogd en of die instructeur een docent is
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'docent') {
    echo "Toegang geweigerd. Alleen docenten kunnen lessen beheren.";
    exit();
}

// Verwijder les
if (isset($_POST['delete_id'])) {
    $deleteId = $_POST['delete_id'];

    // Eerst de koppelingen met leerlingen verwijderen
    $sql = "DELETE FROM les_leerlingen WHERE les_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$deleteId]);

    // Nu de les zelf verwijderen
    $sql = "DELETE FROM lessen WHERE id = ?";
    $stmt = $db->prepare($sql);
    if ($stmt->execute([$deleteId])) {
        $successMessage = "Les succesvol verwijderd.";
    } else {
        $errorMessage = "Fout bij het verwijderen van de les.";
    }
}

// Lessen ophalen
$sql = "SELECT * FROM lessen WHERE instructeur_id = (SELECT id FROM gebruikers WHERE username = ?)";
$stmt = $db->prepare($sql);
$stmt->execute([$_SESSION['username']]);
$lessen = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Les Beheer</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container manage-lessons">
        <h1>Beheer Lessen</h1>

        <?php if (isset($successMessage)): ?>
            <div class="success-message"><?= $successMessage ?></div>
        <?php endif; ?>
        
        <?php if (isset($errorMessage)): ?>
            <div class="error-message"><?= $errorMessage ?></div>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>Titel</th>
                    <th>Datum</th>
                    <th>Tijd</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($lessen): ?>
                    <?php foreach ($lessen as $les): ?>
                        <tr>
                            <td><?= htmlspecialchars($les['titel']) ?></td>
                            <td><?= htmlspecialchars($les['datum']) ?></td>
                            <td><?= htmlspecialchars($les['tijd']) ?></td>
                            <td>
                                <a href="edit_lesson.php?id=<?= $les['id'] ?>" class="edit-button">Bewerken</a>
                                <form method="POST" action="manage_lessons.php" style="display:inline;">
                                <input type="hidden" name="delete_id" value="<?= $les['id'] ?>">
                                 <button type="submit" class="delete-button">Verwijderen</button>
                            </form>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">Geen lessen gevonden.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <a href="make_lesson.php" class="manage-lessons-button">Nieuwe Les Aanmaken</a>   
        <a href="dashboard.php" class="manage-lessons-button">Dashboard</a>
    </div>
</body>
</html>
