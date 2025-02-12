package com.reportesPDF.Creador.de.Reportes.PDF.Controlador;

import com.reportesPDF.Creador.de.Reportes.PDF.DTO.ReportData;
import com.reportesPDF.Creador.de.Reportes.PDF.Servicio.PdfGeneratorService;
import org.springframework.http.HttpHeaders;
import org.springframework.http.MediaType;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

@RestController
@RequestMapping("/api/pdf")
public class PdfController {

    private final PdfGeneratorService pdfGeneratorService;

    public PdfController(PdfGeneratorService pdfGeneratorService) {
        this.pdfGeneratorService = pdfGeneratorService;
    }

    @PostMapping("/generate")
    public ResponseEntity<byte[]> generatePdf(@RequestBody ReportData data) {
        // Generar el PDF con Apache PDFBox
        byte[] pdf = pdfGeneratorService.generatePdf(data.getTitle(), data.getDetails());

        // Configurar la respuesta para la descarga
        return ResponseEntity.ok()
                .header(HttpHeaders.CONTENT_DISPOSITION, "attachment; filename=report.pdf")
                .contentType(MediaType.APPLICATION_PDF)
                .body(pdf);
    }
}

