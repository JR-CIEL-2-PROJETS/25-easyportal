// PlaqueAdapterSuperAdmin.kt
package com.example.easyportal

import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.ImageButton
import android.widget.TextView
import androidx.recyclerview.widget.RecyclerView

class PlaqueAdapterSuperAdmin(
    private val plaquesList: List<Plaque>,
    private val onEdit: (Plaque) -> Unit,
    private val onDelete: (Plaque) -> Unit
) : RecyclerView.Adapter<PlaqueAdapterSuperAdmin.PlaqueViewHolder>() {

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): PlaqueViewHolder {
        val view = LayoutInflater.from(parent.context)
            .inflate(R.layout.item_plaque_super, parent, false)
        return PlaqueViewHolder(view)
    }

    override fun getItemCount(): Int = plaquesList.size

    override fun onBindViewHolder(holder: PlaqueViewHolder, position: Int) {
        val plaque = plaquesList[position]
        holder.numero.text = "Plaque : ${plaque.numero}"
        holder.statut.text = "Statut : ${plaque.statut}"

        holder.itemView.setOnClickListener {
            onEdit(plaque)
        }

        holder.deleteButton.setOnClickListener {
            onDelete(plaque)
        }
    }

    inner class PlaqueViewHolder(view: View) : RecyclerView.ViewHolder(view) {
        val numero: TextView = view.findViewById(R.id.numeroTextViewSuper)
        val statut: TextView = view.findViewById(R.id.statutTextViewSuper)
        val deleteButton: ImageButton = view.findViewById(R.id.deleteButtonSuper)
    }
}
