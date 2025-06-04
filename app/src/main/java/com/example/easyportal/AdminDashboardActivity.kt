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
import android.util.Log
import org.json.JSONException

class AdminDashboardActivity : AppCompatActivity() {

    private var adminEmail: String? = null
    private var adminRole: String = "admin"

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_admin_dashboard)

        adminEmail = intent.getStringExtra("email")
        adminRole = intent.getStringExtra("role") ?: "admin"

        val backButton = findViewById<ImageView>(R.id.back_button)
        backButton.setOnClickListener {
            onBackPressed()
        }

        val logsButton = findViewById<Button>(R.id.btn_logs)
        logsButton.setOnClickListener {
            val intent = Intent(this, LogActivity::class.java)
            intent.putExtra("email", adminEmail)
            startActivity(intent)
        }

        val openGateButton = findViewById<Button>(R.id.btn_portail)
        openGateButton.setOnClickListener {
            openPortal()
        }

        val viewCameraButton = findViewById<Button>(R.id.btn_camera)
        viewCameraButton.setOnClickListener {
            viewCameraFeed()
        }

        val gestionPlaquesButton = findViewById<Button>(R.id.btn_plaques)
        gestionPlaquesButton.setOnClickListener {
            if (adminRole == "super_admin") {
                startActivity(Intent(this, SuperAdminPlaquesActivity::class.java))
            } else {
                startActivity(Intent(this, AdminPlaquesActivity::class.java))
            }

        }

        Toast.makeText(this, "Connecté en tant que : $adminRole", Toast.LENGTH_LONG).show()
    }

    private fun openPortal() {
        val raspberryPiUrl = "http://172.16.15.39:5051/open_gate"

        val requestQueue = Volley.newRequestQueue(this)
        val jsonObjectRequest = JsonObjectRequest(
            Request.Method.POST, raspberryPiUrl, null,
            Response.Listener {
                Toast.makeText(this, "Portail ouvert avec succès!", Toast.LENGTH_SHORT).show()
            },
            Response.ErrorListener { error ->
                Toast.makeText(this, "Erreur: ${error.message}", Toast.LENGTH_SHORT).show()
            }
        )
        requestQueue.add(jsonObjectRequest)
    }

    private fun viewCameraFeed() {
        val intent = Intent(this, CameraFeedActivity::class.java)
        startActivity(intent)
    }
}
