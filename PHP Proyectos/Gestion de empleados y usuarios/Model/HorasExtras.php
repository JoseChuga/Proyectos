<?php

class HorasExtras
{
    private $ID_HoraExtra;
    private $Fecha;
    private $Hora_Normal;
    private $Hora_Doble;
    private $Total_Normal;
    private $Total_Doble;
    private $ID_Empleado;
    private $NombreCompleto;

    public function __construct($ID_HoraExtra, $Fecha, $Hora_Normal, $Hora_Doble, $Total_Normal, $Total_Doble, $ID_Empleado,  $NombreCompleto=null)
    {
        $this->ID_HoraExtra = $ID_HoraExtra;
        $this->Fecha = $Fecha;
        $this->Hora_Normal = $Hora_Normal;
        $this->Hora_Doble = $Hora_Doble;
        $this->Total_Normal = $Total_Normal;
        $this->Total_Doble = $Total_Doble;
        $this->ID_Empleado = $ID_Empleado;
        $this->NombreCompleto = $NombreCompleto;
    }
    public function getIDHoraExtra()
    {
        return $this->ID_HoraExtra;
    }

    public function getFecha()
    {
        return $this->Fecha;
    }

    public function getHoraNormal()
    {
        return $this->Hora_Normal;
    }

    public function getHoraDoble()
    {
        return $this->Hora_Doble;
    }

    public function getTotalNormal()
    {
        return $this->Total_Normal;
    }

    public function getTotalDoble()
    {
        return $this->Total_Doble;
    }

    public function getIDEmpleado()
    {
        return $this->ID_Empleado;
    }

    public function getNombreCompleto() {
        return $this->NombreCompleto;
    }

    public function setIDHoraExtra($ID_HoraExtra)
    {
        $this->ID_HoraExtra = $ID_HoraExtra;
    }

    public function setFecha($Fecha)
    {
        $this->Fecha = $Fecha;
    }

    public function setHoraNormal($Hora_Normal)
    {
        $this->Hora_Normal = $Hora_Normal;
    }

    public function setHoraDoble($Hora_Doble)
    {
        $this->Hora_Doble = $Hora_Doble;
    }

    public function setTotalNormal($Total_Normal)
    {
        $this->Total_Normal = $Total_Normal;
    }

    public function setTotalDoble($Total_Doble)
    {
        $this->Total_Doble = $Total_Doble;
    }

    public function setIDEmpleado($ID_Empleado)
    {
        $this->ID_Empleado = $ID_Empleado;
    }

    public function setNombreCompleto($NombreCompleto) {
        return $this->NombreCompleto = $NombreCompleto;
    }

}
