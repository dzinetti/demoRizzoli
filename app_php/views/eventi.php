<?php
require_once __DIR__."/../config/session.php";
require_once __DIR__.'/../config/db_connection.php';
require_once __DIR__.'/../config/auth.php';
require_once __DIR__.'/../config/qr.php';

require_student_area($conn);

$id_utente = $_SESSION["id_utente"];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_evento'])) {
    $id_evento = intval($_POST['id_evento']);

    $check = $conn->prepare("
        SELECT id_prenotazione 
        FROM prenotazione 
        WHERE id_utente = ? 
        AND id_evento = ?
    ");
    $check->execute([$id_utente, $id_evento]);

    if (!$check->fetch()) {
        $eventoInfo = $conn->prepare("
            SELECT data, ora_inizio, ora_fine, id_spazio
            FROM prenotazione
            WHERE id_evento = ?
            LIMIT 1
        ");
        $eventoInfo->execute([$id_evento]);
        $info = $eventoInfo->fetch();

        if ($info) {
            $insert = $conn->prepare("
                INSERT INTO prenotazione 
                (
                    data,
                    ora_inizio,
                    ora_fine,
                    stato,
                    id_utente,
                    id_spazio,
                    id_evento
                )
                VALUES (?, ?, ?, 'Confermata', ?, ?, ?)
                RETURNING id_prenotazione
            ");
            $insert->execute([
                $info['data'],
                $info['ora_inizio'],
                $info['ora_fine'],
                $id_utente,
                $info['id_spazio'],
                $id_evento
            ]);
            $inserted_id = $insert->fetchColumn();
            if ($inserted_id) {
                request_booking_qr((int)$inserted_id, $id_utente, $id_evento);
            }
        }
    }
}

$sqlEventi = "
    SELECT 
        evento.id_evento,
        evento.titolo,
        evento.descrizione,
        evento.tipologia,
        spazio.nome_spazio,
        MIN(prenotazione.data) AS data
    FROM evento
    INNER JOIN prenotazione 
        ON evento.id_evento = prenotazione.id_evento
    INNER JOIN spazio 
        ON prenotazione.id_spazio = spazio.id_spazio
    GROUP BY evento.id_evento, evento.titolo, evento.descrizione, evento.tipologia, spazio.nome_spazio
    ORDER BY data ASC
";
$eventi = $conn->query($sqlEventi)->fetchAll();
$checkPrenotazioneStmt = $conn->prepare("
    SELECT id_prenotazione 
    FROM prenotazione 
    WHERE id_utente = ? 
    AND id_evento = ?
");
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../app_html/style.css">
    <title>Eventi</title>
</head>
<body>
    <aside class="sidebar">
        <div class="sb-inner">
            <nav class="nav" aria-label="Navigation">
                <button class="home-btn" style="font-family: Arial, Helvetica, sans-serif;" onclick="window.location.href='home.php'">SmartCampus</button>
                <button class="nav-btn active" onclick="window.location.href='eventi.php'" style="color: black;">Eventi</button>
                <button class="nav-btn" onclick="window.location.href='QRattuali.php'" style="color: black;">QR Attuali</button>
                <button class="nav-btn" onclick="window.location.href='dashboard.php'" style="color: black;">Le tue prenotazioni</button>
                <button class="nav-btn active" onclick="window.location.href='../actions/logout.php'" style="color: red;">Logout</button>
            </nav>
        </div>
    </aside>

    <div class="main">
        <h1>Eventi</h1>
        <table>
            <tr>
                <th>Evento</th>
                <th>Luogo</th>
                <th>Data</th>
                <th></th>
            </tr>
            <?php foreach($eventi as $evento): ?>
            <?php
                $checkPrenotazioneStmt->execute([$id_utente, $evento['id_evento']]);
                $giaPrenotato = (bool)$checkPrenotazioneStmt->fetch();
            ?>
            <tr>
                <td><?= htmlspecialchars($evento['titolo']) ?></td>
                <td><?= htmlspecialchars($evento['nome_spazio']) ?></td>
                <td><?= date("d/m/Y", strtotime($evento['data'])) ?></td>
                <td>
                    <?php if($giaPrenotato): ?>
                        <button class="prenota-btn" disabled>Prenotato</button>
                    <?php else: ?>
                        <form method="POST">
                            <input type="hidden" name="id_evento" value="<?= $evento['id_evento'] ?>">
                            <button type="submit" class="prenota-btn">Prenota</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <script src="../../app_html/script.js"></script>
</body>
</html>