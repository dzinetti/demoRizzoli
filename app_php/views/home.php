<?php
require_once __DIR__."/../config/session.php";
require_once __DIR__.'/../config/db_connection.php';
require_once __DIR__.'/../config/auth.php';

require_student_area($conn);

$id_utente = $_SESSION["id_utente"];

$stmt = $conn->prepare("
    SELECT nome, cognome 
    FROM utente 
    WHERE id_utente = ?
");
$stmt->execute([$id_utente]);
$utente = $stmt->fetch();
$nomeCompleto = ($utente['nome'] ?? '') . " " . ($utente['cognome'] ?? '');

$q1 = $conn->prepare("
    SELECT COUNT(*) as totale 
    FROM prenotazione 
    WHERE id_utente = ?
");
$q1->execute([$id_utente]);
$totale = $q1->fetch()['totale'] ?? 0;

$q2 = $conn->prepare("
    SELECT COUNT(*) as futuri 
    FROM prenotazione 
    WHERE id_utente = ? 
    AND data >= CURRENT_DATE
");
$q2->execute([$id_utente]);
$futuri = $q2->fetch()['futuri'] ?? 0;
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../app_html/style.css">
    <title>Home</title>
</head>
<body>
    <aside class="sidebar">
        <div class="sb-inner">
            <nav class="nav" aria-label="Navigation">
                <button class="home-btn"
                    style="font-family: Arial, Helvetica, sans-serif;"
                    onclick="window.location.href='home.php'">
                    SmartCampus
                </button>
                <button class="nav-btn"
                    onclick="window.location.href='eventi.php'">
                    Eventi
                </button>
                <button class="nav-btn"
                    onclick="window.location.href='QRattuali.php'">
                    QR Attuali
                </button>
                <button class="nav-btn"
                    onclick="window.location.href='dashboard.php'">
                    Le tue prenotazioni
                </button>
                <button class="nav-btn active"
                    onclick="window.location.href='../actions/logout.php'"
                    style="color: red;">
                    Logout
                </button>
            </nav>
        </div>
    </aside>

    <div class="main">
        <h1>
            Benvenuto, <?= htmlspecialchars($nomeCompleto) ?>
        </h1>
        <p>
            Benvenuto/a nel nuovo portale dedicato agli eventi della nostra scuola! Siamo davvero felici di vederti qui. <br>
            Questo spazio è stato creato su misura per te, per rendere semplicissimo scoprire, gestire e partecipare a tutte le fantastiche attività scolastiche in programma. <br> <br>
            Ecco un piccolo riassunto della tua bacheca personale:
        </p>
        <div style="
            display:flex;
            gap:20px;
            margin-top:30px;
            flex-wrap:wrap;
        ">
            <div style="
                background:rgba(0,0,0,0.2);
                padding:20px;
                border-radius:12px;
                min-width:220px;
            ">
                <h2><?= $totale ?></h2>
                <p>Prenotazioni Totali</p>
            </div>
            <div style="
                background:rgba(0,0,0,0.2);
                padding:20px;
                border-radius:12px;
                min-width:220px;
            ">
                <h2><?= $futuri ?></h2>
                <p>Eventi Futuri</p>
            </div>
        </div>
    </div>
</body>
</html>