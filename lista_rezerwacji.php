<?php
require 'db.php';

$blad = '';
$sukces = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['usun_id'])) {
    $id = intval($_POST['usun_id']);
    if ($id > 0) {
        $usunieto = usunRezerwacje($pdo, $id);
        if ($usunieto) {
            $sukces = "Rezerwacja o ID $id została usunięta.";
        } else {
            $blad = "Nie znaleziono rezerwacji o ID $id lub nie można jej usunąć.";
        }
    } else {
        $blad = "Niepoprawne ID rezerwacji.";
    }
}

$stmt = $pdo->query("SELECT r.id, s.numer AS sala_numer, r.godzina, r.przedmiot, r.nauczyciel, r.klasa
                     FROM rezerwacje r
                     JOIN sale s ON r.id_sala = s.id
                     ORDER BY r.godzina DESC");
$rezerwacje = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!function_exists('formatujGodzine')) {
    function formatujGodzine($timestamp) {
        return date('Y-m-d H:i', strtotime($timestamp));
    }
}

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8" />
    <title>Lista rezerwacji</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <h1>Lista wszystkich rezerwacji</h1>
    <p><a href="index.php">← Powrót do panelu</a></p>

    <?php if ($blad): ?>
        <p class="error"><?= htmlspecialchars($blad) ?></p>
    <?php elseif ($sukces): ?>
        <p class="success"><?= htmlspecialchars($sukces) ?></p>
    <?php endif; ?>

    <?php if ($rezerwacje): ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Sala</th>
                <th>Godzina</th>
                <th>Przedmiot</th>
                <th>Nauczyciel</th>
                <th>Klasa</th>
                <th>Akcja</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rezerwacje as $r): ?>
            <tr>
                <td><?= $r['id'] ?></td>
                <td><?= htmlspecialchars($r['sala_numer']) ?></td>
                <td><?= formatujGodzine($r['godzina']) ?></td>
                <td><?= htmlspecialchars($r['przedmiot']) ?></td>
                <td><?= htmlspecialchars($r['nauczyciel']) ?></td>
                <td><?= htmlspecialchars($r['klasa']) ?></td>
                <td>
                    <form method="post" style="margin:0;" onsubmit="return confirm('Czy na pewno chcesz usunąć tę rezerwację?');">
                        <input type="hidden" name="usun_id" value="<?= $r['id'] ?>" />
                        <button type="submit" style="background-color:#c0392b; border:none; color:white; padding:5px 10px; border-radius:4px; cursor:pointer;">Usuń</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p>Brak żadnych rezerwacji.</p>
    <?php endif; ?>
</body>
</html>
