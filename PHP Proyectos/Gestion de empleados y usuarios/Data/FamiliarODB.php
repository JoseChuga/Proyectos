<?php

require_once 'SQLSRVConnector.php';
require_once '../Model/Familiar.php';

class FamiliarODB {
    private $connection;

    public function __construct() {
        $this->connection = SQLSRVConnector::getInstance()->getConnection();
        if ($this->connection === null) {
            die("Error: No se pudo establecer la conexión con la base de datos.");
        }
    }

    public function getAll(): array {
        $query = "EXEC ListarFamiliares"; // Supongo que tienes un procedimiento almacenado para mostrar familiares
        $result = $this->connection->query($query);

        $familiares = [];
        while ($row = $result->fetch()) {
            $familiar = new Familiar(
                $row['IDFamiliar'],
                $row['Nombre'],
                $row['Apellido'],
                $row['Relacion'],
                $row['FechaNacimiento'],
                $row['ID_Empleado']
            );
            array_push($familiares, $familiar);
        }

        return $familiares;
    }

    public function getById($idFamiliar) {
        $query = "EXEC BuscarFamiliar @IDFamiliar = :IDFamiliar";
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':IDFamiliar', $idFamiliar, PDO::PARAM_INT); // Cambiado $id a $idFamiliar
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                return new Familiar(
                    $row['IDFamiliar'],
                    $row['Nombre'],
                    $row['Apellido'],
                    $row['Relacion'],
                    $row['FechaNacimiento'],
                    $row['ID_Empleado']
                );
            }
        } catch (PDOException $e) {
            error_log("Failed to execute query: " . $e->getMessage());
            return null;
        }

        return null;
    }

    public function update($idFamiliar, $nombre, $apellido, $relacion, $fechaNacimiento, $idEmpleado) {
        $query = "EXEC ModificarFamiliar 
              @IDFamiliar = :IDFamiliar, 
              @Nombre = :Nombre, 
              @Apellido = :Apellido, 
              @Relacion = :Relacion, 
              @FechaNacimiento = :FechaNacimiento, 
              @ID_Empleado = :ID_Empleado";

        try {
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':IDFamiliar', $idFamiliar, PDO::PARAM_INT);
            $stmt->bindParam(':Nombre', $nombre);
            $stmt->bindParam(':Apellido', $apellido);
            $stmt->bindParam(':Relacion', $relacion);
            $stmt->bindParam(':FechaNacimiento', $fechaNacimiento);
            $stmt->bindParam(':ID_Empleado', $idEmpleado, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Failed to execute update: " . $e->getMessage());
            return false;
        }
    }

    public function insert($familiar) {
        try {
            // Extraer los atributos del objeto familiar
            $nombre = $familiar->getNombre();
            $apellido = $familiar->getApellido();
            $relacion = $familiar->getRelacion();
            $fechaNacimiento = $familiar->getFechaNacimiento();
            $idEmpleado = $familiar->getIdEmpleado();

            // Definir la consulta SQL usando parámetros posicionales
            $query = "EXEC InsertarFamiliar @Nombre = ?, 
                                        @Apellido = ?, 
                                        @Relacion = ?, 
                                        @FechaNacimiento = ?, 
                                        @ID_Empleado = ?";

            // Preparar la declaración SQL
            $stmt = $this->connection->prepare($query);

            // Vincular los parámetros con sus tipos correctos
            $stmt->bindParam(1, $nombre, PDO::PARAM_STR);
            $stmt->bindParam(2, $apellido, PDO::PARAM_STR);
            $stmt->bindParam(3, $relacion, PDO::PARAM_STR);
            $stmt->bindParam(4, $fechaNacimiento, PDO::PARAM_STR);
            $stmt->bindParam(5, $idEmpleado, PDO::PARAM_INT);

            // Ejecutar la declaración y devolver el resultado
            return $stmt->execute();
        } catch (PDOException $e) {
            // Registrar el mensaje de error
            error_log("Failed to execute insert: " . $e->getMessage());
            // Devolver falso para indicar fracaso
            return false;
        }
    }

    public function delete($idFamiliar) {
        try {
            $stmt = $this->connection->prepare("EXEC BorrarFamiliarPorID :ID_Familiar");
            $stmt->bindParam(':ID_Familiar', $idFamiliar, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function buscarFamiliaresPorEmpleado($idEmpleado) {
        $familiares = [];
        try {
            $query = "EXEC BuscarFamiliarPorEmpleado @ID_Empleado = :idEmpleado";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':idEmpleado', $idEmpleado, PDO::PARAM_STR);
            $stmt->execute();
            while ($row = $stmt->fetch()) {
                $familiar = new Familiar(
                    $row['IDFamiliar'],
                    $row['Nombre'],
                    $row['Apellido'],
                    $row['Relacion'],
                    $row['FechaNacimiento'],
                    $row['ID_Empleado'],
                    $row['NombreCompleto']
            );
                array_push($familiares, $familiar);
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        return $familiares;
    }
}
?>
