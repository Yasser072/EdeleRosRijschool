<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Verbind met de database om de gebruiker en lessen te tonen
include 'db.php';
$user_id = $_SESSION['user_id'];

// Haal klantgegevens op
$sql = "SELECT * FROM gebruikers WHERE id = ?";
$stmt = $db->prepare($sql);
$stmt->execute([$user_id]);
$gebruiker = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klant Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Welkom op je Dashboard, <?php echo htmlspecialchars($gebruiker['username']); ?>!</h1>

        <!-- Klantgegevens -->
        <section class="klantgegevens">
            <h2>Jouw Gegevens</h2>
            <p><strong>Naam:</strong> <?php echo htmlspecialchars($gebruiker['username']); ?></p>
            <p><strong>E-mail:</strong> <?php echo htmlspecialchars($gebruiker['email']); ?></p>
            <a href="update_profile.php" class="button">Gegevens Bijwerken</a>
        </section>

        <!-- Geplande Lessen -->
        <section class="lessen">
            <h2>Jouw Geplande Lessen</h2>
            <table>
                <thead>
                    <tr>
                        <th>Datum</th>
                        <th>Tijd</th>
                        <th>Titel</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                // Haal geplande lessen op
                $sql = "SELECT * FROM lessen WHERE instructeur_id = ?"; // Dit moet worden aangepast als je lessen wilt ophalen voor leerlingen
                $stmt = $db->prepare($sql);
                $stmt->execute([$user_id]);

                if ($stmt->rowCount() > 0) {
                    while ($les = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr><td>" . htmlspecialchars($les['datum']) . "</td><td>" . htmlspecialchars($les['tijd']) . "</td><td>" . htmlspecialchars($les['titel']) . "</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='3' class='no-lessons-message'>Er zijn momenteel geen lessen gepland.</td></tr>";
                }
                ?>
                </tbody>
            </table>
        </section>

        <?php if ($gebruiker['role'] === 'docent'): // Alleen docenten kunnen lessen aanmaken en beheren ?>
            <a href="make_lesson.php" class="manage-lessons-button">Les aanmaken</a>
            <a href="manage_lessons.php" class="manage-lessons-button">Lessen overzicht</a>
        <?php endif; ?>

        <a href="index.php" class="home-button"><i class="fas fa-home"></i>Uitloggen</a>

        <?php if ($gebruiker['role'] === 'leerling'): // Alleen leerlingen kunnen les overzicht leerling zien ?>
        <a href="view_lessons.php" class="manage-lessons-button">Les overzicht leerling</a>
        <?php endif; ?>

    </div>
</body>
</html>
