# Documentazione API 

Benvenuto nella documentazione delle API del progetto. Di seguito trovi l'elenco completo di tutti gli endpoint disponibili,
i metodi HTTP accettati, la struttura dei dati richiesti  e i relativi formati di risposta.

---
### endpoint`'http://localhost:5000/api/qrcode' , metodo = POST`
   questa APi crea un qrcode con all'interno i dati passati e restitusce il percorso di esso 
   formato richiesta:{
              "user_id":<user_id>,
              "event_id":<event_id>,
              "booking_code":<booking_code>
              }
   formato risposta:{
              'path':<path>
   }
   



### endpoint`'http://localhost:5000/api/db/ruoli' , metodo = GET`
  restituisce tutti i ruoli salvati nel database
  formato risposta : {
            "stutus":<statoRichiesta>,
            "data":<dati>
  }




### 'http://localhost:5000/api/db/insert_ruolo' , metodo = POST
  Permette di inserire un nuovo ruolo e la sua descrizione all'interno del database.
  formato richiesta:{
            "nome": <nome>,
            "descrizione": <descrizione>
            }
  Formato risposta:{
            "message": "inserito <dati_inseriti>"
            }

## endpoint 'http://localhost:5000/api/events' , metodo = GET
  Restituisce tutti gli eventi salvati nel database relazionale.
  Formato risposta:{
            "status": "success",
            "data":{
                    "id_evento": <id_evento>,
                    "nome": <titolo>,
                    "descrizione": <descrizione>,
                    "tipologia_evento": <tipologia>
            }        
  }


## endpoint 'http://localhost:5000/api/corsi' , metodo = GET
  Tramite questa API, quando un client invia una richiesta GET a questo indirizzo,
  il server fornisce una lista contenente tutti i corsi disponibili in memoria.
  Formato risposta:{
          "id": 1,
          "nome": "italiano",
          "orario": "12",
          "classe": "5f"
        }
