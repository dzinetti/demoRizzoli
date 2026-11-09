import requests
BASE_URL = "http://127.0.0.1:5000"
def test_status():
    URL_STATUS= f"{BASE_URL}/api/"
    output = requests.get(URL_STATUS)
    if output.status_code==200:print(output.json())

def test_corsi():
    URL_corsi= f"{BASE_URL}/api/corsi"
    output = requests.get(URL_corsi)
    if output.status_code==200:print(output.json()) 

def test_corsi_id(id):
    URL_corsi_id=f"{BASE_URL}/api/corsi/{id}"
    output = requests.get(URL_corsi_id)
    if output.status_code==200:print(output.json())
    
def add_ruolo_esempio():
    payload = {"nome": "nome", "descrizione": "descrizione"}
    req = requests.post("http://127.0.0.1:5000/api/db/ruoli/inserisci", json=payload)
    print(req.json())
     
def add_utente_esempio():
    payload = {
        "nome": "mario",
        "cognome": "draghi",
        "email": "mario.draghi@gov.it",
        "password": "covid190",
        "id_ruolo": 2
    }
    req = requests.post("http://127.0.0.1:5000/api/db/utenti/inserisci", json=payload)
    print(req.json())

def add_prenotazione_esempio():
    payload = {
        "data": "2026-06-15",
        "ora_inizio": "10:00:00",
        "ora_fine": "11:00:00",
        "stato": "Confermata",
        "id_utente": 1,
        "id_spazio": 1,
        "id_evento": 1
    }
    req = requests.post("http://127.0.0.1:5000/api/db/prenotazioni/inserisci", json=payload)
    print(req.json())

def test_qr_code():
    test_evento = {"user_id" : -1, "event_id" : -1, "booking_code":"evt-1-usr-1"}
    req = requests.post("http://127.0.0.1:5000/api/qrcode", json=test_evento).json()
    print(req)



def main():
    print("\n--------------------------------------------------------------")
    print("AVVIO DEL CLIENT ")
    print("--------------------------------------------------------------")
    
    #test_status()
    #test_corsi()
    #test_corsi_id(1)
    #add_ruolo_esempio()
    #add_utente_esempio()
    #add_prenotazione_esempio()
    # test_qr_code()
    
if __name__ == "__main__":
    main()
