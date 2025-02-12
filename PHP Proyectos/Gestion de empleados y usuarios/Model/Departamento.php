<?php

class Departamento
{
    private $ID_Departamento;
    private $Nombre;

    public function __construct($ID_Departamento, $Nombre)
    {
        $this->ID_Departamento = $ID_Departamento;
        $this->Nombre = $Nombre;
    }

    public function getIDDepartamento()
    {
        return $this->ID_Departamento;
    }

    public function getNombre()
    {
        return $this->Nombre;
    }

    public function setID_Departamento($ID_Departamento)
    {
        $this->ID_Departamento = $ID_Departamento;
    }

    public function setNombre($Nombre)
    {
        $this->Nombre = $Nombre;
    }
}