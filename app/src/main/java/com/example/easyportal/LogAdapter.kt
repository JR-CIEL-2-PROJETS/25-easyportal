package com.example.easyportal.adapter

import android.content.Context
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.TextView
import androidx.recyclerview.widget.RecyclerView
import com.example.easyportal.R
import com.example.easyportal.model.LogEntry

class LogAdapter(
    private val context: Context,
    private val logs: List<LogEntry>
) : RecyclerView.Adapter<LogAdapter.LogViewHolder>() {

    class LogViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
        val userName: TextView = itemView.findViewById(R.id.user_name)
        val logMessage: TextView = itemView.findViewById(R.id.log_message)
        val logDate: TextView = itemView.findViewById(R.id.log_date)
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): LogViewHolder {
        val view = LayoutInflater.from(context).inflate(R.layout.log_item, parent, false)
        return LogViewHolder(view)
    }

    override fun onBindViewHolder(holder: LogViewHolder, position: Int) {
        val log = logs[position]
        holder.userName.text = log.userName
        holder.logMessage.text = log.logMessage
        holder.logDate.text = log.logDate
    }

    override fun getItemCount(): Int = logs.size
}
