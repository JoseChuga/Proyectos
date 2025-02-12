<?php
require_once '../Model/IRTRA.php';
require_once 'SQLSRVConnector.php';

class IRTRAODB
{

    private $connection;

    public function __construct() {
        $this->connection = SQLSRVConnector::getInstance()->getConnection();
        if ($this->connection === null) {
            die("Error: No se pudo establecer la conexión con la base de datos.");
        }
    }

    public function getAll() {
        $sql = "EXEC MostrarIrtra";
        $stmt = $this->connection->query($sql);
        $registrosIRTRA = [];

        while ($row = $stmt->fetch()) {
            $registrosIRTRA[] = new IRTRA(
                $row['Mes'],
                $row['Anio'],
                $row['Monto_Patronal'],
                $row['ID_Empleado'],
                $row['ID_Poliza'],
                $row['NombreCompleto']
            );
        }

        return $registrosIRTRA;
    }

}