<?php
require_once 'SQLSRVConnector.php';
require_once '../Model/Prestamo.php';

class PrestamoODB {
    private $connection;

    public function __construct() {
        $this->connection = SQLSRVConnector::getInstance()->getConnection(); // Obtener la conexión
        if ($this->connection === null) {
            die("Error: No se pudo establecer la conexión con la base de datos.");
        }
    }

    // Obtener todos los préstamos
    public function getAll() : array {
        $query = "EXEC MostrarTodosLosPrestamos";
        $result = $this->connection->query($query);
        $prestamos = [];
        while ($row = $result->fetch()) {
            $prestamo = new Prestamo(
                $row['ID_Prestamo'],
                $row['Monto'],
                $row['Cuotas'],
                $row['FechaInicio'],
                $row['Cuotas_Restantes'],
                $row['Saldo_Pendiente'],
                $row['Cancelado'],
                $row['ID_Empleado'],
                $row['ID_Poliza'],
                $row['NombreCompleto'],
                $row['Cuenta_Contable']
            );
            // Agregar el objeto prestamo al array
            $prestamos[] = $prestamo;
        }
        return $prestamos;
    }


    // Obtener un préstamo por ID
    public function getById($id) {
        $query = "EXEC ObtenerPrestamoYPoliza @ID_Prestamo = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $row = $stmt->fetch();
        return new Prestamo($row['ID_Prestamo'], $row['Monto'], $row['Cuotas'], $row['FechaInicio'], $row['Cuotas_Restantes'], $row['Saldo_Pendiente'], $row['Cancelado'], $row['ID_Empleado'], $row['ID_Poliza']);
    }

    // Insertar un nuevo préstamo
    public function insert($prestamo) {
        // Prepara la consulta
        $query = "EXEC InsertarPrestamo @Monto = ?, @Cuotas = ?, @FechaInicio = ?, @Cuotas_Restantes = ?, @Saldo_Pendiente = ?, @Cancelado = ?, @ID_Empleado = ?";
        $stmt = $this->connection->prepare($query);

        $monto = $prestamo->getMonto();
        $cuotas = $prestamo->getCuotas();
        $fechaInicio = $prestamo->getFechaInicio();
        $cuotasRestantes = $prestamo->getCuotasRestantes();
        $saldoPendiente = $prestamo->getSaldoPendiente();
        $cancelado = $prestamo->getCancelado();
        $idEmpleado = $prestamo->getIdEmpleado();

        $stmt->bindParam(1, $monto);
        $stmt->bindParam(2, $cuotas);
        $stmt->bindParam(3, $fechaInicio);
        $stmt->bindParam(4, $cuotasRestantes);
        $stmt->bindParam(5, $saldoPendiente);
        $stmt->bindParam(6, $cancelado);
        $stmt->bindParam(7, $idEmpleado);

        // Ejecuta la consulta
        $stmt->execute();
    }

    // Modificar un préstamo existente
    public function update($prestamo) {
        $query = "EXEC ModificarPrestamo @ID_Prestamo = ?, @Monto = ?, @Cuotas = ?, @FechaInicio = ?, @Cuotas_Restantes = ?, @Saldo_Pendiente = ?, @Cancelado = ?, @ID_Empleado = ?, @ID_Poliza = ?";
        $stmt = $this->connection->prepare($query);

        // Obtención de los parámetros del objeto $prestamo
        $idPrestamo = $prestamo->getIdPrestamo();
        $monto = $prestamo->getMonto();
        $cuotas = $prestamo->getCuotas();
        $fechaInicio = $prestamo->getFechaInicio();
        $cuotasRestantes = $prestamo->getCuotasRestantes();
        $saldoPendiente = $prestamo->getSaldoPendiente();
        $cancelado = $prestamo->getCancelado();
        $idEmpleado = $prestamo->getIdEmpleado();
        $idPoliza = $prestamo->getIdPoliza();  // Asegúrate de que este valor sea correcto

        // Vinculación de parámetros
        $stmt->bindParam(1, $idPrestamo);
        $stmt->bindParam(2, $monto);
        $stmt->bindParam(3, $cuotas);
        $stmt->bindParam(4, $fechaInicio);
        $stmt->bindParam(5, $cuotasRestantes);
        $stmt->bindParam(6, $saldoPendiente);
        $stmt->bindParam(7, $cancelado);
        $stmt->bindParam(8, $idEmpleado);
        $stmt->bindParam(9, $idPoliza);

        // Ejecuta la consulta
        $stmt->execute();
    }

    public function getPagoPorPrestamoId($idPrestamo)
    {
        // Consulta para ejecutar el procedimiento almacenado
        $query = "EXEC PagoPrestamo @ID_Prestamo = :idPrestamo";
        // Preparamos la consulta
        $query = $this->connection->prepare($query);
        $query->bindParam(':idPrestamo', $idPrestamo, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);

        // Verificar si el resultado es válido
        if ($result) {
            // Crear un objeto Prestamo y asignar los valores obtenidos de la base de datos
            $prestamo = new Prestamo();
            $prestamo->setMonto($result['Monto']);
            $prestamo->setCuotasRestantes($result['Cuotas_Restantes']);
            $prestamo->setSaldoPendiente($result['Saldo_Pendiente']);
            $prestamo->setIdEmpleado($result['ID_Empleado']);
            $prestamo->setIdPoliza($result['ID_Poliza']);
            $prestamo->setCuentaContable($result['Cuenta_Contable']);
            $prestamo->setNombreCompleto($result['NombreCompleto']);

            return $prestamo;
        } else {
            return null; // Si no hay resultados, devolver null
        }

        // Si no se encuentra un resultado, devolvemos null
        return null;
    }

}
?>
