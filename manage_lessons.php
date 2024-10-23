<?php
session_start();
include 'db.php'; // Verbind met de database

// Controleer of de instructeur is ingelogd
if (!isset($_SESSION['username'])) {
    echo "Geen instructeur ingelogd.";
    exit();
}

$successMessage = ''; // Variabele om de succesmelding op te slaan

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $datum = $_POST['datum'];
    $tijd = $_POST['tijd'];
    $leerlingen = $_POST['leerlingen'] ?? []; // Geselecteerde leerlingen

    // Haal het instructeur_id op op basis van de sessie-username
    $sql = "SELECT id FROM gebruikers WHERE username = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$_SESSION['username']]);
    $instructeur = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$instructeur) {
        echo "Instructeur niet gevonden.";
        exit(); // Stop als de instructeur niet wordt gevonden
    }

    // Les toevoegen met het instructeur_id
    $sql = "INSERT INTO lessen (datum, tijd, instructeur_id) VALUES (?, ?, ?)";
    $stmt = $db->prepare($sql);
    $stmt->execute([$datum, $tijd, $instructeur['id']]);
    
    // Haal het ID van de zojuist toegevoegde les
    $les_id = $db->lastInsertId();

    // Leerlingen koppelen aan de les
    foreach ($leerlingen as $leerling_id) {
        // Haal de naam van de leerling op
        $sql = "SELECT username FROM gebruikers WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$leerling_id]);
        $leerling = $stmt->fetch(PDO::FETCH_ASSOC);

        // Koppel de leerling aan de les
        $sql = "INSERT INTO les_leerlingen (les_id, leerling_id) VALUES (?, ?)";
        $stmt = $db->prepare($sql);
        if ($stmt->execute([$les_id, $leerling_id])) {
            // Voeg de naam van de leerling toe aan de succesmelding
            $successMessage .= 'Leerling ' . htmlspecialchars($leerling['username']) . ' succesvol gekoppeld.<br>';
        } else {
            $successMessage .= 'Fout bij het koppelen van leerling ' . htmlspecialchars($leerling['username']) . '.<br>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Les Aanmaken/Bewerken</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container manage-lessons">
        <h1>Les Aanmaken of Bewerken</h1>

        <div class="success-message" id="successMessage" style="display: <?= $successMessage ? 'block' : 'none'; ?>;">
            <?= $successMessage ?>
        </div>

        <form method="POST" action="manage_lessons.php">
            <label for="datum">Datum:</label>
            <input type="date" id="datum" name="datum" required>
            
            <label for="tijd">Tijd:</label>
            <input type="time" id="tijd" name="tijd" required>
            
            <label for="instructeur">Instructeur:</label>
            <input type="text" id="instructeur" name="instructeur" required value="<?php echo $_SESSION['username']; ?>" readonly>
            
            <label for="leerlingen">Voeg Leerlingen Toe:</label>
            <select id="leerlingen" name="leerlingen[]" multiple>
                <!-- Dynamisch ingevulde opties met leerlingen uit de database -->
                <?php
                // Haal alle leerlingen op uit de database
                $sql = "SELECT * FROM gebruikers WHERE role = 'leerling'";
                $stmt = $db->prepare($sql);
                $stmt->execute();
                while ($leerling = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='" . $leerling['id'] . "'>" . htmlspecialchars($leerling['username']) . "</option>";
                }
                ?>
            </select>

            <button type="submit">Les Opslaan</button>

            <a href="dashboard.php" class="home-button"><i class="fas fa-home"></i> Terug</a>
        </form>
    </div>
</body>
</html>
