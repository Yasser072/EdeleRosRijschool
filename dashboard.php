<?php
session_start();
include 'db.php';

if (!isset($_SESSION['klant_id'])) {
    header("Location: login.php");
    exit;
}

$klant_id = $_SESSION['klant_id'];
$stmt = $pdo->prepare("SELECT * FROM klanten WHERE id = ?");
$stmt->execute([$klant_id]);
$klant = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
    <h1>Welkom, <?php echo htmlspecialchars($klant['naam']); ?></h1>
    <h2>Geplande Lessen</h2>
    <!-- Hier kun je code toevoegen om de geplande lessen op te halen en weer te geven -->
</body>
</html>
