// UserDashboardActivity.kt
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

class UserDashboardActivity : AppCompatActivity() {

    private lateinit var userEmail: String

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_user_dashboard)

        userEmail = intent.getStringExtra("email") ?: ""

        val openGateButton = findViewById<Button>(R.id.open_gate_button)
        openGateButton.setOnClickListener {
            openGate()
        }

        val backButton = findViewById<ImageView>(R.id.back_button)
        backButton.setOnClickListener {
            onBackPressed()
        }

        val viewPlatesButton = findViewById<Button>(R.id.view_plates_button)
        viewPlatesButton.setOnClickListener {
            val intent = Intent(this, PlaquesActivity::class.java)
            intent.putExtra("email", userEmail)
            startActivity(intent)
        }
    }

    private fun openGate() {
        val url = "http://172.16.15.39:5050/open_gate"

        val requestQueue = Volley.newRequestQueue(this)

        val jsonObjectRequest = JsonObjectRequest(Request.Method.POST, url, null,
            Response.Listener { response ->
                try {
                    val message = response.optString("message", "Portail ouvert avec succès.")
                    Toast.makeText(this, message, Toast.LENGTH_SHORT).show()
                } catch (e: JSONException) {
                    Toast.makeText(this, "Erreur lors de l'ouverture du portail.", Toast.LENGTH_SHORT).show()
                }
            },
            Response.ErrorListener { error ->
                Toast.makeText(this, "Erreur: ${error.message}", Toast.LENGTH_SHORT).show()
            }
        )

        requestQueue.add(jsonObjectRequest)
    }
}
