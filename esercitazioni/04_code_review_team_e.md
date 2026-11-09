# Esercitazione pratica: (Solo per il Team E) Come fare una Code Review

Il Team E è il guardiano (Quality Assurance) del progetto *SmartCampus*.
Il vostro lavoro non è solo scrivere codice, ma garantire che quello degli altri sia perfetto prima di entrare in produzione. Siete l'ultimo baluardo prima del `main`.

Quando un Team D o Team B finisce un'Issue, aprirà una Pull Request (PR) verso la repository dell'Organization.

## Fase 1: La Ricezione della PR
Appena ricevete una notifica via GitHub (perché uno studente vi ha taggato o ha aggiunto il vostro team come *Reviewers*):

1. Aprite la pagina dell'Organization e andate nel tab **Pull Requests**.
2. Aprite la PR in questione (es: `#15 - Aggiunta pagina di login`).

## Fase 2: Il controllo iniziale (Sanity Check)
Prima di leggere il codice, controllate:
- **Ha risolto l'Issue assegnata?** Leggete il titolo o la descrizione per vedere se c'è scritto `Fixes #10`.
- **Ha lavorato sul branch giusto?** La PR deve essere da `sua-fork:feature/login` verso `organizzazione:develop` (o `main`).
- **Ha rispettato lo stile?** Ha lasciato commit con messaggi come "fatto" invece di "Inserito form HTML"? Se sì, richiamatelo all'ordine!

## Fase 3: La Code Review ("Files changed")
Spostatevi sul tab **Files changed** della PR su GitHub. Qui vedrete in verde le righe aggiunte e in rosso quelle rimosse.

**Cosa cercare?**
1. **Errori di battitura (typo)** o variabili in italiano/inglese mescolate in modo confuso (es. `$numero_users`).
2. **Errori di Logica/Sicurezza:** Il form invia le password in testo in chiaro? Ha usato le Prepared Statements (`bind_param`) in PHP?
3. **Mancanza di testing:** Per le API Python, provate a chiamarle con Postman usando lo stesso branch. Funziona davvero o va in Crash?

## Fase 4: Lasciare Feedback e Richiedere Modifiche (Request Changes)
Se trovate un errore:
1. Passate con il mouse sopra alla riga di codice problematica.
2. Cliccate sul pulsante azzurro **"+"** (Add review comment).
3. Scrivete un commento costruttivo: 
   *"Il tag form non ha il metodo POST, così i dati finiranno nell'URL! Per favore cambialo in `<form method="POST">`."*
4. Cliccate su **"Start a review"**.

Se ci sono più errori, aggiungete altri commenti.
Alla fine, nell'angolo in alto a destra, cliccate sul pulsante verde **"Review changes"**.
Assicuratevi di selezionare il radio button **Request changes** e lasciate un commento di riepilogo generale (es. *"Ottimo lavoro, ma ci sono problemi di sicurezza nel file login.php. Correggili e poi approveremo."*)

Il creatore della PR (es. Mario) riceverà una notifica, dovrà correggere gli errori sulla sua macchina, fare un nuovo commit e `git push` sulla sua Fork. La PR si aggiornerà in tempo reale.

## Fase 5: Approvare il lavoro (Il Merge!)
Se Mario ha sistemato tutto:
1. Andate sulla PR aggiornata.
2. Controllate che il codice ora sia pulito e senza errori.
3. Selezionate il pulsante verde **"Review changes"** in alto a destra e modificate lo stato in **Approve**.
4. A questo punto si abiliterà in fondo alla pagina il pulsante verde **"Merge pull request"**.
5. Premetelo, confermate, e congratulatevi con il team. 
