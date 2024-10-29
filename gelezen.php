<?php
session_start();
include 'db.php'; // Verbind met de database

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['notification_id'])) {
    $notification_id = $_POST['notification_id'];

    // Update de notificatie naar gelezen
    $sql = "UPDATE notificaties SET is_read = 1 WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$notification_id]);
}

// Redirect terug naar het dashboard
header('Location: dashboard.php');
exit();
?>
