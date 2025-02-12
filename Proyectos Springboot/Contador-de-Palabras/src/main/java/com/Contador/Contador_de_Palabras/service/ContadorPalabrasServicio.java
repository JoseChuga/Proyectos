package com.Contador.Contador_de_Palabras.service;

import com.Contador.Contador_de_Palabras.model.EstadisticasPalabras;
import org.springframework.stereotype.Service;

import java.util.HashMap;
import java.util.Map;

@Service
public class ContadorPalabrasServicio {

    public EstadisticasPalabras analizarTexto(String texto) {
        if (texto == null || texto.isBlank()) {
            return new EstadisticasPalabras(0, new HashMap<>());
        }

        // Convertimos el texto a minúsculas y dividimos por palabras usando expresiones regulares
        String[] palabras = texto.toLowerCase().split("\\W+");
        Map<String, Integer> frecuencia = new HashMap<>();

        for (String palabra : palabras) {
            frecuencia.put(palabra, frecuencia.getOrDefault(palabra, 0) + 1);
        }

        return new EstadisticasPalabras(palabras.length, frecuencia);
    }
}