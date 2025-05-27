package com.example.easyportal

import android.os.Bundle
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
    private var logs = mutableListOf<LogEntry>()

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.log_activity)

        // Initialisation du RecyclerView
        logRecyclerView = findViewById(R.id.log_list_view)

        // Configuration du RecyclerView (LinearLayoutManager)
        logRecyclerView.layoutManager = LinearLayoutManager(this)

        // Charger les logs depuis le serveur
        fetchLogs()
    }

    private fun fetchLogs() {
        val url = "http://192.168.1.185/get_logs.php"  // Remplace par l'IP de ton serveur ou localhost si sur émulateur

        val requestQueue = Volley.newRequestQueue(this)

        val jsonObjectRequest = JsonObjectRequest(
            Request.Method.GET, url, null,
            { response ->
                try {
                    val success = response.getBoolean("success")
                    if (success) {
                        val logsArray = response.getJSONArray("logs")
                        for (i in 0 until logsArray.length()) {
                            val logObject = logsArray.getJSONObject(i)
                            val log = LogEntry(
                                userName = logObject.getString("email"),
                                logMessage = logObject.getString("action")
                            )
                            logs.add(log)
                        }

                        // Mettre à jour l'adapter avec les logs récupérés
                        logAdapter = LogAdapter(this, logs)
                        logRecyclerView.adapter = logAdapter
                    } else {
                        Toast.makeText(this, "Aucun log trouvé", Toast.LENGTH_SHORT).show()
                    }
                } catch (e: Exception) {
                    e.printStackTrace()
                    Toast.makeText(this, "Erreur lors du chargement des logs", Toast.LENGTH_SHORT).show()
                }
            },
            { error ->
                error.printStackTrace()
                Toast.makeText(this, "Erreur de connexion: ${error.message}", Toast.LENGTH_SHORT).show()
            })

        requestQueue.add(jsonObjectRequest)
    }
}
