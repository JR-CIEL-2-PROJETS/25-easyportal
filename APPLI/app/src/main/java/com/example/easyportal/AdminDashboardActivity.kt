package com.example.easyportal

import android.content.Intent
import android.os.Bundle
import android.widget.Button
import android.widget.ImageView
import androidx.appcompat.app.AppCompatActivity

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
    }
}
