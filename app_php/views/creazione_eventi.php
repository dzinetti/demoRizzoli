<?php
require_once __DIR__."/../config/session.php";
require_once __DIR__.'/../config/db_connection.php';
require_once __DIR__.'/../config/auth.php';

require_admin_area($conn);

$messaggio = "";
$tipo_messaggio = "";

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['registra_evento'])) {
    $nome_evento = trim($_POST['nome'] ?? '');
    $tipologia_evento = trim($_POST['tipologia'] ?? '');
    $descrizione_evento = trim($_POST['descrizione'] ?? '');
    $id_spazio = intval($_POST['spazio'] ?? 0);
    $data_evento = trim($_POST['data'] ?? '');
    $ora_inizio = trim($_POST['ora_inizio'] ?? '');
    $ora_fine = trim($_POST['ora_fine'] ?? '');
    $id_utente = intval($_SESSION['id_utente']);

    if (empty($nome_evento) || empty($tipologia_evento) || empty($descrizione_evento) ||
        $id_spazio <= 0 || empty($data_evento) || empty($ora_inizio) || empty($ora_fine)) {
        $messaggio = "Compila tutti i campi obbligatori";
        $tipo_messaggio = "error";
    } else {
        try {
            $sql_evento = "INSERT INTO evento (titolo, descrizione, tipologia) VALUES (?, ?, ?) RETURNING id_evento";
            $stmt_evento = $conn->prepare($sql_evento);
            $stmt_evento->execute([$nome_evento, $descrizione_evento, $tipologia_evento]);
            $id_evento_creato = $stmt_evento->fetchColumn();

            $sql_prenotazione = "INSERT INTO prenotazione (data, ora_inizio, ora_fine, stato, id_utente, id_spazio, id_evento) 
                                 VALUES (?, ?, ?, 'Confermata', ?, ?, ?)";
            $stmt_pren = $conn->prepare($sql_prenotazione);
            $stmt_pren->execute([$data_evento, $ora_inizio, $ora_fine, $id_utente, $id_spazio, $id_evento_creato]);

            $messaggio = "Evento registrato e spazio assegnato con successo!";
            $tipo_messaggio = "success";
        } catch (Exception $e) {
            $messaggio = "Errore durante il salvataggio: " . $e->getMessage();
            $tipo_messaggio = "error";
        }
    }
}

$query_spazi = "SELECT id_spazio, nome_spazio, tipologia FROM spazio";
$spazi_lista = $conn->query($query_spazi)->fetchAll();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../app_html/style.css">
    <title>SmartCampus - Creazione Eventi</title>
    <style>
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 6px; font-weight: bold; text-align: center; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="sb-inner">
            <nav class="nav" aria-label="Navigation">
                <button class="home-btn" style="font-family: Arial, Helvetica, sans-serif;" onclick="window.location.href='dashboard_admin.php'">SmartCampus</button>
                <button class="nav-btn" onclick="window.location.href='gestione_eventi.php'" style="color: black;">Gestione eventi</button>
                <button class="nav-btn active" onclick="window.location.href='creazione_eventi.php'" style="color: black;">Creazione eventi</button>
                <button class="nav-btn" onclick="window.location.href='../actions/logout.php'" style="color: red;">Logout</button>
            </nav>
        </div>
    </aside>

    <div class="main">
        <h1>Benvenuto nella registrazione dell'evento</h1>

        <div class="form-registrazione-container">
            <h2>Dettagli Evento</h2>

            <?php if(!empty($messaggio)): ?>
                <div class="alert alert-<?php echo htmlspecialchars($tipo_messaggio); ?>">
                    <?php echo htmlspecialchars($messaggio); ?>
                </div>
            <?php endif; ?>

            <form action="creazione_eventi.php" method="POST">
                <div class="form-gruppo">
                    <label for="nome">Nome evento</label>
                    <input type="text" name="nome" id="nome" placeholder="Inserisci il nome dell'evento" required>

                    <label for="tipologia">Tipologia</label>
                    <select name="tipologia" id="tipologia" required>
                        <option value="" disabled selected>Scegli una tipologia</option>
                        <option value="PCTO">PCTO</option>
                        <option value="Evento sportivo">Evento sportivo</option>
                        <option value="Incontro professori-famiglia">Incontro professori-famiglia</option>
                        <option value="Conferenza">Conferenza</option>
                    </select>

                    <label for="descrizione">Descrizione evento</label>
                    <input type="text" name="descrizione" id="descrizione" placeholder="Inserisci la descrizione dell'evento" required>

                    <label for="data">Data evento</label>
                    <input type="date" name="data" id="data" required>

                    <label for="ora_inizio">Ora inizio</label>
                    <input type="time" name="ora_inizio" id="ora_inizio" required>

                    <label for="ora_fine">Ora fine</label>
                    <input type="time" name="ora_fine" id="ora_fine" required>

                    <label for="spazio">Seleziona lo spazio</label>
                    <select name="spazio" id="spazio" required>
                        <option value="" disabled selected>Scegli uno spazio</option>
                        <?php foreach($spazi_lista as $spazio): ?>
                            <option value="<?php echo htmlspecialchars($spazio['id_spazio']); ?>">
                                <?php echo htmlspecialchars($spazio['nome_spazio']) . " (" . htmlspecialchars($spazio['tipologia']) . ")"; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" name="registra_evento" class="btn-submit">Registra Evento</button>
            </form>
        </div>
    </div>
</body>
</html>