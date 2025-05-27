package com.example.easyportal

import android.app.AlertDialog
import android.content.Context
import android.content.Intent
import android.os.Bundle
import android.view.LayoutInflater
import android.widget.Button
import android.widget.EditText
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity

class MainActivity : AppCompatActivity() {

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)

        val loginButton: Button = findViewById(R.id.login_button)
        val registerButton: Button = findViewById(R.id.register_button)
        val configureApiButton: Button = findViewById(R.id.configure_api_button)

        loginButton.setOnClickListener {
            startActivity(Intent(this, LoginActivity::class.java))
        }

        registerButton.setOnClickListener {
            startActivity(Intent(this, RegisterActivity::class.java))
        }

        configureApiButton.setOnClickListener {
            showApiConfigPopup()
        }
    }

    private fun showApiConfigPopup() {
        val dialogView = LayoutInflater.from(this).inflate(R.layout.dialog_api_config, null)
        val editText = dialogView.findViewById<EditText>(R.id.api_edit_text)

        // Pré-remplir avec la valeur existante
        val sharedPref = getSharedPreferences("app_config", Context.MODE_PRIVATE)
        editText.setText(sharedPref.getString("api_url", ""))

        val dialog = AlertDialog.Builder(this)
            .setTitle("Configurer l'API")
            .setView(dialogView)
            .setPositiveButton("Enregistrer") { _, _ ->
                val input = editText.text.toString().trim()
                if (input.isEmpty()) {
                    Toast.makeText(this, "L'adresse ne peut pas être vide", Toast.LENGTH_SHORT).show()
                    return@setPositiveButton
                }
                sharedPref.edit().putString("api_url", input).apply()
                Toast.makeText(this, "Adresse API enregistrée", Toast.LENGTH_SHORT).show()
            }
            .setNegativeButton("Annuler", null)
            .create()

        dialog.show()
    }
}
