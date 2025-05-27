package com.example.easyportal

import android.content.Context
import android.content.Intent
import android.os.Bundle
import android.view.View
import android.widget.EditText
import android.widget.ImageView
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import com.android.volley.Request
import com.android.volley.toolbox.JsonObjectRequest
import com.android.volley.toolbox.Volley
import org.json.JSONException
import org.json.JSONObject

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

        showPasswordImageView.setOnClickListener {
            togglePasswordVisibility()
        }

        val loginButton = findViewById<View>(R.id.login_button)
        loginButton.setOnClickListener {
            val email = emailEditText.text.toString().trim()
            val password = passwordEditText.text.toString().trim()
            loginUser(email, password)
        }

        val backButton = findViewById<ImageView>(R.id.back_button)
        backButton.setOnClickListener {
            onBackPressed()
        }
    }

    private fun togglePasswordVisibility() {
        if (isPasswordVisible) {
            passwordEditText.transformationMethod = android.text.method.PasswordTransformationMethod.getInstance()
            showPasswordImageView.setImageResource(R.drawable.oeil)
        } else {
            passwordEditText.transformationMethod = null
            showPasswordImageView.setImageResource(R.drawable.oeil)
        }
        isPasswordVisible = !isPasswordVisible
    }

    private fun loginUser(email: String, password: String) {
        val sharedPref = getSharedPreferences("app_config", Context.MODE_PRIVATE)
        val baseUrl = sharedPref.getString("api_url", null)

        if (baseUrl.isNullOrEmpty()) {
            Toast.makeText(this, "Adresse API non configurée", Toast.LENGTH_LONG).show()
            return
        }

        val url = "$baseUrl/login1.php"

        val requestQueue = Volley.newRequestQueue(this)
        val jsonBody = JSONObject().apply {
            put("email", email)
            put("password", password)
        }

        val jsonObjectRequest = JsonObjectRequest(
            Request.Method.POST, url, jsonBody,
            { response ->
                try {
                    val success = response.getBoolean("success")
                    val message = response.getString("message")
                    if (success) {
                        val role = response.getString("role")
                        Toast.makeText(this, message, Toast.LENGTH_SHORT).show()
                        when (role) {
                            "utilisateur" -> navigateToUserDashboard()
                            "admin", "super_admin" -> navigateToAdminDashboard()
                            else -> Toast.makeText(this, "Rôle inconnu: $role", Toast.LENGTH_SHORT).show()
                        }
                    } else {
                        Toast.makeText(this, message, Toast.LENGTH_SHORT).show()
                    }
                } catch (e: JSONException) {
                    e.printStackTrace()
                    Toast.makeText(this, "Erreur JSON: ${e.message}", Toast.LENGTH_SHORT).show()
                }
            },
            { error ->
                error.printStackTrace()
                Toast.makeText(this, "Erreur réseau: ${error.message}", Toast.LENGTH_LONG).show()
            }
        )

        requestQueue.add(jsonObjectRequest)
    }

    private fun navigateToUserDashboard() {
        startActivity(Intent(this, UserDashboardActivity::class.java))
        finish()
    }

    private fun navigateToAdminDashboard() {
        startActivity(Intent(this, AdminDashboardActivity::class.java))
        finish()
    }
}
