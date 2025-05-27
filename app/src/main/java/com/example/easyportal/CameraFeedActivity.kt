package com.example.easyportal

import android.os.Bundle
import android.webkit.WebChromeClient
import android.webkit.WebResourceRequest
import android.webkit.WebView
import androidx.appcompat.app.AppCompatActivity

class CameraFeedActivity : AppCompatActivity() {

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_camera_feed)

        // Trouver le WebView dans le layout
        val webView = findViewById<WebView>(R.id.webView)
        // Charger l'URL du flux vidéo
        webView.webChromeClient = WebChromeClient()
        webView.loadUrl("http://172.16.15.39:5055/video")  // Remplace par l'URL correcte du flux caméra
    }
}
