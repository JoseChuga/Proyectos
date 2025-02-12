<?php
require_once '../Model/HistorialPagosPrestamos.php';
require_once '../Data/HistorialPagosPrestamosODB.php';

$historialODB = new HistorialPagosPrestamosODB();

// Verificar si se ha recibido el ID_Prestamo desde una solicitud GET
$ID_Prestamo = isset($_GET['ID_Prestamo']) ? $_GET['ID_Prestamo'] : null;

if ($ID_Prestamo) {
    // Buscar historial de pagos por el ID del préstamo
    $pagos = $historialODB->getAllByPrestamoId($ID_Prestamo);

    // Verificar si hay pagos para obtener el nombre completo utilizando el método
    $NombreCompleto = !empty($pagos) ? $pagos[0]->getNombreCompleto() : 'Nombre no disponible';
} else {
    $pagos = [];
    $NombreCompleto = 'Nombre no disponible';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Pagos de Préstamos</title>
    <link rel="stylesheet" href="../Styles/styles.css">
</head>
<body>
<header>
    <h1>Historial de Pagos de Préstamos</h1>
    <nav>
        <ul>
            <li><a href="v.prestamo.php">REGRESAR</a></li>
        </ul>
    </nav>
</header>
<main>
    <section class="HistorialPagos">
        <?php if ($ID_Prestamo && count($pagos) > 0): ?>
            <h2>Pagos Registrados en el Prestamo Seleccionado: </h2>
            <table>
                <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Monto</th>
                    <th>No. Cuota</th>
                    <th>Saldo Pendiente</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($pagos as $pago) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($pago->getFecha()); ?></td>
                        <td><?php echo number_format($pago->getMonto(), 2); ?></td>
                        <td><?php echo htmlspecialchars($pago->getNoCuota()); ?></td>
                        <td><?php echo number_format($pago->getSaldoPendiente(), 2); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif ($ID_Prestamo): ?>
            <p>No se encontraron pagos registrados para este préstamo.</p>
        <?php else: ?>
            <p>ID de préstamo no proporcionado.</p>
        <?php endif; ?>
    </section>
</main>
<footer>
    <p>© 2024 TConsulting. Todos los derechos reservados.</p>
</footer>
</body>
</html>
