<?php

class Ausencia {
    private $idSolicitud;
    private $fechaSolicitud;
    private $fechaInicio;
    private $fechaFin;
    private $motivo;
    private $estado;
    private $cuentaSalario;
    private $descuento;
    private $idEmpleado;
    private $descripcion;

    private $NombreCompleto;

    // Constructor con los 10 parámetros
    public function __construct($idSolicitud=null, $fechaSolicitud, $fechaInicio, $fechaFin, $motivo, $descripcion, $estado, $cuentaSalario, $descuento, $idEmpleado, $NombreCompleto=null) {
        $this->idSolicitud = $idSolicitud;
        $this->fechaSolicitud = $fechaSolicitud;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
        $this->motivo = $motivo;
        $this->descripcion = $descripcion;
        $this->estado = $estado;
        $this->cuentaSalario = $cuentaSalario;
        $this->descuento = $descuento;
        $this->idEmpleado = $idEmpleado;
        $this->NombreCompleto = $NombreCompleto;
    }

    // Métodos getter y setter para todos los atributos

    public function getIdSolicitud() {
        return $this->idSolicitud;
    }

    public function getFechaSolicitud() {
        return $this->fechaSolicitud;
    }

    public function getFechaInicio() {
        return $this->fechaInicio;
    }

    public function getFechaFin() {
        return $this->fechaFin;
    }

    public function getMotivo() {
        return $this->motivo;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function getCuentaSalario() {
        return $this->cuentaSalario;
    }

    public function getDescuento() {
        return $this->descuento;
    }

    public function getIdEmpleado() {
        return $this->idEmpleado;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function getNombreCompleto() {
        return $this->NombreCompleto;
    }

    public function setIdSolicitud($idSolicitud) {
        $this->idSolicitud = $idSolicitud;
    }

    public function setFechaSolicitud($fechaSolicitud) {
        $this->fechaSolicitud = $fechaSolicitud;
    }

    public function setFechaInicio($fechaInicio) {
        $this->fechaInicio = $fechaInicio;
    }

    public function setFechaFin($fechaFin) {
        $this->fechaFin = $fechaFin;
    }

    public function setMotivo($motivo) {
        $this->motivo = $motivo;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

    public function setCuentaSalario($cuentaSalario) {
        $this->cuentaSalario = $cuentaSalario;
    }

    public function setDescuento($descuento) {
        $this->descuento = $descuento;
    }

    public function setIdEmpleado($idEmpleado) {
        $this->idEmpleado = $idEmpleado;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function setNombreCompleto($NombreCompleto) {
        return $this->NombreCompleto = $NombreCompleto;
    }
}

?>


