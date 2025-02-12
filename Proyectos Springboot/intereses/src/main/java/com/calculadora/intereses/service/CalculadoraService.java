package com.calculadora.intereses.service;

import com.calculadora.intereses.dto.InteresRequest;
import com.calculadora.intereses.dto.InteresResponse;
import org.springframework.stereotype.Service;

@Service
public class CalculadoraService {

    public InteresResponse calcularInteres(InteresRequest request) {
        double monto = request.getMonto();
        double tasa = request.getTasaInteres() / 100;
        int plazo = request.getPlazo();
        double interesGenerado;
        double montoFinal;

        if (request.getTipoInteres().equalsIgnoreCase("simple")) {
            interesGenerado = monto * tasa * plazo;
            montoFinal = monto + interesGenerado;
        } else {
            montoFinal = monto * Math.pow(1 + tasa, plazo);
            interesGenerado = montoFinal - monto;
        }

        return new InteresResponse(montoFinal, interesGenerado);
    }
}
