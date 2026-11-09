<?php
require_once __DIR__."/../config/session.php";
require_once __DIR__.'/../config/db_connection.php';
require_once __DIR__.'/../config/auth.php';

require_admin_area($conn);

$id_utente = $_SESSION["id_utente"];

$stmt = $conn->prepare("
    SELECT nome, cognome 
    FROM utente 
    WHERE id_utente = ?
");
$stmt->execute([$id_utente]);
$utente = $stmt->fetch();
$nome_utente = ($utente['nome'] ?? '') . " " . ($utente['cognome'] ?? '');
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../app_html/style.css">
    <title>SmartCampus - Dashboard Admin</title>
</head>
<body>
    <aside class="sidebar">
        <div class="sb-inner">
            <nav class="nav" aria-label="Navigation">
                <button class="home-btn" style="font-family: Arial, Helvetica, sans-serif;" onclick="window.location.href='dashboard_admin.php'">SmartCampus</button>
                <button class="nav-btn" onclick="window.location.href='gestione_eventi.php'" style="color: black;">Gestione eventi</button>
                <button class="nav-btn" onclick="window.location.href='creazione_eventi.php'" style="color: black;">Creazione eventi</button>
                <button class="nav-btn" onclick="window.location.href='../actions/logout.php'" style="color: red;">Logout</button>
            </nav>
        </div>
    </aside>

    <div class="main">
        <h1>Benvenuto, <?php echo htmlspecialchars($nome_utente); ?></h1>
        <p>Benvenuto nell'area di gestione di SmartCampus! <br>
            Questo strumento è progettato per darti il pieno controllo
            sull'organizzazione delle attività d'istituto. <br>
            Da qui puoi creare e pianificare eventi, corsi pomeridiani 
            e assemblee in modo intuitivo e veloce. <br>Avrai inoltre una visione 
            completa sulle prenotazioni di aule magna, laboratori e palestre, <br>
            permettendoti di coordinare al meglio gli spazi scolastici ed evitare accavallamenti.
        </p>
    </div>
    <script src="../../app_html/script.js"></script>
</body>
</html>