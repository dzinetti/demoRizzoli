# Progetto Didattico: "SmartCampus"

## Piattaforma per la Gestione di Eventi e Risorse Scolastiche

### 1. Contesto e Obiettivo

La scuola necessita di un sistema informatico moderno per gestire la prenotazione degli spazi comuni (aule magna, laboratori, palestre) e l'organizzazione di eventi interni (assemblee, corsi pomeridiani, conferenze).
Siete stati incaricati, come Software House, di sviluppare la piattaforma **SmartCampus**.

L'architettura del sistema non sarà un monolite tradizionale, ma un sistema ibrido che sfrutta le migliori caratteristiche di due tecnologie differenti, comunicando tramite API REST.

### 2. Architettura del Sistema

Il progetto prevede due componenti principali che dovranno comunicare tra loro:

- **Core Application (PHP & MySQL):** Il portale web principale. Gestisce la registrazione degli utenti (studenti, docenti, admin), il login, la visualizzazione delle risorse, e il salvataggio delle prenotazioni. Gira su server web (es. Apache/XAMPP) sulla porta 80/8080.
- **Microservizio (Python & Flask):** Un'applicazione separata che fornisce servizi specifici tramite API REST. Gira su un server locale indipendente (es. Werkzeug integrato in Flask) tipicamente sulla porta 5000. Si occuperà di:
    1. Fornire un endpoint pubblico (JSON) con la lista dei prossimi eventi.
    2. Generare un QR Code univoco (immagine) per ogni prenotazione effettuata.

### 3. Suddivisione dei Ruoli e Dettagli Implementativi

La classe sarà divisa in 5 team operativi. Ogni team nominerà un referente (Team Lead).

### Team A: Database & Data Architecture (2-3 studenti)

- **Compiti:** Progettazione del Modello E/R e dello Schema Logico. Normalizzazione fino alla 3NF.
- **Vincoli:** Utilizzo rigoroso delle Foreign Keys (chiavi esterne) con regole di `ON DELETE CASCADE` o `RESTRICT` appropriate.
- **Deliverable:** Script `db_scuola.sql` (creazione tabelle) e `query_fittizie.sql` (inserimento dati fittizi per i test).

### Team B: Backend PHP & Sicurezza (4-5 studenti)

- **Compiti:** Sviluppo della logica server-side e delle rotte web.
- **Struttura cartelle consigliata:** Separare la logica dalla UI. Es: `/config/db.php` (connessione), `/actions/login.php` (logica login), `/views/` (file HTML/PHP inclusi).
- **Sicurezza:** * Per direttiva didattica del docente, in questa versione le password vengono salvate e confrontate in testo semplice.
    - Ogni query deve usare `mysqli->prepare()`, `bind_param()` e `execute()`.
- **Integrazione API:** Per ottenere il QR Code da Python, il codice PHP dovrà inviare una richiesta POST locale. Si richiede l'uso di `cURL` (tramite `curl_init()`) o di `file_get_contents()` configurato con un *stream context* per inviare payload JSON.

### Team C: API & Microservices Python (3-4 studenti)

- **Compiti:** Creazione dell'applicazione Flask.
- **Setup Tecnico:** Creazione di un ambiente virtuale (`python -m venv venv`) e redazione di un file `requirements.txt` (conterrà `flask`, `mysql-connector-python`, `qrcode`, `Pillow`).
- **Endpoint richiesti:**
    - `GET /api/events` -> Si connette al DB MySQL e restituisce una risposta JSON strutturata: `{"status": "success", "data": [{...}, {...}]}`.
    - `POST /api/qrcode` -> Riceve un JSON `{ "user_id": 1, "event_id": 42, "booking_code": "EVT42-USR1" }`. Utilizza la libreria `qrcode` per generare un'immagine `.png`, la salva nella cartella `static/qrcodes/` del server Flask e restituisce al PHP l'URL per visualizzarla: `{"status": "success", "qr_url": "http://localhost:5000/static/qrcodes/EVT42-USR1.png"}`.
    - Ridondanza delle funzionalità: endpoint per gestire le attività sviluppate nella parte di backend in php

### Team D: Frontend & UI/UX (3-4 studenti)

- **Compiti:** Creazione dell'interfaccia utente. Le pagine devono essere responsive (adattabili a smartphone).
- **Tecnologie:** HTML5, CSS3, JS. Si consiglia l'adozione di **Bootstrap 5** (tramite CDN) per velocizzare la creazione di form, tabelle e navbar.
- **Pagine/Viste richieste:**
    - Pagina Login / Registrazione.
    - Dashboard Studente: Tabella con gli eventi futuri e bottone "Prenota" + Sezione "I miei QR Code".
    - Dashboard Admin/Docente: Form per creare un nuovo Evento e selezionare lo spazio.
- **Integrazione:** Lavoreranno con il Team B. Il Team D prepara file `.html` statici, che il Team B trasformerà in file `.php` inserendo i cicli `foreach` per stampare dinamicamente i dati.

### Team E: DevOps, QA & Documentazione (2-3 studenti)

- **Compiti:** Amministratori della repository GitHub. Si occuperanno di testare le API e il codice PHP prima che vada sul branch principale.
- **Strumenti:** Utilizzo di **Postman** o **Insomnia** per testare gli endpoint Flask in modo indipendente.
    - Redazione del `README.md` principale: dovrà contenere la guida passo-passo per far partire l'intero progetto (es. "1. Avvia XAMPP, 2. Importa SQL, 3. Attiva venv Python, 4. Lancia Flask...").
- **Gestione Repository:** Il repository avrà tre cartelle principali alla radice: `/app_php`, `/api`, `/database`.

### 4. Metodologia e Workflow (GitHub)

Tutto il codice dovrà risiedere su un'unica organizzazione/repository GitHub.
Si adotterà un **Git Flow** semplificato:

1. **`main`**: Ramo di produzione. Contiene solo codice funzionante e testato. **Nessuno committa direttamente qui.**
2. **`develop`**: Ramo di integrazione principale.
3. **Rami di feature**: Ogni team creerà dei branch con nomi esplicativi (es. `feature/db-schema`, `feature/php-auth`, `feature/flask-qr`).
4. Per unire il codice in `develop`, i team apriranno delle **Pull Request (PR)** su GitHub. Il Team E dovrà revisionare il codice prima di accettare il merge.

### 5. Fasi del Progetto e Consegne (Milestones)

- **Settimana 1-2 (Design & Setup):** * Team A: Consegna schema E/R (su Draw.io o similari) e script SQL.
    - Team D: Wireframe interfacce (Figma o carta).
    - Tutti: Setup di GitHub e degli ambienti locali. Nessun codice applicativo viene scritto finché il DB non è pronto.
- **Settimana 3-5 (Sviluppo Core Indipendente):** * Team C sviluppa le API (testate con Postman dal Team E).
    - Team B sviluppa la logica di Login PHP.
    - Team D crea i template HTML/CSS.
- **Settimana 6-8 (Integrazione):** * Unione di Frontend e Backend (PHP stampa le pagine HTML).
    - PHP effettua la chiamata HTTP all'API Python al momento del click su "Prenota", salva l'URL del QR Code nel DB e lo mostra a schermo.
- **Settimana 9-10 (Testing & Deploy):** Pulizia del codice, bug fixing, stesura documentazione finale. Presentazione del progetto (Demo Day) simulando la consegna al "Cliente Scuola".

### 6. Criteri di Valutazione

La valutazione terrà conto di:

- **Qualità e Sicurezza del codice:** Assenza di SQL Injection (uso di Prepared Statements) e corretta gestione delle password.
- **Integrazione API:** Successo nella comunicazione bidirezionale tra PHP e Python.
- **Lavoro di squadra (Version Control):** Cronologia dei commit ordinata, messaggi di commit chiari, corretta risoluzione dei conflitti su Git.
- **User Experience (UX):** Il sito deve essere navigabile, chiaro e non generare errori PHP (Notice/Warning) visibili all'utente finale.
- **Documentazione:** Completezza tecnica del file README.

## Esercitazioni Git e Markdown
Il progetto prevede diverse guide specifiche per gestire e sopravvivere al workflow aziendale basato su GitHub:
- `esercitazioni/01_primo_branch.md`
- `esercitazioni/02_aggiornare_fork.md`
- `esercitazioni/03_risoluzione_conflitti.md`
- `esercitazioni/04_code_review_team_e.md`

## Recap

- **Settimana 1 (8-13 Aprile): Setup e Progettazione.** Definizione del database, creazione della repository GitHub, divisione in team e setup degli ambienti (Venv Python, XAMPP).
- **Settimana 2-3 (Aprile): Sviluppo Moduli Base.** Team DB popola i dati; Team PHP fa il login; Team Python crea i primi endpoint; Team UI crea i mockup.
- **Settimana 4-5 (Maggio): Integrazione.** Il cuore del progetto. PHP inizia a chiamare le API Python. Gestione delle sessioni e delle prime prenotazioni reali.
- **Settimana 6-7 (Maggio): Rifinitura e Debug.** Gestione degli errori (es: cosa succede se il server Python è offline?), validazione form, estetica CSS.
- **Settimana 8 (Fine Maggio): Documentazione e Freeze.** Stop al codice. Si scrive il manuale e si prepara la presentazione.
- **Ultima settimana (Giugno): Demo Day.** Presentazione in classe (stile "conferenza stampa") per il voto finale.


### 7. Guida all'Installazione e Avvio in Locale

Questa guida permette a chiunque di avviare l'intero progetto SmartCampus 
sul proprio PC seguendo i passi in ordine.

**Requisiti**
- XAMPP (Apache + MySQL) installato
- Python 3.x installato
- Git installato


Passo 1 — Avviare XAMPP
1. Apri il pannello di controllo di XAMPP
2. Avvia i moduli **Apache** e **MySQL**



Passo 2 — Importare il Database
1. Apri il browser e vai su `http://localhost/phpmyadmin`
2. Clicca su **"Nuovo"** e crea un database chiamato `scuola`
3. Seleziona il database `scuola`, clicca su **"Importa"**
4. Carica il file `database/db_scuola.sql`
5. Clicca su **"Esegui"**
6. Ripeti l'importazione con il file `database/query_fittizie.sql` 
   per popolare il database con dati di test



Passo 3 — Avviare l'applicazione PHP
1. Copia l'intera cartella del progetto dentro `C:/xampp/htdocs/`
2. Apri il browser e vai su `http://localhost/SmartCampus-Project/`



Passo 4 — Configurare l'ambiente Python
Apri il terminale nella cartella `api` del progetto ed esegui:

**Su Windows:**
```
cd api
python -m venv venv
venv\Scripts\activate
pip install -r requirements.txt
```

**Su Mac/Linux:**
```
cd api
python3 -m venv venv
source venv/bin/activate
pip install -r requirements.txt
```



Passo 5 — Avviare il server Flask
Con il venv attivo, esegui:
```
python app.py
```
Il server sarà disponibile su `http://localhost:5000`

Verifica che funzioni aprendo nel browser: `http://localhost:5000/api/`  
Risposta attesa: `[{"status": "online"}]`

---
