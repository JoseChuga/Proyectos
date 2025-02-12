<?php
require_once '../Data/SQLSRVConnector.php';
require_once '../Model/Nomina.php';

class NominaODB
{
    private $connection;

    public function __construct()
    {
        $this->connection = SQLSRVConnector::getInstance()->getConnection();
        if ($this->connection === null) {
            die("Error: No se pudo establecer la conexión con la base de datos.");
        }
    }

    // Función para ejecutar el procedimiento almacenado GenerarNomina y devolver array de objetos Nomina
    public function generarNomina()
    {
        try {
            // Ejecutar el procedimiento que genera la nómina
            $sql = "EXEC GenerarNomina";
            $stmt = $this->connection->query($sql);

            // Ahora, ejecutamos el procedimiento que muestra la nómina
            $sqlMostrar = "EXEC MostrarNomina";
            $stmtMostrar = $this->connection->query($sqlMostrar);

            if (!$stmtMostrar) {
                throw new Exception("Error al ejecutar la consulta MostrarNomina: " . implode(", ", $this->connection->errorInfo()));
            }

            // Usamos fetchAll para obtener todos los resultados de una vez
            $rows = $stmtMostrar->fetchAll(PDO::FETCH_ASSOC);

            if (empty($rows)) {
                // Manejo del caso en que no hay resultados
                return []; // Devuelve un array vacío
            }

            $nominas = [];
            foreach ($rows as $row) {
                $nominas[] = new Nomina(
                    $row['idNomina'] ?? null,
                    $row['Mes'],
                    $row['Anio'],
                    $row['SalarioBase'],
                    $row['PagoNomina'],
                    $row['Horas_Extras'],
                    $row['Horas_Dobles'],
                    $row['Comision'],
                    $row['Bonificacion'],
                    $row['Descuento_Prestamo'],
                    $row['Descuento_Tienda'],
                    $row['Tipo_Periodo'],
                    $row['SalarioFinal'],
                    $row['ID_Empleado'],
                    $row['ID_HoraExtra'] ?? null,
                    $row['ID_Compra'] ?? null,
                    $row['ID_Prestamo'] ?? null,
                    $row['ID_Poliza'] ?? null,
                    $row['NombreCompleto']
                );
            }

            return $nominas; // Devuelve el array de objetos Nomina
        } catch (PDOException $e) {
            echo "Error de base de datos: " . $e->getMessage();
            return []; // Devuelve un array vacío en caso de error
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return []; // Devuelve un array vacío en caso de error
        }
    }
}