<?php
session_start();
include 'db.php';

// Controleer of de leerling is ingelogd
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'leerling') {
    echo "Toegang geweigerd. Alleen leerlingen kunnen lessen bekijken.";
    exit();
}

// Haal de leerling ID op
$sql = "SELECT id FROM gebruikers WHERE username = ?";
$stmt = $db->prepare($sql);
$stmt->execute([$_SESSION['username']]);
$leerling = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$leerling) {
    echo "Leerling niet gevonden.";
    exit();
}

// Lessen ophalen waarvoor deze leerling is ingeschreven
$sql = "SELECT l.titel, l.datum, l.tijd FROM lessen l 
        JOIN les_leerlingen ll ON l.id = ll.les_id 
        WHERE ll.leerling_id = ?";
$stmt = $db->prepare($sql);
$stmt->execute([$leerling['id']]);
$lessen = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mijn Lessen</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="view-lessons">
        <h1>Mijn Lessen</h1>
        <table>
            <tr>
                <th>Titel</th>
                <th>Datum</th>
                <th>Tijd</th>
            </tr>
            <?php if (empty($lessen)): ?>
                <tr>
                    <td colspan="3">Geen lessen gevonden.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($lessen as $les): ?>
                    <tr>
                        <td><?= htmlspecialchars($les['titel']) ?></td>
                        <td><?= htmlspecialchars($les['datum']) ?></td>
                        <td><?= htmlspecialchars($les['tijd']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
        <a href="dashboard.php" class="home-button"><i class="fas fa-home"></i> Terug naar Dashboard</a>
    </div>
</body>
</html>
