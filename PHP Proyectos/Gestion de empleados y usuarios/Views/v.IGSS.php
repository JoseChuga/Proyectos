<?php

require_once '../Model/IGSS.php';
require_once '../Data/IGSSODB.php';

$igssODB = new IGSSODB();

// Obtener todos los registros de IGSS para mostrar en la tabla
$registrosIGSS = $igssODB->getAll();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Lista de Aportes IGSS</title>
    <link rel="stylesheet" href="../Styles/styles.css">
</head>

<body>
<header>
    <h1>Gestión de Aportes IGSS</h1>
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
    <section class="IGSS">
        <h2>Aportes IGSS Registrados</h2>
        <table>
            <thead>
            <tr>
                <th>Mes</th>
                <th>Año</th>
                <th>Monto Patronal</th>
                <th>Monto Laboral</th>
                <th>Nombre del Empleado</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($registrosIGSS as $igss) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($igss->getMes()); ?></td>
                    <td><?php echo htmlspecialchars($igss->getAnio()); ?></td>
                    <td><?php echo number_format($igss->getMontoPatronal(), 2); ?></td>
                    <td><?php echo number_format($igss->getMontoLaboral(), 2); ?></td>
                    <td><?php echo htmlspecialchars($igss->getNombreCompleto()); ?></td>
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
