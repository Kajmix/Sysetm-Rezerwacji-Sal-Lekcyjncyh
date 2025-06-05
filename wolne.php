<?php
require 'db.php';

$godzina = '';
$wolneSale = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $godzina = $_POST['godzina'] ?? '';
    if ($godzina) {
        if (strlen($godzina) == 16) {
            $godzina .= ":00";
        }
        $wolneSale = znajdzWolneSale($pdo, $godzina);
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8" />
    <title>Wolne sale</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <h1>Znajdź wolne sale</h1>
    <p><a href="index.php">← Powrót do panelu</a></p>
    <form method="post">
        <label>Godzina (YYYY-MM-DD HH:MM):
            <input type="datetime-local" name="godzina" required value="<?= htmlspecialchars($godzina) ?>" />
        </label>
        <button type="submit">Szukaj</button>
    </form>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <h2>Wolne sale o <?= htmlspecialchars($godzina) ?></h2>
        <?php if ($wolneSale): ?>
            <ul>
                <?php foreach ($wolneSale as $s): ?>
                    <li><?= htmlspecialchars($s['numer']) ?> (<?= htmlspecialchars($s['typ']) ?>)</li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Brak wolnych sal o podanej godzinie.</p>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
