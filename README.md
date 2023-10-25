# Progetto Monitoraggio di parametri ambientali (M.P.A.) 2
Progetto presentato all'esame di stato 2018/19 per il diploma di **Perito informatico**, ottenuto presso l'ITIS Giordani a Caserta, indirizzo Informatica e telecomunicazioni.  [Download project documentation](https://github.com/AlessandroBonomo28/Centralina-IoT-MPA2/raw/main/docTecnicaMPA2.doc)
#### Obiettivo
Realizzazione di *Centraline IoT* che raccolgono dati ambientali che vengono inviati, mediante wi-fi, ad un sito remoto.

![362875-635573441388121239-16x9-3363977870](https://github.com/AlessandroBonomo28/Centralina-IoT-MPA2/assets/75626033/22883bc7-fc26-4914-a8db-f32de719ed4d)
## Tech stack
-	`Hardware arduino + ESP8266`: arduino legge periodicamente i dati dei sensori e li trasmette, mediante il modulo wifi ESP8266, al sito remoto;
-	`Web server remoto Apache` in ascolto di richieste HTTP GET/POST da parte della centralina IoT arduino.
-	`PHP + MySql database` PHP elabora le richieste al server, si connette al database e deposita, mediante sql dml, i dati ricevuti da arduino nelle rispettive tabelle;
-	`JavaScript + JQuery` funzioni Javascript che prelevano i dati del database e utilizzano librerie grafiche per rappresentarli.
## Struttura del Repository
In questo repository troverai 3 cartelle e la documentazione del progetto:
- `arduino software` software da caricare su arduino
- `mpa2 web root` insieme di tutti gli script php e funzioni javascript da mettere nella root del tuo web server apache
- `database schema` script sql per inizializzare il database mysql
- `documentazione` progettazione del database, funzionigrammi, schema delle risorse di sistema e dettagli implementativi. [Documentazione](https://github.com/AlessandroBonomo28/Centralina-IoT-MPA2/raw/main/docTecnicaMPA2.doc)
- `Youtube tutorial` https://www.youtube.com/playlist?list=PL7X2zBebu0O2Qnhch-h9dmvI2NynVb9TV
## Web interface
![ultime letture](https://github.com/AlessandroBonomo28/MPA-2/assets/75626033/1f8505ac-53ed-4620-8d09-648e2b2e1399)

![grafico](https://github.com/AlessandroBonomo28/MPA-2/assets/75626033/7ff6f688-d7bf-4b39-8076-7bff8f723285)
## Database
![db](https://github.com/AlessandroBonomo28/MPA-2/assets/75626033/5108958d-7d99-44f7-9cec-06c034111826)
## Hardware
![mpa2](https://github.com/AlessandroBonomo28/MPA-2/assets/75626033/00cb88be-0333-4959-869c-79e898d673c1)

