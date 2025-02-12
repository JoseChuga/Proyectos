<?php

class Nomina {
    private $idNomina;
    private $mes;
    private $anio;
    private $salarioBase;
    private $pagoNomina;
    private $horasExtras;
    private $horasDobles;
    private $comision;
    private $bonificacion;
    private $descuentoPrestamo;
    private $descuentoTienda;
    private $tipoPeriodo;
    private $salarioFinal;
    private $idEmpleado;
    private $idHoraExtra;
    private $idCompra;
    private $idPrestamo;
    private $idPoliza;

    private $NombreCompleto;

    // Constructor con parámetros opcionales
    public function __construct(
        $idNomina = null, $mes, $anio, $salarioBase, $pagoNomina, $horasExtras,
        $horasDobles, $comision, $bonificacion, $descuentoPrestamo,
        $descuentoTienda, $tipoPeriodo, $salarioFinal, $idEmpleado, $idHoraExtra = null,
        $idCompra = null, $idPrestamo = null, $idPoliza = null, $NombreCompleto = null
    ) {
        $this->idNomina = $idNomina;
        $this->mes = $mes;
        $this->anio = $anio;
        $this->salarioBase = $salarioBase;
        $this->pagoNomina = $pagoNomina;
        $this->horasExtras = $horasExtras;
        $this->horasDobles = $horasDobles;
        $this->comision = $comision;
        $this->bonificacion = $bonificacion;
        $this->descuentoPrestamo = $descuentoPrestamo;
        $this->descuentoTienda = $descuentoTienda;
        $this->tipoPeriodo = $tipoPeriodo;
        $this->salarioFinal = $salarioFinal;
        $this->idEmpleado = $idEmpleado;
        $this->idHoraExtra = $idHoraExtra;
        $this->idCompra = $idCompra;
        $this->idPrestamo = $idPrestamo;
        $this->idPoliza = $idPoliza;
        $this->NombreCompleto = $NombreCompleto;
    }

    // Métodos getter y setter para todos los atributos

    public function getIdNomina() {
        return $this->idNomina;
    }

    public function setIdNomina($idNomina) {
        $this->idNomina = $idNomina;
    }

    public function getNombreCompleto() {
        return $this->NombreCompleto;
    }

    public function setNombreCompleto($NombreCompleto) {
        $this->NombreCompleto = $NombreCompleto;
    }

    public function getMes() {
        return $this->mes;
    }

    public function setMes($mes) {
        $this->mes = $mes;
    }

    public function getAnio() {
        return $this->anio;
    }

    public function setAnio($anio) {
        $this->anio = $anio;
    }

    public function getSalarioBase() {
        return $this->salarioBase;
    }

    public function setSalarioBase($salarioBase) {
        $this->salarioBase = $salarioBase;
    }

    public function getPagoNomina() {
        return $this->pagoNomina;
    }

    public function setPagoNomina($pagoNomina) {
        $this->pagoNomina = $pagoNomina;
    }

    public function getHorasExtras() {
        return $this->horasExtras;
    }

    public function setHorasExtras($horasExtras) {
        $this->horasExtras = $horasExtras;
    }

    public function getHorasDobles() {
        return $this->horasDobles;
    }

    public function setHorasDobles($horasDobles) {
        $this->horasDobles = $horasDobles;
    }

    public function getComision() {
        return $this->comision;
    }

    public function setComision($comision) {
        $this->comision = $comision;
    }

    public function getDeduccion() {
        return $this->deduccion;
    }

    public function setDeduccion($deduccion) {
        $this->deduccion = $deduccion;
    }

    public function getBonificacion() {
        return $this->bonificacion;
    }

    public function setBonificacion($bonificacion) {
        $this->bonificacion = $bonificacion;
    }

    public function getDescuentoPrestamo() {
        return $this->descuentoPrestamo;
    }

    public function setDescuentoPrestamo($descuentoPrestamo) {
        $this->descuentoPrestamo = $descuentoPrestamo;
    }

    public function getDescuentoTienda() {
        return $this->descuentoTienda;
    }

    public function setDescuentoTienda($descuentoTienda) {
        $this->descuentoTienda = $descuentoTienda;
    }

    public function getTipoPeriodo() {
        return $this->tipoPeriodo;
    }

    public function setTipoPeriodo($tipoPeriodo) {
        $this->tipoPeriodo = $tipoPeriodo;
    }

    public function getSalarioFinal() {
        return $this->salarioFinal;
    }

    public function setSalarioFinal($salarioFinal) {
        $this->salarioFinal = $salarioFinal;
    }

    public function getIdEmpleado() {
        return $this->idEmpleado;
    }

    public function setIdEmpleado($idEmpleado) {
        $this->idEmpleado = $idEmpleado;
    }

    public function getIdHoraExtra() {
        return $this->idHoraExtra;
    }

    public function setIdHoraExtra($idHoraExtra) {
        $this->idHoraExtra = $idHoraExtra;
    }

    public function getIdCompra() {
        return $this->idCompra;
    }

    public function setIdCompra($idCompra) {
        $this->idCompra = $idCompra;
    }

    public function getIdPrestamo() {
        return $this->idPrestamo;
    }

    public function setIdPrestamo($idPrestamo) {
        $this->idPrestamo = $idPrestamo;
    }

    public function getIdPoliza() {
        return $this->idPoliza;
    }

    public function setIdPoliza($idPoliza) {
        $this->idPoliza = $idPoliza;
    }
}
