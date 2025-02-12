<?php

require_once 'SQLSRVConnector.php';
require_once dirname(__DIR__) . '/Model/Usuario.php';

class UsuarioODB {
    private $connection;

    public function __construct() {
        $this->connection = SQLSRVConnector::getInstance()->getConnection();
    }

    public function getAll() {
        try {
            $query = "EXEC MostrarUsuarios";
            $result = $this->connection->query($query);

            if (!$result) {
                throw new Exception("Error en la consulta: " . $this->connection->errorInfo()[2]);
            }

            $usuarios = [];
            while ($row = $result->fetch(PDO::FETCH_ASSOC)){
                $usuario = new Usuario($row['ID_Usuario'], $row['Usuario'], $row['Correo'], null, $row['Empresa'], null, null, $row['NombreCompleto'], $row['Rol']);

                array_push($usuarios, $usuario);
            }

            return $usuarios;
        } catch (Exception $e) {
            echo "Excepción capturada: ",  $e->getMessage(), "\n";
            return [];
        }
    }


    public function getById($idUsuario) {
        $query = "EXEC ObtenerUsuarioporID ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(1, $idUsuario, PDO::PARAM_INT); // Usa $idUsuario en lugar de $id
        $stmt->execute();

        $row = $stmt->fetch();

        if ($row) {
            return new Usuario(
                $row['ID_Usuario'],
                $row['Usuario'],
                $row['Correo'],
                $row['Password'],
                $row['Empresa'],
                $row['NombreCompleto'],
                $row['Rol']
            );
        } else {
            echo "XD";
            return null;
        }
    }

    public function insert($nombreUsuario, $correo, $password, $empresa, $idEmpleado, $idRol) {
        $query = "EXEC IngresarUsuario ?, ?, ?, ?, ?, ?";
        $stmt = $this->connection->prepare($query);

        // Usa las variables directamente
        $stmt->bindParam(1, $nombreUsuario);
        $stmt->bindParam(2, $correo);
        $stmt->bindParam(3, $password); // Asegúrate de almacenar el hash aquí
        $stmt->bindParam(4, $empresa);
        $stmt->bindParam(5, $idEmpleado);
        $stmt->bindParam(6, $idRol);

        // Ejecuta la consulta
        $stmt->execute();
    }

    public function updatePassword($usuario) {
        // Consulta modificada para actualizar solo la contraseña del usuario
        $query = "EXEC ModificarPasswordUsuario ?, ?, ?";
        $stmt = $this->connection->prepare($query);

        // Asignamos los valores a variables
        $idUsuario = $usuario->getID_Usuario();
        $passwordAnterior = $usuario->getPasswordAnterior();
        $passwordNueva = $usuario->getPasswordNueva();

        $stmt->bindParam(1, $idUsuario);
        $stmt->bindParam(2, $passwordAnterior);
        $stmt->bindParam(3, $passwordNueva);

        $stmt->execute();
    }

    public function delete($id) {
        $query = "EXEC BorrarUsuario ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();
    }


    // Método de Login para autenticar el usuario
    public function login($usuario, $password) {
        $query = "EXEC ValidarUsuario :usuario";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            // Comparación directa de la contraseña sin encriptación
            if ($password === $row['Password']) { // Reemplaza 'Password' con el nombre real del campo de contraseña en la base de datos
                return new Usuario(
                    $row['ID_Usuario'],
                    $row['Usuario'],
                    $row['Correo'],
                    null, // No se devuelve la contraseña por seguridad
                    $row['Empresa'],
                    $row['ID_Empleado'],
                    $row['ID_Rol']
                );
            } else {
                return null; // Contraseña incorrecta
            }
        }
        return null; // Usuario no encontrado
    }
}
?>
