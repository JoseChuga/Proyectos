package com.convertidor.monedas.model;

import lombok.Data;
import java.util.Map;

import com.fasterxml.jackson.annotation.JsonProperty;

@Data
public class ExchangeRateResponse {
    
    @JsonProperty("base")
    private String base;

    @JsonProperty("rates")
    private Map<String, Double> rates;

    @JsonProperty("date")
    private String date;

    public String getBase() {
        return base;
    }

    public void setBase(String base) {
        this.base = base;
    }

    public Map<String, Double> getRates() {
        return rates;
    }

    public void setRates(Map<String, Double> rates) {
        this.rates = rates;
    }

    public String getDate() {
        return date;
    }

    public void setDate(String date) {
        this.date = date;
    }
}