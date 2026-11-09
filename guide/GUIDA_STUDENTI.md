Benvenuti nel team di sviluppo di **SmartCampus**!
Per lavorare in modo professionale, coordinato e permettere al docente di valutare il contributo di ognuno, utilizzeremo le funzionalità avanzate di GitHub e lavoreremo all'interno di una **Git Organization** dedicata alla classe. Non scriveremo solo codice, ma gestiremo il progetto come in una vera Software House, organizzati in **Teams**.

Questa guida vi illustra le fasi fondamentali del nostro worflow che **ogni membro del team** dovrà compiere.

## 0. Setup Iniziale: Il metodo "Fork" dalla Organization

Il codice principale risiede nella repository ufficiale della nostra **Organization**. Prima di iniziare a scrivere codice, ogni studente deve creare la propria area di lavoro personale (Fork).

Procedura obbligatoria:
1. **Navigazione**: Andate sulla pagina della repository principale all'interno dell'Organization fornita dal docente.
2. **Fork**: Cliccate sul pulsante Fork (in alto a destra). Selezionate il vostro account personale come destinazione. Ora avrete una copia esatta del progetto sul vostro account personale.
3. **Clone**: Fate il `git clone` del link della **vostra fork** (non di quella dell'Organization!) sul PC del laboratorio.
   ```bash
   git clone https://github.com/vostro-nome-utente/SmartCampus-Project.git
   ```
4. **Creare le PR**: Lavorerete sempre sulla vostra fork. Quando completerete i compiti, invierete il lavoro alla repository dell'Organization tramite Pull Request.

### 0.5 Mantenere la propria Fork "Sincronizzata" (Importante!)

Il docente o gli altri team (tramite PR approvate) aggiungeranno regolarmente nuovo codice alla repository principale dell'Organization. La vostra fork **NON si aggiorna da sola**. 
Se il docente fa una modifica o se un compagno ha concluso un lavoro, la vostra fork rimarrà indietro. Per evitare disastri e conflitti, **all'inizio di ogni lezione** dovete aggiornarla:

**Metodo 1 (Più semplice - tramite GitHub UI):**
1. Andate sulla pagina della **vostra Fork** su GitHub.
2. Cliccate sul pulsante **"Sync fork"** (sotto al bottone verde "Code") e poi su **"Update branch"**.
3. Aprite il terminale sul vostro PC, posizionatevi sul ramo `main` e scaricate le novità sul computer:
   ```bash
   git checkout main
   git pull origin main
   ```

**Metodo 2 (Più professionale - tramite Terminale):**
Consultate l'esercitazione `esercitazioni/02_aggiornare_fork.md` per imparare a usare il comando `git fetch upstream` e aggiornare il codice direttamente da Git.

## 1. Le "Issues": Il Team Leader assegna i compiti

Le *Issues* sono i "post-it" del vostro progetto. Ogni volta che c'è una nuova funzionalità o un bug, si crea un'Issue nella repository **dell'Organization**.

**Il compito di creare le Issues spetta principalmente al Team Leader di ogni team.**

### Come creare e gestire un'Issue (Per i Team Leader):
1. Andate sulla pagina del repository del progetto dell'Organization su GitHub.
2. Cliccate sul tab **"Issues"** e poi su **"New issue"**.
3. **Titolo chiaro:** Es. `[Team B] Sviluppo pagina login.php`.
4. **Descrizione dettagliata:** Scrivete cosa deve fare esattamente il task.
5. **Assegnazione (Assignees):** Assegnate l'Issue ai membri del vostro team che dovranno svolgere il lavoro. *(Il docente valuterà il lavoro partendo da qui!)*
6. **Etichette (Labels):** Aggiungete label appropriate (`enhancement`, `bug`, etc.).

> **Regola d'oro:** Nessuno dovrebbe mai scrivere codice se prima non c'è un'Issue aperta e assegnata a lui/lei!

## 2. I Commit: Registrare il Lavoro sulla propria Fork

Ogni membro del team svolge il lavoro assegnato sulla propria Fork.

### Come svolgere il lavoro:
1. **Lavorate sul vostro Branch:** Non lavorate mai sul `main`. Create un branch dedicato per la vostra issue (es. `feature/issue-4-login`).
   ```bash
   git checkout -b feature/issue-4-login
   ```
2. **Commit frequenti e chiari:** Fate commit per ogni piccolo blocco logico completato.
   ✅ *Corretto:* `Aggiunto controllo hash password in login.php`.
3. **Collegate il commit all'Issue:** Nel messaggio del commit, inserite il riferimento all'Issue aperta nell'Organization (es. `Completata query SQL (Riferimento #4)` o `Fixes #4`).
4. **Push sulla vostra Fork:** Inviate il branch aggiornato alla vostra repository personale.
   ```bash
   git push origin feature/issue-4-login
   ```

## 3. Le Pull Request (PR) e il controllo del Team E

Avete finito il vostro compito e pushato sulla vostra fork. Ora dovete chiedere di unire il vostro codice alla repository principale dell'Organization.

### Come aprire una Pull Request:
1. Su GitHub, andate sulla pagina della **vostra Fork**.
2. Cliccate su **"Compare & pull request"**.
3. Assicuratevi che i branch siano corretti:
   * **Base repository:** repository dell'Organization (es. ramo `develop` o `main`).
   * **Head repository:** la vostra fork e il vostro branch (es. `feature/issue-4-login`).
4. **Titolo e Descrizione:** Descrivete chiaramente cosa avete fatto e referenziate l'Issue (es. `Closes #4`). Menziante `@Team-E` o il responsabile della QA per la revisione.
5. Cliccate su **"Create pull request"**.

### Il ruolo del Team E (Quality Assurance):
Il **Team E** ha il compito di controllare tutte le Pull Request aperte.
1. Un membro del Team E esegue la **Code Review**, verificando che il codice funzioni, rispetti le linee guida e risolva l'Issue.
2. Se ci sono problemi, il Team E lascia commenti sulla PR richiedendo modifiche agli autori.
3. Se tutto è corretto, il Team E approva la PR ed esegue il **Merge** nella repository dell'Organization.

Buon lavoro e buona programmazione!
