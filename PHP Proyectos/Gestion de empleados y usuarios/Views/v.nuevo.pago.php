<?php

require_once '../Model/HistorialPagosPrestamos.php';
require_once '../Data/HistorialPagosPrestamosODB.php';

$historialODB = new HistorialPagosPrestamosODB();

// Obtener todo el historial de pagos para mostrar en la tabla
$historialPagos = $historialODB->getAll();

// Verificar si hay un parámetro de búsqueda y realizar la búsqueda
if (isset($_GET['nombreCompleto'])) {
    $historialPagos = $historialODB->getByNombreCompleto($_GET['nombreCompleto']);
}

// Si hay registros, obtener el primer pago (el más reciente)
$primerPago = !empty($historialPagos) ? $historialPagos[0] : null;

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Pagos de Préstamos</title>
    <link rel="stylesheet" href="../Styles/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.1/dist/sweetalert2.min.css" rel="stylesheet">
</head>
<body>
<header>
    <h1>Gestión de Historial de Pagos</h1>
    <nav>
        <ul>
            <li><a href="indexAdmon.php">Inicio</a></li>
            <li><a href="#" class="active">Historial de Pagos</a></li>
            <li><a href="v.empleados.php">Empleados</a></li>
        </ul>
    </nav>
</header>

<main>
    <section class="HistorialPagos">
        <div class="search-bar">
            <form method="GET" action="">
                <label for="search">Buscar por Empleado:</label>
                <input type="text" id="search" name="nombreCompleto" placeholder="Nombre Completo">
                <button type="submit" class="btn btn-buscar">Buscar</button>
            </form>
        </div>

        <h2>Pagos Registrados</h2>
        <table>
            <thead>
            <tr>
                <th>Fecha</th>
                <th>Monto</th>
                <th>Número de Cuota</th>
                <th>Saldo Pendiente</th>
                <th>Empleado</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($historialPagos as $pago) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($pago->getFecha()); ?></td>
                    <td><?php echo htmlspecialchars($pago->getMonto()); ?></td>
                    <td><?php echo htmlspecialchars($pago->getNoCuota()); ?></td>
                    <td><?php echo htmlspecialchars($pago->getSaldoPendiente()); ?></td>
                    <td><?php echo htmlspecialchars($pago->getNombreCompleto()); ?></td>
                    <td>
                        <!-- Enlace para Nuevo Pago -->
                        <a href="v.RealizarPago.php?ID_Pago=<?php echo $pago->getIdPago(); ?>" class="btn btn-nuevo-pago">Nuevo Pago</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</main>

<footer>
    <p>&copy; 2024 TConsulting. Todos los derechos reservados.</p>
</footer>
</body>
</html>
