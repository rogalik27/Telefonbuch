<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'dbconnect.php';
$contact = SqlConn::update();
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontakt bearbeiten</title>
    <link rel="stylesheet" href="styles.css"> 
</head>
<body>
    <h2>Kontakt <?= htmlspecialchars($contact['id']) ?> bearbeiten</h2>
    <form action="" method="POST">
        <input type="text" name="vorname" value="<?= htmlspecialchars($contact['vorname']) ?>" required>
        <input type="text" name="nachname" value="<?= htmlspecialchars($contact['nachname']) ?>" required>
        <input type="text" name="phone" value="<?= htmlspecialchars($contact['phone']) ?>" required>
        <input type="email" name="email" value="<?= htmlspecialchars($contact['email'] ?? '') ?>">
        <button type="submit">Speichern</button>
    </form>
    <a href="index.php">Zurück</a>
</body>
</html>
