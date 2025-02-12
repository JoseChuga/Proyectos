<?php
require_once '../Data/PrestamoODB.php';
require_once '../Model/Prestamo.php';
require_once '../Data/PolizaODB.php';
require_once '../Data/EmpleadoODB.php';

$prestamoODB = new PrestamoODB();
$empleadoODB = new EmpleadoODB();
$polizaODB = new PolizaODB();

$idPrestamo = $_GET['ID_Prestamo'] ?? null;

if ($idPrestamo) {
    $prestamo = $prestamoODB->getById($idPrestamo);
    if ($prestamo) {
        $idPoliza = $prestamo->getIdPoliza();
        $poliza = $polizaODB->getById($idPoliza);
        $idEmpleado = $prestamo->getIdEmpleado();
        $empleados = $empleadoODB->getAll();
    } else {
        echo "Error: Préstamo no encontrado.";
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prestamo = new Prestamo();
    $prestamo->setIdPrestamo($_POST['ID_Prestamo']);
    $prestamo->setMonto($_POST['Monto']);
    $prestamo->setCuotas($_POST['Cuotas']);
    $prestamo->setFechaInicio($_POST['FechaInicio']);
    $prestamo->setCuotasRestantes($_POST['Cuotas_Restantes']);
    $prestamo->setSaldoPendiente($_POST['Saldo_Pendiente']);
    $prestamo->setCancelado($_POST['Cancelado'] ?? 0);
    $prestamo->setIdEmpleado($idEmpleado);
    $prestamo->setIdPoliza($idPoliza);

    $result = $prestamoODB->update($prestamo);
        if ($result) {
            header("Location: v.prestamo.php?action=created");
            exit(); // Termina el script después de la redirección
        } else {
            header("Location: v.prestamo.php?action=error");
            exit();
        }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Préstamo</title>
    <link rel="stylesheet" href="../Styles/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function calcularMonto() {
            const saldoPendiente = parseFloat(document.getElementById('saldoPendiente').value);
            const cuotas = parseInt(document.getElementById('cuotas').value);
            if (!isNaN(saldoPendiente) && !isNaN(cuotas) && cuotas > 0) {
                const monto = saldoPendiente / cuotas;
                document.getElementById('monto').value = monto.toFixed(2); // Actualiza el monto con dos decimales
            } else {
                document.getElementById('monto').value = ''; // Limpia el monto si los valores no son válidos
            }
        }
    </script>
</head>
<body>
<header>
    <h1>Editar Préstamo</h1>
    <nav>
        <ul>
            <li><a href="v.prestamo.php">REGRESAR</a></li>
        </ul>
    </nav>
</header>

<main>
    <section class="form-section">
        <h2>Modificar Préstamo</h2>
        <form id="prestamoForm" action="v.editar.prestamo.php?ID_Prestamo=<?php echo htmlspecialchars($idPrestamo); ?>" method="POST" class="form-crear-editar" onsubmit="calcularMonto();">
            <input type="hidden" name="ID_Prestamo" value="<?php echo htmlspecialchars($idPrestamo); ?>">
            <input type="hidden" name="ID_Empleado" value="<?php echo htmlspecialchars($idEmpleado); ?>">
            <input type="hidden" name="ID_Poliza" value="<?php echo htmlspecialchars($idPoliza); ?>">

            <div class="form-group">
                <label for="Monto">Monto (Calculado):</label>
                <!-- Campo Monto de solo lectura -->
                <input type="text" id="monto" name="Monto" value="<?php echo number_format($prestamo->getMonto(), 2); ?>" readonly>
            </div>

            <div class="form-group">
                <label for="Cuotas">Cuotas:</label>
                <input type="number" id="cuotas" name="Cuotas" value="<?php echo htmlspecialchars($prestamo->getCuotas()); ?>" required onchange="calcularMonto()">
            </div>

            <div class="form-group">
                <label for="FechaInicio">Fecha de Inicio:</label>
                <input type="date" id="fechaInicio" name="FechaInicio" value="<?php echo htmlspecialchars($prestamo->getFechaInicio()); ?>" required readonly>
            </div>

            <div class="form-group">
                <label for="Cuotas_Restantes">Cuotas Restantes:</label>
                <input type="number" id="cuotasRestantes" name="Cuotas_Restantes" value="<?php echo htmlspecialchars($prestamo->getCuotasRestantes()); ?>" required>
            </div>

            <div class="form-group">
                <label for="Saldo_Pendiente">Cantidad a Desembolsar:</label>
                <input type="number" step="0.01" id="saldoPendiente" name="Saldo_Pendiente" value="<?php echo htmlspecialchars($prestamo->getSaldoPendiente()); ?>" required oninput="calcularMonto()">
            </div>

            <div class="form-group form-buttons">
                <button type="submit" class="btn-submit">Actualizar Registro</button>
            </div>
        </form>
    </section>
</main>

<footer>
    <p>© 2024 TConsulting. Todos los derechos reservados.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>

