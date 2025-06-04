package com.example.easyportal

import android.os.Bundle
import android.webkit.WebChromeClient
import android.webkit.WebResourceRequest
import android.webkit.WebView
import androidx.appcompat.app.AppCompatActivity
import android.widget.ImageView

class CameraFeedActivity : AppCompatActivity() {

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_camera_feed)  // Utilise le layout de l'XML

        // Initialisation du bouton de retour
        val backButton = findViewById<ImageView>(R.id.back_button)
        backButton.setOnClickListener {
            onBackPressed()  // Retour à l'activité précédente
        }

        // Initialisation du WebView pour afficher le flux vidéo
        val webView = findViewById<WebView>(R.id.webView)
        webView.webChromeClient = WebChromeClient()
        webView.loadUrl("http://172.16.15.39:5000/video")  // URL du flux caméra (à adapter selon votre cas)
    }
}
