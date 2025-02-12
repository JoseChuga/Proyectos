<?php

require_once '../Model/Tienda.php';
require_once '../Data/TiendaODB.php';

$tiendaODB = new TiendaODB();

// Verificar si se ha enviado un ID_Empleado
if (isset($_GET['ID_Empleado'])) {
    $idEmpleado = $_GET['ID_Empleado'];

    // Obtener las compras del empleado
    $compras = $tiendaODB->getComprasPorEmpleado($idEmpleado);
    $Compra = null;
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
    <title>Lista de Compras</title>
    <link rel="stylesheet" href="../Styles/styles.css">
</head>

<body>
<header>
    <h1>Gestión de Compras de Tienda</h1>
</header>
<nav>
    <ul>
        <li><a href="v.tienda.php">REGRESAR</a></li>
    </ul>
</nav>
<main>
    <section class="Compras">
        <h2>Compras Registradas</h2>
        <table>
            <thead>
            <tr>
                <th>Monto de la Compra</th>
                <th>Fecha</th>
                <th>Nombre del Empleado</th>
                <th>No. Cuenta</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($compras): ?>
                <?php foreach ($compras as $compra) : ?>
                    <tr>
                        <td><?php echo number_format($compra->Total, 2); ?></td>
                        <td><?php echo htmlspecialchars($compra->Fecha); ?></td>
                        <td><?php echo htmlspecialchars($compra->NombreCompleto); ?></td>
                        <td><?php echo htmlspecialchars($compra->Cuenta_Contable); ?></td>
                        <td>
                            <?php if ($compra->Fecha >= date('Y-m-d', strtotime('-1 day'))) : ?>
                                <a href="v.editar.compra.php?Compra=<?php echo $compra->Compra; ?>" class="btn btn-editar">Editar</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No hay compras registradas para este empleado.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </section>
</main>

<footer>
    <p>© 2024 TConsulting. Todos los derechos reservados.</p>
</footer>

</body>
</html>
