package com.Contador.Contador_de_Palabras.model;

import java.util.Map;

public class EstadisticasPalabras {
    private int totalPalabras; // Número total de palabras en el texto
    private Map<String, Integer> frecuenciaPalabras; // Frecuencia de cada palabra

    public EstadisticasPalabras(int totalPalabras, Map<String, Integer> frecuenciaPalabras) {
        this.totalPalabras = totalPalabras;
        this.frecuenciaPalabras = frecuenciaPalabras;
    }

    public int getTotalPalabras() {
        return totalPalabras;
    }

    public void setTotalPalabras(int totalPalabras) {
        this.totalPalabras = totalPalabras;
    }

    public Map<String, Integer> getFrecuenciaPalabras() {
        return frecuenciaPalabras;
    }

    public void setFrecuenciaPalabras(Map<String, Integer> frecuenciaPalabras) {
        this.frecuenciaPalabras = frecuenciaPalabras;
    }
}
