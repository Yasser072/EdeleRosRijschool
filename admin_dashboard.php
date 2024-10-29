<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Admin Dashboard</h1>
        <a href="manage_teachers.php" class="admin-button">Beheer Docenten</a>
        <a href="manage_students.php" class="admin-button">Beheer Leerlingen</a>
        <a href="index.php" class="home-button"><i class="fas fa-home"></i> Uitloggen</a>
    </div>
</body>
</html>
