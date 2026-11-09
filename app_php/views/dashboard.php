<?php
require_once __DIR__."/../config/session.php";
require_once __DIR__.'/../config/db_connection.php';
require_once __DIR__.'/../config/auth.php';

require_student_area($conn);

$id_utente = $_SESSION["id_utente"];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['elimina_prenotazione'])) {
    $id_prenotazione = intval($_POST['id_prenotazione']);
    $delete = $conn->prepare("
        DELETE FROM prenotazione 
        WHERE id_prenotazione = ? 
        AND id_utente = ?
    ");
    $delete->execute([$id_prenotazione, $id_utente]);
    header("Location: dashboard.php");
    exit();
}

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

$prenotazioni = $conn->prepare("
    SELECT 
        prenotazione.id_prenotazione,
        evento.titolo,
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
$prenotazioni->execute([$id_utente]);
$listaPrenotazioni = $prenotazioni->fetchAll();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../app_html/style.css">
    <title>Le tue prenotazioni</title>
    <style>
        .delete-btn{
            background:transparent;
            color:white;
            border:none;
            padding:10px 14px;
            border-radius:8px;
            cursor:pointer;
            font-weight:bold;
            transition:0.2s;
        }
        .delete-btn:hover{
            background:#b52b27;
        }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="sb-inner">
            <nav class="nav" aria-label="Navigation">
                <button class="home-btn" style="font-family: Arial, Helvetica, sans-serif;" onclick="window.location.href='home.php'">SmartCampus</button>
                <button class="nav-btn" onclick="window.location.href='eventi.php'" style="color: black;">Eventi</button>
                <button class="nav-btn" onclick="window.location.href='QRattuali.php'" style="color: black;">QR Attuali</button>
                <button class="nav-btn active" onclick="window.location.href='dashboard.php'" style="color: black;">Le tue prenotazioni</button>
                <button class="nav-btn active" onclick="window.location.href='../actions/logout.php'" style="color: red;">Logout</button>
            </nav>
        </div>
    </aside>

    <div class="main">
        <h1>Dashboard di <?= htmlspecialchars($nomeCompleto) ?></h1>

        <div style="display:flex; gap:20px; margin-top:30px; flex-wrap:wrap;">
            <div style="background:rgba(0,0,0,0.2); padding:20px; border-radius:12px; min-width:220px;">
                <h2><?= $totale ?></h2>
                <p>Prenotazioni Totali</p>
            </div>
            <div style="background:rgba(0,0,0,0.2); padding:20px; border-radius:12px; min-width:220px;">
                <h2><?= $futuri ?></h2>
                <p>Eventi Futuri</p>
            </div>
        </div>

        <h2 style="margin-top:40px;">Le tue prenotazioni</h2>
        <table>
            <tr>
                <th>Evento</th>
                <th>Tipologia</th>
                <th>Luogo</th>
                <th>Data</th>
                <th>Orario</th>
                <th>Stato</th>
                <th>Azione</th>
            </tr>
            <?php foreach($listaPrenotazioni as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['titolo']) ?></td>
                <td><?= htmlspecialchars($row['tipologia']) ?></td>
                <td><?= htmlspecialchars($row['nome_spazio']) ?></td>
                <td><?= date("d/m/Y", strtotime($row['data'])) ?></td>
                <td><?= substr($row['ora_inizio'], 0, 5) ?> - <?= substr($row['ora_fine'], 0, 5) ?></td>
                <td><?= htmlspecialchars($row['stato']) ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="id_prenotazione" value="<?= $row['id_prenotazione'] ?>">
                        <button type="submit" name="elimina_prenotazione" class="delete-btn">Elimina</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>