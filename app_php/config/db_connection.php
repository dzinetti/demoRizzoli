<?php
$host = "10.0.0.225";
$port = 5432;
$db   = "rizzoli";
$user = "webapp";
$pass = "password";

try {
    $dsn = "pgsql:host={$host};port={$port};dbname={$db}";
    $conn = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("Connessione fallita: " . $e->getMessage());
}

function ensure_default_roles(PDO $conn): void
{
    $roles = [
        "admin" => "Amministratore della piattaforma",
        "studente" => "Utente studente della piattaforma"
    ];

    $select = $conn->prepare("SELECT id_ruolo FROM ruolo WHERE nome = ? LIMIT 1");
    $insert = $conn->prepare("INSERT INTO ruolo (nome, descrizione) VALUES (?, ?)");

    foreach ($roles as $nome => $descrizione) {
        $select->execute([$nome]);
        if ($select->fetch() === false) {
            $insert->execute([$nome, $descrizione]);
        }
    }
}

ensure_default_roles($conn);
