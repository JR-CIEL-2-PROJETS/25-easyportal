import serial
import time

# Initialiser la liaison série avec Arduino
try:
    arduino = serial.Serial('/dev/ttyACM0', 9600, timeout=1)
    print(" Liaison série Arduino établie sur /dev/ttyACM0")
except serial.SerialException as e:
    print(f" Erreur liaison série Arduino : {e}")
    exit(1)

def envoyer_commande(commande):
    if arduino and arduino.is_open:
        arduino.write(commande.encode())
        print(f"Commande envoyée : {commande}")
    else:
        print(" Liaison série Arduino non disponible")

# Exemple d'utilisation
envoyer_commande('1')  # Commande pour ouvrir le portail

# Fermer la liaison série
arduino.close()
