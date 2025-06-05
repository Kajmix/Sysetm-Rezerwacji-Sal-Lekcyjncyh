<?php
require 'db.php';

$sale = pobierzSale($pdo);
$plan = [];
$wybrana_sala = null;

if (isset($_GET['id_sala'])) {
    $wybrana_sala = $_GET['id_sala'];
    $plan = wyswietlPlanSali($pdo, $wybrana_sala);
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8" />
    <title>Plan sali</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <h1>Plan sali</h1>
    <p><a href="index.php">← Powrót do panelu</a></p>
    <form method="get">
        <label>Wybierz salę:
            <select name="id_sala" required>
                <option value="">-- wybierz --</option>
                <?php foreach ($sale as $s): ?>
                    <option value="<?= $s['id'] ?>" <?= ($wybrana_sala == $s['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($s['numer']) ?> (<?= htmlspecialchars($s['typ']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
        <button type="submit">Pokaż plan</button>
    </form>

    <?php if ($plan): ?>
        <h2>Rezerwacje dla sali <?= htmlspecialchars(current(array_filter($sale, fn($s)=>$s['id']==$wybrana_sala))['numer']) ?></h2>
        <table border="1" cellpadding="5" cellspacing="0">
            <tr>
                <th>Godzina</th>
                <th>Przedmiot</th>
                <th>Nauczyciel</th>
                <th>Klasa</th>
            </tr>
            <?php foreach ($plan as $r): ?>
            <tr>
                <td><?= formatujGodzine($r['godzina']) ?></td>
                <td><?= htmlspecialchars($r['przedmiot']) ?></td>
                <td><?= htmlspecialchars($r['nauczyciel']) ?></td>
                <td><?= htmlspecialchars($r['klasa']) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php elseif ($wybrana_sala): ?>
        <p>Brak rezerwacji dla tej sali.</p>
    <?php endif; ?>
</body>
</html>
