<?php

class Prestamo {
    private $idPrestamo;
    private $monto;
    private $cuotas;
    private $fechaInicio;
    private $cuotasRestantes;
    private $saldoPendiente;
    private $cancelado;
    private $idEmpleado;
    private $idPoliza;
    private $CuentaContable;
    private $NombreCompleto;

    // Constructor con los parámetros correspondientes
    public function __construct($idPrestamo = null,
                                $monto = null,
                                $cuotas = null,
                                $fechaInicio = null,
                                $cuotasRestantes = null,
                                $saldoPendiente = null,
                                $cancelado = 0,
                                $idEmpleado = null,
                                $idPoliza = null,
                                $NombreCompleto=null,
                                $CuentaContable = null
                                ) {
        $this->idPrestamo = $idPrestamo;
        $this->monto = $monto;
        $this->cuotas = $cuotas;
        $this->fechaInicio = $fechaInicio;
        $this->cuotasRestantes = $cuotasRestantes;
        $this->saldoPendiente = $saldoPendiente;
        $this->cancelado = $cancelado;
        $this->idEmpleado = $idEmpleado;
        $this->idPoliza = $idPoliza;
        $this->NombreCompleto = $NombreCompleto;
        $this->CuentaContable = $CuentaContable;

    }

    // Métodos getter y setter para cada atributo

    public function getIdPrestamo() {
        return $this->idPrestamo;
    }

    public function getMonto() {
        return $this->monto;
    }

    public function getCuotas() {
        return $this->cuotas;
    }

    public function getFechaInicio() {
        return $this->fechaInicio;
    }

    public function getCuotasRestantes() {
        return $this->cuotasRestantes;
    }

    public function getSaldoPendiente() {
        return $this->saldoPendiente;
    }

    public function getCancelado() {
        return $this->cancelado;
    }

    public function getIdEmpleado() {
        return $this->idEmpleado;
    }

    public function getIdPoliza() {
        return $this->idPoliza;
    }

    public function getCuentaContable() {
        return $this->CuentaContable;
    }

    public function getNombreCompleto() {
        return $this->NombreCompleto;
    }

    public function setIdPrestamo($idPrestamo) {
        $this->idPrestamo = $idPrestamo;
    }

    public function setMonto($monto) {
        $this->monto = $monto;
    }

    public function setCuotas($cuotas) {
        $this->cuotas = $cuotas;
    }

    public function setFechaInicio($fechaInicio) {
        $this->fechaInicio = $fechaInicio;
    }

    public function setCuotasRestantes($cuotasRestantes) {
        $this->cuotasRestantes = $cuotasRestantes;
    }

    public function setSaldoPendiente($saldoPendiente) {
        $this->saldoPendiente = $saldoPendiente;
    }

    public function setCancelado($cancelado) {
        $this->cancelado = $cancelado;
    }

    public function setIdEmpleado($idEmpleado) {
        $this->idEmpleado = $idEmpleado;
    }

    public function setIdPoliza($idPoliza) {
        $this->idPoliza = $idPoliza;
    }

    public function setCuentaContable($CuentaContable) {
        $this->CuentaContable = $CuentaContable;
    }

    public function setNombreCompleto($NombreCompleto) {
        $this->NombreCompleto = $NombreCompleto;
    }
}

?>
