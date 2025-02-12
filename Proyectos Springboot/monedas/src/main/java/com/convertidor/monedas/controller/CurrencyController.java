package com.convertidor.monedas.controller;

import com.convertidor.monedas.model.ExchangeRateResponse;
import com.convertidor.monedas.service.ExchangeRateService;
import lombok.RequiredArgsConstructor;
import org.springframework.web.bind.annotation.*;

@RestController
@RequestMapping("/api/v1/currency")
@RequiredArgsConstructor
public class CurrencyController {

    private final ExchangeRateService exchangeRateService;

    @GetMapping("/rates")
    public ExchangeRateResponse getRates(@RequestParam String base) {
        return exchangeRateService.getRates(base);
    }

    @GetMapping("/convert")
    public Double convert(
            @RequestParam String from,
            @RequestParam String to,
            @RequestParam Double amount) {
        return exchangeRateService.convert(from, to, amount);
    }
}
