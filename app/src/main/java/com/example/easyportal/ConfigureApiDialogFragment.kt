package com.example.easyportal

import android.app.Dialog
import android.os.Bundle
import android.util.Patterns
import android.view.LayoutInflater
import android.view.View
import android.widget.EditText
import android.widget.Toast
import androidx.appcompat.app.AlertDialog
import androidx.fragment.app.DialogFragment

class ConfigureApiDialogFragment : DialogFragment() {

    override fun onCreateDialog(savedInstanceState: Bundle?): Dialog {
        val builder = AlertDialog.Builder(requireContext())
        val inflater = LayoutInflater.from(context)
        val dialogView: View = inflater.inflate(R.layout.dialog_api_config, null)

        val apiEditText: EditText = dialogView.findViewById(R.id.api_edit_text)

        // Pré-remplir avec l’URL actuelle
        apiEditText.setText(ApiManager.getBaseUrl(requireContext()))

        builder.setView(dialogView)
            .setTitle("Configurer l'API")
            .setPositiveButton("Enregistrer") { _, _ ->
                var enteredUrl = apiEditText.text.toString().trim()

                // Ajoute http:// si nécessaire
                if (!enteredUrl.startsWith("http://") && !enteredUrl.startsWith("https://")) {
                    enteredUrl = "http://$enteredUrl"
                }

                if (!Patterns.WEB_URL.matcher(enteredUrl).matches()) {
                    Toast.makeText(requireContext(), "URL invalide", Toast.LENGTH_SHORT).show()
                    return@setPositiveButton
                }

                ApiManager.setBaseUrl(requireContext(), enteredUrl)
                Toast.makeText(requireContext(), "API configurée : $enteredUrl", Toast.LENGTH_SHORT).show()
            }
            .setNegativeButton("Annuler") { dialog, _ ->
                dialog.cancel()
            }

        return builder.create()
    }
}
