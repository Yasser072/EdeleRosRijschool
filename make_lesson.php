<?php
session_start();
include 'db.php'; // Verbind met de database

// Controleer of de instructeur is ingelogd en of die instructeur een docent is
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'docent') {
    echo "Toegang geweigerd. Alleen docenten kunnen lessen aanmaken.";
    exit();
}

$successMessage = ''; // Variabele om de succesmelding op te slaan
$errorMessage = ''; // Variabele om foutmeldingen op te slaan

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $datum = $_POST['datum'];
    $tijd = $_POST['tijd'];
    $titel = $_POST['titel'] ?? '';
    $omschrijving = $_POST['omschrijving'] ?? '';
    $leerlingen = $_POST['leerlingen'] ?? [];

    // Haal het instructeur_id op
    $sql = "SELECT id FROM gebruikers WHERE username = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$_SESSION['username']]);
    $instructeur = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$instructeur) {
        echo "Instructeur niet gevonden.";
        exit();
    }

    // Les toevoegen met het instructeur_id, titel en omschrijving
    $sql = "INSERT INTO lessen (datum, tijd, instructeur_id, titel, omschrijving) VALUES (?, ?, ?, ?, ?)";
    $stmt = $db->prepare($sql);
    $stmt->execute([$datum, $tijd, $instructeur['id'], $titel, $omschrijving]);
    
    // Haal het ID van de zojuist toegevoegde les
    $les_id = $db->lastInsertId();

    // Leerlingen koppelen aan de les
    foreach ($leerlingen as $leerling_id) {
        // Koppel de leerling aan de les
        $sql = "INSERT INTO les_leerlingen (les_id, leerling_id) VALUES (?, ?)";
        $stmt = $db->prepare($sql);
        if ($stmt->execute([$les_id, $leerling_id])) {
            // Notificatie voor de leerling dat er een nieuwe les is toegevoegd
            $notifySql = "INSERT INTO notificaties (user_id, message) VALUES (?, ?)";
            $notifyStmt = $db->prepare($notifySql);
            $notifyStmt->execute([$leerling_id, "Nieuwe les toegevoegd: $titel."]);

            // Haal de naam van de leerling op voor de succesmelding
            $sql = "SELECT username FROM gebruikers WHERE id = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$leerling_id]);
            $leerling = $stmt->fetch(PDO::FETCH_ASSOC);
            $successMessage .= 'Leerling ' . htmlspecialchars($leerling['username']) . ' succesvol gekoppeld.<br>';
        } else {
            $errorMessage .= 'Fout bij het koppelen van leerling met ID ' . htmlspecialchars($leerling_id) . '.<br>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Les Aanmaken</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container manage-lessons">
        <h1>Les Aanmaken</h1>

        <?php if (!empty($successMessage)): ?>
            <div class="success-message">
                <?= $successMessage ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($errorMessage)): ?>
            <div class="error-message">
                <?= $errorMessage ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="make_lesson.php">
            <label for="datum">Datum:</label>
            <input type="date" id="datum" name="datum" required>
            
            <label for="tijd">Tijd:</label>
            <input type="time" id="tijd" name="tijd" required>

            <label for="titel">Titel van de Les:</label>
            <input type="text" id="titel" name="titel" required>

            <label for="omschrijving">Omschrijving van de Les:</label>
            <textarea id="omschrijving" name="omschrijving" rows="4" required></textarea>
            
            <label for="instructeur">Instructeur:</label>
            <input type="text" id="instructeur" name="instructeur" required value="<?php echo $_SESSION['username']; ?>" readonly>
            
            <label for="leerlingen">Voeg Leerlingen Toe:</label>
            <div class="leerlingen-lijst">
                <?php
                // Haal alle leerlingen op uit de database
                $sql = "SELECT * FROM gebruikers WHERE role = 'leerling'";
                $stmt = $db->prepare($sql);
                $stmt->execute();
                while ($leerling = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<div class='checkbox-item'>";
                    echo "<input type='checkbox' id='leerling_" . $leerling['id'] . "' name='leerlingen[]' value='" . $leerling['id'] . "'>";
                    echo "<label for='leerling_" . $leerling['id'] . "'>" . htmlspecialchars($leerling['username']) . "</label>";
                    echo "</div>";
                }
                ?>
            </div>

            <button type="submit">Les Opslaan</button>
            <a href="dashboard.php" class="home-button"><i class="fas fa-home"></i> Dashboard</a>
        </form>
    </div>
</body>
</html>
