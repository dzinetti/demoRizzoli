<?php
require_once __DIR__."/../config/session.php";
require_once __DIR__.'/../config/db_connection.php';
require_once __DIR__.'/../config/auth.php';

require_admin_area($conn);

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['salva_modifiche'])) {
    $id_evento = intval($_POST['id_evento']);
    $id_prenotazione = intval($_POST['id_prenotazione']);
    $nome = trim($_POST['nome'] ?? '');
    $tipologia = trim($_POST['tipologia'] ?? '');
    $descrizione = trim($_POST['descrizione'] ?? '');
    $id_spazio = intval($_POST['spazio']);

    $update_evento = $conn->prepare("UPDATE evento SET titolo = ?, descrizione = ?, tipologia = ? WHERE id_evento = ?");
    $update_evento->execute([$nome, $descrizione, $tipologia, $id_evento]);

    $update_prenotazione = $conn->prepare("UPDATE prenotazione SET id_spazio = ? WHERE id_prenotazione = ?");
    $update_prenotazione->execute([$id_spazio, $id_prenotazione]);

    header("Location: gestione_eventi.php?status=updated");
    exit();
}

$sql_eventi = "SELECT E.id_evento, E.titolo, E.descrizione, E.tipologia, 
                      S.id_spazio, S.nome_spazio, 
                      P.id_prenotazione
               FROM evento E
               JOIN prenotazione P ON E.id_evento = P.id_evento
               JOIN spazio S ON P.id_spazio = S.id_spazio
               ORDER BY E.id_evento DESC";
$risultato_eventi = $conn->query($sql_eventi)->fetchAll();

$query_spazi_popup = "SELECT id_spazio, nome_spazio FROM spazio";
$spazi_array = $conn->query($query_spazi_popup)->fetchAll();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../app_html/style.css">
    <title>SmartCampus - Gestione Eventi</title>
</head>
<body>
    <aside class="sidebar">
        <div class="sb-inner">
            <nav class="nav" aria-label="Navigation">
                <button class="home-btn" style="font-family: Arial, Helvetica, sans-serif;" onclick="window.location.href='dashboard_admin.php'">SmartCampus</button>
                <button class="nav-btn active" onclick="window.location.href='gestione_eventi.php'" style="color: black;">Gestione eventi</button>
                <button class="nav-btn" onclick="window.location.href='creazione_eventi.php'" style="color: black;">Creazione eventi</button>
                <button class="nav-btn" onclick="window.location.href='../actions/logout.php'" style="color: red;">Logout</button>
            </nav>
        </div>
    </aside>

    <div class="main">
        <h1>Eventi Pianificati</h1>

        <?php if(isset($_GET['status']) && $_GET['status'] == 'updated'): ?>
            <div style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin: 20px; font-weight: bold;">
                Modifiche salvate con successo!
            </div>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>Evento</th>
                    <th>Tipologia</th>
                    <th>Descrizione</th>
                    <th>Spazio Prenotato</th>
                    <th>Azione</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($risultato_eventi)): ?>
                    <?php foreach($risultato_eventi as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['titolo']) ?></td>
                            <td><?= htmlspecialchars($row['tipologia']) ?></td>
                            <td><?= htmlspecialchars($row['descrizione']) ?></td>
                            <td><?= htmlspecialchars($row['nome_spazio']) ?></td>
                            <td>
                                <button class='btn-modifica' onclick='apriModifica(this)'
                                    data-id-evento='<?= $row['id_evento'] ?>'
                                    data-id-prenotazione='<?= $row['id_prenotazione'] ?>'
                                    data-titolo='<?= htmlspecialchars($row['titolo'], ENT_QUOTES) ?>'
                                    data-tipologia='<?= htmlspecialchars($row['tipologia'], ENT_QUOTES) ?>'
                                    data-descrizione='<?= htmlspecialchars($row['descrizione'], ENT_QUOTES) ?>'
                                    data-id-spazio='<?= $row['id_spazio'] ?>'>
                                    Modifica Evento
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan='5' style='text-align:center;'>Nessun evento registrato.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div id="mioPopup" class="sfondo-popup">
        <div class="contenuto-popup">
            <span id="tastoChiudi" class="tasto-chiudi">&times;</span>
            <h3 style="margin-top: 0;">Modifica Dati Evento</h3>
            <form action="gestione_eventi.php" method="POST">
                <input type="hidden" name="id_evento" id="pop_id_evento">
                <input type="hidden" name="id_prenotazione" id="pop_id_prenotazione">

                <div class="form-gruppo">
                    <label for="pop_nome">Nome evento</label>
                    <input type="text" name="nome" id="pop_nome" required>
                </div>

                <div class="form-gruppo">
                    <label for="pop_tipologia">Tipologia</label>
                    <select name="tipologia" id="pop_tipologia" required>
                        <option value="PCTO">PCTO</option>
                        <option value="Evento sportivo">Evento sportivo</option>
                        <option value="Incontro professori-famiglia">Incontro professori-famiglia</option>
                        <option value="Conferenza">Conferenza</option>
                    </select>
                </div>

                <div class="form-gruppo">
                    <label for="pop_descrizione">Descrizione evento</label>
                    <input type="text" name="descrizione" id="pop_descrizione" required>
                </div>

                <div class="form-gruppo">
                    <label for="pop_spazio">Spazio Prenotato</label>
                    <select name="spazio" id="pop_spazio" required>
                        <?php foreach($spazi_array as $spazio): ?>
                            <option value="<?php echo $spazio['id_spazio']; ?>"><?php echo htmlspecialchars($spazio['nome_spazio']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" name="salva_modifiche" class="btn-salva">Salva Modifiche</button>
            </form>
        </div>
    </div>

    <script>
        const popup = document.getElementById("mioPopup");
        const tastoChiudi = document.getElementById("tastoChiudi");

        function apriModifica(bottone) {
            document.getElementById("pop_id_evento").value = bottone.getAttribute("data-id-evento");
            document.getElementById("pop_id_prenotazione").value = bottone.getAttribute("data-id-prenotazione");
            document.getElementById("pop_nome").value = bottone.getAttribute("data-titolo");
            document.getElementById("pop_tipologia").value = bottone.getAttribute("data-tipologia");
            document.getElementById("pop_descrizione").value = bottone.getAttribute("data-descrizione");
            document.getElementById("pop_spazio").value = bottone.getAttribute("data-id-spazio");
            popup.style.display = "flex";
        }

        tastoChiudi.onclick = function() { popup.style.display = "none"; }
        window.onclick = function(event) { if (event.target == popup) popup.style.display = "none"; }
    </script>
</body>
</html>