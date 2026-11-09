# Esercitazione pratica: I Merge Conflicts con il Team E

La paura più grande di tutti i team in un progetto open source sono i **Merge Conflicts**. Essi accadono quando due persone lavorano allo stesso tempo, nello **stesso file** e magari sulle **stesse righe di codice**.

A un certo punto, due studenti manderanno le loro PR (Pull Requests) nell'Organization. 
Il **Team E** ne approverà una (es. quella di Mario), che finirà nel ramo `develop` (o `main`). Quando sarà il turno della seconda PR (es. quella di Luigi), GitHub si accorgerà che Luigi sta provando a modificare un file che è già stato cambiato nel frattempo da Mario.
GitHub darà l'errore: "This branch has conflicts that must be resolved".

Il **Team E (o il Team Leader di Luigi)** chiederà a Luigi di risolvere il conflitto **sulla sua Fork** per procedere con il Merge della sua PR.

## Fase 1: Come risolvere i conflitti (Cosa deve fare Luigi)

Invece di cancellare tutto, c'è un modo elegante per sistemarlo. Luigi deve "scaricare la situazione problematica" e sistemarla in locale sul suo editor (VS Code).

1. Luigi prima di tutto, **sincronizza il `main` locale con l'upstream** (vedi Esercitazione 02).
   ```bash
   git checkout main
   git fetch upstream
   git merge upstream/main
   ```
2. Ora, Luigi torna sul ramo della sua PR (il ramo con il conflitto!).
   ```bash
   git checkout feature/nuova-nav-luigi
   ```
3. Luigi fonde il `main` (ora aggiornato con le modifiche di Mario) nel suo ramo in conflitto:
   ```bash
   git merge main
   ```
4. A questo punto il terminale suonerà l'allarme: `CONFLICT (content): Merge conflict in index.php`.

## Fase 2: Risolverlo in VS Code

Luigi apre il file segnalato in conflitto (es. `index.php`) nel suo IDE (VS Code).
Vedrà qualcosa di simile:

```html
<<<<<<< HEAD (Il ramo di Luigi, cioè Current Change)
<h1>Benvenuti da Luigi</h1>
======= (Ciò che GitHub ha scaricato da main e da Mario, cioè Incoming Change)
<h1>Benvenuto Ciao da Mario</h1>
>>>>>>> main
```

VS Code fornirà tre pulsanti sopra la scritta in conflitto:
* `Accept Current Change` (Tieni il codice di Luigi)
* `Accept Incoming Change` (Tieni il codice di Mario). (Spesso è la scelta sbagliata, perché annulla il lavoro di Luigi appena fatto).
* `Accept Both Changes` (Tieni entrambi, li metti l'uno sotto l'altro e poi li metti a posto a mano!).

Luigi sceglie l'opzione corretta (es: "Accept Both Changes", cancella le due righe `<h1>` e scrive un'unica riga: `<h1>Benvenuti da Luigi e Mario</h1>`).

## Fase 3: Salvare le modifiche pulite e aggiornare la PR

Una volta ripulito tutto il codice conflittuale, Luigi "dice a Git" che ha risolto il problema e rifà il commit.

1. Aggiungiamo i file ripuliti allo staging:
   ```bash
   git add .
   ```
2. Creiamo il commit di merge:
   ```bash
   git commit -m "Risolto conflitto di merge con il main in index.php"
   ```
3. Adesso mandiamo le modifiche alla propria Fork su GitHub:
   ```bash
   git push origin feature/nuova-nav-luigi
   ```

## Fase 4: La palla torna al Team E

Appena Luigi fa il `push`, GitHub si aggiorna automaticamente!
Il **Team E**, andando sulla pagina della Pull Request (che prima era bloccata con l'errore rosso), vedrà ora che il conflitto è risolto.
Ora il Team E potrà premere finalmente il pulsante verde `Merge pull request`.
