<?php

require_once('../Model/Usuario.php');
require_once '../Data/UsuarioODB.php';

$usuarioODB = new UsuarioODB();
$message = '';

// Verificar si se ha enviado un ID_Usuario para eliminar
if (isset($_GET['ID_Usuario'])) {
    $idUsuario = $_GET['ID_Usuario'];

    // Verificar si el ID_Usuario es válido
    if (is_numeric($idUsuario)) {
        // Llamar al método para eliminar el usuario en el objeto de acceso a datos
        $usuarioODB->delete($idUsuario);
        // Redirigir con un parámetro de éxito
        header('Location: ' . $_SERVER['PHP_SELF'] . '?action=deleted');
        exit();
    } else {
        $message = 'ID de usuario inválido.';
    }
}

// Obtener todos los usuarios para mostrar en la tabla
$usuarios = $usuarioODB->getAll();

// Manejo de mensajes de éxito
if (isset($_GET['action']) && $_GET['action'] === 'deleted') {
    $message = 'El usuario fue eliminado correctamente.';
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Lista de Usuarios</title>
    <link rel="stylesheet" href="../Styles/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.1/dist/sweetalert2.min.css" rel="stylesheet">
</head>

<body>
<header>
    <h1>Gestión de Usuarios</h1>
    <button onclick="cerrarSesion()" class="btn btn-eliminar">Cerrar Sesión</button>
</header>
<nav>
    <ul>
        <li><a href="v.usuariosRRHH.php">Usuarios</a></li>
        <li><a href="v.empleadosRRHH.php">Empleados</a></li>
        <li><a href="v.ExpedienteRRHH.php">Expedientes</a></li>
        <li><a href="v.ausenciasRRHH.php">Permisos</a></li>
    </ul>
</nav>
<main>
    <section class="Usuarios">
        <h2>Usuarios Registrados</h2>
        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <table>
            <thead>
            <tr>
                <th>ID Usuario</th>
                <th>Nombre de Usuario</th>
                <th>Correo</th>
                <th>Empresa</th>
                <th>Nombre Completo</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($usuarios as $usuario) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($usuario->getIdUsuario()); ?></td>
                    <td><?php echo htmlspecialchars($usuario->getUsuario()); ?></td>
                    <td><?php echo htmlspecialchars($usuario->getCorreo()); ?></td>
                    <td><?php echo htmlspecialchars($usuario->getEmpresa()); ?></td>
                    <td><?php echo htmlspecialchars($usuario->getNombreCompleto()); ?></td>
                    <td><?php echo htmlspecialchars($usuario->getRol()); ?></td>
                    <td>
                        <div class="botones-acciones">
                            <a href="v.editar.usuario.php?ID_Usuario=<?php echo $usuario->getIdUsuario(); ?>" class="btn btn-editar">Editar</a>
                            <button class="btn btn-eliminar" data-id="<?php echo $usuario->getIdUsuario(); ?>">Eliminar</button>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <button class="btn-nuevo">Agregar Nuevo Usuario +</button>
    </section>
</main>

<footer>
    <p>&copy; 2024 TConsulting. Todos los derechos reservados.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Código para manejar los botones de eliminación
    const deleteButtons = document.querySelectorAll('.btn-eliminar');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const usuarioId = this.getAttribute('data-id');

            Swal.fire({
                text: "Seguro que quieres eliminar el registro, no podrás revertir esta acción.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `?ID_Usuario=${usuarioId}`;
                }
            });
        });
    });

    // Código para manejar el botón "Agregar Nuevo Usuario"
    const nuevoUsuarioButton = document.querySelector('.btn-nuevo');

    if (nuevoUsuarioButton) {
        nuevoUsuarioButton.addEventListener('click', function() {
            window.location.href = 'v.nuevo.usuario.php';
        });
    }

    function cerrarSesion() {
        // Redirige al usuario a la página de inicio de sesión
        window.location.href = '../index.html';
    }
</script>

</body>