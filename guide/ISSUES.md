# Guida al Project Management: Issues, Epics e Sub-issues

Questa guida spiega come organizzare bene i task di SmartCampus all'interno della **Git Organization** progettata per la classe.
Il Project Management non riguarda solo scrivere codice: serve anche una gestione chiara e trasparente del lavoro di ogni singolo Team.
Su GitHub usiamo tre concetti base per organizzare il carico dei vari Teams: Epic, Issue (Task) e Sub-issue.

## 1. Vocabolario del Team di Sviluppo

### Epic (Macro-obiettivo di un Team)

Un'Epic è un contenitore di lavoro molto grande per un Team specifico.
Di solito richiede settimane, coinvolge l'intero Team e definisce una macro-funzionalità del progetto (es. App PHP o Database logic).
Non la chiudete mai in un pomeriggio!

Esempi di Epic:
- (Team UI) Sviluppo dell'intero frontend dell'App
- (Team Backend) Creazione API e popolamento del database MongoDB/MySQL
- (Team E - QA) Stesura dei casi di test completi e documentazione QA.

**Gestione:** le Epic restano aperte molto a lungo sulla repository generale dell'Organization, chiudendosi solo quando l'intera funzionalità è completa.

### Issue (Task Operativo - gestito dal Team Leader)

Un'Issue è un lavoro specifico, misurabile e assegnabile a **un singolo studente o coppia**.
Si tratta di un compito che può essere completato in poche ore o un paio di giorni (es. un paio di ore di laboratorio).
**Il Team Leader del team interessato ha il compito (generalmente) di creare queste issue nella repository principale dell'Organization**.

Esempi di Issue:
- Creare lo schema della tabella `Utenti` nel DB.
- Scrivere la singola API per l'Autenticazione PHP.
- Testare le rotte dell'API tramite Postman (Role del Team E).

**Gestione:** il team leader apre l'issue sull'Organization e l'assegna ai compagni del team, che lavoreranno sulla **propria fork** menzionandone il numero (es. `#12`). L'issue si completa e chiude tramite formale Pull Request.

### Sub-issue o Checklist (Sotto-task)

Quando si apre un'Issue e si capisce che è piuttosto complessa per un solo commit, il lavoro si spezza ulteriormente usando checklist Markdown (`- [ ] task ...`). Serve per non perdersi per strada.

## 2. Il Flusso di Gestione (Step-by-step per Teams)

Il workflow segue lo standard industriale open-source:

### Step 1: Creare le Issues principali (Team Leader)
1. Il Team Leader entra nella repo principale dell'Organization.
2. Crea nuove "Issues", ad esempio: `[Team B] Form HTML di Login`.
3. Associa a ogni issue le giuste etichette (`enhancement`, `team-B` etc.).
4. **Assegnazione**: Il Team Leader imposta l'assegnatario (l'utente dello studente incaricato) nel riquadro *Assignees*.

### Step 2: Lavorare, Committare e Aprire la PR (Membro del Team)
1. Lo studente entra nella postazione. Apre il proprio terminale e si muove sulla **propria Fork** locale del progetto.
2. Crea un **branch** dedito (`git checkout -b feature/login`).
3. Scrive codice, verifica che tutto funzioni. Esegue commit precisi, citando SEMPRE l'issue dell'organization (es. `Creata l'API login (fixes #12)`).
4. Esegue il Push sulla **propria Fork personale**.
5. Apre la Pull Request su GitHub (sua fork -> main o develop repo dell'Organization).

### Step 3: La Revisione (Team E / QA Team)
È severamente vietato "mergiare" direttamente (o fare una PR senza revisione).
1. Quando la PR è aperta, entra in scena il **Team E**.
2. Il Team E legge il codice, testa se funziona e se l'issue coperta è al 100%.
3. Il Team E può **approvare** (facendo il merge) oppure richiedere **Changes Requested** lasciando commenti agli studenti autori.
4. Gli autori sistemano i problemi sulla propria fork/branch e inviano nuovi commit per farsi riapprovare.

## 3. Regole d'Oro per un voto alto
- **Regola 1**: Nessuno studente può scrivere codice a caso. Se fai un commit, quel lavoro DEVE corrispondere a un'Issue dell'Organization assegnata a TE.
- **Regola 2**: Nessuno pusha codice direttamente (no auto-merge per PR senza approvazione del QA/Team E).
- **Regola 3**: Tracciabilità totale! Il docente valuterà il vostro contributo incrociando i commit sulle fork personali e le issue chiuse e reviewate su GitHub!
