CREATE DATABASE IF NOT EXISTS srsl;
    USE srsl;
    CREATE TABLE IF NOT EXISTS sale(
        id INT AUTO_INCREMENT PRIMARY KEY,
        numer VARCHAR(50),
        pojemnosc INT,
        typ ENUM('pracownia', 'klasa', 'sala gimnastyczna')
    );
    CREATE TABLE IF NOT EXISTS rezerwacje(
        id INT AUTO_INCREMENT PRIMARY KEY,
        id_sala INT NOT NULL,
        godzina TIMESTAMP,
        przedmiot VARCHAR(100),
        nauczyciel VARCHAR(200),
        klasa INT,
        FOREIGN KEY (id_sala) REFERENCES sale(id)
    );