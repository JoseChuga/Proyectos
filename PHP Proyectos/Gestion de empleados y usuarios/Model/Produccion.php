<?php
class Produccion
{
    private $ID_Produccion;
    private $Fecha;
    private $Piezas_Elaboradas;
    private $Bonificacion;
    private $ID_Empleado;
    private $ID_Poliza;

    private $NombreCompleto;
    private $CuentaContable;

    public function __construct($ID_Produccion, $Fecha, $Piezas_Elaboradas, $Bonificacion, $ID_Empleado, $ID_Poliza, $NombreCompleto = null, $CuentaContable = null)
    {
        $this->ID_Produccion = $ID_Produccion;
        $this->Fecha = $Fecha;
        $this->Piezas_Elaboradas = $Piezas_Elaboradas;
        $this->Bonificacion = $Bonificacion;
        $this->ID_Empleado = $ID_Empleado;
        $this->ID_Poliza = $ID_Poliza;
        $this->NombreCompleto = $NombreCompleto;
        $this->CuentaContable = $CuentaContable;
    }

    public function getIDProduccion()
    {
        return $this->ID_Produccion;
    }

    public function getFecha()
    {
        return $this->Fecha;
    }

    public function getPiezasElaboradas()
    {
        return $this->Piezas_Elaboradas;
    }

    public function getBonificacion()
    {
        return $this->Bonificacion;
    }

    public function getIDEmpleado()
    {
        return $this->ID_Empleado;
    }

    public function getIDPoliza()
    {
        return $this->ID_Poliza;
    }

    public function getNombreCompleto()
    {
        return $this->NombreCompleto;
    }

    public function getCuentaContable()
    {
        return $this->CuentaContable;
    }

    public function setIDProduccion($ID_Produccion)
    {
        $this->ID_Produccion = $ID_Produccion;
    }

    public function setFecha($Fecha)
    {
        $this->Fecha = $Fecha;
    }

    public function setPiezasElaboradas($Piezas_Elaboradas)
    {
        $this->Piezas_Elaboradas = $Piezas_Elaboradas;
    }

    public function setBonificacion($Bonificacion)
    {
        $this->Bonificacion = $Bonificacion;
    }

    public function setIDEmpleado($ID_Empleado)
    {
        $this->ID_Empleado = $ID_Empleado;
    }

    public function setIDPoliza($ID_Poliza)
    {
        $this->ID_Poliza = $ID_Poliza;
    }

    public function setNombreCompleto($NombreCompleto)
    {
        $this->NombreCompleto = $NombreCompleto;
    }

    public function setCuentaContable($CuentaContable)
    {
        $this->CuentaContable = $CuentaContable;
    }
}
?>
