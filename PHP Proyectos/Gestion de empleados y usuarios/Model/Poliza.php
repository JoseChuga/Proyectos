<?php
class Poliza
{
    private $ID_Poliza;
    private $Fecha;
    private $Descripcion;
    private $Monto;
    private $ID_Empleado;
    private $NombreCompleto;
    private $CuentaContable;

    public function __construct($ID_Poliza, $Fecha, $Descripcion, $Monto, $ID_Empleado, $NombreCompleto=null, $CuentaContable=null, $NombreContable=null)
    {
        $this->ID_Poliza = $ID_Poliza;
        $this->Fecha = $Fecha;
        $this->Descripcion = $Descripcion;
        $this->Monto = $Monto;
        $this->ID_Empleado = $ID_Empleado;
        $this->NombreCompleto = $NombreCompleto;
        $this->CuentaContable = $CuentaContable;
    }

    public function getIDPoliza()
    {
        return $this->ID_Poliza;
    }

    public function getFecha()
    {
        return $this->Fecha;
    }

    public function getDescripcion()
    {
        return $this->Descripcion;
    }

    public function getMonto()
    {
        return $this->Monto;
    }

    public function getIDEmpleado()
    {
        return $this->ID_Empleado;
    }

    public function getNombreCompleto()
    {
        return $this->NombreCompleto;
    }

    public function getCuentaContable()
    {
        return $this->CuentaContable;
    }

    public function setIDPoliza($ID_Poliza)
    {
        $this->ID_Poliza = $ID_Poliza;
    }

    public function setFecha($Fecha)
    {
        $this->Fecha = $Fecha;
    }

    public function setDescripcion($Descripcion)
    {
        $this->Descripcion = $Descripcion;
    }

    public function setMonto($Monto)
    {
        $this->Monto = $Monto;
    }

    public function setIDEmpleado($ID_Empleado)
    {
        return $this->ID_Empleado = $ID_Empleado;
    }

    public function setNombreCompleto($NombreCompleto)
    {
        return $this->NombreCompleto = $NombreCompleto;
    }

    public function setCuentaContable($CuentaContable)
    {
        return $this->CuentaContable = $CuentaContable;
    }
}
