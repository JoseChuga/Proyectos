<?php

class Conexion
{
    private $con;

    public function __construct()
    {
        try {
            $this->con = new PDO('sqlsrv:server=JSUHULA\UMGDB2;database=TConsulting', null, null, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::SQLSRV_ATTR_DIRECT_QUERY => true,
                PDO::SQLSRV_ATTR_FETCHES_NUMERIC_TYPE => true,
                PDO::SQLSRV_ATTR_ENCODING => PDO::SQLSRV_ENCODING_UTF8
            ]);
        } catch (PDOException $e) {
            error_log("Connection error: " . $e->getMessage());
            // Handle exception
        }
    }

    public function getUser()
    {
        try {
            $stmt = $this->con->prepare("EXEC MostrarEmpleados");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Query error: " . $e->getMessage());
            return [];
        }
    }

    public function modificarEmpleado($ID_Empleado, $Nombre, $Apellido, $Fecha_Nac, $Fecha_Contra, $Salario_Base, $Depto_ID, $Foto)
    {
        try {
            // Asegurarse de que los datos están en UTF-8
            $Nombre = mb_convert_encoding($Nombre, 'UTF-8');
            $Apellido = mb_convert_encoding($Apellido, 'UTF-8');

            if ($Foto === NULL) {
                // No modificar la columna Foto
                $query = "UPDATE Empleado SET Nombre = :Nombre, Apellido = :Apellido, Fecha_Nac = :Fecha_Nac, Fecha_Contra = :Fecha_Contra, Salario_Base = :Salario_Base, Depto_ID = :Depto_ID WHERE ID_Empleado = :ID_Empleado";
                $stmt = $this->con->prepare($query);
                $stmt->bindParam(':Nombre', $Nombre);
                $stmt->bindParam(':Apellido', $Apellido);
                $stmt->bindParam(':Fecha_Nac', $Fecha_Nac);
                $stmt->bindParam(':Fecha_Contra', $Fecha_Contra);
                $stmt->bindParam(':Salario_Base', $Salario_Base);
                $stmt->bindParam(':Depto_ID', $Depto_ID);
                $stmt->bindParam(':ID_Empleado', $ID_Empleado);
            } else {
                // Modificar la columna Foto también
                $query = "UPDATE Empleado SET Nombre = :Nombre, Apellido = :Apellido, Fecha_Nac = :Fecha_Nac, Fecha_Contra = :Fecha_Contra, Salario_Base = :Salario_Base, Depto_ID = :Depto_ID, Foto = :Foto WHERE ID_Empleado = :ID_Empleado";
                $stmt = $this->con->prepare($query);
                $stmt->bindParam(':Nombre', $Nombre);
                $stmt->bindParam(':Apellido', $Apellido);
                $stmt->bindParam(':Fecha_Nac', $Fecha_Nac);
                $stmt->bindParam(':Fecha_Contra', $Fecha_Contra);
                $stmt->bindParam(':Salario_Base', $Salario_Base);
                $stmt->bindParam(':Depto_ID', $Depto_ID);
                $stmt->bindParam(':Foto', $Foto, PDO::PARAM_LOB); // Parámetro tipo LOB para datos binarios
                $stmt->bindParam(':ID_Empleado', $ID_Empleado);
            }

            if ($stmt->execute()) {
                return true;
            } else {
                // Mostrar el error si la ejecución falla
                echo "Error en la consulta: " . implode(" ", $stmt->errorInfo());
                return false;
            }
        } catch (PDOException $e) {
            // Capturar cualquier excepción y mostrar el mensaje
            echo "Excepción capturada: " . $e->getMessage();
            return false;
        }
    }

    public function insertarEmpleado($Nombre, $Apellido, $Fecha_Nac, $Fecha_Contra, $Salario_Base, $Depto_ID, $Foto)
    {
        try {
            // Asegurarse de que los datos están en UTF-8
            $Nombre = mb_convert_encoding($Nombre, 'UTF-8');
            $Apellido = mb_convert_encoding($Apellido, 'UTF-8');

            // Comprobar si se proporciona una foto o no
            if ($Foto === NULL) {
                // No se proporciona foto
                $query = "INSERT INTO Empleado (Nombre, Apellido, Fecha_Nac, Fecha_Contra, Salario_Base, Depto_ID)
                      VALUES (:Nombre, :Apellido, :Fecha_Nac, :Fecha_Contra, :Salario_Base, :Depto_ID)";
                $stmt = $this->con->prepare($query);
                $stmt->bindParam(':Nombre', $Nombre);
                $stmt->bindParam(':Apellido', $Apellido);
                $stmt->bindParam(':Fecha_Nac', $Fecha_Nac);
                $stmt->bindParam(':Fecha_Contra', $Fecha_Contra);
                $stmt->bindParam(':Salario_Base', $Salario_Base);
                $stmt->bindParam(':Depto_ID', $Depto_ID);
            } else {
                // Se proporciona una foto
                $query = "INSERT INTO Empleado (Nombre, Apellido, Fecha_Nac, Fecha_Contra, Salario_Base, Depto_ID, Foto)
                      VALUES (:Nombre, :Apellido, :Fecha_Nac, :Fecha_Contra, :Salario_Base, :Depto_ID, :Foto)";
                $stmt = $this->con->prepare($query);
                $stmt->bindParam(':Nombre', $Nombre);
                $stmt->bindParam(':Apellido', $Apellido);
                $stmt->bindParam(':Fecha_Nac', $Fecha_Nac);
                $stmt->bindParam(':Fecha_Contra', $Fecha_Contra);
                $stmt->bindParam(':Salario_Base', $Salario_Base);
                $stmt->bindParam(':Depto_ID', $Depto_ID);
                // Parámetro tipo LOB para datos binarios
                $stmt->bindParam(':Foto', $Foto, PDO::PARAM_LOB);
            }

            if ($stmt->execute()) {
                return true;
            } else {
                // Mostrar el error si la ejecución falla
                echo "Error en la consulta: " . implode(" ", $stmt->errorInfo());
                return false;
            }
        } catch (PDOException $e) {
            // Capturar cualquier excepción y mostrar el mensaje
            echo "Excepción capturada: " . $e->getMessage();
            return false;
        }
    }

    public function borrarEmpleado($ID_Empleado)
    {
        try {
            $query = "EXEC BorrarEmpleado @ID_Empleado = :ID_Empleado";
            $stmt = $this->con->prepare($query);
            $stmt->bindParam(':ID_Empleado', $ID_Empleado, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return true;
            } else {
                echo "Error en la consulta: " . implode(" ", $stmt->errorInfo());
                return false;
            }
        } catch (PDOException $e) {
            echo "Excepción capturada: " . $e->getMessage();
            return false;
        }
    }

    public function insertarAusencia($FechaSolicitud, $Fecha_Inicio, $Fecha_Fin, $Motivo, $Descripcion, $Estado, $Cuenta_Salario, $Descuento, $ID_Empleado)
    {
        try {
            $query = "EXEC InsertarAusencia @FechaSolicitud = :FechaSolicitud, @Fecha_Inicio = :Fecha_Inicio, @Fecha_Fin = :Fecha_Fin, @Motivo = :Motivo, @Descripcion = :Descripcion, @Estado= :Estado, @Cuenta_Salario= :Cuenta_Salario, @Descuento= :Descuento, @ID_Empleado = :ID_Empleado";
            $stmt = $this->con->prepare($query);

            $stmt->bindParam(':FechaSolicitud', $FechaSolicitud);
            $stmt->bindParam(':Fecha_Inicio', $Fecha_Inicio);
            $stmt->bindParam(':Fecha_Fin', $Fecha_Fin);
            $stmt->bindParam(':Motivo', $Motivo);
            $stmt->bindParam(':Descripcion', $Descripcion);
            $stmt->bindParam(':Estado', $Estado);
            $stmt->bindParam(':Cuenta_Salario', $Cuenta_Salario);
            $stmt->bindParam(':Descuento', $Descuento);
            $stmt->bindParam(':ID_Empleado', $ID_Empleado);

            if ($stmt->execute()) {
                return true;
            } else {
                echo "Error en la inserción: " . implode(" ", $stmt->errorInfo());
                return false;
            }
        } catch (PDOException $e) {
            echo "Excepción capturada: " . $e->getMessage();
            return false;
        }
    }

    public function buscarAusenciaReciente($ID_Empleado)
    {
        try {
            $query = "EXEC MostrarAusenciasPorEmpleado @ID_Empleado = :ID_Empleado";
            $stmt = $this->con->prepare($query);
            $stmt->bindParam(':ID_Empleado', $ID_Empleado, PDO::PARAM_INT);
            $stmt->execute();

            $ausencias = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($ausencias) > 0) {
                // Ordenar por FechaSolicitud y devolver la más reciente
                usort($ausencias, function ($a, $b) {
                    return strtotime($b['FechaSolicitud']) - strtotime($a['FechaSolicitud']);
                });
                return $ausencias[0]; // Devolver la ausencia más reciente
            } else {
                return null;
            }
        } catch (PDOException $e) {
            echo "Error al buscar la ausencia: " . $e->getMessage();
            return null;
        }
    }


    public function modificarAusencia($ID_Solicitud, $FechaSolicitud, $Fecha_Inicio, $Fecha_Fin, $Motivo, $Descripcion, $Estado, $Cuenta_Salario, $Descuento, $ID_Empleado)
    {
        try {
            $query = "EXEC ModificarAusencia @ID_Solicitud = :ID_Solicitud, @FechaSolicitud = :FechaSolicitud, @Fecha_Inicio = :Fecha_Inicio, 
                @Fecha_Fin = :Fecha_Fin, @Motivo = :Motivo, @Descripcion = :Descripcion, @Estado = :Estado, @Cuenta_Salario = :Cuenta_Salario, 
                @Descuento = :Descuento, @ID_Empleado = :ID_Empleado";

            $stmt = $this->con->prepare($query);

            $stmt->bindParam(':ID_Solicitud', $ID_Solicitud, PDO::PARAM_INT);
            $stmt->bindParam(':FechaSolicitud', $FechaSolicitud);
            $stmt->bindParam(':Fecha_Inicio', $Fecha_Inicio);
            $stmt->bindParam(':Fecha_Fin', $Fecha_Fin);
            $stmt->bindParam(':Motivo', $Motivo);
            $stmt->bindParam(':Descripcion', $Descripcion);
            $stmt->bindParam(':Estado', $Estado);
            $stmt->bindParam(':Cuenta_Salario', $Cuenta_Salario, PDO::PARAM_BOOL);
            $stmt->bindParam(':Descuento', $Descuento);
            $stmt->bindParam(':ID_Empleado', $ID_Empleado, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return true;
            } else {
                echo "Error en la modificación: " . implode(" ", $stmt->errorInfo());
                return false;
            }
        } catch (PDOException $e) {
            echo "Excepción capturada: " . $e->getMessage();
            return false;
        }
    }

    public function getAusencias()
    {
        $query = "SELECT * FROM Ausencias"; // Ajusta la consulta según tu estructura de base de datos
        $stmt = $this->con->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Función para borrar una ausencia
    public function borrarAusencia($id_solicitud)
    {
        try {
            // Prepara la llamada al procedimiento almacenado
            $stmt = $this->con->prepare("EXEC BorrarAusencia :ID_Solicitud");
            $stmt->bindParam(':ID_Solicitud', $id_solicitud, PDO::PARAM_INT);

            // Ejecuta el procedimiento almacenado
            $stmt->execute();

            // Verifica si se borró alguna fila
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            // Maneja errores
            echo "Error al borrar la ausencia: " . $e->getMessage();
            return false;
        }
    }

    public function buscarAusencia($ID_Solicitud)
    {
        try {
            $query = "SELECT * FROM Ausencias WHERE ID_Solicitud = :ID_Solicitud";
            $stmt = $this->con->prepare($query);
            $stmt->bindParam(':ID_Solicitud', $ID_Solicitud, PDO::PARAM_INT);
            $stmt->execute();

            // Retorna la ausencia si se encuentra
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    public function buscarEmpleado($ID_Empleado)
    {
        try {
            $query = "SELECT * FROM Empleado WHERE ID_Empleado = :ID_Empleado";
            $stmt = $this->con->prepare($query);
            $stmt->bindParam(':ID_Empleado', $ID_Empleado, PDO::PARAM_INT);
            $stmt->execute();

            // Retorna los datos del empleado si se encuentra
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    public function ActualizarEmpleado($ID_Empleado, $Nombre, $Apellido, $Fecha_Nac, $Fecha_Contra, $Salario_Base, $Depto_ID, $Foto) {
        try {
            $query = "UPDATE Empleado SET Nombre = :Nombre, Apellido = :Apellido, Fecha_Nac = :Fecha_Nac, Fecha_Contra = :Fecha_Contra, Salario_Base = :Salario_Base, Depto_ID = :Depto_ID, Foto = :Foto WHERE ID_Empleado = :ID_Empleado";
            $stmt = $this->con->prepare($query);
            $stmt->bindParam(':Nombre', $Nombre);
            $stmt->bindParam(':Apellido', $Apellido);
            $stmt->bindParam(':Fecha_Nac', $Fecha_Nac);
            $stmt->bindParam(':Fecha_Contra', $Fecha_Contra);
            $stmt->bindParam(':Salario_Base', $Salario_Base);
            $stmt->bindParam(':Depto_ID', $Depto_ID);
            $stmt->bindParam(':Foto', $Foto);
            $stmt->bindParam(':ID_Empleado', $ID_Empleado, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function ActualizarAusencia($ID_Solicitud, $FechaSolicitud, $Fecha_Inicio, $Fecha_Fin, $Motivo, $Descripcion, $Estado, $Cuenta_Salario, $Descuento, $ID_Empleado) {
        try {
            $stmt = $this->con->prepare("
            EXEC ModificarAusencia 
            @ID_Solicitud = :ID_Solicitud,
            @FechaSolicitud = :FechaSolicitud,
            @Fecha_Inicio = :Fecha_Inicio,
            @Fecha_Fin = :Fecha_Fin,
            @Motivo = :Motivo,
            @Descripcion = :Descripcion,
            @Estado = :Estado,
            @Cuenta_Salario = :Cuenta_Salario,
            @Descuento = :Descuento,
            @ID_Empleado = :ID_Empleado
        ");

            $stmt->bindParam(':ID_Solicitud', $ID_Solicitud, PDO::PARAM_INT);
            $stmt->bindParam(':FechaSolicitud', $FechaSolicitud);
            $stmt->bindParam(':Fecha_Inicio', $Fecha_Inicio);
            $stmt->bindParam(':Fecha_Fin', $Fecha_Fin);
            $stmt->bindParam(':Motivo', $Motivo);
            $stmt->bindParam(':Descripcion', $Descripcion);
            $stmt->bindParam(':Estado', $Estado);
            $stmt->bindParam(':Cuenta_Salario', $Cuenta_Salario, PDO::PARAM_INT); // Usar PARAM_INT para 1 o 0
            $stmt->bindParam(':Descuento', $Descuento, PDO::PARAM_STR); // Para números decimales
            $stmt->bindParam(':ID_Empleado', $ID_Empleado, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Función para modificar un expediente
    public function ActualizarExpediente($No_Expedientes, $Tipo_Documento, $Archivo, $ID_Empleado) {
        try {
            $stmt = $this->con->prepare("
        EXEC ModificarExpediente
        @No_Expedientes = :No_Expedientes,
        @Tipo_Documento = :Tipo_Documento,
        @Archivo = CONVERT(VARBINARY(MAX), :Archivo), -- Asegúrate de que este parámetro esté definido como VARBINARY
        @ID_Empleado = :ID_Empleado
        ");

            $stmt->bindParam(':No_Expedientes', $No_Expedientes, PDO::PARAM_INT);
            $stmt->bindParam(':Tipo_Documento', $Tipo_Documento);
            $stmt->bindParam(':Archivo', $Archivo, PDO::PARAM_LOB); // Esto debería manejar el archivo binario correctamente
            $stmt->bindParam(':ID_Empleado', $ID_Empleado, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }


// Función para buscar expedientes por empleado
    public function buscarExpediente($No_Expedientes) {
        try {
            $stmt = $this->con->prepare("SELECT * FROM Expediente WHERE No_Expedientes = :No_Expedientes");
            $stmt->bindParam(':No_Expedientes', $No_Expedientes, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

// Función para borrar un expediente
    public function borrarExpediente($No_Expedientes) {
        try {
            $query = "EXEC BorrarExpediente @No_Expedientes = :No_Expedientes";
            $stmt = $this->con->prepare($query);
            $stmt->bindParam(':No_Expedientes', $No_Expedientes, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

// Función para insertar un expediente
    public function insertarExpediente($Tipo_Documento, $Archivo, $ID_Empleado) {
        try {
            $query = "EXEC InsertarExpediente 
            @Tipo_Documento = :Tipo_Documento,
            @Archivo = :Archivo,
            @ID_Empleado = :ID_Empleado";

            $stmt = $this->con->prepare($query);

            $stmt->bindParam(':Tipo_Documento', $Tipo_Documento);
            $stmt->bindParam(':Archivo', $Archivo, PDO::PARAM_LOB);
            $stmt->bindParam(':ID_Empleado', $ID_Empleado, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

// Función para obtener todos los expedientes (opcional)
    public function mostrarExpedientes() {
        try {
            $query = "EXEC MostrarExpedientes";
            $stmt = $this->con->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }


    public function prepare($query) {
        return $this->con->prepare($query);  // Retorna la preparación de la consulta con PDO
    }

    public function execute($stmt, $params) {
        try {
            return $stmt->execute($params);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function listarFamiliares() {
        try {
            $stmt = $this->con->prepare("EXEC ListarFamiliares");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Manejo del error, puedes loguearlo o mostrar un mensaje de error
            error_log("Error al listar familiares: " . $e->getMessage());
            return false;
        }
    }


    public function insertarFamiliar($nombre, $apellido, $relacion, $fechaNacimiento, $idEmpleado) {
        $stmt = $this->con->prepare("EXEC InsertarFamiliar @Nombre = ?, @Apellido = ?, @Relacion = ?, @FechaNacimiento = ?, @ID_Empleado = ?");
        return $stmt->execute([$nombre, $apellido, $relacion, $fechaNacimiento, $idEmpleado]);
    }

    public function modificarFamiliar($idFamiliar, $nombre, $apellido, $relacion, $fechaNacimiento, $idEmpleado) {
        $stmt = $this->con->prepare("EXEC ModificarFamiliar @IDFamiliar = ?, @Nombre = ?, @Apellido = ?, @Relacion = ?, @FechaNacimiento = ?, @ID_Empleado = ?");
        return $stmt->execute([$idFamiliar, $nombre, $apellido, $relacion, $fechaNacimiento, $idEmpleado]);
    }

    public function borrarFamiliar($idFamiliar) {
        $stmt = $this->con->prepare("EXEC BorrarFamiliarPorID @IDFamiliar = ?");
        return $stmt->execute([$idFamiliar]);
    }

    public function buscarFamiliar($idFamiliar) {
        $stmt = $this->con->prepare("EXEC BuscarFamiliar @IDFamiliar = ?");
        $stmt->execute([$idFamiliar]);
        return $stmt->fetch(PDO::FETCH_ASSOC); // Usamos fetch() para obtener un solo resultado
    }

    public function mostrarHorasExtras() {
        $stmt = $this->con->prepare("EXEC MostrarHorasExtras");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function agregarHorasExtras($fecha, $horaNormal, $horaDoble, $idEmpleado) {
        $stmt = $this->con->prepare("EXEC AgregarHorasExtras :Fecha, :Hora_Normal, :Hora_Doble, :ID_Empleado");
        $stmt->bindParam(':Fecha', $fecha);
        $stmt->bindParam(':Hora_Normal', $horaNormal);
        $stmt->bindParam(':Hora_Doble', $horaDoble);
        $stmt->bindParam(':ID_Empleado', $idEmpleado);
        $stmt->execute();
    }

    public function modificarHorasExtras($ID_HoraExtra, $Fecha, $Hora_Normal, $Hora_Doble, $ID_Empleado) {
        try {
            // Preparar la consulta para ejecutar el procedimiento almacenado
            $stmt = $this->con->prepare("EXEC ModificarHoraExtra :ID_HoraExtra, :Fecha, :Hora_Normal, :Hora_Doble, :ID_Empleado");

            // Asignar los parámetros
            $stmt->bindParam(':ID_HoraExtra', $ID_HoraExtra, PDO::PARAM_INT);
            $stmt->bindParam(':Fecha', $Fecha, PDO::PARAM_STR);
            $stmt->bindParam(':Hora_Normal', $Hora_Normal, PDO::PARAM_STR);
            $stmt->bindParam(':Hora_Doble', $Hora_Doble, PDO::PARAM_STR);
            $stmt->bindParam(':ID_Empleado', $ID_Empleado, PDO::PARAM_INT);

            // Ejecutar la consulta
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error al modificar la hora extra: " . $e->getMessage();
            return false;
        }
    }

    public function eliminarHoraExtra($idHoraExtra) {
        $stmt = $this->con->prepare("EXEC EliminarHoraExtra :ID_HoraExtra");
        $stmt->bindParam(':ID_HoraExtra', $idHoraExtra);
        $stmt->execute();
    }

    public function buscarHoraExtra($ID_HoraExtra) {
        try {
            // Preparar el procedimiento almacenado
            $stmt = $this->con->prepare("EXEC BuscarHoraExtra :ID_HoraExtra");

            // Asignar el parámetro
            $stmt->bindParam(':ID_HoraExtra', $ID_HoraExtra, PDO::PARAM_INT);

            // Ejecutar la consulta
            $stmt->execute();

            // Obtener el resultado
            $horaExtra = $stmt->fetch(PDO::FETCH_ASSOC);

            return $horaExtra;
        } catch (PDOException $e) {
            echo "Error al buscar la hora extra: " . $e->getMessage();
            return false;
        }
    }
}

?>
