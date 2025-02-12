<?php

require_once '../Model/Ausencia.php';
require_once '../Data/AusenciaODB.php';

$ausenciaODB = new AusenciaODB();
$message = '';

// Verificar si se ha enviado un ID_Solicitud para eliminar
if (isset($_GET['ID_Solicitud'])) {
    $idSolicitud = $_GET['ID_Solicitud'];

    // Verificar si el ID_Solicitud es válido
    if (is_numeric($idSolicitud)) {
        // Llamar al método para eliminar la ausencia en el objeto de acceso a datos
        $ausenciaODB->delete($idSolicitud);
        // Redirigir con un parámetro de éxito
        header('Location: ' . $_SERVER['PHP_SELF'] . '?action=deleted');
        exit();
    } else {
        $message = 'ID de solicitud inválido.';
    }
}

// Obtener todas las ausencias para mostrar en la tabla
$ausencias = $ausenciaODB->getAll();

// Manejo de mensajes de éxito
if (isset($_GET['action']) && $_GET['action'] === 'deleted') {
    $message = 'La ausencia fue eliminada correctamente.';
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Lista de Ausencias</title>
    <link rel="stylesheet" href="../Styles/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.1/dist/sweetalert2.min.css" rel="stylesheet">
</head>

<body>
<header>
    <h1>Gestión de Ausencias</h1>
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
    <section class="Ausencias">
        <h2>Ausencias Registradas</h2>
        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <table>
            <thead>
            <tr>
                <th class="text-wrap">Fecha Solicitud</>
                <th class="text-wrap">Fecha Inicio</>
                <th class="text-wrap">Fecha Fin</>
                <th>Motivo</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th class="text-wrap">Cuenta Salario</th>
                <th>Descuento</th>
                <th>Empleado</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($ausencias as $ausencia) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($ausencia->getFechaSolicitud()); ?></td>
                    <td><?php echo htmlspecialchars($ausencia->getFechaInicio()); ?></td>
                    <td><?php echo htmlspecialchars($ausencia->getFechaFin()); ?></td>
                    <td class="text-wrap"><?php echo htmlspecialchars($ausencia->getMotivo()); ?></td>
                    <td class="text-wrap"><?php echo htmlspecialchars($ausencia->getDescripcion()); ?></td>
                    <td><?php echo htmlspecialchars($ausencia->getEstado()); ?></td>
                    <td><?php echo htmlspecialchars($ausencia->getCuentaSalario() ? 'Sí' : 'No'); ?></td>
                    <td><?php echo number_format($ausencia->getDescuento(), 2);?></td>
                    <td <td class="text-wrap"><?php echo htmlspecialchars($ausencia->getNombreCompleto()); ?></td>
                    <td>
                        <div class="botones-acciones">
                            <a href="v.editar.ausencia.empleado.php?ID_Solicitud=<?php echo $ausencia->getIdSolicitud(); ?>" class="btn btn-editar">Editar</a>
                            <button class="btn btn-eliminar" data-id="<?php echo $ausencia->getIdSolicitud(); ?>">Eliminar</button>
                        </div>
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
    // Código para manejar los botones de eliminación
    const deleteButtons = document.querySelectorAll('.btn-eliminar');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const ausenciaId = this.getAttribute('data-id');

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
                    window.location.href = `?ID_Solicitud=${ausenciaId}`;
                }
            });
        });
    });

    // Código para manejar el botón "Agregar Nueva Ausencia"
    const nuevaAusenciaButton = document.querySelector('.btn-nuevo');

    if (nuevaAusenciaButton) {
        nuevaAusenciaButton.addEventListener('click', function() {
            window.location.href = 'v.nueva.ausencia.php';
        });
    }

    function cerrarSesion() {
        // Redirige al usuario a la página index.php
        window.location.href = '../index.html';
    }
</script>

</body>

</html>
