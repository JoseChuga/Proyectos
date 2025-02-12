<?php
require_once '../Model/Intecap.php';
require_once '../Data/IntecapODB.php';

$intecapODB = new IntecapODB();
$intecapList = $intecapODB->getAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de INTECAP</title>
    <link rel="stylesheet" href="../Styles/styles.css">
</head>
<body>
<header>
    <h1>Gestión de INTECAP</h1>
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
    <section class="INTECAP">
        <h2>Registros de INTECAP</h2>
        <table>
            <thead>
            <tr>
                <th>Mes</th>
                <th>Año</th>
                <th>Monto Patronal</th>
                <th>Nombre del Empleado</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($intecapList as $intecap) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($intecap->getMes()); ?></td>
                    <td><?php echo htmlspecialchars($intecap->getAnio()); ?></td>
                    <td><?php echo number_format($intecap->getMontoPatronal(), 2); ?></td>
                    <td><?php echo htmlspecialchars($intecap->getNombreCompleto()); ?></td>
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
