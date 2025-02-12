package com.calculadora.intereses.Controller;

import com.calculadora.intereses.dto.InteresRequest;
import com.calculadora.intereses.dto.InteresResponse;
import com.calculadora.intereses.service.CalculadoraService;
import jakarta.validation.Valid;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

@RestController
@RequestMapping("/api/calculadora")
public class CalculadoraController {

    @Autowired
    private CalculadoraService service;

    @PostMapping("/calcular")
    public ResponseEntity<InteresResponse> calcularInteres(@Valid @RequestBody InteresRequest request) {
        InteresResponse response = service.calcularInteres(request);
        return ResponseEntity.ok(response);
    }
}
