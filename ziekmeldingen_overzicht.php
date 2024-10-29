<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Verbind met de database
include 'db.php';

// Verwijder ziekmelding als een ID is verzonden
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
    $deleteId = $_POST['delete_id'];
    
    $deleteSql = "DELETE FROM ziekmeldingen WHERE id = ?";
    $deleteStmt = $db->prepare($deleteSql);
    
    if ($deleteStmt->execute([$deleteId])) {
        $successMessage = "Ziekmelding succesvol verwijderd!";
    } else {
        $errorMessage = "Er is een fout opgetreden bij het verwijderen van de ziekmelding.";
    }
}

// Haal alle ziekmeldingen op
$sql = "SELECT z.*, g.username FROM ziekmeldingen z JOIN gebruikers g ON z.user_id = g.id ORDER BY z.datum DESC";
$stmt = $db->prepare($sql);
$stmt->execute();
$ziekmeldingen = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overzicht Ziekmeldingen</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container manage-lessons">
        <h1>Overzicht Ziekmeldingen</h1>

        <?php if (isset($successMessage)): ?>
            <div class="success-message"><?= htmlspecialchars($successMessage) ?></div>
        <?php endif; ?>
        
        <?php if (isset($errorMessage)): ?>
            <div class="error-message"><?= htmlspecialchars($errorMessage) ?></div>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>Naam</th>
                    <th>Reden</th>
                    <th>Datum</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($ziekmeldingen)): ?>
                <?php foreach ($ziekmeldingen as $ziekmelding): ?>
                    <tr>
                        <td><?= htmlspecialchars($ziekmelding['username']) ?></td>
                        <td><?= htmlspecialchars($ziekmelding['reden']) ?></td>
                        <td><?= htmlspecialchars($ziekmelding['datum']) ?></td>
                        <td>
                            <form method="POST" action="ziekmeldingen_overzicht.php" style="display:inline;">
                                <input type="hidden" name="delete_id" value="<?= $ziekmelding['id'] ?>">
                                <button type="submit" class="delete-button">Verwijderen</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">Er zijn momenteel geen ziekmeldingen.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>

        <a href="dashboard.php" class="manage-lessons-button">Terug naar Dashboard</a>
    </div>
</body>
</html>
