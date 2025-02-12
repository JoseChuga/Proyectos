<?php

require_once '../Model/Poliza.php';
require_once '../Data/PolizaODB.php';

$polizaContableODB = new PolizaODB();
$idPoliza = $_GET['ID_Poliza'] ?? null; // Verifica que este valor sea correcto

// Obtener todas las pólizas para mostrar en la tabla
$polizas = $polizaContableODB->getAll();

$poliza = null;
$descripcion = '';


if ($idPoliza) {
    $poliza = $polizaContableODB->getById($idPoliza);

    if ($poliza) {
        $descripcion = $poliza->getDescripcion(); // Solo accede si la póliza no es null
    } else {
        // Manejo de error si la póliza no fue encontrada
        echo "<script>
                Swal.fire({
                    title: 'Error',
                    text: 'Póliza no encontrada.',
                    icon: 'error'
                }).then(() => {
                    window.location.href = 'v.polizas.php';
                });
              </script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Lista de Pólizas Contables</title>
    <link rel="stylesheet" href="../Styles/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.1/dist/sweetalert2.min.css" rel="stylesheet">
</head>

<body>
<header>
    <h1>Gestión de Pólizas Contables</h1>
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
    <section class="Polizas">
        <h2>Pólizas Registradas</h2>
        <table>
            <thead>
            <tr>
                <th>Fecha</th>
                <th>Descripción</th>
                <th>Monto</th>
                <th>Nombre del Empleado</th>
                <th>No. Cuenta</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($polizas as $poliza) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($poliza->getFecha()); ?></td>
                    <td><?php echo htmlspecialchars($poliza->getDescripcion()); ?></td>
                    <td><?php echo number_format($poliza->getMonto(), 2); ?></td>
                    <td><?php echo htmlspecialchars($poliza->getNombreCompleto()); ?></td>
                    <td><?php echo htmlspecialchars($poliza->getCuentaContable()); ?></td>
                    <td>
                        <a href="<?php
                        if (strpos($poliza->getDescripcion(), 'Comisión Sobre Ventas') !== false) {
                            echo "v.editar.poliza.php?ID_Poliza=" . $poliza->getIdPoliza();
                        } elseif (strpos($poliza->getDescripcion(), 'Bonificación por Producción') !== false) {
                            echo "PolizaProduccion.php?ID_Poliza=" . $poliza->getIdPoliza();
                        }
                        ?>" class="btn btn-editar">Ver</a>

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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function cerrarSesion() {
        // Redirige al usuario a la página index.php
        window.location.href = '../index.html';
    }
</script>
</body>

</html>
