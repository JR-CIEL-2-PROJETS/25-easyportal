package com.example.easyportal

import android.app.AlertDialog
import android.content.Context
import android.content.DialogInterface
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

        val sharedPref = getSharedPreferences("app_config", Context.MODE_PRIVATE)
        val baseUrl = sharedPref.getString("api_url", null)

        if (baseUrl.isNullOrEmpty()) {
            Toast.makeText(this, "Adresse API non configurée", Toast.LENGTH_LONG).show()
            return
        }

        val email = intent.getStringExtra("email")
        if (email.isNullOrEmpty()) {
            Toast.makeText(this, "Email non fourni", Toast.LENGTH_SHORT).show()
            finish()
            return
        }

        val url = "$baseUrl/getPlaquesByUser.php?email=$email"

        val requestQueue = Volley.newRequestQueue(this)
        val jsonObjectRequest = JsonObjectRequest(Request.Method.GET, url, null,
            { response ->
                try {
                    val success = response.getBoolean("success")
                    if (success) {
                        val plaquesArray = response.getJSONArray("plaques")
                        for (i in 0 until plaquesArray.length()) {
                            val obj = plaquesArray.getJSONObject(i)
                            val plaque = Plaque(
                                id = obj.getInt("id"),
                                numero = obj.getString("numero"),
                                statut = obj.getString("statut"),
                                nom = obj.getString("nom"),
                                prenom = obj.getString("prenom")
                            )
                            plaquesList.add(plaque)
                        }
                        adapter.notifyDataSetChanged()
                    } else {
                        Toast.makeText(this, "Erreur lors du chargement des plaques", Toast.LENGTH_SHORT).show()
                    }
                } catch (e: JSONException) {
                    e.printStackTrace()
                    Toast.makeText(this, "Erreur JSON: ${e.message}", Toast.LENGTH_SHORT).show()
                }
            },
            { error ->
                error.printStackTrace()
                Toast.makeText(this, "Erreur réseau: ${error.message}", Toast.LENGTH_LONG).show()
            })

        requestQueue.add(jsonObjectRequest)

        val addPlaqueButton = findViewById<View>(R.id.addPlaqueButton)
        addPlaqueButton.setOnClickListener {
            if (plaquesList.size >= 5) {
                Toast.makeText(this, "Vous ne pouvez pas avoir plus de 5 plaques.", Toast.LENGTH_SHORT).show()
            } else {
                showAddPlaqueDialog(baseUrl, email)
            }
        }
    }

    private fun showAddPlaqueDialog(baseUrl: String, email: String?) {
        val dialogView = LayoutInflater.from(this).inflate(R.layout.dialog_add_edit_plaque, null)
        val numeroEditText = dialogView.findViewById<EditText>(R.id.numeroEditText)
        val statutEditText = dialogView.findViewById<EditText>(R.id.statutEditText)

        val alertDialog = AlertDialog.Builder(this)
            .setTitle("Ajouter une plaque")
            .setView(dialogView)
            .setPositiveButton("Ajouter") { dialog, _ ->
                val numero = numeroEditText.text.toString()
                val statut = statutEditText.text.toString()

                if (numero.isNotEmpty() && statut.isNotEmpty()) {
                    addPlaque(baseUrl, email, numero, statut)
                } else {
                    Toast.makeText(this, "Tous les champs sont requis.", Toast.LENGTH_SHORT).show()
                }
            }
            .setNegativeButton("Annuler", null)
            .create()

        alertDialog.show()
    }

    private fun addPlaque(baseUrl: String, email: String?, numero: String, statut: String) {
        val requestQueue = Volley.newRequestQueue(this)

        val jsonBody = JSONObject().apply {
            put("email", email)
            put("numero", numero)
            put("statut", statut)
        }

        val jsonObjectRequest = JsonObjectRequest(
            Request.Method.POST, "$baseUrl/addPlaque.php", jsonBody,
            { response ->
                try {
                    val success = response.getBoolean("success")
                    if (success) {
                        Toast.makeText(this, "Plaque ajoutée avec succès", Toast.LENGTH_SHORT).show()
                        plaquesList.clear()  // Refresh the list after adding a plaque
                        loadPlaques(baseUrl, email)
                    } else {
                        Toast.makeText(this, "Erreur: ${response.getString("message")}", Toast.LENGTH_SHORT).show()
                    }
                } catch (e: JSONException) {
                    e.printStackTrace()
                    Toast.makeText(this, "Erreur: ${e.message}", Toast.LENGTH_SHORT).show()
                }
            },
            { error ->
                error.printStackTrace()
                Toast.makeText(this, "Erreur réseau: ${error.message}", Toast.LENGTH_LONG).show()
            })

        requestQueue.add(jsonObjectRequest)
    }

    private fun onPlaqueEdit(plaque: Plaque) {
        val dialogView = LayoutInflater.from(this).inflate(R.layout.dialog_add_edit_plaque, null)
        val numeroEditText = dialogView.findViewById<EditText>(R.id.numeroEditText)
        val statutEditText = dialogView.findViewById<EditText>(R.id.statutEditText)

        numeroEditText.setText(plaque.numero)
        statutEditText.setText(plaque.statut)

        val alertDialog = AlertDialog.Builder(this)
            .setTitle("Modifier la plaque")
            .setView(dialogView)
            .setPositiveButton("Modifier") { dialog, _ ->
                val numero = numeroEditText.text.toString()
                val statut = statutEditText.text.toString()

                if (numero.isNotEmpty() && statut.isNotEmpty()) {
                    updatePlaque(plaque.id, numero, statut)
                } else {
                    Toast.makeText(this, "Tous les champs sont requis.", Toast.LENGTH_SHORT).show()
                }
            }
            .setNegativeButton("Annuler", null)
            .create()

        alertDialog.show()
    }

    private fun updatePlaque(plaqueId: Int, numero: String, statut: String) {
        val sharedPref = getSharedPreferences("app_config", Context.MODE_PRIVATE)
        val baseUrl = sharedPref.getString("api_url", null) ?: return

        val requestQueue = Volley.newRequestQueue(this)

        val jsonBody = JSONObject().apply {
            put("id", plaqueId)
            put("numero", numero)
            put("statut", statut)
        }

        val jsonObjectRequest = JsonObjectRequest(
            Request.Method.POST, "$baseUrl/updatePlaque.php", jsonBody,
            { response ->
                try {
                    val success = response.getBoolean("success")
                    if (success) {
                        Toast.makeText(this, "Plaque modifiée avec succès", Toast.LENGTH_SHORT).show()
                        plaquesList.clear()
                        loadPlaques(baseUrl, intent.getStringExtra("email"))
                    } else {
                        Toast.makeText(this, "Erreur: ${response.getString("message")}", Toast.LENGTH_SHORT).show()
                    }
                } catch (e: JSONException) {
                    e.printStackTrace()
                    Toast.makeText(this, "Erreur: ${e.message}", Toast.LENGTH_SHORT).show()
                }
            },
            { error ->
                error.printStackTrace()
                Toast.makeText(this, "Erreur réseau: ${error.message}", Toast.LENGTH_LONG).show()
            })

        requestQueue.add(jsonObjectRequest)
    }

    private fun onPlaqueDelete(plaque: Plaque) {
        val alertDialog = AlertDialog.Builder(this)
            .setTitle("Supprimer cette plaque")
            .setMessage("Êtes-vous sûr de vouloir supprimer cette plaque?")
            .setPositiveButton("Supprimer") { _, _ ->
                deletePlaque(plaque.id)
            }
            .setNegativeButton("Annuler", null)
            .create()

        alertDialog.show()
    }

    private fun deletePlaque(plaqueId: Int) {
        val sharedPref = getSharedPreferences("app_config", Context.MODE_PRIVATE)
        val baseUrl = sharedPref.getString("api_url", null) ?: return

        val requestQueue = Volley.newRequestQueue(this)

        val jsonBody = JSONObject().apply {
            put("id", plaqueId)
        }

        val jsonObjectRequest = JsonObjectRequest(
            Request.Method.POST, "$baseUrl/deletePlaque.php", jsonBody,
            { response ->
                try {
                    val success = response.getBoolean("success")
                    if (success) {
                        Toast.makeText(this, "Plaque supprimée avec succès", Toast.LENGTH_SHORT).show()
                        plaquesList.clear()
                        loadPlaques(baseUrl, intent.getStringExtra("email"))
                    } else {
                        Toast.makeText(this, "Erreur: ${response.getString("message")}", Toast.LENGTH_SHORT).show()
                    }
                } catch (e: JSONException) {
                    e.printStackTrace()
                    Toast.makeText(this, "Erreur: ${e.message}", Toast.LENGTH_SHORT).show()
                }
            },
            { error ->
                error.printStackTrace()
                Toast.makeText(this, "Erreur réseau: ${error.message}", Toast.LENGTH_LONG).show()
            })

        requestQueue.add(jsonObjectRequest)
    }

    private fun loadPlaques(baseUrl: String, email: String?) {
        val url = "$baseUrl/getPlaquesByUser.php?email=$email"
        val requestQueue = Volley.newRequestQueue(this)
        val jsonObjectRequest = JsonObjectRequest(Request.Method.GET, url, null,
            { response ->
                try {
                    val success = response.getBoolean("success")
                    if (success) {
                        val plaquesArray = response.getJSONArray("plaques")
                        plaquesList.clear()
                        for (i in 0 until plaquesArray.length()) {
                            val obj = plaquesArray.getJSONObject(i)
                            val plaque = Plaque(
                                id = obj.getInt("id"),
                                numero = obj.getString("numero"),
                                statut = obj.getString("statut"),
                                nom = obj.getString("nom"),
                                prenom = obj.getString("prenom")
                            )
                            plaquesList.add(plaque)
                        }
                        adapter.notifyDataSetChanged()
                    } else {
                        Toast.makeText(this, "Erreur lors du chargement des plaques", Toast.LENGTH_SHORT).show()
                    }
                } catch (e: JSONException) {
                    e.printStackTrace()
                    Toast.makeText(this, "Erreur JSON: ${e.message}", Toast.LENGTH_SHORT).show()
                }
            },
            { error ->
                error.printStackTrace()
                Toast.makeText(this, "Erreur réseau: ${error.message}", Toast.LENGTH_LONG).show()
            })
        val backButton = findViewById<ImageView>(R.id.back_button)
        backButton.setOnClickListener {
            onBackPressed()  // Revient à l'écran précédent
        }

        requestQueue.add(jsonObjectRequest)
    }
}
