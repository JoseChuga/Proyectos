<?php
require_once '../Model/Familiar.php';
require_once '../Data/FamiliarODB.php';
require_once '../Model/Empleado.php';

$familiarODB = new FamiliarODB();

$idEmpleado = $_GET['ID_Empleado'] ?? null; // Asegúrate de que esto esté al inicio

if (isset($_GET['ID_Familiar']) && is_numeric($_GET['ID_Familiar'])) {
    $idFamiliar = $_GET['ID_Familiar'];
    $familiarODB->delete($idFamiliar);

    // Redirecciona incluyendo el ID_Empleado y la acción realizada
    header('Location: ' . $_SERVER['PHP_SELF'] . '?ID_Empleado=' . $idEmpleado . '&action=deleted');
    exit();
} elseif (isset($_GET['ID_Familiar'])) {
    $message = 'ID de familiar inválido.';
}

// Obtener todos los familiares para mostrar en la tabla
$familiares = $familiarODB->buscarFamiliaresPorEmpleado($idEmpleado);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Familiares</title>
    <link rel="stylesheet" href="../Styles/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.1/dist/sweetalert2.min.css" rel="stylesheet">
</head>
<body>
<header>
    <h1>Gestión de Familiares</h1>
    <button onclick="cerrarSesion()" class="btn btn-eliminar">Cerrar Sesión</button>
</header>
<nav>
    <ul>
        <li>
            <a href="#">RRHH</a>
            <ul>
                <li><a href="v.empleados.php">Empleados</a></li>
                <li><a href="v.Expediente.php">Expedientes</a></li>
                <li><a href="v.ausencias.php">Permisos</a></li>
            </ul>
        </li>
        <li>
            <a href="#">Nómina</a>
            <ul>
                <li><a href="v.nomina.php">Pagos</a></li>
            </ul>
        </li>
        <li>
            <a href="#">Contabilidad</a>
            <ul>
                <li><a href="v.Poliza.php">Polizas Contables</a></li>
                <li><a href="v.horasextras.php">Horas Extras</a></li>
                <li><a href="v.comisiones.php">Comisiones sobre ventas</a></li>
                <li><a href="v.produccion.php">Bonificaciones por producción</a></li>
            </ul>
        </li>
        <li>
            <a href="#">BANTRAB</a>
            <ul>
                <li><a href="v.prestamo.php">Prestamos</a></li>
            </ul>
        </li>
        <li>
            <a href="#">Tienda</a>
            <ul>
                <li><a href="v.tienda.php">Registro de Tienda</a></li>
            </ul>
        </li>
    </ul>
</nav>
<main>
    <section class="Familiares">
        <h2>Familiares Registrados</h2>
        <table>
            <thead>
            <tr>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Relación</th>
                <th>Fecha de Nacimiento</th>
                <th>Nombe del Empleado</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($familiares as $familiar) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($familiar->getNombre()); ?></td>
                    <td><?php echo htmlspecialchars($familiar->getApellido()); ?></td>
                    <td><?php echo htmlspecialchars($familiar->getRelacion()); ?></td>
                    <td><?php echo htmlspecialchars($familiar->getFechaNacimiento()); ?></td>
                    <td><?php echo htmlspecialchars($familiar->getNombreCompleto()); ?></td>
                    <td>
                        <a href="v.editar.familiar.php?ID_Familiar=<?php echo $familiar->getIdFamiliar(); ?>" class="btn btn-editar">Editar</a>
                        <button class="btn btn-eliminar" data-id="<?php echo $familiar->getIdFamiliar(); ?>" data-id-empleado="<?php echo $familiar->getIdEmpleado(); ?>">Eliminar</button>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <button data-id-empleado="<?php echo $familiar->getIdEmpleado(); ?>" class="btn-nuevo">Agregar Nuevo Familiar +</button>
    </section>
</main>

<footer>
    <p>&copy; 2024 TConsulting. Todos los derechos reservados.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const deleteButtons = document.querySelectorAll('.btn-eliminar');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const familiarId = this.getAttribute('data-id');
            const empleadoId = this.getAttribute('data-id-empleado'); // Obtiene el ID del empleado

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
                    window.location.href = `?ID_Familiar=${familiarId}&ID_Empleado=${empleadoId}`; // Incluye el ID_Empleado en la redirección
                }
            });
        });
    });

    const nuevoEmpleadoButton = document.querySelector('.btn-nuevo');

    if (nuevoEmpleadoButton) {
        nuevoEmpleadoButton.addEventListener('click', function() {
            const idEmpleado = this.getAttribute('data-id-empleado');
            window.location.href = `v.nuevo.familiar.php?ID_Empleado=${idEmpleado}`;
        });
    }

    function cerrarSesion() {
        // Redirige al usuario a la página index.php
        window.location.href = '../index.html';
    }

</script>
</body>
</html>

