<?php
require_once 'SQLSRVConnector.php';
require_once '../Model/HorasExtras.php';

class HorasExtrasODB {
    private $connection;

    public function __construct() {
        $this->connection = SQLSRVConnector::getInstance()->getConnection();
        if ($this->connection === null) {
            die("Error: No se pudo establecer la conexión con la base de datos.");
        }
    }

    // Obtener todas las horas extras
    public function getAll() : array {
        $query = "EXEC MostrarHorasExtras";
        $result = $this->connection->query($query);
        $horasExtras = [];
        while ($row = $result->fetch()) {
            $horaExtra = new HorasExtras(
                $row['ID_HoraExtra'],
                $row['Fecha'],
                $row['Hora_Normal'],
                $row['Hora_Doble'],
                $row['Total_Normal'],
                $row['Total_Doble'],
                $row['ID_Empleado'],
                $row['NombreCompleto']
            );
            array_push($horasExtras, $horaExtra);
        }
        return $horasExtras;
    }

    // Obtener hora extra por ID
    public function getById($id) {
        $query = "EXEC BuscarHoraExtra ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $row = $stmt->fetch();
        return new HorasExtras(
            $row['ID_HoraExtra'],
            $row['Fecha'],
            $row['Hora_Normal'],
            $row['Hora_Doble'],
            $row['Total_Normal'],
            $row['Total_Doble'],
            $row['ID_Empleado']
        );
    }

    // Insertar nueva hora extra
    public function insert($horaExtra) {
        $query = "EXEC AgregarHorasExtras ?, ?, ?, ?, ?, ?"; // Modificado para usar el nuevo procedimiento
        $stmt = $this->connection->prepare($query);

        $fecha = $horaExtra->getFecha(); // Método para obtener la fecha
        $horaNormal = $horaExtra->getHoraNormal();
        $horaDoble = $horaExtra->getHoraDoble();
        $totalNormal = $horaExtra->getTotalNormal(); // Asegúrate de que estos métodos existan
        $totalDoble = $horaExtra->getTotalDoble(); // Asegúrate de que estos métodos existan
        $idEmpleado = $horaExtra->getIDEmpleado();

        $stmt->bindParam(1, $fecha);
        $stmt->bindParam(2, $horaNormal);
        $stmt->bindParam(3, $horaDoble);
        $stmt->bindParam(4, $totalNormal);
        $stmt->bindParam(5, $totalDoble);
        $stmt->bindParam(6, $idEmpleado);

        // Ejecutar la consulta
        return $stmt->execute();
    }

    // Actualizar hora extra
    public function update(HorasExtras $horasExtra) {
        $query = "EXEC ModificarHoraExtra ?, ?, ?, ?, ?, ?, ?"; // 7 parámetros
        $stmt = $this->connection->prepare($query);

        // Asignar los valores de los atributos del objeto
        $idHoraExtra = $horasExtra->getIdHoraExtra();
        $fecha = $horasExtra->getFecha();
        $horaNormal = $horasExtra->getHoraNormal();
        $horaDoble = $horasExtra->getHoraDoble();
        $totalNormal = $horasExtra->getTotalNormal(); // Obtener el total normal
        $totalDoble = $horasExtra->getTotalDoble(); // Obtener el total doble
        $idEmpleado = $horasExtra->getIdEmpleado();

        $stmt->bindParam(1, $idHoraExtra);
        $stmt->bindParam(2, $fecha);
        $stmt->bindParam(3, $horaNormal);
        $stmt->bindParam(4, $horaDoble);
        $stmt->bindParam(5, $totalNormal); // Asegúrate de que se pasa el total normal
        $stmt->bindParam(6, $totalDoble); // Asegúrate de que se pasa el total doble
        $stmt->bindParam(7, $idEmpleado); // Este es el parámetro de ID_Empleado

        // Ejecutar la consulta
        return $stmt->execute();
    }


    // Borrar hora extra por ID
    public function delete($id) {
        $query = "EXEC BorrarHoraExtra ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
    }
}
