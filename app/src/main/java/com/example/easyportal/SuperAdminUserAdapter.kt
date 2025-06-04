// SuperAdminUserAdapter.kt
package com.example.easyportal

import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Button
import android.widget.TextView
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView

class SuperAdminUserAdapter(
    private val usersMap: Map<String, List<Plaque>>,
    private val onAddPlaque: (String) -> Unit,
    private val onEdit: (Plaque) -> Unit,
    private val onDelete: (Plaque) -> Unit
) : RecyclerView.Adapter<SuperAdminUserAdapter.UserViewHolder>() {

    private val emails: List<String>
        get() = usersMap.keys.toList()  // ✅ toujours à jour

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): UserViewHolder {
        val view = LayoutInflater.from(parent.context)
            .inflate(R.layout.item_user_with_plaques, parent, false)
        return UserViewHolder(view)
    }

    override fun getItemCount(): Int = emails.size

    override fun onBindViewHolder(holder: UserViewHolder, position: Int) {
        val email = emails[position]
        val plaques = usersMap[email] ?: emptyList()

        val nomPrenom = if (plaques.isNotEmpty()) {
            "${plaques[0].prenom} ${plaques[0].nom}"
        } else {
            "Utilisateur sans plaque"
        }

        holder.nomPrenom.text = nomPrenom
        holder.email.text = email

        holder.addPlaqueButton.setOnClickListener {
            onAddPlaque(email)
        }

        holder.noPlaquesText.visibility = if (plaques.isEmpty()) View.VISIBLE else View.GONE
        holder.plaqueList.visibility = if (plaques.isEmpty()) View.GONE else View.VISIBLE

        holder.plaqueList.layoutManager = LinearLayoutManager(holder.itemView.context)
        holder.plaqueList.adapter = PlaqueAdapterSuperAdmin(plaques, onEdit, onDelete)
    }

    inner class UserViewHolder(view: View) : RecyclerView.ViewHolder(view) {
        val nomPrenom: TextView = view.findViewById(R.id.nomPrenomTextViewUser)
        val email: TextView = view.findViewById(R.id.emailTextViewUser)
        val plaqueList: RecyclerView = view.findViewById(R.id.recyclerViewPlaques)
        val addPlaqueButton: Button = view.findViewById(R.id.btnAddPlaque)
        val noPlaquesText: TextView = view.findViewById(R.id.noPlaquesTextView)
    }
}

