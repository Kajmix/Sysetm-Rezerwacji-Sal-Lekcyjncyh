<?php
try {
    $initPdo = new PDO('mysql:host=localhost;charset=utf8', 'root', '');
    $initPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $initPdo->exec("CREATE DATABASE IF NOT EXISTS srsl");
    $initPdo->exec("USE srsl");
    
    $initPdo->exec("CREATE TABLE IF NOT EXISTS sale(
        id INT AUTO_INCREMENT PRIMARY KEY,
        numer VARCHAR(50),
        pojemnosc INT,
        typ ENUM('pracownia', 'klasa', 'sala gimnastyczna')
    )");
    
    $initPdo->exec("CREATE TABLE IF NOT EXISTS rezerwacje(
        id INT AUTO_INCREMENT PRIMARY KEY,
        id_sala INT NOT NULL,
        godzina TIMESTAMP,
        przedmiot VARCHAR(100),
        nauczyciel VARCHAR(200),
        klasa INT,
        FOREIGN KEY (id_sala) REFERENCES sale(id)
    )");
    
    $initPdo = null;
} catch (PDOException $e) {
    die("Database initialization error: " . $e->getMessage());
}

$pdo = new PDO('mysql:host=localhost;dbname=srsl;charset=utf8', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$pdo = new PDO('mysql:host=localhost;dbname=srsl;charset=utf8', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function pobierzSale(PDO $pdo) {
    $stmt = $pdo->query("SELECT * FROM sale ORDER BY numer");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function pobierzRezerwacje(PDO $pdo) {
    $stmt = $pdo->query("SELECT r.id, r.id_sala, s.numer AS sala_numer, r.godzina, r.przedmiot, r.nauczyciel, r.klasa
        FROM rezerwacje r
        JOIN sale s ON r.id_sala = s.id
        ORDER BY r.godzina");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function usunRezerwacje(PDO $pdo, int $id): bool {
    $stmt = $pdo->prepare("DELETE FROM rezerwacje WHERE id = ?");
    return $stmt->execute([$id]) && $stmt->rowCount() > 0;
}

function sprawdzDostepnosc(PDO $pdo, $id_sala, $godzina) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM rezerwacje WHERE id_sala = ? AND godzina = ?");
    $stmt->execute([$id_sala, $godzina]);
    return $stmt->fetchColumn() == 0;
}

function dodajRezerwacje(PDO $pdo, $id_sala, $godzina, $przedmiot, $nauczyciel, $klasa) {
    if (!sprawdzDostepnosc($pdo, $id_sala, $godzina)) {
        return false;
    }
    $stmt = $pdo->prepare("INSERT INTO rezerwacje (id_sala, godzina, przedmiot, nauczyciel, klasa) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$id_sala, $godzina, $przedmiot, $nauczyciel, $klasa]);
    return true;
}

function wyswietlPlanSali(PDO $pdo, $id_sala) {
    $stmt = $pdo->prepare("SELECT * FROM rezerwacje WHERE id_sala = ? ORDER BY godzina");
    $stmt->execute([$id_sala]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function znajdzWolneSale(PDO $pdo, $godzina) {
    $stmt = $pdo->prepare("SELECT * FROM sale WHERE id NOT IN (
        SELECT id_sala FROM rezerwacje WHERE godzina = ?
    ) ORDER BY numer");
    $stmt->execute([$godzina]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function raportWykorzystania(PDO $pdo) {
    $stmt = $pdo->query("SELECT s.numer, COUNT(r.id) AS liczba_rezerwacji
        FROM sale s
        LEFT JOIN rezerwacje r ON s.id = r.id_sala
        GROUP BY s.id ORDER BY s.numer");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function wyszukajRezerwacje(PDO $pdo, $nauczyciel = null, $klasa = null) {
    if ($nauczyciel) {
        $stmt = $pdo->prepare("SELECT r.id, s.numer AS sala_numer, r.godzina, r.przedmiot, r.nauczyciel, r.klasa
            FROM rezerwacje r
            JOIN sale s ON r.id_sala = s.id
            WHERE r.nauczyciel LIKE ?
            ORDER BY r.godzina");
        $stmt->execute(["%$nauczyciel%"]);
    } elseif ($klasa) {
        $stmt = $pdo->prepare("SELECT r.id, s.numer AS sala_numer, r.godzina, r.przedmiot, r.nauczyciel, r.klasa
            FROM rezerwacje r
            JOIN sale s ON r.id_sala = s.id
            WHERE r.klasa = ?
            ORDER BY r.godzina");
        $stmt->execute([$klasa]);
    } else {
        return [];
    }
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function formatujGodzine($datetime) {
    return date("Y-m-d H:i", strtotime($datetime));
}
?>
