<?php

require_once '../Model/Prestamo.php';
require_once '../Data/PrestamoODB.php';

$prestamoODB = new PrestamoODB();

// Verificar si se ha enviado un ID_Prestamo para eliminar
if (isset($_GET['ID_Prestamo'])) {
    $idPrestamo = $_GET['ID_Prestamo'];

    // Redirigir con un parámetro de éxito
    header('Location: ' . $_SERVER['PHP_SELF'] . '?action=deleted');
    exit();
}

// Obtener todos los préstamos para mostrar en la tabla
$prestamos = $prestamoODB->getAll();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Lista de Préstamos</title>
    <link rel="stylesheet" href="../Styles/styles.css">
</head>

<body>
<header>
    <h1>Gestión de Préstamos</h1>
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
    <section class="Prestamos">
        <h2>Préstamos Registrados</h2>
        <table>
            <thead>
            <tr>
                <th>Monto</th>
                <th>Cuotas</th>
                <th  class="text-wrap">Fecha de Inicio</th>
                <th  class="text-wrap">Cuotas Restantes</th>
                <th  class="text-wrap">Saldo Pendiente</th>
                <th>Nombre del Empleado</th>
                <th>No. Cuenta</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($prestamos as $prestamo) : ?>
                <tr>
                    <td><?php echo number_format($prestamo->getMonto(), 2); ?></td>
                    <td><?php echo htmlspecialchars($prestamo->getCuotas()); ?></td>
                    <td><?php echo htmlspecialchars($prestamo->getFechaInicio()); ?></td>
                    <td><?php echo htmlspecialchars($prestamo->getCuotasRestantes()); ?></td>
                    <td><?php echo number_format($prestamo->getSaldoPendiente(), 2); ?></td>
                    <td><?php echo htmlspecialchars($prestamo->getNombreCompleto()); ?></td>
                    <td><?php echo htmlspecialchars($prestamo->getCuentaContable()); ?></td>
                    <td>
                        <a href="v.editar.prestamo.php?ID_Prestamo=<?php echo $prestamo->getIdPrestamo(); ?>" class="btn btn-editar">Editar</a>
                        <a href="v.RealizarPago.php?ID_Prestamo=<?php echo $prestamo->getIdPrestamo(); ?>" class="btn btn-editar">Realizar Pago</a>
                        <a href="v.HistorialPagosPrestamos.php?ID_Prestamo=<?php echo $prestamo->getIdPrestamo(); ?>" class="btn btn-buscar">Historial</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <button class="btn-nuevo">Agregar Nuevo Préstamo +</button>
    </section>
</main>

<footer>
    <p>© 2024 TConsulting. Todos los derechos reservados.</p>
</footer>

<script>
    const nuevoPrestamoButton = document.querySelector('.btn-nuevo');

    if (nuevoPrestamoButton) {
        nuevoPrestamoButton.addEventListener('click', function() {
            window.location.href = 'v.nuevo.prestamo.php';
        });
    }

    function cerrarSesion() {
        // Redirige al usuario a la página index.php
        window.location.href = '../index.html';
    }
</script>

</body>
</html>
