package com.example.easyportal

import android.content.Context

object ApiManager {
    private var baseUrl: String? = null

    fun getBaseUrl(context: Context): String {
        if (baseUrl == null) {
            val sharedPref = context.getSharedPreferences("app_config", Context.MODE_PRIVATE)
            baseUrl = sharedPref.getString("api_url", "http://192.168.131.129:8080")
        }
        return baseUrl!!
    }

    fun setBaseUrl(context: Context, url: String) {
        baseUrl = url
        val sharedPref = context.getSharedPreferences("app_config", Context.MODE_PRIVATE)
        sharedPref.edit().putString("api_url", url).apply()
    }
}
