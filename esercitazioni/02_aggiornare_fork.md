# Esercitazione pratica: Aggiornare la propria Fork (Sync with Upstream)

Mentre tu stai lavorando sulla tua Fork, altri studenti e Teams (dopo l'approvazione del Team E) staranno facendo il merge delle loro Pull Request nel ramo `main` o `develop` della repository dell'**Organization**.
Questo significa che la *tua* Fork rimarrà indietro (out of date) rispetto alla repository principale (Upstream).

Per evitare conflitti, all'inizio di ogni lezione o prima di iniziare un nuovo branch, **devi sempre sincronizzare la tua Fork locale con le modifiche del progetto principale**.

Questa operazione fa si che il tuo `main` locale diventi identico al `main` dell'Organization.

## Fase 1: Aggiungere il Remote "Upstream" (Da fare solo la prima volta)

Per scaricare le modifiche dall'Organization, il tuo Git locale deve sapere dove si trova la repo principale. 
Di default, il tuo comando `git clone` ha impostato `origin` che punta alla **tua fork**. Ora aggiungiamo `upstream` che punta all'**Organization**.

Nel terminale, dentro la cartella del progetto:

```bash
# Sostituisci l'URL con l'URL HTTPS della repository principale dell'Organization
git remote add upstream https://github.com/NomeOrganization/SmartCampus-Project.git
```

Per verificare che il remote sia stato aggiunto correttamente:

```bash
git remote -v
```

Dovresti vedere sia `origin` (la tua fork) che `upstream` (l'Organization).

## Fase 2: Sincronizzare il codice (Da fare spesso!)

Assicurati di essere sul tuo branch `main`:

```bash
git checkout main
```

Scarica le novità dall'Organization (senza modificare nulla per ora):

```bash
git fetch upstream
```

Ora unisci (merge) le modifiche dell'Organization nel tuo `main` locale:

```bash
git merge upstream/main
```
*(Se il branch principale dell'Organization si chiama `develop`, farai `git merge upstream/develop`)*.

Ora il tuo `main` locale è aggiornato! Ma la tua Fork su GitHub non lo è ancora. Devi caricare queste modifiche:

```bash
git push origin main
```

## Fase 3: Creare il branch per il nuovo task

Ora che il tuo progetto è perfettamente in pari con il lavoro di tutta la classe, puoi iniziare il tuo nuovo lavoro:

```bash
git checkout -b feature/nuovo-task
```

E ripetere il ciclo: scrivi codice -> `git commit` -> `git push origin feature/nuovo-task` -> Apri Pull Request.
