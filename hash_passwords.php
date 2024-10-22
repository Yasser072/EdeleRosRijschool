<?php
$wachtwoorden = ['admin123', 'docent1', 'docent2', 'docent3', 'docent4', 'docent5'];

foreach ($wachtwoorden as $key => $wachtwoord) {
    $hashed_password = password_hash($wachtwoord, PASSWORD_DEFAULT);
    echo "Wachtwoord voor gebruiker " . ($key + 1) . ": " . $hashed_password . "<br>";
}
?>
