<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Telefonbuch</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Telefonbuch</h2>
        <form action="add.php" method="POST">
            <input type="text" name="vorname" placeholder="Vorname" required>
            <input type="text" name="nachname" placeholder="Nachname" required>
            <input type="text" name="phone" placeholder="Telefonnummer" required>
            <input type="email" name="email" placeholder="E-Mail (optional)">
            <button type="submit">Hinzufügen</button>
        </form>
        <table class="contact-table">
            <tr>
                <th>Name</th>
                <th>Telefon</th>
                <th>Email</th>
                <th>Aktionen</th>
            </tr>
            <?php
            include 'dbconnect.php';
            $result = SqlConn::read();
            while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row["vorname"]) ?> <?= htmlspecialchars($row["nachname"]) ?></td>
                <td><?= htmlspecialchars($row["phone"]) ?></td>
                <td><?= htmlspecialchars($row["email"] ?? "—") ?></td>
                <td>
                    <div class="dropdown">
                    <button class="dropbtn">⋮</button>
                    <div class="dropdown-content">
                    <a href="edit.php?id=<?= $row['id'] ?>">Bearbeiten</a>
                    <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Möchten Sie diesen Kontakt wirklich löschen?')">Löschen</a>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
