<?php
require_once __DIR__."/../config/session.php";
require_once __DIR__.'/../config/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    header("Location: ../../app_html/login.html");
    exit();
}

$email = trim($_POST['email'] ?? "");
$password = trim($_POST['password'] ?? "");

$sql = "SELECT * FROM utente WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$email]);
$record = $stmt->fetch();

if ($record) {
    $db_pw = $record["password"];
    $password_ok = ($password === $db_pw);

    if ($password_ok) {
        $_SESSION["id_utente"] = $record["id_utente"];
        $_SESSION["id_ruolo"] = $record["id_ruolo"];

        $sql_ruolo = "SELECT nome FROM ruolo WHERE id_ruolo = ?";
        $stmt2 = $conn->prepare($sql_ruolo);
        $stmt2->execute([$record["id_ruolo"]]);
        $ris2 = $stmt2->fetch();

        if (($ris2["nome"] ?? "") === "admin") {
            header("Location: ../views/dashboard_admin.php");
        } else {
            header("Location: ../views/dashboard.php");
        }
        exit();
    }
}

header("Location: ../../app_html/login.html?error=1");
exit();