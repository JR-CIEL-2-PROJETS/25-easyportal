package com.example.easyportal

import android.content.Intent
import android.os.Bundle
import android.util.Log
import android.widget.*
import androidx.appcompat.app.AppCompatActivity
import okhttp3.*
import org.json.JSONObject
import java.io.IOException

class LoginActivity : AppCompatActivity() {

    private lateinit var email: EditText
    private lateinit var password: EditText
    private lateinit var loginButton: Button
    private lateinit var forgotPassword: TextView
    private lateinit var showPassword: ImageView
    private var isPasswordVisible = false

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_login)

        // Initialisation des champs
        email = findViewById(R.id.email)
        password = findViewById(R.id.password)
        loginButton = findViewById(R.id.login_button)
        forgotPassword = findViewById(R.id.forgot_password)
        showPassword = findViewById(R.id.show_password)

        // Afficher/Masquer le mot de passe
        showPassword.setOnClickListener {
            togglePasswordVisibility(password, showPassword)
        }

        // Connexion via API
        loginButton.setOnClickListener {
            val userEmail = email.text.toString()
            val userPassword = password.text.toString()
            login(userEmail, userPassword)
        }

        // Fonction mot de passe oublié (ici juste un toast pour démonstration)
        forgotPassword.setOnClickListener {
            Toast.makeText(this@LoginActivity, "Mot de passe oublié cliqué", Toast.LENGTH_SHORT).show()
        }
    }

    private fun togglePasswordVisibility(editText: EditText, icon: ImageView) {
        if (isPasswordVisible) {
            editText.inputType = android.text.InputType.TYPE_CLASS_TEXT or android.text.InputType.TYPE_TEXT_VARIATION_PASSWORD
            icon.setImageResource(R.drawable.oeil)  // Icône "œil fermé"
        } else {
            editText.inputType = android.text.InputType.TYPE_CLASS_TEXT or android.text.InputType.TYPE_TEXT_VARIATION_VISIBLE_PASSWORD
            icon.setImageResource(R.drawable.oeil)  // Icône "œil ouvert"
        }
        isPasswordVisible = !isPasswordVisible
        editText.setSelection(editText.text.length) // Garde le curseur à la fin
    }

    private fun login(email: String, password: String) {
        // Envoi de la requête avec les paramètres username et password dans l'URL
        val loginUrl = "https://4db7e0eb-4ed7-4f36-8568-cbdb4e93af75.mock.pstmn.io/connexion/user1?username=$email&password=$password"
        // Pour l'admin, utilise cette URL :
        // val loginUrl = "https://4db7e0eb-4ed7-4f36-8568-cbdb4e93af75.mock.pstmn.io/connexion/admin1?username=$email&password=$password"

        val client = OkHttpClient()
        val request = Request.Builder()
            .url(loginUrl)
            .get()  // Utilisation de GET pour envoyer l'URL avec les paramètres
            .addHeader("Content-Type", "application/json")
            .build()

        client.newCall(request).enqueue(object : Callback {
            override fun onFailure(call: Call, e: IOException) {
                runOnUiThread {
                    Toast.makeText(this@LoginActivity, "Erreur de connexion", Toast.LENGTH_SHORT).show()
                    Log.e("LoginActivity", "Erreur de connexion: ${e.message}")
                }
            }

            override fun onResponse(call: Call, response: Response) {
                val responseBody = response.body?.string() // Lire la réponse

                runOnUiThread {
                    if (response.isSuccessful) {
                        try {
                            // Analyser la réponse JSON pour obtenir le rôle
                            val jsonResponse = JSONObject(responseBody)
                            val userRole = jsonResponse.getString("role")

                            // Redirection selon le rôle
                            if (userRole == "admin") {
                                // Rediriger vers le tableau de bord admin
                                val intent = Intent(this@LoginActivity, AdminDashboardActivity::class.java)
                                startActivity(intent)
                                finish()
                            } else if (userRole == "user") {
                                // Rediriger vers le tableau de bord utilisateur
                                val intent = Intent(this@LoginActivity, UserDashboardActivity::class.java)
                                startActivity(intent)
                                finish()
                            } else {
                                Toast.makeText(this@LoginActivity, "Rôle inconnu", Toast.LENGTH_SHORT).show()
                            }
                        } catch (e: Exception) {
                            e.printStackTrace()
                            Toast.makeText(this@LoginActivity, "Erreur d'analyse de la réponse", Toast.LENGTH_SHORT).show()
                        }
                    } else {
                        Toast.makeText(this@LoginActivity, "Erreur de connexion: ${responseBody}", Toast.LENGTH_LONG).show()
                        Log.e("LoginActivity", "Erreur de connexion: $responseBody")
                    }
                }
            }
        })
    }
}
