package com.calculadora.intereses.dto;

public class InteresResponse {

    private Double montoFinal;
    private Double interesGenerado;

    public InteresResponse(Double montoFinal, Double interesGenerado) {
        this.montoFinal = montoFinal;
        this.interesGenerado = interesGenerado;
    }

    public Double getMontoFinal() {
        return montoFinal;
    }

    public void setMontoFinal(Double montoFinal) {
        this.montoFinal = montoFinal;
    }

    public Double getInteresGenerado() {
        return interesGenerado;
    }

    public void setInteresGenerado(Double interesGenerado) {
        this.interesGenerado = interesGenerado;
    }

    
}
