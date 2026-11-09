# parte Ziyad
from flask import Flask, jsonify, request
import json
import main

app = Flask(__name__)


def get_json_payload():
    payload = request.get_json(silent=True)

    if isinstance(payload, str):
        return json.loads(payload)

    return payload or {}


@app.route('/api/qrcode', methods=["POST"])
def returnQrCode():
    try:
        payload = get_json_payload()
        qr_url = main.generate_qr_code(payload)
        return jsonify({"status": "success", "qr_url": qr_url, "path": qr_url})
    except Exception as e:
        return jsonify({"status": "failure", "message": f"error {e}"}), 400


@app.route('/api/events', methods=["GET"])
def getPublicEventsFromDb():
    return jsonify({"status": "success", "data": main.fetch_eventi_from_db()})


# -------------------------------------------------------------------------------------------
### RUOLO ###
@app.route('/api/db/ruoli', methods=["GET"])
def getRuoliFromDb():
    try:
        return jsonify({"status": "success", "data": main.fetch_ruoli_from_db()})
    except Exception as e:
        return jsonify({"status": "failure", "msg": str(e)}), 500


@app.route('/api/db/ruoli/inserisci', methods=["POST"])
def insertRuoloToDb():
    try:
        payload = get_json_payload()
        result = main.add_ruolo_to_db(payload["nome"], payload["descrizione"])
        if isinstance(result, Exception):
            raise result
        return jsonify({"status": "success", "message": f"inserito {payload}"}), 201
    except Exception as e:
        return jsonify({"status": "failure", "msg": str(e)}), 400


@app.route('/api/db/ruoli/<int:id>', methods=["GET"])
def getRuoloFromDb(id: int):
    try:
        result = main.fetch_ruolo_from_db(id)
        if isinstance(result, Exception):
            raise result
        return jsonify({"status": "success", "data": result})
    except Exception as e:
        return jsonify({"status": "failure", "msg": str(e)}), 404


### UTENTE ###
@app.route('/api/db/utenti', methods=["GET"])
def getUtentiFromDb():
    try:
        return jsonify({"status": "success", "data": main.fetch_utenti_from_db()})
    except Exception as e:
        return jsonify({"status": "failure", "msg": str(e)}), 500


@app.route('/api/db/utenti/inserisci', methods=["POST"])
def insertUtenteToDb():
    try:
        payload = get_json_payload()
        result = main.add_utente_to_db(
            payload["nome"],
            payload["cognome"],
            payload["email"],
            payload["password"],
            payload["id_ruolo"]
        )
        if isinstance(result, Exception):
            raise result
        return jsonify({"status": "success", "message": f"inserito {payload}"}), 201
    except Exception as e:
        return jsonify({"status": "failure", "msg": str(e)}), 400


@app.route('/api/db/utenti/<int:id>', methods=["GET"])
def getUtenteFromDb(id: int):
    try:
        result = main.fetch_utente_from_db(id)
        if isinstance(result, Exception):
            raise result
        return jsonify({"status": "success", "data": result})
    except Exception as e:
        return jsonify({"status": "failure", "msg": str(e)}), 404


### SPAZIO ###
@app.route('/api/db/spazi', methods=["GET"])
def getSpaziFromDb():
    try:
        return jsonify({"status": "success", "data": main.fetch_spazi_from_db()})
    except Exception as e:
        return jsonify({"status": "failure", "msg": str(e)}), 500


@app.route('/api/db/spazi/inserisci', methods=["POST"])
def insertSpazioToDb():
    try:
        payload = get_json_payload()
        result = main.add_spazio_to_db(
            payload["nome_spazio"],
            payload["tipologia"],
            payload["capienza_massima"]
        )
        if isinstance(result, Exception):
            raise result
        return jsonify({"status": "success", "message": f"inserito {payload}"}), 201
    except Exception as e:
        return jsonify({"status": "failure", "msg": str(e)}), 400


@app.route('/api/db/spazi/<int:id>', methods=["GET"])
def getSpazioFromDb(id: int):
    try:
        result = main.fetch_spazio_from_db(id)
        if isinstance(result, Exception):
            raise result
        return jsonify({"status": "success", "data": result})
    except Exception as e:
        return jsonify({"status": "failure", "msg": str(e)}), 404


### EVENTO ###
@app.route('/api/db/eventi', methods=["GET"])
def getEventiFromDb():
    try:
        return jsonify({"status": "success", "data": main.fetch_eventi_from_db()})
    except Exception as e:
        return jsonify({"status": "failure", "msg": str(e)}), 500


@app.route('/api/db/eventi/inserisci', methods=["POST"])
def insertEventoToDb():
    try:
        payload = get_json_payload()
        result = main.add_evento_to_db(
            payload["titolo"],
            payload.get("descrizione", ""),
            payload["tipologia"]
        )
        if isinstance(result, Exception):
            raise result
        return jsonify({"status": "success", "id_evento": result}), 201
    except Exception as e:
        return jsonify({"status": "failure", "msg": str(e)}), 400


@app.route('/api/db/eventi/<int:id>', methods=["GET"])
def getEventoFromDb(id: int):
    try:
        result = main.fetch_evento_from_db(id)
        if isinstance(result, Exception):
            raise result
        return jsonify({"status": "success", "data": result})
    except Exception as e:
        return jsonify({"status": "failure", "msg": str(e)}), 404


### PRENOTAZIONE ###
@app.route('/api/db/prenotazioni', methods=["GET"])
def getPrenotazioniFromDb():
    try:
        return jsonify({"status": "success", "data": main.fetch_prenotazioni_from_db()})
    except Exception as e:
        return jsonify({"status": "failure", "msg": str(e)}), 500


@app.route('/api/db/prenotazioni/inserisci', methods=["POST"])
def insertPrenotazioneToDb():
    try:
        payload = get_json_payload()
        result = main.add_prenotazione_to_db(
            payload["data"],
            payload["ora_inizio"],
            payload["ora_fine"],
            payload.get("stato", "Confermata"),
            payload["id_utente"],
            payload["id_spazio"],
            payload["id_evento"]
        )
        if isinstance(result, Exception):
            raise result
        return jsonify({"status": "success", "id_prenotazione": result}), 201
    except Exception as e:
        return jsonify({"status": "failure", "msg": str(e)}), 400


@app.route('/api/db/prenotazioni/<int:id>', methods=["GET"])
def getPrenotazioneFromDb(id: int):
    try:
        result = main.fetch_prenotazione_from_db(id)
        if isinstance(result, Exception):
            raise result
        return jsonify({"status": "success", "data": result})
    except Exception as e:
        return jsonify({"status": "failure", "msg": str(e)}), 404


# -------------------------------------------------------------------------------------------
@app.route('/api/', methods=["GET"])
def getStatus():
    return main.fetchStatus(), 200


@app.route('/api/corsi', methods=["GET"])
def getCorso():
    return main.fetchCorso(), 200


@app.route('/api/corsi/<int:id>', methods=["GET"])
def getId(id: int):
    try:
        return main.fetchId(id), 200
    except ValueError as e:
        return jsonify({"msg": str(e)}), 404


if __name__ == "__main__":
    app.run(debug=True, port=5000)
