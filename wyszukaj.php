<?php
require 'db.php';

$nauczyciel = '';
$klasa = '';
$wyniki = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nauczyciel = trim($_POST['nauczyciel'] ?? '');
    $klasa = $_POST['klasa'] ?? '';

    if ($nauczyciel) {
        $wyniki = wyszukajRezerwacje($pdo, $nauczyciel, null);
    } elseif ($klasa) {
        $wyniki = wyszukajRezerwacje($pdo, null, $klasa);
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8" />
    <title>Wyszukaj rezerwacje</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <h1>Wyszukaj rezerwacje</h1>
    <p><a href="index.php">← Powrót do panelu</a></p>

    <form method="post">
        <label>Nauczyciel:
            <input type="text" name="nauczyciel" value="<?= htmlspecialchars($nauczyciel) ?>" />
        </label><br><br>
        <label>lub Klasa:
            <input type="number" name="klasa" min="1" value="<?= htmlspecialchars($klasa) ?>" />
        </label><br><br>
        <button type="submit">Szukaj</button>
    </form>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <h2>Wyniki wyszukiwania</h2>
        <?php if ($wyniki): ?>
            <table border="1" cellpadding="5" cellspacing="0">
                <tr>
                    <th>Sala</th>
                    <th>Godzina</th>
                    <th>Przedmiot</th>
                    <th>Nauczyciel</th>
                    <th>Klasa</th>
                </tr>
                <?php foreach ($wyniki as $r): ?>
                <tr>
                    <td><?= htmlspecialchars($r['sala_numer']) ?></td>
                    <td><?= formatujGodzine($r['godzina']) ?></td>
                    <td><?= htmlspecialchars($r['przedmiot']) ?></td>
                    <td><?= htmlspecialchars($r['nauczyciel']) ?></td>
                    <td><?= htmlspecialchars($r['klasa']) ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>Brak wyników.</p>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
