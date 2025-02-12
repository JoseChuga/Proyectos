<?php

class Rol
{
    private $idRol;
    private $rol;

    public function __construct($idRol, $rol)
    {
        $this->idRol = $idRol;
        $this->rol = $rol;
    }

    public function getIdRol()
    {
        return $this->idRol;
    }

    public function getRol()
    {
        return $this->rol;
    }
}
