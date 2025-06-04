package com.example.easyportal

import android.app.AlertDialog
import android.content.Intent
import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.widget.EditText
import android.widget.ImageView
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.android.volley.Request
import com.android.volley.toolbox.JsonObjectRequest
import com.android.volley.toolbox.Volley
import org.json.JSONException
import org.json.JSONObject

class PlaquesActivity : AppCompatActivity() {

    private lateinit var recyclerView: RecyclerView
    private lateinit var adapter: PlaqueAdapter
    private val plaquesList = mutableListOf<Plaque>()

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_plaques)

        recyclerView = findViewById(R.id.recyclerView)
        recyclerView.layoutManager = LinearLayoutManager(this)
        adapter = PlaqueAdapter(plaquesList, ::onPlaqueEdit, ::onPlaqueDelete)
        recyclerView.adapter = adapter

        val baseUrl = ApiManager.getBaseUrl()
        val email = intent.getStringExtra("email")

        if (email.isNullOrEmpty()) {
            Toast.makeText(this, "Email non fourni", Toast.LENGTH_SHORT).show()
            finish()
            return
        }

        loadPlaques(baseUrl, email)

        findViewById<View>(R.id.addPlaqueButton).setOnClickListener {
            if (plaquesList.size >= 5) {
                Toast.makeText(this, "Vous ne pouvez pas avoir plus de 5 plaques.", Toast.LENGTH_SHORT).show()
            } else {
                showAddPlaqueDialog(baseUrl, email)
            }
        }

        findViewById<ImageView>(R.id.back_button).setOnClickListener {
            onBackPressed()
        }
    }

    private fun showAddPlaqueDialog(baseUrl: String, email: String?) {
        val dialogView = LayoutInflater.from(this).inflate(R.layout.dialog_add_edit_plaque, null)
        val numeroEditText = dialogView.findViewById<EditText>(R.id.numeroEditText)

        AlertDialog.Builder(this)
            .setTitle("Ajouter une plaque")
            .setView(dialogView)
            .setPositiveButton("Ajouter") { _, _ ->
                val numero = numeroEditText.text.toString().trim()
                if (numero.isNotEmpty()) {
                    val statutParDefaut = "en attente"
                    addPlaque(baseUrl, email, numero, statutParDefaut)
                } else {
                    Toast.makeText(this, "Le numéro est requis.", Toast.LENGTH_SHORT).show()
                }
            }
            .setNegativeButton("Annuler", null)
            .create()
            .show()
    }

    private fun addPlaque(baseUrl: String, email: String?, numero: String, statut: String) {
        val requestQueue = Volley.newRequestQueue(this)

        val jsonBody = JSONObject().apply {
            put("email", email)
            put("numero", numero)
            put("statut", statut)
        }

        val url = "$baseUrl/add_plaque.php"
        val jsonObjectRequest = JsonObjectRequest(Request.Method.POST, url, jsonBody,
            { response ->
                try {
                    if (response.getBoolean("success")) {
                        Toast.makeText(this, "Plaque ajoutée avec succès", Toast.LENGTH_SHORT).show()
                        plaquesList.clear()
                        loadPlaques(baseUrl, email)
                    } else {
                        Toast.makeText(this, "Erreur: ${response.getString("message")}", Toast.LENGTH_SHORT).show()
                    }
                } catch (e: JSONException) {
                    Toast.makeText(this, "Erreur: ${e.message}", Toast.LENGTH_SHORT).show()
                }
            },
            { error ->
                Toast.makeText(this, "Erreur réseau: ${error.message ?: "inconnue"}", Toast.LENGTH_LONG).show()
                error.printStackTrace()
            })

        requestQueue.add(jsonObjectRequest)
    }

    private fun updatePlaque(plaqueId: Int, numero: String, statut: String, email: String) {
        val baseUrl = ApiManager.getBaseUrl()
        val requestQueue = Volley.newRequestQueue(this)

        val jsonBody = JSONObject().apply {
            put("id", plaqueId)
            put("numero", numero)
            put("statut", statut)
        }

        val url = "$baseUrl/update_plaque.php"
        val jsonObjectRequest = JsonObjectRequest(Request.Method.POST, url, jsonBody,
            { response ->
                try {
                    if (response.getBoolean("success")) {
                        Toast.makeText(this, "Plaque modifiée avec succès", Toast.LENGTH_SHORT).show()
                        plaquesList.clear()
                        loadPlaques(baseUrl, email)
                    } else {
                        Toast.makeText(this, "Erreur: ${response.getString("message")}", Toast.LENGTH_SHORT).show()
                    }
                } catch (e: JSONException) {
                    Toast.makeText(this, "Erreur: ${e.message}", Toast.LENGTH_SHORT).show()
                }
            },
            { error ->
                Toast.makeText(this, "Erreur réseau: ${error.message ?: "inconnue"}", Toast.LENGTH_LONG).show()
                error.printStackTrace()
            })

        requestQueue.add(jsonObjectRequest)
    }

    private fun deletePlaque(plaqueId: Int) {
        val baseUrl = ApiManager.getBaseUrl()
        val email = intent.getStringExtra("email") ?: return
        val requestQueue = Volley.newRequestQueue(this)

        val jsonBody = JSONObject().apply {
            put("id", plaqueId)
        }

        val url = "$baseUrl/delete_plaque.php"
        val jsonObjectRequest = JsonObjectRequest(Request.Method.POST, url, jsonBody,
            { response ->
                try {
                    if (response.getBoolean("success")) {
                        Toast.makeText(this, "Plaque supprimée avec succès", Toast.LENGTH_SHORT).show()
                        plaquesList.clear()
                        loadPlaques(baseUrl, email)
                    } else {
                        Toast.makeText(this, "Erreur: ${response.getString("message")}", Toast.LENGTH_SHORT).show()
                    }
                } catch (e: JSONException) {
                    Toast.makeText(this, "Erreur: ${e.message}", Toast.LENGTH_SHORT).show()
                }
            },
            { error ->
                Toast.makeText(this, "Erreur réseau: ${error.message ?: "inconnue"}", Toast.LENGTH_LONG).show()
                error.printStackTrace()
            })

        requestQueue.add(jsonObjectRequest)
    }

    private fun loadPlaques(baseUrl: String, email: String?) {
        val url = "$baseUrl/getPlaquesByUser.php?email=$email"
        val requestQueue = Volley.newRequestQueue(this)

        val jsonObjectRequest = JsonObjectRequest(Request.Method.GET, url, null,
            { response ->
                try {
                    if (response.getBoolean("success")) {
                        val plaquesArray = response.getJSONArray("plaques")
                        plaquesList.clear()
                        for (i in 0 until plaquesArray.length()) {
                            val obj = plaquesArray.getJSONObject(i)
                            val plaque = Plaque(
                                id = obj.getInt("id"),
                                numero = obj.getString("numero"),
                                statut = obj.getString("statut"),
                                nom = obj.getString("nom"),
                                prenom = obj.getString("prenom"),
                                email = email ?: ""
                            )
                            plaquesList.add(plaque)
                        }
                        adapter.notifyDataSetChanged()
                    } else {
                        Toast.makeText(this, "Erreur lors du chargement des plaques", Toast.LENGTH_SHORT).show()
                    }
                } catch (e: JSONException) {
                    Toast.makeText(this, "Erreur JSON: ${e.message}", Toast.LENGTH_SHORT).show()
                }
            },
            { error ->
                Toast.makeText(this, "Erreur réseau: ${error.message ?: "inconnue"}", Toast.LENGTH_LONG).show()
                error.printStackTrace()
            })

        requestQueue.add(jsonObjectRequest)
    }

    private fun onPlaqueEdit(plaque: Plaque) {
        val dialogView = LayoutInflater.from(this).inflate(R.layout.dialog_add_edit_plaque, null)
        val numeroEditText = dialogView.findViewById<EditText>(R.id.numeroEditText)

        numeroEditText.setText(plaque.numero)

        AlertDialog.Builder(this)
            .setTitle("Modifier la plaque")
            .setView(dialogView)
            .setPositiveButton("Modifier") { _, _ ->
                val numero = numeroEditText.text.toString()
                val email = intent.getStringExtra("email") ?: return@setPositiveButton
                if (numero.isNotEmpty()) {
                    updatePlaque(plaque.id, numero, plaque.statut, email)
                } else {
                    Toast.makeText(this, "Le numéro est requis.", Toast.LENGTH_SHORT).show()
                }
            }
            .setNegativeButton("Annuler", null)
            .create()
            .show()
    }

    private fun onPlaqueDelete(plaque: Plaque) {
        AlertDialog.Builder(this)
            .setTitle("Supprimer cette plaque")
            .setMessage("Êtes-vous sûr de vouloir supprimer cette plaque ?")
            .setPositiveButton("Supprimer") { _, _ -> deletePlaque(plaque.id) }
            .setNegativeButton("Annuler", null)
            .create()
            .show()
    }
}
