
import RPi.GPIO as GPIO
import time
import subprocess
import re
import cv2
from flask import Flask, request, Response
from picamera2 import Picamera2
import threading
import mysql.connector
from datetime import datetime
import serial
from flask_cors import CORS

# Configuration du serveur Flask
app = Flask(__name__)
CORS(app)  # Activer CORS pour toutes les routes

# Configuration de la caméra
camera = Picamera2()
camera.configure(camera.create_preview_configuration(main={"format": 'XRGB8888', "size": (640, 480)}))
camera.start()

def generate_frames():
    while True:
        frame = camera.capture_array()
        ret, buffer = cv2.imencode('.jpg', frame)
        frame = buffer.tobytes()
        yield (b'--frame\r\n'
               b'Content-Type: image/jpeg\r\n\r\n' + frame + b'\r\n')

@app.route('/video')
def video():
    return Response(generate_frames(), mimetype='multipart/x-mixed-replace; boundary=frame')

# Point de terminaison pour ouvrir le portail
API_KEY = "3rQ7hekyd3kav7pLM7nkHOKAVGYVAqEq"  # Remplacez par une clé sécurisée

@app.route('/open_gate', methods=['POST'])
def open_gate():
    api_key = request.headers.get('X-API-KEY')
    if api_key != API_KEY:
        return "Non autorisé", 401

    try:
        envoyer_commande('1')  # Commande pour ouvrir le portail
        return "Portail ouvert avec succès", 200
    except Exception as e:
        return f"Échec de l'ouverture du portail: {str(e)}", 500

def run_flask():
    app.run(host='0.0.0.0', port=5000)

# Configuration des broches GPIO pour le capteur ultrasonique
TRIG_PIN = 23  # Broche GPIO pour TRIG
ECHO_PIN = 24  # Broche GPIO pour ECHO

# Configurer le mode des broches
GPIO.setmode(GPIO.BCM)
GPIO.setup(TRIG_PIN, GPIO.OUT)
GPIO.setup(ECHO_PIN, GPIO.IN)
GPIO.output(TRIG_PIN, GPIO.LOW)

DISTANCE_SEUIL = 10  # seuil distance en cm

print("Détection activée, serveur Flask en cours...")

# Lancer Flask dans un thread séparé
flask_thread = threading.Thread(target=run_flask)
flask_thread.daemon = True
flask_thread.start()

# Variables pour gérer l’état du portail
portail_ouvert = False
dernier_temps_ouverture = 0

def insert_plate_to_db(plate):
    try:
        connection = mysql.connector.connect(
            host='51.210.151.13',
            user='easyportal2025',
            password='EasyPortal2025!',
            database='easyportal2025'
        )
        cursor = connection.cursor()
        insert_query = """
            INSERT INTO plaques (numero, user_id, statut, date_ajout)
            VALUES (%s, %s, %s, %s)
        """
        current_time = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
        data = (plate, None, 'actif', current_time)
        cursor.execute(insert_query, data)
        connection.commit()
        print(f" Plaque '{plate}' insérée dans la base de données.")
    except mysql.connector.Error as err:
        if err.errno == 1062:
            print(f"Plaque '{plate}' déjà enregistrée.")
        else:
            print(f" Erreur MySQL : {err}")
    finally:
        if connection.is_connected():
            cursor.close()
            connection.close()

def is_plate_in_db(plate):
    """Vérifie si la plaque est déjà dans la base"""
    try:
        connection = mysql.connector.connect(
            host='51.210.151.13',
            user='easyportal2025',
            password='EasyPortal2025!',
            database='easyportal2025'
        )
        cursor = connection.cursor()
        query = "SELECT COUNT(*) FROM plaques WHERE numero = %s AND statut = 'actif'"
        cursor.execute(query, (plate,))
        count = cursor.fetchone()[0]
        return count > 0
    except mysql.connector.Error as err:
        print(f" Erreur MySQL lors de la vérification : {err}")
        return False
    finally:
        if connection.is_connected():
            cursor.close()
            connection.close()

def run_alpr_and_extract_plate(image_path):
    global portail_ouvert, dernier_temps_ouverture
    result = subprocess.run(['alpr', '-c', 'fr', image_path], capture_output=True, text=True)
    output = result.stdout

    pattern = re.compile(r'([A-Z0-9]+)\s+confidence:\s+([0-9.]+)')
    matches = pattern.findall(output)

    if matches:
        best_match = max(matches, key=lambda x: float(x[1]))
        plate, confidence = best_match

        if len(plate) >= 5:
            formatted_plate = f"{plate[:2]}-{plate[2:5]}-{plate[5:]}"
        else:
            formatted_plate = plate

        print(f"Plaque détectée: {formatted_plate} (Confiance: {confidence})")

        # Insérer la plaque même si pas en base
        insert_plate_to_db(formatted_plate)

        # Vérifier si plaque est autorisée en base
        if is_plate_in_db(formatted_plate):
            print(" Plaque autorisée : ouverture portail")

            # Exécuter le script portail.py
            envoyer_commande('1')  # Commande pour ouvrir le portail

            portail_ouvert = True
            dernier_temps_ouverture = time.time()
            time.sleep(6)  # maintien ouverture 6 sec
        else:
            print("⚠ Plaque non autorisée : portail non ouvert")
    else:
        print("Aucune plaque détectée.")

def detect_vehicle():
    global portail_ouvert, dernier_temps_ouverture
    last_detection_time = 0
    countdown_active = False
    try:
        while True:
            current_time = time.time()

            # Déclenchement ultrason
            GPIO.output(TRIG_PIN, GPIO.HIGH)
            time.sleep(0.00001)
            GPIO.output(TRIG_PIN, GPIO.LOW)

            while GPIO.input(ECHO_PIN) == GPIO.LOW:
                pulse_start = time.time()
            while GPIO.input(ECHO_PIN) == GPIO.HIGH:
                pulse_end = time.time()

            pulse_duration = pulse_end - pulse_start
            distance = pulse_duration * 17150

            if current_time - last_detection_time > 5:  # Attendre 5 secondes avant la prochaine détection
                if distance < DISTANCE_SEUIL:
                    print("Véhicule détecté")
                    image_path = "/home/pi/img.jpg"
                    print(" Activation de la caméra pour 5 secondes...")

                    # Caméra déjà démarrée, capture flux
                    start_time = time.time()
                    while time.time() - start_time < 5:
                        frame = camera.capture_array()
                        cv2.imshow("Flux Caméra", frame)
                        cv2.waitKey(1)

                    camera.capture_file(image_path)
                    print(f"Image sauvegardée: {image_path}")

                    print("Lancement reconnaissance plaque...")
                    run_alpr_and_extract_plate(image_path)

                    last_detection_time = current_time
                    print("Détection terminée.\n")
                else:
                    print("Aucun véhicule détecté.")
            else:
                if countdown_active:
                    remaining_time = 5 - (current_time - last_detection_time)
                    print(f"Attente {int(remaining_time)}s avant prochaine détection...")

            if distance < DISTANCE_SEUIL and portail_ouvert:
                print("Fermeture du portail : véhicule détecté")
                time.sleep(6)  # Attendre 6 secondes avant de fermer le portail
                print("Portail fermé")
                countdown_active = True
                portail_ouvert = False
                last_detection_time = current_time

            time.sleep(1)
    except KeyboardInterrupt:
        print("Arrêt du programme")
        GPIO.cleanup()
        cv2.destroyAllWindows()

# Initialiser la liaison série avec Arduino
try:
    arduino = serial.Serial('/dev/ttyACM0', 9600, timeout=1)
except serial.SerialException as e:
    print(f" Erreur liaison série Arduino : {e}")
    exit(1)

def envoyer_commande(commande):
    if arduino and arduino.is_open:
        arduino.write(commande.encode())
    else:
        print(" Liaison série Arduino non disponible")

detection_thread = threading.Thread(target=detect_vehicle)
detection_thread.daemon = True
detection_thread.start()

while True:
    time.sleep(1)


