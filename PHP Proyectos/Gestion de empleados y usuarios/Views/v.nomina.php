<?php
require_once '../Data/NominaODB.php';

$nominaController = new NominaODB();
$nominas = [];

if (isset($_GET['action']) && $_GET['action'] === 'generate') {
    $nominas = $nominaController->generarNomina();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Generar Nómina</title>
    <link rel="stylesheet" href="../Styles/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<header>
    <h1>Generación de Nómina</h1>
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
    <section class="Nomina">
        <form id="generarNominaForm" method="GET" style="display: none;">
            <input type="hidden" name="action" value="generate">
        </form>
        <button onclick="confirmarGeneracionNomina()" class="btn btn-eliminar">Generar Nómina</button>
        <button onclick="window.location.href='v.IGSS.php'" class="btn btn-editar">Ver Cálculo de IGSS</button>
        <button onclick="window.location.href='v.IRTRA.php'" class="btn btn-buscar">Ver Cálculo de IRTRA</button>
        <button onclick="window.location.href='v.INTECAP.php'" class="btn employee-button">Ver Cálculo de INTECAP</button>

        <?php if (!empty($nominas)): ?>
            <h2>Resultados de Nómina</h2>
            <div class="table-container">
                <table>
                    <thead>
                    <tr>
                        <th>Mes</th>
                        <th>Año</th>
                        <th class="text-wrap">Salario Base</th>
                        <th class="text-wrap">Pago Nómina</th>
                        <th>Comisión</th>
                        <th>Bonificación</th>
                        <th class="text-wrap">Descuento Préstamo</th>
                        <th class="text-wrap">Descuento Tienda</th>
                        <th class="text-wrap">Tipo Periodo</th>
                        <th class="text-wrap">Salario Final</th>
                        <th class="text-wrap">Nombre del Empleado</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($nominas as $nomina): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($nomina->getMes()); ?></td>
                            <td><?php echo htmlspecialchars($nomina->getAnio()); ?></td>
                            <td><?php echo htmlspecialchars($nomina->getSalarioBase()); ?></td>
                            <td><?php echo htmlspecialchars($nomina->getPagoNomina()); ?></td>
                            <td><?php echo htmlspecialchars($nomina->getComision()); ?></td>
                            <td><?php echo htmlspecialchars($nomina->getBonificacion()); ?></td>
                            <td><?php echo number_format($nomina->getDescuentoPrestamo(), 2); ?></td>
                            <td><?php echo number_format($nomina->getDescuentoTienda(), 2); ?></td>
                            <td><?php echo htmlspecialchars($nomina->getTipoPeriodo()); ?></td>
                            <td><?php echo number_format($nomina->getSalarioFinal(), 2); ?></td>
                            <td><?php echo htmlspecialchars($nomina->getNombreCompleto()); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>No hay datos de nómina para mostrar.</p>
        <?php endif; ?>
    </section>
</main>

<script>
    function confirmarGeneracionNomina() {
        Swal.fire({
            title: '¿Desea generar la Nómina?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('generarNominaForm').submit();
            }
        });
    }

    function cerrarSesion() {
        // Redirige al usuario a la página index.php
        window.location.href = '../index.html';
    }
</script>

</body>
</html>
