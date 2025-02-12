<?php
class IGSS {
    private $mes;
    private $anio;
    private $montoPatronal;
    private $montoLaboral;
    private $idEmpleado;
    private $idPoliza;
    private $nombreCompleto;

    public function __construct($mes, $anio, $montoPatronal, $montoLaboral, $idEmpleado, $idPoliza, $nombreCompleto) {
        $this->mes = $mes;
        $this->anio = $anio;
        $this->montoPatronal = $montoPatronal;
        $this->montoLaboral = $montoLaboral;
        $this->idEmpleado = $idEmpleado;
        $this->idPoliza = $idPoliza;
        $this->nombreCompleto = $nombreCompleto;
    }

    // Getters
    public function getMes() {
        return $this->mes;
    }

    public function getAnio() {
        return $this->anio;
    }

    public function getMontoPatronal() {
        return $this->montoPatronal;
    }

    public function getMontoLaboral() {
        return $this->montoLaboral;
    }

    public function getIdEmpleado() {
        return $this->idEmpleado;
    }

    public function getIdPoliza() {
        return $this->idPoliza;
    }

    public function getNombreCompleto() {
        return $this->nombreCompleto;
    }

    // Setters
    public function setMes($mes) {
        $this->mes = $mes;
    }

    public function setAnio($anio) {
        $this->anio = $anio;
    }

    public function setMontoPatronal($montoPatronal) {
        $this->montoPatronal = $montoPatronal;
    }

    public function setMontoLaboral($montoLaboral) {
        $this->montoLaboral = $montoLaboral;
    }

    public function setIdEmpleado($idEmpleado) {
        $this->idEmpleado = $idEmpleado;
    }

    public function setIdPoliza($idPoliza) {
        $this->idPoliza = $idPoliza;
    }

    public function setNombreCompleto($nombreCompleto) {
        $this->nombreCompleto = $nombreCompleto;
    }
}
