package com.reportesPDF.Creador.de.Reportes.PDF.DTO;

import java.util.Map;

public class ReportData {
    private String title;
    private Map<String, String> details;
    public String getTitle() {
        return title;
    }
    public void setTitle(String title) {
        this.title = title;
    }
    public Map<String, String> getDetails() {
        return details;
    }
    public void setDetails(Map<String, String> details) {
        this.details = details;
    }

}
