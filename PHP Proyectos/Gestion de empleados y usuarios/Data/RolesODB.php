<?php
require_once 'SQLSRVConnector.php';
require_once '../Model/Roles.php';
class RolesODB
{
    private $connection;

    public function __construct()
    {
        $this->connection = SQLSRVConnector::getInstance()->getConnection(); // Obtén la conexión aquí
        if ($this->connection === null) {
            die("Error: No se pudo establecer la conexión con la base de datos.");
        }
    }

    public function getAll(): array
    {
        $query = "SELECT ID_Rol, Rol FROM Roles";
        $result = $this->connection->query($query);
        $roles = [];
        while ($row = $result->fetch()) {
            $rol = new Rol($row['ID_Rol'], $row['Rol']);
            array_push($roles, $rol);
        }
        return $roles;
    }

    public function getById($id)
    {
        $query = "SELECT ID_Rol, Rol FROM Roles WHERE ID_Rol = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $row = $stmt->fetch();
        return new Rol($row['ID_Rol'], $row['Rol']);
    }

    public function insert($rol)
    {
        $query = "INSERT INTO Roles (Rol) VALUES (?)";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(1, $rol->getRol());
        $stmt->execute();
    }

    public function update($rol)
    {
        $query = "UPDATE Roles SET Rol = ? WHERE ID_Rol = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(1, $rol->getRol());
        $stmt->bindParam(2, $rol->getIdRol());
        $stmt->execute();
    }

    public function delete($id)
    {
        $query = "DELETE FROM Roles WHERE ID_Rol = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
    }
}
