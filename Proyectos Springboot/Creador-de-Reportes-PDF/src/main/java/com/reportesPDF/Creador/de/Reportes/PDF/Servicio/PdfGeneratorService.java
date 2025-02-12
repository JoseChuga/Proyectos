package com.reportesPDF.Creador.de.Reportes.PDF.Servicio;

import org.apache.pdfbox.pdmodel.PDDocument;
import org.apache.pdfbox.pdmodel.PDPage;
import org.apache.pdfbox.pdmodel.PDPageContentStream;
import org.apache.pdfbox.pdmodel.font.PDType1Font;
import org.springframework.stereotype.Service;

import java.io.ByteArrayOutputStream;
import java.util.Map;

@Service
public class PdfGeneratorService {
    public byte[] generatePdf(String title, Map<String, String> details) {
        try (PDDocument document = new PDDocument()) {
            PDPage page = new PDPage();
            document.addPage(page);

            try (PDPageContentStream contentStream = new PDPageContentStream(document, page)) {
                contentStream.setFont(PDType1Font.HELVETICA_BOLD, 18);
                contentStream.beginText();
                contentStream.newLineAtOffset(100, 700);
                contentStream.showText(title);
                contentStream.endText();

                contentStream.setFont(PDType1Font.HELVETICA, 12);
                int y = 650;
                for (Map.Entry<String, String> entry : details.entrySet()) {
                    contentStream.beginText();
                    contentStream.newLineAtOffset(100, y);
                    contentStream.showText(entry.getKey() + ": " + entry.getValue());
                    contentStream.endText();
                    y -= 20;
                }
            }

            ByteArrayOutputStream out = new ByteArrayOutputStream();
            document.save(out);
            return out.toByteArray();
        } catch (Exception e) {
            throw new RuntimeException("Error al generar el PDF", e);
        }
    }
}
