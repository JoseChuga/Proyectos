<?php

require_once '../Model/Ausencia.php';
require_once '../Data/SQLSRVConnector.php';

class AusenciaODB {
    private $connection;

    public function __construct() {
        $this->connection = SQLSRVConnector::getInstance()->getConnection();
        if ($this->connection === null) {
            die("Error: No se pudo establecer la conexión con la base de datos.");
        }
    }

    // Obtener todas las ausencias
    public function getAll() {
        $sql = "EXEC MostrarAusencias";
        $stmt = $this->connection->query($sql);
        $ausencias = [];

        while ($row = $stmt->fetch()) {
            $ausencias[] = new Ausencia(
                $row['ID_Solicitud'],
                $row['FechaSolicitud'],
                $row['Fecha_Inicio'],
                $row['Fecha_Fin'],
                $row['Motivo'],
                $row['Descripcion'],
                $row['Estado'],
                $row['Cuenta_Salario'],
                $row['Descuento'],
                $row['ID_Empleado'],
                $row['NombreCompleto']
            );
        }

        return $ausencias;
    }

    // Obtener una ausencia por su ID_Solicitud
    public function getById($idSolicitud) {
        $sql = "EXEC BuscarAusencia :idSolicitud";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':idSolicitud', $idSolicitud);
        $stmt->execute();
        $row = $stmt->fetch();
        if ($row) {
            return new Ausencia(
                $row['ID_Solicitud'],
                $row['FechaSolicitud'],
                $row['Fecha_Inicio'],
                $row['Fecha_Fin'],
                $row['Motivo'],
                $row['Descripcion'],
                $row['Estado'],
                $row['Cuenta_Salario'],
                $row['Descuento'],
                $row['ID_Empleado']
            );
        }

        return null;
    }


    // Insertar una nueva ausencia
    public function insert($ausencia) {
        try {
            // Asignar valores por defecto
            $cuentaSalario = null; // Enviar como NULL
            $descuento = null; // Enviar como NULL
            $estado = 'PENDIENTE'; // Estado por defecto si faltan valores

            // Obtener valores del objeto ausencia
            $fechaSolicitud = $ausencia->getFechaSolicitud();
            $fechaInicio = $ausencia->getFechaInicio();
            $fechaFin = $ausencia->getFechaFin();
            $motivo = $ausencia->getMotivo();
            $descripcion = $ausencia->getDescripcion();
            $idEmpleado = $ausencia->getIdEmpleado(); // Almacenar ID empleado en una variable

            $query = "EXEC InsertarAusencia ?, ?, ?, ?, ?, ?, ?, ?, ?"; // Consulta

            $stmt = $this->connection->prepare($query);

            // Asignar parámetros a la consulta
            $stmt->bindParam(1, $fechaSolicitud, PDO::PARAM_STR);
            $stmt->bindParam(2, $fechaInicio, PDO::PARAM_STR);
            $stmt->bindParam(3, $fechaFin, PDO::PARAM_STR);
            $stmt->bindParam(4, $motivo, PDO::PARAM_STR);
            $stmt->bindParam(5, $descripcion, PDO::PARAM_STR);
            $stmt->bindParam(6, $estado, PDO::PARAM_STR); // Usar estado por defecto
            $stmt->bindParam(7, $cuentaSalario, PDO::PARAM_BOOL); // Enviar como NULL
            $stmt->bindParam(8, $descuento, PDO::PARAM_STR); // Enviar como NULL
            $stmt->bindParam(9, $idEmpleado, PDO::PARAM_INT); // Usar variable para ID Empleado

            return $stmt->execute(); // Ejecutar consulta
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage());
            return false; // En caso de error
        }
    }




    // Actualizar una ausencia existente
    public function update($idAusencia, $idEmpleado, $fechaSolicitud, $fechaInicio, $fechaFin, $motivo, $descripcion, $estado, $cuentaSalario, $descuento)
    {

        // Imprimir las fechas para depuración
        echo "Fecha Solicitud: $fechaSolicitud\n";
        echo "Fecha Inicio: $fechaInicio\n";
        echo "Fecha Fin: $fechaFin\n";

        // Validar que las fechas estén en el formato correcto
        if (!DateTime::createFromFormat('Y-m-d', $fechaSolicitud) ||
            !DateTime::createFromFormat('Y-m-d', $fechaInicio) ||
            !DateTime::createFromFormat('Y-m-d', $fechaFin)) {
            throw new Exception("Una o más fechas no son válidas.");
        }

        $sql = "EXEC ModificarAusencia 
            @ID_Solicitud = :idSolicitud, 
            @ID_Empleado = :idEmpleado, 
            @FechaSolicitud = :fechaSolicitud, 
            @Fecha_Inicio = :fechaInicio, 
            @Fecha_Fin = :fechaFin, 
            @Motivo = :motivo, 
            @Descripcion = :descripcion, 
            @Estado = :estado, 
            @Cuenta_Salario = :cuentaSalario, 
            @Descuento = :descuento";

        $stmt = $this->connection->prepare($sql);

        // Vincular parámetros
        $stmt->bindParam(':idSolicitud', $idAusencia);
        $stmt->bindParam(':idEmpleado', $idEmpleado);
        $stmt->bindParam(':fechaSolicitud', $fechaSolicitud);
        $stmt->bindParam(':fechaInicio', $fechaInicio);
        $stmt->bindParam(':fechaFin', $fechaFin);
        $stmt->bindParam(':motivo', $motivo);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':cuentaSalario', $cuentaSalario);
        $stmt->bindParam(':descuento', $descuento);

        // Ejecutar la consulta y manejar errores
        if (!$stmt->execute()) {
            $errorInfo = $stmt->errorInfo();
            throw new Exception("Error al actualizar la ausencia: " . $errorInfo[2]);
        }

        return true; // Devuelve verdadero si la actualización fue exitosa
    }


    // Eliminar una ausencia por su ID_Solicitud
    public function delete($idSolicitud) {
        $sql = "EXEC BorrarAusencia :idSolicitud";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':idSolicitud', $idSolicitud);
        $stmt->execute();
    }

    }

?>

