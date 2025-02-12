<?php
class Tienda
{
    private $ID_Compra;
    private $Cuotas;
    private $Monto;
    private $Max_Credit;
    private $Saldo_Pendiente;
    private $Credito_Disponible;
    private $ID_Empleado;
    private $NombreCompleto;
    private $CuentaContable;
    private $Total;
    private $Fecha;
    private $Compra;
    private $Pago;

    public function __construct(
        $ID_Compra,
        $Cuotas = null,
        $Monto = null,
        $Max_Credit = null,
        $Saldo_Pendiente,
        $Credito_Disponible = null,
        $ID_Empleado,
        $NombreCompleto = null,
        $CuentaContable = null,
        $Total = null,
        $Fecha = null,
        $Compra = null,
        $Pago = null
    ) {
        $this->ID_Compra = $ID_Compra;
        $this->Cuotas = $Cuotas;
        $this->Monto = $Monto;
        $this->Max_Credit = $Max_Credit;
        $this->Saldo_Pendiente = $Saldo_Pendiente;
        $this->Credito_Disponible = $Credito_Disponible;
        $this->ID_Empleado = $ID_Empleado;
        $this->NombreCompleto = $NombreCompleto;
        $this->CuentaContable = $CuentaContable;
        $this->Total = $Total;
        $this->Fecha = $Fecha;
        $this->Compra = $Compra;
        $this->Pago = $Pago;
    }

    public function getIDCompra()
    {
        return $this->ID_Compra;
    }

    public function getCompra()
    {
        return $this->Compra;
    }

    public function getMonto()
    {
        return $this->Monto;
    }

    public function getPago()
    {
        return $this->Pago;
    }

    public function getFecha()
    {
        return $this->Fecha;
    }

    public function getCuotas()
    {
        return $this->Cuotas;
    }

    public function getMaxCredit()
    {
        return $this->Max_Credit;
    }

    public function getSaldoPendiente()
    {
        return $this->Saldo_Pendiente;
    }

    public function getCreditoDisponible()
    {
        return $this->Credito_Disponible;
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

    public function getTotal()
    {
        return $this->Total;
    }

    public function setIDCompra($ID_Compra)
    {
        $this->ID_Compra = $ID_Compra;
    }

    public function setPago($Pago)
    {
        return $this->Pago = $Pago;
    }

    public function setMonto($Monto)
    {
        return $this->Monto = $Monto;
    }


    public function setCompra($Compra)
    {
        return $this->Compra = $Compra;
    }

    public function setFecha($Fecha)
    {
        return $this->Fecha = $Fecha;
    }

    public function setCuotas($Cuotas)
    {
        $this->Cuotas = $Cuotas;
    }

    public function setMaxCredit($Max_Credit)
    {
        $this->Max_Credit = $Max_Credit;
    }

    public function setSaldoPendiente($Saldo_Pendiente)
    {
        $this->Saldo_Pendiente = $Saldo_Pendiente;
    }

    public function setCreditoDisponible($Credito_Disponible)
    {
        $this->Credito_Disponible = $Credito_Disponible;
    }

    public function setIDEmpleado($ID_Empleado)
    {
        $this->ID_Empleado = $ID_Empleado;
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
