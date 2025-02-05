package com.example.easyportal

import android.content.Intent
import android.os.Bundle
import android.view.View
import android.widget.EditText
import android.widget.ImageView
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import com.android.volley.Request
import com.android.volley.Response
import com.android.volley.toolbox.JsonObjectRequest
import com.android.volley.toolbox.Volley
import org.json.JSONException

class LoginActivity : AppCompatActivity() {

    private lateinit var emailEditText: EditText
    private lateinit var passwordEditText: EditText
    private lateinit var showPasswordImageView: ImageView
    private var isPasswordVisible = false

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_login)

        emailEditText = findViewById(R.id.email)
        passwordEditText = findViewById(R.id.password)
        showPasswordImageView = findViewById(R.id.show_password)

        // Handle password visibility toggle
        showPasswordImageView.setOnClickListener {
            togglePasswordVisibility()
        }

        // When login button is clicked
        val loginButton = findViewById<View>(R.id.login_button)
        loginButton.setOnClickListener {
            val email = emailEditText.text.toString()
            val password = passwordEditText.text.toString()
            loginUser(email, password)
        }

        // Handle back button
        val backButton = findViewById<ImageView>(R.id.back_button)
        backButton.setOnClickListener {
            onBackPressed()  // Go back to the previous screen
        }
    }

    private fun togglePasswordVisibility() {
        if (isPasswordVisible) {
            // Hide password
            passwordEditText.transformationMethod = android.text.method.PasswordTransformationMethod.getInstance()
            showPasswordImageView.setImageResource(R.drawable.oeil)  // Change image to eye closed
        } else {
            // Show password
            passwordEditText.transformationMethod = null
            showPasswordImageView.setImageResource(R.drawable.oeil)  // Change image to eye open
        }
        isPasswordVisible = !isPasswordVisible
    }

    private fun loginUser(email: String, password: String) {
        // Tentative de connexion pour les utilisateurs
        val urlUser = "https://4db7e0eb-4ed7-4f36-8568-cbdb4e93af75.mock.pstmn.io/connexion/user1?email=$email&password=$password"
        // Tentative de connexion pour l'admin
        val urlAdmin = "https://4db7e0eb-4ed7-4f36-8568-cbdb4e93af75.mock.pstmn.io/connexion/admin1?email=admin@gmail.com&password=admin"

        val requestQueue = Volley.newRequestQueue(this)

        // Connexion pour utilisateur normal
        val jsonObjectRequestUser = JsonObjectRequest(Request.Method.GET, urlUser, null,
            Response.Listener { response ->
                try {
                    val userArray = response.getJSONArray("user")
                    if (userArray.length() > 0) {
                        val user = userArray.getJSONObject(0)
                        val userEmail = user.getString("email")
                        val userPassword = user.getString("password")

                        if (email == userEmail && password == userPassword) {
                            // Connexion réussie pour un utilisateur
                            navigateToUserDashboard()
                        } else {
                            Toast.makeText(this, "Identifiants utilisateur invalides.", Toast.LENGTH_SHORT).show()
                        }
                    } else {
                        Toast.makeText(this, "Utilisateur non trouvé.", Toast.LENGTH_SHORT).show()
                    }
                } catch (e: JSONException) {
                    Toast.makeText(this, "Erreur de connexion.", Toast.LENGTH_SHORT).show()
                }
            },
            Response.ErrorListener { error ->
                Toast.makeText(this, "Erreur: ${error.message}", Toast.LENGTH_SHORT).show()
            }
        )

        // Connexion pour admin
        val jsonObjectRequestAdmin = JsonObjectRequest(Request.Method.GET, urlAdmin, null,
            Response.Listener { response ->
                try {
                    val adminArray = response.getJSONArray("administrateur")
                    if (adminArray.length() > 0) {
                        val admin = adminArray.getJSONObject(0)
                        val adminEmail = admin.getString("email")
                        val adminPassword = admin.getString("password")

                        if (email == adminEmail && password == adminPassword) {
                            // Connexion réussie pour l'admin
                            navigateToAdminDashboard()
                        } else {
                            Toast.makeText(this, "Identifiants admin invalides.", Toast.LENGTH_SHORT).show()
                        }
                    } else {
                        Toast.makeText(this, "Admin non trouvé.", Toast.LENGTH_SHORT).show()
                    }
                } catch (e: JSONException) {
                    Toast.makeText(this, "Erreur de connexion pour admin.", Toast.LENGTH_SHORT).show()
                }
            },
            Response.ErrorListener { error ->
                Toast.makeText(this, "Erreur: ${error.message}", Toast.LENGTH_SHORT).show()
            }
        )

        // Ajout des requêtes à la queue
        requestQueue.add(jsonObjectRequestUser)
        requestQueue.add(jsonObjectRequestAdmin)
    }

    private fun navigateToUserDashboard() {
        val intent = Intent(this, UserDashboardActivity::class.java)
        startActivity(intent)
        finish()  // Facultatif : terminer l'activité de connexion pour éviter de revenir en arrière
    }

    private fun navigateToAdminDashboard() {
        val intent = Intent(this, AdminDashboardActivity::class.java)
        startActivity(intent)
        finish()  // Facultatif : terminer l'activité de connexion pour éviter de revenir en arrière
    }
}
