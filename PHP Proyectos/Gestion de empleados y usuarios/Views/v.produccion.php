<?php

require_once '../Model/Produccion.php';
require_once '../Data/ProduccionODB.php';

$produccionODB = new ProduccionODB();

// Verificar si se ha enviado un ID_Produccion para eliminar
if (isset($_GET['ID_Produccion'])) {
    $idProduccion = $_GET['ID_Produccion'];

    // Llamar al método para eliminar la producción
    $produccionODB->borrarProduccion($idProduccion);

    // Redirigir con un parámetro de éxito
    header('Location: ' . $_SERVER['PHP_SELF'] . '?action=deleted');
    exit();
}

// Obtener todas las producciones para mostrar en la tabla
$producciones = $produccionODB->mostrarProduccion();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Lista de Producción</title>
    <link rel="stylesheet" href="../Styles/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.1/dist/sweetalert2.min.css" rel="stylesheet">
</head>

<body>
<header>
    <h1>Gestión de Producción</h1>
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
    <section class="Produccion">
        <h2>Producción Registrada</h2>
        <table>
            <thead>
            <tr>
                <th>Fecha</th>
                <th>Piezas Elaboradas</th>
                <th>Bonificación</th>
                <th>Nombre del Empleado</th>
                <th>No. Cuenta</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($producciones as $produccion) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($produccion->getFecha()); ?></td>
                    <td><?php echo htmlspecialchars($produccion->getPiezasElaboradas()); ?></td>
                    <td><?php echo number_format($produccion->getBonificacion(), 2); ?></td>
                    <td><?php echo htmlspecialchars($produccion->getNombreCompleto()); ?></td>
                    <td><?php echo htmlspecialchars($produccion->getCuentaContable()); ?></td>
                    <td>
                        <a href="v.editar.produccion.php?ID_Produccion=<?php echo $produccion->getIDProduccion(); ?>" class="btn btn-editar">Editar</a>
                        <button class="btn btn-eliminar" data-id="<?php echo $produccion->getIDProduccion(); ?>">Eliminar</button>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <button class="btn-nuevo">Agregar Nueva Producción +</button>
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
            const produccionId = this.getAttribute('data-id');

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
                    window.location.href = `?ID_Produccion=${produccionId}`;
                }
            });
        });
    });

    const nuevoProduccionButton = document.querySelector('.btn-nuevo');

    if (nuevoProduccionButton) {
        nuevoProduccionButton.addEventListener('click', function() {
            window.location.href = 'v.nueva.produccion.php';
        });
    }

    function cerrarSesion() {
        // Redirige al usuario a la página index.php
        window.location.href = '../index.html';
    }
</script>
</body>
</html>

