<?php
require_once '../Model/Empleado.php';
require_once '../Data/EmpleadoODB.php';
require_once '../Data/UsuarioODB.php';
require_once '../Data/RolesODB.php';

$empleadoODB = new EmpleadoODB();
$empleados = $empleadoODB->getAll();

$rolODB = new RolesODB(); // Instancia de RolODB para obtener los roles
$roles = $rolODB->getAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['Usuario'];
    $correo = $_POST['Correo'];
    $password = $_POST['Password'];
    $empresa = $_POST['Empresa'];
    $idEmpleado = $_POST['ID_Empleado'];
    $idRol = $_POST['ID_Rol'];

    $usuarioODB = new UsuarioODB();
    $result = $usuarioODB->insert($usuario, $correo, $password, $empresa, $idEmpleado, $idRol);

    header("Location: v.usuarios.php?action=created");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Nuevo Usuario</title>
    <link rel="stylesheet" href="../Styles/styles.css">
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleButton = document.getElementById('togglePassword');
            const icon = toggleButton.querySelector('img');

            // Cambiar el tipo de input y el icono según el estado actual
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text'; // Muestra la contraseña
                icon.src = '../Imagenes/eye-icon2.png'; // Icono de ojo abierto
            } else {
                passwordInput.type = 'password'; // Oculta la contraseña
                icon.src = '../Imagenes/eye-icon-cerrado.png'; // Icono de ojo cerrado
            }
        }
    </script>
</head>
<body>
<header>
    <h1>Crear Nuevo Usuario</h1>
    <nav>
        <ul>
            <li><a href="v.usuarios.php">Regresar</a></li>
        </ul>
    </nav>
</header>

<main>
    <section class="usuario">
        <form action="v.nuevo.usuario.php" method="POST" class="form-crear-editar">
            <div class="form-group">
                <label for="Usuario">Usuario:</label>
                <input type="text" id="usuario" name="Usuario" required>
            </div>
            <div class="form-group">
                <label for="Correo">Correo:</label>
                <input type="email" id="correo" name="Correo" required>
            </div>
            <div class="form-group">
                <label for="Password">Contraseña:</label>
                <div style="display: flex; align-items: center;">
                    <input type="Text" id="Password" name="Password" required style="flex: 1;">
                </div>
            </div>
            <div class="form-group">
                <label for="Empresa">Empresa:</label>
                <input type="text" id="empresa" name="Empresa" required>
            </div>
            <div class="form-group">
                <label for="ID_Empleado">Empleado:</label>
                <select id="id_empleado" name="ID_Empleado" required>
                    <option value="">Seleccionar...</option>
                    <?php foreach ($empleados as $empleado) : ?>
                        <option value="<?php echo htmlspecialchars($empleado->getIdEmpleado()); ?>">
                            <?php echo htmlspecialchars($empleado->getNombre() . ' ' . $empleado->getApellido()); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="ID_Rol">Rol:</label>
                <select id="id_rol" name="ID_Rol" required>
                    <option value="">Seleccionar...</option>
                    <?php foreach ($roles as $rol) : ?>
                        <option value="<?php echo htmlspecialchars($rol->getIdRol()); ?>">
                            <?php echo htmlspecialchars($rol->getRol()); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group form-buttons">
                <button type="submit" class="btn-submit">Guardar Usuario</button>
            </div>
        </form>
    </section>
</main>
</body>
</html>
