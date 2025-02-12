<?php

require_once '../Model/Tienda.php';
require_once '../Data/TiendaODB.php';

$tiendaODB = new TiendaODB();

// Verificar si se ha enviado un ID_Empleado
if (isset($_GET['ID_Empleado'])) {
    $idEmpleado = $_GET['ID_Empleado'];

    // Obtener los pagos del empleado
    $pagos = $tiendaODB->getPagosPorEmpleado($idEmpleado);
} else {
    // Si no se envía el ID, redirigir a una vista de error o mostrar mensaje
    echo "No se ha recibido un ID de empleado.";
    exit();
}

// Obtener la fecha actual
$fechaActual = date('Y-m-d');

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Lista de Pagos</title>
    <link rel="stylesheet" href="../Styles/styles.css">
</head>

<body>
<header>
    <h1>Gestión de Pagos de Tienda</h1>
</header>
<nav>
    <ul>
        <li><a href="v.tienda.php">REGRESAR</a></li>
    </ul>
</nav>
<main>
    <section class="Pagos">
        <h2>Pagos Registrados</h2>
        <table>
            <thead>
            <tr>
                <th>Monto del Pago</th>
                <th>Fecha</th>
                <th>Nombre del Empleado</th>
                <th>No. Cuenta</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($pagos as $pago) : ?>
                <tr>
                    <td><?php echo number_format($pago->Pago, 2); ?></td>
                    <td><?php echo htmlspecialchars($pago->Fecha); ?></td>
                    <td><?php echo htmlspecialchars($pago->NombreCompleto); ?></td>
                    <td><?php echo htmlspecialchars($pago->Cuenta_Contable); ?></td>
                    <td>
                        <?php if ($pago->Fecha >= date('Y-m-d', strtotime('-1 day'))) : ?>
                            <a href="v.editar.pago.php?ID_Pago_Tienda=<?php echo $pago->ID_Pago_Tienda; ?>" class="btn btn-editar">Editar</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</main>

<footer>
    <p>© 2024 TConsulting. Todos los derechos reservados.</p>
</footer>

</body>
</html>
