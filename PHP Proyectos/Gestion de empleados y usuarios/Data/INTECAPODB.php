<?php

require_once '../Model/INTECAP.php';
require_once 'SQLSRVConnector.php';

class INTECAPODB
{
    private $connection;

    public function __construct()
    {
        $this->connection = SQLSRVConnector::getInstance()->getConnection();
        if ($this->connection === null) {
            die("Error: No se pudo establecer la conexión con la base de datos.");
        }
    }

    public function getAll() {
        $sql = "EXEC MostrarIntecap";
        $stmt = $this->connection->query($sql);
        $intecapList = [];

        while ($row = $stmt->fetch()) {
            $intecapList[] = new Intecap(
                $row['Mes'],
                $row['Anio'],
                $row['Monto_Patronal'],
                $row['ID_Empleado'],
                $row['ID_Poliza'],
                $row['NombreCompleto']
            );
        }

        return $intecapList;
    }

}