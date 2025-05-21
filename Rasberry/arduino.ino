
#include <NewPing.h>

// Broches moteur (PWM)
const int IN1 = 5;
const int IN2 = 6;
const int motorSpeed = 50;

// Broches LED
const int LED_ROUGE = 3;
const int LED_VERTE = 2;

// Capteur ultrason
#define TRIG_PIN 9
#define ECHO_PIN 10
#define MAX_DISTANCE 200
NewPing sonar(TRIG_PIN, ECHO_PIN, MAX_DISTANCE);

// Suivi de l'état du portail
bool estOuvert = false;  // false = fermé, true = ouvert

void setup() {
  pinMode(IN1, OUTPUT);
  pinMode(IN2, OUTPUT);
  pinMode(LED_ROUGE, OUTPUT);
  pinMode(LED_VERTE, OUTPUT);

  digitalWrite(LED_ROUGE, HIGH); // au début, on suppose le portail fermé
  digitalWrite(LED_VERTE, LOW);

  Serial.begin(9600);
  Serial.println("Commande : 1 = ouvrir, 0 = fermer");
}

void loop() {
  // Détection automatique par capteur ultrason
  int distance = sonar.ping_cm();
  if (distance > 0 && distance < 10 && estOuvert) {
    Serial.println("Détection : attente avant fermeture automatique...");
    delay(2000); // Délai de 2 secondes avant la fermeture
    Serial.println("Fermeture automatique...");
    fermerPortail(1900); // Ferme pendant 2 secondes
  }

  // Commandes manuelles
  if (Serial.available()) {
    char cmd = Serial.read();
    if (cmd == '1') {
      Serial.println("Ouverture manuelle...");
      ouvrirPortail(1800); // Ouvre pendant 2 secondes
    } else if (cmd == '0') {
      Serial.println("Fermeture manuelle..1.");
      fermerPortail(1900); // Ferme pendant 2 secondes
    }
  }
}

void ouvrirPortail(unsigned long duree) {
  analogWrite(IN1, motorSpeed);
  analogWrite(IN2, 0);
  digitalWrite(LED_ROUGE, HIGH); // LED rouge allumée pendant le mouvement
  digitalWrite(LED_VERTE, LOW);
  delay(duree);
  stopMotor();

  estOuvert = true; // état mis à jour
  digitalWrite(LED_VERTE, HIGH); // LED verte allumée lorsque le portail est ouvert
  digitalWrite(LED_ROUGE, LOW);

  Serial.println("Portail ouvert !");
}

void fermerPortail(unsigned long duree) {
  analogWrite(IN1, 0);
  analogWrite(IN2, motorSpeed);
  digitalWrite(LED_ROUGE, HIGH); // LED rouge allumée pendant le mouvement
  digitalWrite(LED_VERTE, LOW);
  delay(duree);
  stopMotor();

  estOuvert = false; // état mis à jour
  digitalWrite(LED_VERTE, LOW); // LED verte éteinte lorsque le portail est fermé
  digitalWrite(LED_ROUGE, HIGH);

  Serial.println("Portail fermé !");
}

void stopMotor() {
  analogWrite(IN1, 0);
  analogWrite(IN2, 0);
}
