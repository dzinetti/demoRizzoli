<?php
require_once __DIR__."/../config/session.php";
require_once __DIR__.'/../config/db_connection.php';
require_once __DIR__.'/../config/auth.php';
require_once __DIR__.'/../config/qr.php';

require_student_area($conn);

$id_utente = $_SESSION["id_utente"];

$query = $conn->prepare("
    SELECT 
        prenotazione.id_prenotazione,
        evento.id_evento,
        evento.titolo,
        evento.descrizione,
        evento.tipologia,
        spazio.nome_spazio,
        prenotazione.data,
        prenotazione.ora_inizio,
        prenotazione.ora_fine,
        prenotazione.stato
    FROM prenotazione
    INNER JOIN evento 
        ON prenotazione.id_evento = evento.id_evento
    INNER JOIN spazio 
        ON prenotazione.id_spazio = spazio.id_spazio
    WHERE prenotazione.id_utente = ?
    ORDER BY prenotazione.data ASC
");
$query->execute([$id_utente]);
$risultati = $query->fetchAll();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../app_html/style.css">
    <title>QR Attuali</title>
    <style>
        .qr-container{
            display:flex;
            flex-direction:column;
            gap:25px;
            margin-top:30px;
        }
        .qr-card{
            display:flex;
            justify-content:space-between;
            align-items:center;
            background:rgba(255,255,255,0.08);
            border-radius:18px;
            padding:25px;
            box-shadow:0 10px 30px rgba(0,0,0,0.25);
        }
        .qr-info{
            flex:1;
        }
        .qr-info h2{
            margin:0;
            color:black;
            font-size:28px;
        }
        .qr-info p{
            color:rgba(0, 0, 0, 0.8);
            margin-top:10px;
            line-height:1.5;
        }
        .qr-details{
            margin-top:18px;
        }
        .qr-details h4{
            margin:6px 0;
            color:black;
            font-weight:400;
        }
        .qr-image{
            margin-left:30px;
        }
        .qr-image img{
            width:130px;
            height:130px;
            object-fit:contain;
            background:white;
            padding:10px;
            border-radius:12px;
        }
    </style>
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
                    onclick="window.location.href='eventi.php'"
                    style="color: black;">
                    Eventi
                </button>
                <button class="nav-btn active"
                    onclick="window.location.href='QRattuali.php'"
                    style="color: black;">
                    QR Attuali
                </button>
                <button class="nav-btn"
                    onclick="window.location.href='dashboard.php'"
                    style="color: black;">
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
        <h1>I tuoi QR Attuali</h1>
        <div class="qr-container">
            <?php foreach($risultati as $row): ?>
            <div class="qr-card">
                <div class="qr-info">
                    <h2><?= htmlspecialchars($row['titolo']) ?></h2>
                    <p><?= htmlspecialchars($row['descrizione']) ?></p>
                    <div class="qr-details">
                        <h4><strong>Tipologia:</strong> <?= htmlspecialchars($row['tipologia']) ?></h4>
                        <h4><strong>Luogo:</strong> <?= htmlspecialchars($row['nome_spazio']) ?></h4>
                        <h4><strong>Data:</strong> <?= date("d/m/Y", strtotime($row['data'])) ?></h4>
                        <h4><strong>Orario:</strong> <?= substr($row['ora_inizio'], 0, 5) ?> - <?= substr($row['ora_fine'], 0, 5) ?></h4>
                        <h4><strong>Stato:</strong> <?= htmlspecialchars($row['stato']) ?></h4>
                    </div>
                </div>
                <div class="qr-image">
                    <img src="<?= htmlspecialchars(booking_qr_url((int)$row['id_prenotazione'])) ?>" alt="QR Code">
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script src="../../app_html/script.js"></script>
</body>
</html>