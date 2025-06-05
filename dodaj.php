<?php
require 'db.php';

$komunikat = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_sala = $_POST['id_sala'] ?? '';
    $godzina = $_POST['godzina'] ?? '';
    $przedmiot = trim($_POST['przedmiot'] ?? '');
    $nauczyciel = trim($_POST['nauczyciel'] ?? '');
    $klasa = $_POST['klasa'] ?? '';

    if (!$id_sala || !$godzina || !$przedmiot || !$nauczyciel || !$klasa) {
        $komunikat = "<p style='color:red;'>Wszystkie pola są wymagane.</p>";
    } else {
        if (strlen($godzina) == 16) {
            $godzina .= ":00";
        }
        if (dodajRezerwacje($pdo, $id_sala, $godzina, $przedmiot, $nauczyciel, $klasa)) {
            $komunikat = "<p style='color:green;'>Rezerwacja dodana pomyślnie.</p>";
        } else {
            $komunikat = "<p style='color:red;'>Błąd: konflikt - sala już zarezerwowana o tej godzinie.</p>";
        }
    }
}

$sale = pobierzSale($pdo);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8" />
    <title>Dodaj Rezerwację</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <h1>Dodaj rezerwację</h1>
    <p><a href="index.php">← Powrót do panelu</a></p>
    <?php echo $komunikat; ?>
    <form method="post">
        <label>Sala:
            <select name="id_sala" required>
                <option value="">-- wybierz --</option>
                <?php foreach ($sale as $s): ?>
                    <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['numer']) ?> (<?= htmlspecialchars($s['typ']) ?>)</option>
                <?php endforeach; ?>
            </select>
        </label><br><br>

        <label>Godzina (YYYY-MM-DD HH:MM):
            <input type="datetime-local" name="godzina" required />
        </label><br><br>

        <label>Przedmiot:
            <input type="text" name="przedmiot" required />
        </label><br><br>

        <label>Nauczyciel:
            <input type="text" name="nauczyciel" required />
        </label><br><br>

        <label>Klasa:
            <input type="number" name="klasa" min="1" required />
        </label><br><br>

        <button type="submit">Dodaj</button>
    </form>
</body>
</html>
