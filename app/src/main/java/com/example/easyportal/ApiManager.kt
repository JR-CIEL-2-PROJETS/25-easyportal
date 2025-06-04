package com.example.easyportal

object ApiManager {
    private var baseUrl: String = "http://192.168.131.129:8080"

    fun getBaseUrl(): String {
        return baseUrl
    }

    fun setBaseUrl(url: String) {
        baseUrl = url
    }
}
