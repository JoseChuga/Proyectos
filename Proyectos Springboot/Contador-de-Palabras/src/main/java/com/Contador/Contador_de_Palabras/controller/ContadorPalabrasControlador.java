package com.Contador.Contador_de_Palabras.controller;

import com.Contador.Contador_de_Palabras.model.EstadisticasPalabras;
import com.Contador.Contador_de_Palabras.service.ContadorPalabrasServicio;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.web.bind.annotation.*;

@RestController
@RequestMapping("/api/palabras")
public class ContadorPalabrasControlador {

    @Autowired
    private ContadorPalabrasServicio contadorPalabrasServicio;

    // Endpoint para analizar el texto enviado en el cuerpo de la solicitud
    @PostMapping("/analizar")
    public EstadisticasPalabras analizarTexto(@RequestBody String texto) {
        return contadorPalabrasServicio.analizarTexto(texto);
    }
}

