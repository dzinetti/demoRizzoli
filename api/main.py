from flask import Flask, jsonify
import psycopg2
import logging
import qrcode
from pathlib import Path

app = Flask(__name__)

def get_connection():
    try:
        connection = psycopg2.connect(
            user="webapp",
            password="password",
            host="10.0.0.225",
            port=5432,
            dbname="rizzoli"
        )
        connection.autocommit = True
        return connection
    except Exception as e:
        logging.error(e)
        return None

def generate_qr_code(data: dict):
    import json
    img = qrcode.make(json.dumps(data))
    qr_dir = Path(__file__).resolve().parent / "static" / "qrcodes"
    qr_dir.mkdir(parents=True, exist_ok=True)
    booking_code = data.get("booking_code") or f"EVT-{data.get('booking_id', data.get('event_id', 'unknown'))}"
    img.save(qr_dir / f"{booking_code}.png")
    return f"http://localhost:5000/static/qrcodes/{booking_code}.png"

corsi = [
    {'nome': 'italiano', 'orario': '12', 'classe': '5f', 'id': 1},
    {'nome': 'matematica', 'orario': '11', 'classe': '4f', 'id': 2},
    {'nome': 'inglese', 'orario': '10', 'classe': '4h', 'id': 3},
    {'nome': 'tedesco', 'orario': '9', 'classe': '3d', 'id': 4}
]

def fetch_eventi_from_db():
    conn = get_connection()
    if not conn:
        return []
    try:
        with conn.cursor() as cursor:
            query = "SELECT id_evento, titolo, descrizione, tipologia FROM evento"
            cursor.execute(query)
            output = []
            for row in cursor.fetchall():
                output.append({"id_evento": row[0], "titolo": row[1], "descrizione": row[2], "tipologia": row[3]})
            return output
    except Exception as e:
        return e
    finally:
        conn.close()

def fetch_evento_from_db(id_evento):
    conn = get_connection()
    if not conn:
        raise Exception("Database non raggiungibile")
    try:
        with conn.cursor() as cursor:
            query = "SELECT id_evento, titolo, descrizione, tipologia FROM evento WHERE id_evento = %s"
            cursor.execute(query, (id_evento,))
            row = cursor.fetchone()
            if row:
                return {"id_evento": row[0], "titolo": row[1], "descrizione": row[2], "tipologia": row[3]}
            raise IndexError("Evento non trovato")
    except Exception as e:
        return e
    finally:
        conn.close()

def add_evento_to_db(titolo, descrizione, tipologia):
    conn = get_connection()
    if not conn:
        raise Exception("Database non raggiungibile")
    try:
        with conn.cursor() as cursor:
            query = "INSERT INTO evento (titolo, descrizione, tipologia) VALUES (%s, %s, %s) RETURNING id_evento"
            cursor.execute(query, (titolo, descrizione, tipologia))
            inserted_id = cursor.fetchone()[0]
            return inserted_id
    except Exception as e:
        return e
    finally:
        conn.close()

def fetch_prenotazioni_from_db():
    conn = get_connection()
    if not conn:
        return []
    try:
        with conn.cursor() as cursor:
            query = """
                SELECT id_prenotazione, data, ora_inizio, ora_fine, stato, id_utente, id_spazio, id_evento
                FROM prenotazione
            """
            cursor.execute(query)
            output = []
            for row in cursor.fetchall():
                output.append({
                    "id_prenotazione": row[0],
                    "data": str(row[1]),
                    "ora_inizio": str(row[2]),
                    "ora_fine": str(row[3]),
                    "stato": row[4],
                    "id_utente": row[5],
                    "id_spazio": row[6],
                    "id_evento": row[7]
                })
            return output
    except Exception as e:
        return e
    finally:
        conn.close()

def fetch_prenotazione_from_db(id_prenotazione):
    conn = get_connection()
    if not conn:
        raise Exception("Database non raggiungibile")
    try:
        with conn.cursor() as cursor:
            query = """
                SELECT id_prenotazione, data, ora_inizio, ora_fine, stato, id_utente, id_spazio, id_evento
                FROM prenotazione
                WHERE id_prenotazione = %s
            """
            cursor.execute(query, (id_prenotazione,))
            row = cursor.fetchone()
            if row:
                return {
                    "id_prenotazione": row[0],
                    "data": str(row[1]),
                    "ora_inizio": str(row[2]),
                    "ora_fine": str(row[3]),
                    "stato": row[4],
                    "id_utente": row[5],
                    "id_spazio": row[6],
                    "id_evento": row[7]
                }
            raise IndexError("Prenotazione non trovata")
    except Exception as e:
        return e
    finally:
        conn.close()

def add_prenotazione_to_db(data, ora_inizio, ora_fine, stato, id_utente, id_spazio, id_evento):
    conn = get_connection()
    if not conn:
        raise Exception("Database non raggiungibile")
    try:
        with conn.cursor() as cursor:
            query = """
                INSERT INTO prenotazione (data, ora_inizio, ora_fine, stato, id_utente, id_spazio, id_evento)
                VALUES (%s, %s, %s, %s, %s, %s, %s)
                RETURNING id_prenotazione
            """
            values = (data, ora_inizio, ora_fine, stato, id_utente, id_spazio, id_evento)
            cursor.execute(query, values)
            inserted_id = cursor.fetchone()[0]
            return inserted_id
    except Exception as e:
        return e
    finally:
        conn.close()

def fetch_ruoli_from_db():
    conn = get_connection()
    if not conn:
        return []
    try:
        with conn.cursor() as cursor:
            query = "SELECT id_ruolo, nome, descrizione FROM ruolo"
            cursor.execute(query)
            output = []
            for row in cursor.fetchall():
                output.append({"id": row[0], "nome": row[1], "descrizione": row[2]})
            if output:
                return output
            raise Exception("Nessun ruolo trovato")
    except Exception as e:
        return e
    finally:
        conn.close()

def fetch_ruolo_from_db(id):
    conn = get_connection()
    if not conn:
        raise Exception("Database non raggiungibile")
    try:
        with conn.cursor() as cursor:
            query = "SELECT nome, descrizione FROM ruolo WHERE id_ruolo = %s"
            cursor.execute(query, (id,))
            output = cursor.fetchone()
            if output:
                return {"nome": output[0], "descrizione": output[1]}
            raise IndexError("Ruolo non trovato")
    except Exception as e:
        return e
    finally:
        conn.close()

def add_ruolo_to_db(nome, descrizione):
    conn = get_connection()
    if not conn:
        raise Exception("Database non raggiungibile")
    try:
        with conn.cursor() as cursor:
            query = "INSERT INTO ruolo (nome, descrizione) VALUES (%s, %s)"
            cursor.execute(query, (nome, descrizione))
            return 200
    except Exception as e:
        return e
    finally:
        conn.close()

def fetch_utenti_from_db():
    conn = get_connection()
    if not conn:
        return []
    try:
        with conn.cursor() as cursor:
            query = "SELECT id_utente, nome, cognome, id_ruolo FROM utente"
            cursor.execute(query)
            output = []
            for row in cursor.fetchall():
                output.append({"id": row[0], "nome": row[1], "cognome": row[2], "id_ruolo": row[3]})
            return output
    except Exception as e:
        return e
    finally:
        conn.close()

def add_utente_to_db(nome, cognome, email, password, id_ruolo):
    conn = get_connection()
    if not conn:
        raise Exception("Database non raggiungibile")
    try:
        with conn.cursor() as cursor:
            query = "INSERT INTO utente (nome, cognome, email, password, id_ruolo) VALUES (%s, %s, %s, %s, %s)"
            cursor.execute(query, (nome, cognome, email, password, id_ruolo))
            return 200
    except Exception as e:
        return e
    finally:
        conn.close()

def fetch_utente_from_db(id):
    conn = get_connection()
    if not conn:
        raise Exception("Database non raggiungibile")
    try:
        with conn.cursor() as cursor:
            query = "SELECT nome, cognome, id_ruolo FROM utente WHERE id_utente = %s"
            cursor.execute(query, (id,))
            output = cursor.fetchone()
            if output:
                return {"nome": output[0], "cognome": output[1], "id_ruolo": output[2]}
            raise IndexError("Utente non trovato")
    except Exception as e:
        return e
    finally:
        conn.close()

def fetch_spazi_from_db():
    conn = get_connection()
    if not conn:
        return []
    try:
        with conn.cursor() as cursor:
            query = "SELECT id_spazio, nome_spazio, tipologia, capienza_massima FROM spazio"
            cursor.execute(query)
            output = []
            for row in cursor.fetchall():
                output.append({
                    "id_spazio": row[0],
                    "nome_spazio": row[1],
                    "tipologia": row[2],
                    "capienza_massima": row[3]
                })
            return output
    except Exception as e:
        return e
    finally:
        conn.close()

def add_spazio_to_db(nome_spazio, tipologia, capienza_massima):
    conn = get_connection()
    if not conn:
        raise Exception("Database non raggiungibile")
    try:
        with conn.cursor() as cursor:
            query = "INSERT INTO spazio (nome_spazio, tipologia, capienza_massima) VALUES (%s, %s, %s) RETURNING id_spazio"
            cursor.execute(query, (nome_spazio, tipologia, capienza_massima))
            return cursor.fetchone()[0]
    except Exception as e:
        return e
    finally:
        conn.close()

def fetch_spazio_from_db(id):
    conn = get_connection()
    if not conn:
        raise Exception("Database non raggiungibile")
    try:
        with conn.cursor() as cursor:
            query = "SELECT id_spazio, nome_spazio, tipologia, capienza_massima FROM spazio WHERE id_spazio = %s"
            cursor.execute(query, (id,))
            output = cursor.fetchone()
            if output:
                return {
                    "id_spazio": output[0],
                    "nome_spazio": output[1],
                    "tipologia": output[2],
                    "capienza_massima": output[3]
                }
            raise IndexError("Spazio non trovato")
    except Exception as e:
        return e
    finally:
        conn.close()

def fetchStatus():
    return [{'status': 'online'}]

def fetchCorso():
    return corsi

def fetchId(id):
    corsetto = {"Corso": "inesistente"}
    for corso in corsi:
        if corso['id'] == id:
            corsetto = corso
    return corsetto