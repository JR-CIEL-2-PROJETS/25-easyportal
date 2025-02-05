package com.example.easyportal

import android.os.Bundle
import android.view.View
import android.widget.Button
import android.widget.ImageView
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import com.android.volley.Request
import com.android.volley.Response
import com.android.volley.toolbox.JsonObjectRequest
import com.android.volley.toolbox.Volley
import org.json.JSONException

class UserDashboardActivity : AppCompatActivity() {

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_user_dashboard)  // Assure-toi que le nom du layout est correct

        // Récupérer le bouton "Ouvrir le portail"
        val openGateButton = findViewById<Button>(R.id.open_gate_button)

        // Ajouter un listener pour le clic sur le bouton
        openGateButton.setOnClickListener {
            // Appel de la fonction pour envoyer la requête
            openGate()
        }

        // Récupérer le bouton "Retour"
        val backButton = findViewById<ImageView>(R.id.back_button)

        // Ajouter un listener pour le clic sur le bouton de retour
        backButton.setOnClickListener {
            // Appeler la fonction de retour
            onBackPressed()  // Retourne à l'activité précédente
        }
    }

    private fun openGate() {
        // L'URL de la requête
        val url = "https://aa8ef8d1-a278-416a-9cc9-85baa14b5d59.mock.pstmn.io/Dashboard/Portail"

        // Créer une requête GET avec Volley
        val requestQueue = Volley.newRequestQueue(this)

        val jsonObjectRequest = JsonObjectRequest(Request.Method.GET, url, null,
            Response.Listener { response ->
                // Traitement de la réponse
                try {
                    // Ici, tu peux gérer la réponse reçue si nécessaire
                    // Par exemple, afficher un message de succès
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

        // Ajouter la requête à la file d'attente
        requestQueue.add(jsonObjectRequest)
    }
}
