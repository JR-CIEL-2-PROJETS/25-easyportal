package com.example.easyportal

import android.content.Intent
import android.os.Bundle
import android.widget.ImageView
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.android.volley.Request
import com.android.volley.toolbox.JsonObjectRequest
import com.android.volley.toolbox.Volley
import com.example.easyportal.adapter.LogAdapter
import com.example.easyportal.model.LogEntry
import org.json.JSONArray

class LogActivity : AppCompatActivity() {

    private lateinit var logRecyclerView: RecyclerView
    private lateinit var logAdapter: LogAdapter
    private val logs = mutableListOf<LogEntry>()

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.log_activity)

        val email = intent.getStringExtra("email") // Email de l'admin

        logRecyclerView = findViewById(R.id.log_list_view)
        logRecyclerView.layoutManager = LinearLayoutManager(this)

        findViewById<ImageView>(R.id.back_button).setOnClickListener {
            val intent = Intent(this, AdminDashboardActivity::class.java)
            intent.putExtra("email", email)
            startActivity(intent)
            finish()
        }

        fetchLogs(email)
    }

    private fun fetchLogs(email: String?) {
        val baseUrl = ApiManager.getBaseUrl(this)
        val url = "$baseUrl/php/get_logs_mobile.php?email=$email"

        val requestQueue = Volley.newRequestQueue(this)

        val jsonObjectRequest = JsonObjectRequest(
            Request.Method.GET, url, null,
            { response ->
                try {
                    if (response.getBoolean("success")) {
                        val logsArray: JSONArray = response.getJSONArray("logs")
                        logs.clear()
                        for (i in 0 until logsArray.length()) {
                            val logObject = logsArray.getJSONObject(i)
                            val log = LogEntry(
                                userName = logObject.getString("email"),
                                logMessage = logObject.getString("action"),
                                logDate = logObject.getString("date_entree") // ✅ ajoute la date ici
                            )
                            logs.add(log)
                        }
                        logAdapter = LogAdapter(this, logs)
                        logRecyclerView.adapter = logAdapter
                    } else {
                        Toast.makeText(this, response.getString("message"), Toast.LENGTH_SHORT).show()
                    }
                } catch (e: Exception) {
                    Toast.makeText(this, "Erreur lors du traitement JSON", Toast.LENGTH_SHORT).show()
                }
            },
            { error ->
                val responseData = error.networkResponse?.data?.let { String(it) } ?: "Réponse inconnue"
                Toast.makeText(this, "Erreur réseau: ${error.message}\n$responseData", Toast.LENGTH_LONG).show()
                error.printStackTrace()
            })

        requestQueue.add(jsonObjectRequest)
    }
}
