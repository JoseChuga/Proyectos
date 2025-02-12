<?php

class Comisiones
{
    private $ID_Comision;
    private $mes;
    private $anio;
    private $montoVentas;
    private $porcentaje;
    private $comision;
    private $idEmpleado;
    private $nombreCompleto;
    private $cuentaContable;

    public function __construct($ID_Comision, $mes, $anio, $montoVentas, $porcentaje, $comision, $idEmpleado, $nombreCompleto, $cuentaContable) {
        $this->ID_Comision = $ID_Comision;
        $this->mes = $mes;
        $this->anio = $anio;
        $this->montoVentas = $montoVentas;
        $this->porcentaje = $porcentaje;
        $this->comision = $comision;
        $this->idEmpleado = $idEmpleado;
        $this->nombreCompleto = $nombreCompleto;
        $this->cuentaContable = $cuentaContable;
    }

    // Métodos Getters
    public function getIDComision() {
        return $this->ID_Comision;
    }

    public function getMes() {
        return $this->mes;
    }

    public function getAnio() {
        return $this->anio;
    }

    public function getMontoVentas() {
        return $this->montoVentas;
    }

    public function getPorcentaje() {
        return $this->porcentaje;
    }

    public function getComision() {
        return $this->comision;
    }

    public function getIDEmpleado() {
        return $this->idEmpleado;
    }

    public function getNombreCompleto() {
        return $this->nombreCompleto;
    }

    public function getCuentaContable() {
        return $this->cuentaContable;
    }

    // Métodos Setters
    public function setIDComision($ID_Comision) {
        $this->ID_Comision = $ID_Comision;
    }

    public function setMes($mes) {
        $this->mes = $mes;
    }

    public function setAnio($anio) {
        $this->anio = $anio;
    }

    public function setMontoVentas($montoVentas) {
        $this->montoVentas = $montoVentas;
    }

    public function setPorcentaje($porcentaje) {
        $this->porcentaje = $porcentaje;
    }

    public function setComision($comision) {
        $this->comision = $comision;
    }

    public function setIDEmpleado($idEmpleado) {
        $this->idEmpleado = $idEmpleado;
    }

    public function setNombreCompleto($nombreCompleto) {
        return $this->nombreCompleto = $nombreCompleto;
    }

    public function setCuentaContable($cuentaContable) {
        return $this->cuentaContable = $cuentaContable;
    }
}

