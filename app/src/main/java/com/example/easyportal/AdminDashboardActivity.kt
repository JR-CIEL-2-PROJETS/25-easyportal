package com.example.easyportal

import android.content.Intent
import android.os.Bundle
import android.widget.Button
import android.widget.ImageView
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import com.android.volley.Request
import com.android.volley.Response
import com.android.volley.toolbox.JsonObjectRequest
import com.android.volley.toolbox.Volley
import org.json.JSONException

class AdminDashboardActivity : AppCompatActivity() {

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_admin_dashboard)

        // Gérer le clic sur le bouton "back" pour revenir à l'écran précédent
        val backButton = findViewById<ImageView>(R.id.back_button)
        backButton.setOnClickListener {
            onBackPressed()  // Revient à l'écran précédent
        }

        // Gérer le clic sur le bouton "Logs"
        val logsButton = findViewById<Button>(R.id.btn_logs)
        logsButton.setOnClickListener {
            // Créer un Intent pour démarrer l'activité des logs
            val intent = Intent(this, LogActivity::class.java)
            startActivity(intent)  // Lancer l'activité des logs
        }

        // Gérer le clic sur le bouton "Ouvrir le portail"
        val openGateButton = findViewById<Button>(R.id.btn_portail)
        openGateButton.setOnClickListener {
            // Appeler la fonction pour envoyer la requête d'ouverture du portail
            openPortal()
        }

        // Gérer le clic sur le bouton "Voir le flux caméra"
        val viewCameraButton = findViewById<Button>(R.id.btn_camera)
        viewCameraButton.setOnClickListener {
            // Ouvrir un WebView ou une autre activité pour afficher le flux vidéo
            viewCameraFeed()
        }
    }

    // Fonction pour ouvrir le portail
    private fun openPortal() {
        val raspberryPiUrl = "http://172.16.15.39:5050/open_gate"  // Remplace par l'URL correcte

        // Créer une requête POST pour ouvrir le portail
        val requestQueue = Volley.newRequestQueue(this)
        val jsonObjectRequest = JsonObjectRequest(Request.Method.POST, raspberryPiUrl, null,
            Response.Listener { response ->
                // Traitement de la réponse
                try {
                    // Afficher un message de succès
                    Toast.makeText(this, "Portail ouvert avec succès!", Toast.LENGTH_SHORT).show()
                } catch (e: JSONException) {
                    // Gérer les erreurs JSON si nécessaire
                    Toast.makeText(this, "Erreur lors de l'ouverture du portail.", Toast.LENGTH_SHORT).show()
                }
            },
            Response.ErrorListener { error ->
                // Gérer les erreurs de la requête
                Toast.makeText(this, "Erreur: ${error.message}", Toast.LENGTH_SHORT).show()
            }
        )
        requestQueue.add(jsonObjectRequest)
    }

    // Fonction pour voir le flux caméra
    private fun viewCameraFeed() {
        // Lancer un WebView ou une activité pour afficher le flux vidéo
        val intent = Intent(this, CameraFeedActivity::class.java)
        startActivity(intent)  // Lancer une nouvelle activité pour afficher le flux caméra
    }
}
