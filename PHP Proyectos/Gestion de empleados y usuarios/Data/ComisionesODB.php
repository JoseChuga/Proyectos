<?php
require_once 'SQLSRVConnector.php';
require_once '../Model/Comisiones.php';

class ComisionesODB {
    private $connection;

    public function __construct() {
        $this->connection = SQLSRVConnector::getInstance()->getConnection(); // Obtén la conexión aquí
        if ($this->connection === null) {
            die("Error: No se pudo establecer la conexión con la base de datos.");
        }
    }

    // Obtener todas las comisiones de un empleado
    public function getAll() : array {
        $query = "EXEC MostrarComisionesVentaS";
        $stmt = $this->connection->prepare($query);
        try {
            $stmt->execute();
            $comisiones = [];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Aquí creas objetos de la clase Comisiones
                $comision = new Comisiones(
                    $row['ID_Comision'],
                    $row['Mes'],
                    $row['Anio'],
                    $row['Monto_Ventas'],
                    $row['Porcentaje'],
                    $row['Comision'],
                    $row['ID_Empleado'],
                    $row['NombreCompleto'],
                    $row['Cuenta_Contable']
                );
                $comisiones[] = $comision;
            }

            return $comisiones;
        } catch (PDOException $e) {
            throw new Exception("Error en la consulta: " . $e->getMessage());
        }
    }

    public function getByID($ID_Comision) {
        try {
            $sql = "EXEC [dbo].[BuscarComisionesPorEmpleado] :ID_Comision";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':ID_Comision', $ID_Comision, PDO::PARAM_INT);

            // Ejecutar el procedimiento almacenado
            $stmt->execute();

            // Retornar los resultados
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;

        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error: " . $e->getMessage();
            return false;
        }
    }



    // Insertar una nueva comisión y póliza
    public function insertarComisionYPoliza($mes, $anio, $montoVentas, $porcentaje, $comision, $idEmpleado, $descripcion) {
        $query = "EXEC InsertarComisionYPoliza :Mes, :Anio, :Monto_Ventas, :Porcentaje, :Comision, :ID_Empleado, :Descripcion";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(":Mes", $mes);
        $stmt->bindParam(":Anio", $anio);
        $stmt->bindParam(":Monto_Ventas", $montoVentas);
        $stmt->bindParam(":Porcentaje", $porcentaje);
        $stmt->bindParam(":Comision", $comision);
        $stmt->bindParam(":ID_Empleado", $idEmpleado);
        $stmt->bindParam(":Descripcion", $descripcion);
        $stmt->execute();
    }


    // Modificar una comisión existente
    public function update($idComision, $mes, $anio, $montoVentas, $porcentaje, $idEmpleado, $descripcion) {
        $query = "EXEC ModificarComisionVentas :ID_Comision, :Mes, :Anio, :Monto_Ventas, :Porcentaje, :ID_Empleado, :Descripcion";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(":ID_Comision", $idComision);
        $stmt->bindParam(":Mes", $mes);
        $stmt->bindParam(":Anio", $anio);
        $stmt->bindParam(":Monto_Ventas", $montoVentas);
        $stmt->bindParam(":Porcentaje", $porcentaje);
        $stmt->bindParam(":ID_Empleado", $idEmpleado);
        $stmt->bindParam(":Descripcion", $descripcion);
        $stmt->execute();
    }

    // Borrar una comisión y su póliza asociada
    public function delete($idComision) {
        $query = "EXEC BorrarComisionVentas :ID_Comision";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(":ID_Comision", $idComision);
        $stmt->execute();
    }
}
?>
