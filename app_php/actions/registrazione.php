<?php
require_once __DIR__."/../config/session.php";
require_once __DIR__.'/../config/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    header("Location: ../../app_html/registrazione.html");
    exit();
}

$password = $_POST['password'] ?? "";
$password_seconda = $_POST['password_seconda'] ?? "";
$email = trim($_POST['email'] ?? "");
$nome = trim($_POST['nome'] ?? "");
$cognome = trim($_POST['cognome'] ?? "");
$ruolo = trim($_POST['ruolo'] ?? "");

$sql = "SELECT id_utente FROM utente WHERE email = ?";
$statement = $conn->prepare($sql);
$statement->execute([$email]);

if ($statement->fetch()) {
    echo "<script>alert('Email esiste già, usare un email diversa o provare ad accedere');window.location.href='../../app_html/registrazione.html';</script>";
} elseif ($nome === "" || $cognome === "" || $email === "" || $ruolo === "") {
    echo "<script>alert('Inserire valori in tutti i campi');window.location.href='../../app_html/registrazione.html';</script>";
} elseif ($password !== $password_seconda) {
    echo "<script>alert('Le due password non corrispondono.');window.location.href='../../app_html/registrazione.html';</script>";
} elseif (strlen($password) < 8) {
    echo "<script>alert('password troppo debole, sono necessari almeno 8 caratteri');window.location.href='../../app_html/registrazione.html';</script>";
} else {
    $sqlRuolo = "SELECT id_ruolo FROM ruolo WHERE nome = ?";
    $stmtRuolo = $conn->prepare($sqlRuolo);
    $stmtRuolo->execute([$ruolo]);
    $rowRuolo = $stmtRuolo->fetch();

    if ($rowRuolo) {
        $id_ruolo = $rowRuolo["id_ruolo"];
    } else {
        echo "<script>alert('Ruolo non esiste, contattare gli sviluppatori per risolvere il problema');window.location.href='../../app_html/registrazione.html';</script>";
        exit();
    }

    $sqlInsert = "INSERT INTO utente (nome, cognome, email, id_ruolo, password) VALUES (?, ?, ?, ?, ?) RETURNING id_utente";
    $stmtInsert = $conn->prepare($sqlInsert);

    if ($stmtInsert->execute([$nome, $cognome, $email, $id_ruolo, $password])) {
        $id_utente = $stmtInsert->fetchColumn();

        $_SESSION["id_utente"] = $id_utente;
        $_SESSION["id_ruolo"] = $id_ruolo;

        switch ($ruolo) {
            case 'admin':
                echo "<script>alert('Registrazione avvenuta con successo!');window.location.href = '../views/dashboard_admin.php';</script>";
                break;
            default:
                echo "<script>alert('Registrazione avvenuta con successo!');window.location.href = '../views/dashboard.php';</script>";
                break;
        }
        exit();
    } else {
        echo "<script>alert('Registrazione Fallita');window.location.href='../../app_html/registrazione.html';</script>";
    }
}