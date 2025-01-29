package com.example.easyportal

import android.content.Intent
import android.os.Bundle
import android.util.Log
import android.widget.*
import androidx.appcompat.app.AppCompatActivity
import okhttp3.*
import org.json.JSONObject
import java.io.IOException
import okhttp3.MediaType.Companion.toMediaTypeOrNull
import okhttp3.RequestBody.Companion.toRequestBody

class RegisterActivity : AppCompatActivity() {

    private lateinit var etNom: EditText
    private lateinit var etPrenom: EditText
    private lateinit var etEmail: EditText
    private lateinit var etPassword: EditText
    private lateinit var etConfirmPassword: EditText
    private lateinit var showPassword: ImageView
    private lateinit var showConfirmPassword: ImageView
    private lateinit var backButton: ImageView
    private lateinit var registerButton: Button
    private var isPasswordVisible = false
    private var isConfirmPasswordVisible = false

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_register)

        // Initialisation des champs
        etNom = findViewById(R.id.nom)
        etPrenom = findViewById(R.id.prenom)
        etEmail = findViewById(R.id.email)
        etPassword = findViewById(R.id.password)
        etConfirmPassword = findViewById(R.id.confirm_password)
        showPassword = findViewById(R.id.show_password)
        showConfirmPassword = findViewById(R.id.show_confirm_password)
        backButton = findViewById(R.id.back_button)
        registerButton = findViewById(R.id.register_button)

        // Afficher/Masquer le mot de passe
        showPassword.setOnClickListener { togglePasswordVisibility(etPassword, showPassword) }
        showConfirmPassword.setOnClickListener { togglePasswordVisibility(etConfirmPassword, showConfirmPassword) }

        // Bouton Retour
        backButton.setOnClickListener { finish() }

        // Bouton Register
        registerButton.setOnClickListener { sendRegisterRequest() }
    }

    private fun togglePasswordVisibility(editText: EditText, icon: ImageView) {
        if (isPasswordVisible) {
            editText.inputType = 129 // TYPE_TEXT_VARIATION_PASSWORD
            icon.setImageResource(R.drawable.oeil) // Icône "œil fermé"
        } else {
            editText.inputType = 145 // TYPE_TEXT_VARIATION_VISIBLE_PASSWORD
            icon.setImageResource(R.drawable.oeil) // Icône "œil ouvert"
        }
        isPasswordVisible = !isPasswordVisible
        editText.setSelection(editText.text.length) // Garde le curseur à la fin
    }

    private fun sendRegisterRequest() {
        val url = "https://4db7e0eb-4ed7-4f36-8568-cbdb4e93af75.mock.pstmn.io/connexion/register" +
                "?nom=${etNom.text}" +
                "&prenom=${etPrenom.text}" +
                "&email=${etEmail.text}" +
                "&password=${etPassword.text}"

        val request = Request.Builder()
            .url(url)
            .get() // Utilisation de GET au lieu de POST
            .addHeader("Content-Type", "application/json") // Si nécessaire
            .build()

        val client = OkHttpClient()

        client.newCall(request).enqueue(object : Callback {
            override fun onFailure(call: Call, e: IOException) {
                runOnUiThread {
                    Toast.makeText(this@RegisterActivity, "Erreur de connexion", Toast.LENGTH_SHORT).show()
                    Log.e("RegisterActivity", "Erreur de connexion: ${e.message}")
                }
            }

            override fun onResponse(call: Call, response: Response) {
                val responseBody = response.body?.string() // Lire la réponse

                runOnUiThread {
                    if (response.isSuccessful) {
                        Toast.makeText(this@RegisterActivity, "Inscription réussie!", Toast.LENGTH_SHORT).show()
                        Log.d("RegisterActivity", "Lancement de SuccessActivity")

                        val intent = Intent(this@RegisterActivity, SuccessActivity::class.java)
                        startActivity(intent)
                        finish()
                    } else {
                        Toast.makeText(this@RegisterActivity, "Erreur d'inscription: $responseBody", Toast.LENGTH_LONG).show()
                        Log.e("RegisterActivity", "Erreur d'inscription: $responseBody")
                    }
                }
            }
        })
    }
}
