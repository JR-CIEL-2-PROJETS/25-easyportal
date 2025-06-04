// SuperAdminPlaquesActivity.kt
package com.example.easyportal

import android.app.AlertDialog
import android.os.Bundle
import android.view.LayoutInflater
import android.widget.*
import androidx.appcompat.app.AppCompatActivity
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.android.volley.Request
import com.android.volley.toolbox.JsonObjectRequest
import com.android.volley.toolbox.Volley
import org.json.JSONObject
import org.json.JSONException
import android.util.Log


class SuperAdminPlaquesActivity : AppCompatActivity() {

    private lateinit var recyclerView: RecyclerView
    private lateinit var adapter: SuperAdminUserAdapter
    private val usersMap = mutableMapOf<String, MutableList<Plaque>>()

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_super_admin_plaques)

        recyclerView = findViewById(R.id.recyclerViewSuper)
        recyclerView.layoutManager = LinearLayoutManager(this)
        adapter = SuperAdminUserAdapter(usersMap, ::onAddPlaqueClick, ::onPlaqueEdit, ::onPlaqueDelete)
        recyclerView.adapter = adapter

        findViewById<ImageView>(R.id.back_button_super).setOnClickListener {
            onBackPressed()
        }

        findViewById<Button>(R.id.addPlaqueButtonSuper).setOnClickListener {
            showGlobalAddPlaqueDialog()
        }

        loadAllUsersAndPlaques()
    }

    private fun loadAllUsersAndPlaques() {
        val baseUrl = ApiManager.getBaseUrl()
        val url = "$baseUrl/getAllPlaques.php"
        val requestQueue = Volley.newRequestQueue(this)

        val jsonObjectRequest = JsonObjectRequest(Request.Method.GET, url, null,
            { response ->
                try {
                    if (response.getBoolean("success")) {
                        val plaquesArray = response.getJSONArray("plaques")
                        usersMap.clear()
                        for (i in 0 until plaquesArray.length()) {
                            val obj = plaquesArray.getJSONObject(i)
                            val email = obj.getString("email")
                            val nom = obj.getString("nom")
                            val prenom = obj.getString("prenom")

                            if (!usersMap.containsKey(email)) {
                                usersMap[email] = mutableListOf()
                            }

                            // Même sans plaque, on enregistre l'utilisateur avec une entrée vide
                            if (obj.isNull("plaque_id")) {
                                usersMap[email]?.add(
                                    Plaque(
                                        id = -1,
                                        numero = "",
                                        statut = "",
                                        nom = nom,
                                        prenom = prenom,
                                        email = email
                                    )
                                )
                            } else {
                                val plaque = Plaque(
                                    id = obj.getInt("plaque_id"),
                                    numero = obj.getString("numero"),
                                    statut = obj.getString("statut"),
                                    nom = nom,
                                    prenom = prenom,
                                    email = email
                                )
                                usersMap[email]?.add(plaque)
                            }
                        }
                        Log.d("ADAPTER_DATA", "Nombre d’utilisateurs: ${usersMap.size}")
                        for ((email, plaques) in usersMap) {
                            Log.d("ADAPTER_DATA", "Utilisateur: $email avec ${plaques.size} plaques")
                        }

                        adapter.notifyDataSetChanged()
                    } else {
                        Toast.makeText(this, "Erreur de chargement", Toast.LENGTH_SHORT).show()
                    }
                } catch (e: JSONException) {
                    Toast.makeText(this, "Erreur JSON: ${e.message}", Toast.LENGTH_SHORT).show()
                }
            },
            { error ->
                Toast.makeText(this, "Erreur réseau: ${error.message}", Toast.LENGTH_LONG).show()
            })

        requestQueue.add(jsonObjectRequest)
    }

    private fun showGlobalAddPlaqueDialog() {
        val dialogView = LayoutInflater.from(this).inflate(R.layout.dialog_add_edit_plaque_admin, null)
        val numeroEditText = dialogView.findViewById<EditText>(R.id.numeroEditTextAdmin)
        val emailSpinner = dialogView.findViewById<Spinner>(R.id.emailSpinner)
        val statutSpinner = dialogView.findViewById<Spinner>(R.id.statutSpinner)

        val emailList = usersMap.keys.toList()

        emailSpinner.adapter = ArrayAdapter(this, android.R.layout.simple_spinner_dropdown_item, emailList)
        statutSpinner.adapter = ArrayAdapter(this, android.R.layout.simple_spinner_dropdown_item, listOf("actif", "bloqué"))

        AlertDialog.Builder(this)
            .setTitle("Ajouter une plaque")
            .setView(dialogView)
            .setPositiveButton("Ajouter") { _, _ ->
                val numero = numeroEditText.text.toString()
                val statut = statutSpinner.selectedItem.toString()
                val email = emailSpinner.selectedItem as String

                if (numero.isNotEmpty()) {
                    addPlaque(email, numero, statut)
                } else {
                    Toast.makeText(this, "Numéro requis", Toast.LENGTH_SHORT).show()
                }
            }
            .setNegativeButton("Annuler", null)
            .create()
            .show()
    }

    private fun onAddPlaqueClick(email: String) {
        val dialogView = LayoutInflater.from(this).inflate(R.layout.dialog_add_edit_plaque_admin, null)
        val numeroEditText = dialogView.findViewById<EditText>(R.id.numeroEditTextAdmin)
        val emailSpinner = dialogView.findViewById<Spinner>(R.id.emailSpinner)
        val statutSpinner = dialogView.findViewById<Spinner>(R.id.statutSpinner)

        emailSpinner.adapter = ArrayAdapter(this, android.R.layout.simple_spinner_dropdown_item, listOf(email))
        emailSpinner.setSelection(0)
        emailSpinner.isEnabled = false

        statutSpinner.adapter = ArrayAdapter(this, android.R.layout.simple_spinner_dropdown_item, listOf("actif", "bloqué"))

        AlertDialog.Builder(this)
            .setTitle("Ajouter une plaque")
            .setView(dialogView)
            .setPositiveButton("Ajouter") { _, _ ->
                val numero = numeroEditText.text.toString()
                val statut = statutSpinner.selectedItem.toString()
                if (numero.isNotEmpty()) {
                    addPlaque(email, numero, statut)
                }
            }
            .setNegativeButton("Annuler", null)
            .create()
            .show()
    }

    private fun addPlaque(email: String, numero: String, statut: String) {
        val baseUrl = ApiManager.getBaseUrl()
        val url = "$baseUrl/add_plaque.php"
        val jsonBody = JSONObject().apply {
            put("email", email)
            put("numero", numero)
            put("statut", statut)
        }

        val requestQueue = Volley.newRequestQueue(this)
        val jsonObjectRequest = JsonObjectRequest(Request.Method.POST, url, jsonBody,
            { response ->
                if (response.getBoolean("success")) {
                    Toast.makeText(this, "Plaque ajoutée", Toast.LENGTH_SHORT).show()
                    loadAllUsersAndPlaques()
                } else {
                    Toast.makeText(this, response.getString("message"), Toast.LENGTH_SHORT).show()
                }
            },
            { error ->
                Toast.makeText(this, "Erreur: ${error.message}", Toast.LENGTH_LONG).show()
            })

        requestQueue.add(jsonObjectRequest)
    }

    private fun onPlaqueEdit(plaque: Plaque) {
        val baseUrl = ApiManager.getBaseUrl()
        val dialogView = LayoutInflater.from(this).inflate(R.layout.dialog_add_edit_plaque_admin, null)
        val numeroEditText = dialogView.findViewById<EditText>(R.id.numeroEditTextAdmin)
        val emailSpinner = dialogView.findViewById<Spinner>(R.id.emailSpinner)
        val statutSpinner = dialogView.findViewById<Spinner>(R.id.statutSpinner)

        numeroEditText.setText(plaque.numero)
        emailSpinner.adapter = ArrayAdapter(this, android.R.layout.simple_spinner_dropdown_item, listOf(plaque.email))
        emailSpinner.setSelection(0)
        emailSpinner.isEnabled = false
        statutSpinner.adapter = ArrayAdapter(this, android.R.layout.simple_spinner_dropdown_item, listOf("actif", "bloqué"))
        statutSpinner.setSelection(if (plaque.statut == "actif") 0 else 1)

        AlertDialog.Builder(this)
            .setTitle("Modifier la plaque")
            .setView(dialogView)
            .setPositiveButton("Modifier") { _, _ ->
                val numero = numeroEditText.text.toString()
                val statut = statutSpinner.selectedItem.toString()
                updatePlaque(baseUrl, plaque.id, numero, statut)
            }
            .setNegativeButton("Annuler", null)
            .create()
            .show()
    }

    private fun updatePlaque(baseUrl: String, id: Int, numero: String, statut: String) {
        val url = "$baseUrl/update_plaque.php"
        val jsonBody = JSONObject().apply {
            put("id", id)
            put("numero", numero)
            put("statut", statut)
        }

        val requestQueue = Volley.newRequestQueue(this)
        val jsonObjectRequest = JsonObjectRequest(Request.Method.POST, url, jsonBody,
            { response ->
                if (response.getBoolean("success")) {
                    Toast.makeText(this, "Plaque modifiée", Toast.LENGTH_SHORT).show()
                    loadAllUsersAndPlaques()
                } else {
                    Toast.makeText(this, "Erreur: ${response.getString("message")}", Toast.LENGTH_SHORT).show()
                }
            },
            { error ->
                Toast.makeText(this, "Erreur: ${error.message}", Toast.LENGTH_SHORT).show()
            })

        requestQueue.add(jsonObjectRequest)
    }

    private fun onPlaqueDelete(plaque: Plaque) {
        val baseUrl = ApiManager.getBaseUrl()
        val url = "$baseUrl/delete_plaque.php"
        val jsonBody = JSONObject().apply {
            put("id", plaque.id)
        }

        val requestQueue = Volley.newRequestQueue(this)
        val jsonObjectRequest = JsonObjectRequest(Request.Method.POST, url, jsonBody,
            { response ->
                if (response.getBoolean("success")) {
                    Toast.makeText(this, "Plaque supprimée", Toast.LENGTH_SHORT).show()
                    loadAllUsersAndPlaques()
                } else {
                    Toast.makeText(this, "Erreur: ${response.getString("message")}", Toast.LENGTH_SHORT).show()
                }
            },
            { error ->
                Toast.makeText(this, "Erreur: ${error.message}", Toast.LENGTH_SHORT).show()
            })

        requestQueue.add(jsonObjectRequest)
    }
}
