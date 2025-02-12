<?php

require_once 'SQLSRVConnector.php';
require_once '../Model/Empleado.php';
require_once '../Model/Departamento.php';

class EmpleadoODB {
    private $connection;

    public function __construct() {
        $this->connection = SQLSRVConnector::getInstance()->getConnection();
        if ($this->connection === null) {
            die("Error: No se pudo establecer la conexión con la base de datos.");
        }
    }

    public function getAll(): array {
        $query = "EXEC MostrarEmpleados";
        $result = $this->connection->query($query);

        $empleados = [];
        while ($row = $result->fetch()) {
            $empleado = new Empleado(
                $row['ID_Empleado'],
                $row['Nombre'],
                $row['Apellido'],
                $row['Fecha_Nacimiento'],
                $row['Fecha_Contratacion'],
                $row['Salario_Base'],
                new Departamento($row['ID_Departamento'], $row['Departamento']),
                $row['Foto'],
                1,// Activo siempre será 1 en este caso, porque ya se filtraron los inactivos
                 $row['Cuenta_Contable']
            );
            array_push($empleados, $empleado);
        }

        return $empleados;
    }

    public function getById($id) {
        $query = "EXEC BuscarEmpleado @ID_Empleado = :ID_Empleado";
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':ID_Empleado', $id, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $departamento = new Departamento($row['ID_Departamento'], $row['Departamento']);

                return new Empleado(
                    $row['ID_Empleado'],
                    $row['Nombre'],
                    $row['Apellido'],
                    $row['Fecha_Nacimiento'],
                    $row['Fecha_Contratacion'],
                    $row['Salario_Base'],
                    $departamento,
                    $row['Foto'],
                    $row['Activo'],
                    $row['Cuenta_Contable']
                );
            }
        } catch (PDOException $e) {
            error_log("Failed to execute query: " . $e->getMessage());
            return null;
        }

        return null;
    }

    public function update($idEmpleado, $nombre, $apellido, $fechaNacimiento, $fechaContratacion, $salarioBase, $deptoId, $foto, $Cuenta_Contable) {
        try {
            if ($foto) {
                // Consulta cuando hay una foto
                $query = "EXEC ModificarEmpleado 
                        @ID_Empleado = :ID_Empleado, 
                        @Nombre = :Nombre, 
                        @Apellido = :Apellido, 
                        @Fecha_Nacimiento = :Fecha_Nacimiento, 
                        @Fecha_Contratacion = :Fecha_Contratacion, 
                        @Salario_Base = :Salario_Base, 
                        @ID_Departamento = :ID_Departamento, 
                        @Foto = :Foto,
                        @Cuenta_Contable = :Cuenta_Contable";
            } else {
                // Consulta cuando no hay foto
                $query = "EXEC ModificarEmpleado 
                        @ID_Empleado = :ID_Empleado, 
                        @Nombre = :Nombre, 
                        @Apellido = :Apellido, 
                        @Fecha_Nacimiento = :Fecha_Nacimiento, 
                        @Fecha_Contratacion = :Fecha_Contratacion, 
                        @Salario_Base = :Salario_Base, 
                        @ID_Departamento = :ID_Departamento, 
                        @Foto = NULL,
                        @Cuenta_Contable = :Cuenta_Contable";
            }

            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':ID_Empleado', $idEmpleado, PDO::PARAM_INT);
            $stmt->bindParam(':Nombre', $nombre);
            $stmt->bindParam(':Apellido', $apellido);
            $stmt->bindParam(':Fecha_Nacimiento', $fechaNacimiento);
            $stmt->bindParam(':Fecha_Contratacion', $fechaContratacion);
            $stmt->bindParam(':Salario_Base', $salarioBase);
            $stmt->bindParam(':ID_Departamento', $deptoId, PDO::PARAM_INT);
            $stmt->bindParam(':Cuenta_Contable', $Cuenta_Contable);


            if ($foto) {
                $stmt->bindParam(':Foto', $foto, PDO::PARAM_LOB);
            }

            // Depuración: Imprimir la consulta SQL
            $queryDebug = $query;
            $queryDebug = str_replace(':ID_Empleado', $idEmpleado, $queryDebug);
            $queryDebug = str_replace(':Nombre', $nombre, $queryDebug);
            $queryDebug = str_replace(':Apellido', $apellido, $queryDebug);
            $queryDebug = str_replace(':Fecha_Nacimiento', $fechaNacimiento, $queryDebug);
            $queryDebug = str_replace(':Fecha_Contratacion', $fechaContratacion, $queryDebug);
            $queryDebug = str_replace(':Salario_Base', $salarioBase, $queryDebug);
            $queryDebug = str_replace(':ID_Departamento', $deptoId, $queryDebug);
            $queryDebug = str_replace(':Foto', $foto ? 'BLOB DATA' : 'NULL', $queryDebug);
            $stmt->bindParam(':Cuenta_Contable', $Cuenta_Contable);

            error_log("SQL Query: $queryDebug");

            $result = $stmt->execute();

            // Depuración: Verificar el resultado de la ejecución
            if ($result) {
                error_log("Actualización exitosa para el empleado ID: $idEmpleado");
            } else {
                error_log("Error en la actualización para el empleado ID: $idEmpleado");
            }

            return $result;
        } catch (PDOException $e) {
            error_log("Failed to execute update: " . $e->getMessage());
            return false;
        }
    }

    public function insert($empleado) {
        try {
            $activo = 1;  // Estado por defecto al insertar un nuevo empleado

            // Obtener los valores del objeto empleado
            $nombre = $empleado->getNombre();
            $apellido = $empleado->getApellido();
            $fechaNacimiento = $empleado->getFechaNacimiento();
            $fechaContratacion = $empleado->getFechaContratacion();
            $salarioBase = $empleado->getSalarioBase();
            $deptoID = $empleado->getDepartamento()->getIdDepartamento();
            $foto = $empleado->getFoto() !== null ? $empleado->getFoto() : null;
            $cuentaContable = $empleado->getCuentaContable();  // El nuevo campo

            // Consulta ajustada para incluir o excluir la foto según corresponda
            if ($foto) {
                $query = "EXEC InsertarEmpleado 
                @Nombre = :Nombre, 
                @Apellido = :Apellido, 
                @Fecha_Nacimiento = :Fecha_Nacimiento, 
                @Fecha_Contratacion = :Fecha_Contratacion, 
                @Salario_Base = :Salario_Base, 
                @ID_Departamento = :ID_Departamento, 
                @Foto = :Foto,
                @Activo = :Activo,
                @Cuenta_Contable = :Cuenta_Contable";
            } else {
                $query = "EXEC InsertarEmpleado 
                @Nombre = :Nombre, 
                @Apellido = :Apellido, 
                @Fecha_Nacimiento = :Fecha_Nacimiento, 
                @Fecha_Contratacion = :Fecha_Contratacion, 
                @Salario_Base = :Salario_Base, 
                @ID_Departamento = :ID_Departamento, 
                @Foto = NULL,
                @Activo = :Activo,
                @Cuenta_Contable = :Cuenta_Contable";
            }

            // Preparar la consulta
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':Nombre', $nombre);
            $stmt->bindParam(':Apellido', $apellido);
            $stmt->bindParam(':Fecha_Nacimiento', $fechaNacimiento);
            $stmt->bindParam(':Fecha_Contratacion', $fechaContratacion);
            $stmt->bindParam(':Salario_Base', $salarioBase);
            $stmt->bindParam(':ID_Departamento', $deptoID, PDO::PARAM_INT);
            $stmt->bindParam(':Activo', $activo, PDO::PARAM_BOOL);
            $stmt->bindParam(':Cuenta_Contable', $cuentaContable);

            // Vincular la foto solo si existe
            if ($foto) {
                $stmt->bindParam(':Foto', $foto, PDO::PARAM_LOB);
            }

            // Ejecutar la consulta
            $result = $stmt->execute();

            // Verificación de la inserción
            if ($result) {
                error_log("Inserción exitosa para el empleado: $nombre $apellido");
            } else {
                error_log("Error en la inserción para el empleado: $nombre $apellido");
            }

            return $result;
        } catch (PDOException $e) {
            error_log("Excepción capturada: " . $e->getMessage());
            return false;
        }
    }

    public function delete($id) {
        $query = "EXEC DesactivarEmpleado @ID_Empleado = :ID_Empleado";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':ID_Empleado', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getSalarioBase($idEmpleado) {
        // Consulta para obtener el salario base de un empleado
        $query = "SELECT Salario_Base FROM Empleado WHERE ID_Empleado = ?";
        $stmt = $this->connection->prepare($query);

        // Vincular el parámetro ID del empleado
        $stmt->bindParam(1, $idEmpleado, PDO::PARAM_INT);

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener el resultado
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Devolver el salario base o null si no se encuentra el empleado
        return $result ? $result['Salario_Base'] : null;
    }

}