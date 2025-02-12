<?php

require_once 'SQLSRVConnector.php';
require_once '../Model/HistorialPagosPrestamos.php';

class HistorialPagosPrestamosODB
{
    private $connection;

    public function __construct()
    {
        $this->connection = SQLSRVConnector::getInstance()->getConnection(); // Obtener la conexión
        if ($this->connection === null) {
            die("Error: No se pudo establecer la conexión con la base de datos.");
        }
    }

    // Obtener todo el historial de pagos
    public function getAllByPrestamoId(int $idPrestamo): array
    {
        // Preparamos la consulta para ejecutar el procedimiento almacenado con el parámetro @ID_Prestamo
        $query = "EXEC MostrarHistorialDePagos @ID_Prestamo = :idPrestamo";

        // Preparamos la consulta con el parámetro
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':idPrestamo', $idPrestamo, PDO::PARAM_INT);
        $stmt->execute();

        $pagos = [];

        // Procesamos el resultado
        while ($row = $stmt->fetch()) {
            // Crea un nuevo objeto HistorialPagosPrestamos con los datos obtenidos
            $pago = new HistorialPagosPrestamos(
                $row['ID_Pago'],
                $row['Fecha'],
                $row['Monto'],
                $row['No_cuota'],
                $row['Saldo_Pendiente'],
                $row['ID_Poliza'],
                $row['ID_Prestamos'],
                $row['NombreCompleto']
            );

            array_push($pagos, $pago);
        }

        return $pagos;
    }


    // Obtener historial de pagos por nombre y apellido del empleado


    // Insertar un nuevo pago y actualizar el préstamo
    public function insert($pago)
    {
        $query = "EXEC InsertarHistorialYActualizarPrestamo @Fecha = ?, @Monto = ?, @No_cuota = ?, @Saldo_Pendiente = ?, @ID_Empleado = ?, @ID_Poliza = ?, @ID_Prestamos = ?";
        $stmt = $this->connection->prepare($query);

        $fecha = $pago->getFecha(); // Fecha automática
        $monto = $pago->getMonto();
        $noCuota = $pago->getNoCuota();
        $saldoPendiente = $pago->getSaldoPendiente(); // Saldo Pendiente no modificable
        $idEmpleado = $pago->getIdEmpleado(); // No modificable
        $idPoliza = $pago->getIdPoliza(); // No modificable
        $idPrestamo = $pago->getIdPrestamo();

        // Bind de los parámetros con los valores del objeto
        $stmt->bindParam(1, $fecha);
        $stmt->bindParam(2, $monto);
        $stmt->bindParam(3, $noCuota);
        $stmt->bindParam(4, $saldoPendiente);
        $stmt->bindParam(5, $idEmpleado);
        $stmt->bindParam(6, $idPoliza);
        $stmt->bindParam(7, $idPrestamo);

        // Ejecuta la consulta
        $stmt->execute();
    }

}

