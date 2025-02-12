<?php
require_once '../Model/HorasExtras.php';
require_once '../Data/HorasExtrasODB.php';

$horasExtrasODB = new HorasExtrasODB();

// Inicializar variables para mensajes
$message = '';

if (isset($_GET['ID_HoraExtra'])) {
    $idHoraExtra = $_GET['ID_HoraExtra'];

    // Verificar si el ID_HoraExtra es válido
    if (is_numeric($idHoraExtra)) {
        // Llamar al método para eliminar la hora extra en el objeto de acceso a datos
        $horasExtrasODB->delete($idHoraExtra);
        // Redirigir con un parámetro de éxito
        header('Location: ' . $_SERVER['PHP_SELF'] . '?action=deleted');
        exit();
    } else {
        $message = 'ID de hora extra inválido.';
    }
}

// Obtener todas las horas extras para mostrar en la tabla
$horasExtras = $horasExtrasODB->getAll();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Lista de Horas Extras</title>
    <link rel="stylesheet" href="../Styles/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
</head>

<body>
<header>
    <h1>Gestión de Horas Extras</h1>
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
    <section class="HorasExtras">
        <h2>Horas Extras Registradas</h2>
        <?php if ($message): ?>
            <div class="error-message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <?php if (isset($_GET['action']) && $_GET['action'] === 'deleted'): ?>
            <div class="success-message">La hora extra ha sido eliminada correctamente.</div>
        <?php endif; ?>
        <table>
            <thead>
            <tr>
                <th>Fecha</th>
                <th>Hora Normal</th>
                <th>Hora Doble</th>
                <th>Total Normal</th>
                <th>Total Doble</th>
                <th>Nombre Completo</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($horasExtras as $horaExtra) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($horaExtra->getFecha()); ?></td>
                    <td><?php echo htmlspecialchars($horaExtra->getHoraNormal()); ?></td>
                    <td><?php echo htmlspecialchars($horaExtra->getHoraDoble()); ?></td>
                    <td><?php echo number_format($horaExtra->getTotalNormal(), 2); ?></td>
                    <td><?php echo number_format($horaExtra->getTotalDoble(), 2); ?></td>
                    <td><?php echo htmlspecialchars($horaExtra->getNombreCompleto()); ?></td>
                    <td>
                        <a href="v.editar.horasextras.php?ID_HoraExtra=<?php echo $horaExtra->getIDHoraExtra(); ?>" class="btn btn-editar">Editar</a>
                        <button class="btn btn-eliminar" data-id="<?php echo $horaExtra->getIDHoraExtra(); ?>">Eliminar</button>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <button class="btn-nuevo">Agregar Nueva Ausencia +</button>
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
            const horaExtraId = this.getAttribute('data-id');

            Swal.fire({
                text: "¿Seguro que quieres eliminar el registro? No podrás revertir esta acción.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `?ID_HoraExtra=${horaExtraId}`;
                }
            });
        });
    });

    const nuevaAusenciaButton = document.querySelector('.btn-nuevo');

    if (nuevaAusenciaButton) {
        nuevaAusenciaButton.addEventListener('click', function() {
            window.location.href = 'v.nueva.horasextras.php';
        });
    }

    function cerrarSesion() {
        // Redirige al usuario a la página index.php
        window.location.href = '../index.html';
    }
</script>
</body>

</html>

