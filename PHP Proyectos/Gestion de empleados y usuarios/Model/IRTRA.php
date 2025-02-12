<?php

class IRTRA
{
    private $idIRTRA;
    private $mes;
    private $anio;
    private $montoPatronal;
    private $idEmpleado;
    private $idPoliza;

    private $NombreCompleto;

    public function __construct($idIRTRA = null, $mes = null, $anio = null, $montoPatronal = null, $idEmpleado = null, $idPoliza = null, $NombreCompleto = null)
    {
        $this->idIRTRA = $idIRTRA;
        $this->mes = $mes;
        $this->anio = $anio;
        $this->montoPatronal = $montoPatronal;
        $this->idEmpleado = $idEmpleado;
        $this->idPoliza = $idPoliza;
        $this->NombreCompleto = $NombreCompleto;
    }

    // Getters y Setters
    public function getIdIRTRA()
    {
        return $this->idIRTRA;
    }

    public function getNombreCompleto()
    {
        return $this->NombreCompleto;
    }

    public function getMes()
    {
        return $this->mes;
    }

    public function setMes($mes)
    {
        $this->mes = $mes;
    }

    public function getAnio()
    {
        return $this->anio;
    }

    public function setAnio($anio)
    {
        $this->anio = $anio;
    }

    public function getMontoPatronal()
    {
        return $this->montoPatronal;
    }

    public function setMontoPatronal($montoPatronal)
    {
        $this->montoPatronal = $montoPatronal;
    }

    public function getIdEmpleado()
    {
        return $this->idEmpleado;
    }

    public function setIdEmpleado($idEmpleado)
    {
        $this->idEmpleado = $idEmpleado;
    }

    public function getIdPoliza()
    {
        return $this->idPoliza;
    }

    public function setIdPoliza($idPoliza)
    {
        $this->idPoliza = $idPoliza;
    }
}