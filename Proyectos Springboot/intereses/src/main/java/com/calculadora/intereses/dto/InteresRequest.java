package com.calculadora.intereses.dto;

import jakarta.validation.constraints.*;

public class InteresRequest {

    @NotNull(message = "El monto es obligatorio")
    @Positive(message = "El monto debe ser mayor a 0")
    private Double monto;

    @NotNull(message = "La tasa de interés es obligatoria")
    @Positive(message = "La tasa de interés debe ser mayor a 0")
    private Double tasaInteres;

    @NotNull(message = "El plazo es obligatorio")
    @Positive(message = "El plazo debe ser mayor a 0")
    private Integer plazo;

    @NotNull(message = "El tipo de interés es obligatorio")
    @Pattern(regexp = "simple|compuesto", message = "El tipo de interés debe ser 'simple' o 'compuesto'")
    private String tipoInteres;

    public Double getMonto() {
        return monto;
    }

    public void setMonto(Double monto) {
        this.monto = monto;
    }

    public Double getTasaInteres() {
        return tasaInteres;
    }

    public void setTasaInteres(Double tasaInteres) {
        this.tasaInteres = tasaInteres;
    }

    public Integer getPlazo() {
        return plazo;
    }

    public void setPlazo(Integer plazo) {
        this.plazo = plazo;
    }

    public String getTipoInteres() {
        return tipoInteres;
    }

    public void setTipoInteres(String tipoInteres) {
        this.tipoInteres = tipoInteres;
    }

    
}
