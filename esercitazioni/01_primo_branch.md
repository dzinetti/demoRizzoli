# Esercitazione pratica: primo Branch e Pull Request

Prerequisito: prima di iniziare, si deve aver già fatto la Fork della repository dell'Organization (creata dal docente per la classe) sul proprio account personale, e il `git clone` della propria fork sul PC. Si apre il terminale (o Git Bash) dentro la cartella del progetto clonato.

## Fase 1: creare un branch con il proprio nome

Sul ramo `main` non si lavora direttamente: si crea sempre un branch separato sulla propria fork.

Dal terminale ci si assicura di essere su `main`:

```bash
git checkout main
# oppure
git switch main
```

Poi si crea e si usa un nuovo branch con nome e cognome (con trattini al posto degli spazi):

```bash
git checkout -b nome-cognome
# esempio
git checkout -b mario-rossi
```

Per controllo, si usa:

```bash
git branch
```

Il branch attivo compare con l'asterisco.

## Fase 2: creare il file di testo

Ora si aggiunge il proprio contributo (assegnato precedentemente dal Team Leader nell'Issue dell'Organization).

1. Si apre il progetto con l'editor (es. VS Code).
2. Si entra nella cartella `docs/studenti`.
3. Se `studenti` non esiste, si crea.
4. Si crea un file chiamato `nome-cognome.txt` (esempio: `mario-rossi.txt`).

Nel file si scrive una breve presentazione con team e attività prevista.

Esempio:

> Ciao, sono Mario Rossi. Faccio parte del Team B e mi occuperò dello sviluppo della logica PHP per il login utenti.

Infine si salva il file.

## Fase 3: salvare e inviare (commit e push)

Adesso si registra la modifica e si invia alla **propria fork** su GitHub.

Si aggiunge il file allo staging:

```bash
git add docs/studenti/nome-cognome.txt
```

Suggerimento: si può anche usare `git add .`, ma con attenzione per non includere file sbagliati.

Si crea il commit con un messaggio chiaro, citando l'Issue originale:

```bash
git commit -m "Aggiunta presentazione di Mario Rossi (Riferimento #1)"
```

Si invia il branch alla fork (remote `origin`):

```bash
git push origin nome-cognome
```

## Fase 4: aprire la Pull Request su GitHub (Review by Team E)

Il codice ora è sulla propria fork, quindi si passa alla consegna tramite Pull Request verso la repo dell'Organization.

1. Si va sulla pagina GitHub della propria fork.
2. Si clicca su `Compare & pull request` nel banner del branch appena pushato.

Controlli importanti nella schermata PR:

- `base repository`: deve essere quella dell'Organization (esempio: `OrganizationName/SmartCampus-Project`).
- `base`: `main` (o `develop`, in base alle istruzioni).
- `head repository`: deve essere la propria fork personale (esempio: `mario-rossi/SmartCampus-Project`).
- `compare`: deve essere il branch appena pushato (es. `nome-cognome`).
- Si inserisce un titolo (esempio: `Presentazione Mario Rossi`) e una breve descrizione. Si tagga o si nomina il `@Team-E` per richiedere la Code Review.

Per finire, si clicca su `Create pull request`.

### Subito dopo: il Team E

A questo punto la palla passa al **Team E (Quality Assurance)**, che valuterà la Pull Request aperta nell'Organization. Se la presentazione è corretta, il Team E approverà e farà il Merge. Se c'è qualche errore (es. file collocato altrove o sintassi sbagliata), un membro del Team E richiederà delle correzioni lasciando un commento sulla PR.
