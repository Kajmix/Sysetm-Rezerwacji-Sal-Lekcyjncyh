<?php
require 'db.php';

$raport = raportWykorzystania($pdo);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8" />
    <title>Raport wykorzystania sal</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <h1>Raport wykorzystania sal</h1>
    <p><a href="index.php">← Powrót do panelu</a></p>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>Sala</th>
            <th>Liczba rezerwacji</th>
        </tr>
        <?php foreach ($raport as $r): ?>
        <tr>
            <td><?= htmlspecialchars($r['numer']) ?></td>
            <td><?= $r['liczba_rezerwacji'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
