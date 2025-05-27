package com.example.easyportal

import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.TextView
import androidx.recyclerview.widget.RecyclerView

class PlaqueAdapter(
    private val plaquesList: List<Plaque>,
    private val onPlaqueEdit: (Plaque) -> Unit,
    private val onPlaqueDelete: (Plaque) -> Unit
) : RecyclerView.Adapter<PlaqueAdapter.PlaqueViewHolder>() {

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): PlaqueViewHolder {
        val view = LayoutInflater.from(parent.context).inflate(R.layout.item_plaque, parent, false)
        return PlaqueViewHolder(view)
    }

    override fun onBindViewHolder(holder: PlaqueViewHolder, position: Int) {
        val plaque = plaquesList[position]
        holder.numeroTextView.text = plaque.numero
        holder.statutTextView.text = plaque.statut

        holder.itemView.setOnClickListener {
            onPlaqueEdit(plaque)
        }

        holder.deleteButton.setOnClickListener {
            onPlaqueDelete(plaque)
        }
    }

    override fun getItemCount(): Int = plaquesList.size

    inner class PlaqueViewHolder(view: View) : RecyclerView.ViewHolder(view) {
        val numeroTextView: TextView = view.findViewById(R.id.numeroTextView)
        val statutTextView: TextView = view.findViewById(R.id.statutTextView)
        val deleteButton: View = view.findViewById(R.id.deleteButton)
    }
}
