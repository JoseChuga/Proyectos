<?php
require_once '../Model/Usuario.php';
require_once '../Data/UsuarioODB.php';

$usuarioODB = new UsuarioODB();
$idUsuario = $_GET['ID_Usuario'];  // Obtener el ID de usuario desde la URL
$usuario = $usuarioODB->getById($idUsuario);  // Suponiendo que tienes un método para obtener el usuario por ID

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idUsuario = $_POST['ID_Usuario'];
    $passwordAnterior = $_POST['PasswordAnterior'];
    $passwordNueva = $_POST['PasswordNueva'];
    $passwordConfirmacion = $_POST['PasswordConfirmacion'];

    if ($passwordNueva === $passwordConfirmacion) {
        $usuario->setPasswordAnterior($passwordAnterior); // Método que se encarga de asignar la contraseña anterior
        $usuario->setPasswordNueva($passwordNueva); // Método para asignar la nueva contraseña
        $result = $usuarioODB->updatePassword($usuario);

        if ($result) {
            header("Location: v.usuarios.php?action=updated");
            exit();
        } else {
            echo "<p>Error al actualizar la contraseña. Verifica la contraseña anterior.</p>";
        }
    } else {
        echo "<p>Las contraseñas no coinciden.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="../Styles/styles.css">
</head>
<body>
<header>
    <h1>Editar Usuario</h1>
    <nav>
        <ul>
            <li><a href="v.usuarios.php">Regresar</a></li>
        </ul>
    </nav>
</header>

<main>
    <section class="usuario">
        <form action="v.editar.usuario.php?id=<?php echo htmlspecialchars($idUsuario); ?>" method="POST" class="form-crear-editar">
            <input type="hidden" name="ID_Usuario" value="<?php echo htmlspecialchars($usuario->getID_Usuario()); ?>">

            <div class="form-group">
                <label for="Usuario">Usuario:</label>
                <input type="text" id="usuario" name="Usuario" value="<?php echo htmlspecialchars($usuario->getUsuario()); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="Correo">Correo:</label>
                <input type="email" id="correo" name="Correo" value="<?php echo htmlspecialchars($usuario->getCorreo()); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="Empresa">Empresa:</label>
                <input type="text" id="empresa" name="Empresa" value="<?php echo htmlspecialchars($usuario->getEmpresa()); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="ID_Empleado">Empleado:</label>
                <input type="text" id="empleado" name="ID_Empleado" value="<?php echo htmlspecialchars($usuario->getNombreCompleto()); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="ID_Rol">Rol:</label>
                <input type="text" id="rol" name="ID_Rol" value="<?php echo htmlspecialchars($usuario->getRol()); ?>" readonly>
            </div>

            <div class="form-group">
                <label for="PasswordAnterior">Contraseña Anterior:</label>
                <input type="password" id="passwordAnterior" name="PasswordAnterior" required>
            </div>
            <div class="form-group">
                <label for="PasswordNueva">Nueva Contraseña:</label>
                <input type="password" id="passwordNueva" name="PasswordNueva" required>
            </div>
            <div class="form-group">
                <label for="PasswordConfirmacion">Confirmar Nueva Contraseña:</label>
                <input type="password" id="passwordConfirmacion" name="PasswordConfirmacion" required>
            </div>

            <div class="form-group form-buttons">
                <button type="submit" class="btn-submit">Actualizar Contraseña</button>
            </div>
        </form>
    </section>
</main>
</body>
</html>
