package com.convertidor.monedas.service;

import com.convertidor.monedas.model.ExchangeRateResponse;
import lombok.RequiredArgsConstructor;
import org.springframework.stereotype.Service;
import org.springframework.web.client.RestTemplate;

@Service
@RequiredArgsConstructor
public class ExchangeRateService {

    private final RestTemplate restTemplate;
    private final String apiUrl = "https://api.exchangeratesapi.io/latest";
    private final String apiKey = "faf73b81294ed7ac4a0129aed2bbbb39";

    public ExchangeRateResponse getRates(String baseCurrency) {
        String url = String.format("%s?access_key=%s", apiUrl, apiKey);
    
        // Obtener la respuesta como un String para inspeccionarla
        String response = restTemplate.getForObject(url, String.class);
        System.out.println("Respuesta cruda de la API: " + response);
    
        // Intentar mapear la respuesta a la clase ExchangeRateResponse
        return restTemplate.getForObject(url, ExchangeRateResponse.class);
    }

    public Double convert(String fromCurrency, String toCurrency, Double amount) {
        // Obtener las tasas de cambio con EUR como base
        ExchangeRateResponse rates = getRates(fromCurrency);
    
        // Convertir el monto a EUR primero
        Double fromRate = rates.getRates().get(fromCurrency);
        if (fromRate == null) {
            throw new IllegalArgumentException("La moneda origen no está soportada: " + fromCurrency);
        }
    
        Double toRate = rates.getRates().get(toCurrency);
        if (toRate == null) {
            throw new IllegalArgumentException("La moneda destino no está soportada: " + toCurrency);
        }
    
        // Calcular la conversión
        return (amount / fromRate) * toRate;
    }
}
