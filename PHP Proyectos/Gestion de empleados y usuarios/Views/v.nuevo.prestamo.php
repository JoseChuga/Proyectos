<?php
require_once '../Data/PrestamoODB.php';
require_once '../Data/EmpleadoODB.php';

$prestamoODB = new PrestamoODB();
$empleadoODB = new EmpleadoODB();

// Obtener la lista de empleados
$empleados = $empleadoODB->getAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $monto = trim($_POST['Monto']);
    $cuotas = intval($_POST['Cuotas']);
    $fechaInicio = trim($_POST['FechaInicio']);
    $cuotasRestantes = intval($_POST['Cuotas']);
    $saldoPendiente = trim($_POST['Saldo_Pendiente']);
    $cancelado = isset($_POST['Cancelado']) ? 1 : 0; // Si está marcado, valor es 1
    $idEmpleado = intval($_POST['ID_Empleado']);

    // Validación en el servidor
    if (empty($monto) || empty($cuotas) || empty($fechaInicio) || empty($cuotasRestantes) || empty($saldoPendiente) || empty($idEmpleado)) {
        echo "<script>
            Swal.fire({
                title: 'Error',
                text: 'Todos los campos son obligatorios.',
                icon: 'error'
            });
        </script>";
    } else {
        // Crear un objeto Prestamo
        $prestamo = new Prestamo(null, $monto, $cuotas, $fechaInicio, $cuotasRestantes, $saldoPendiente, $cancelado, $idEmpleado);

        // Llamada al método de inserción
        $result = $prestamoODB->insert($prestamo);

        if ($result) {
            header("Location: v.prestamo.php?action=created");
            exit(); // Termina el script después de la redirección
        } else {
            header("Location: v.prestamo.php?action=error");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Préstamo</title>
    <link rel="stylesheet" href="../Styles/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function calcularMonto() {
            const saldoPendiente = parseFloat(document.getElementById('saldoPendiente').value);
            const cuotas = parseInt(document.getElementById('cuotas').value);
            if (!isNaN(saldoPendiente) && !isNaN(cuotas) && cuotas > 0) {
                const monto = saldoPendiente / cuotas;
                document.getElementById('monto').value = monto.toFixed(2); // Formato a dos decimales
            } else {
                document.getElementById('monto').value = '';
            }
        }
    </script>
</head>
<body>
<header>
    <h1>Registrar Préstamo</h1>
    <nav>
        <ul>
            <li><a href="v.prestamo.php">REGRESAR</a></li>
        </ul>
    </nav>
</header>
<main>
    <section class="form-section">
        <h2>Registrar Nuevo Préstamo</h2>
        <form id="prestamoForm" action="v.nuevo.prestamo.php" method="POST" class="form-crear-editar" onsubmit="calcularMonto();">
            <input type="hidden" id="monto" name="Monto">
            <div class="form-group">
                <label for="Cuotas">Cuotas:</label>
                <select id="cuotas" name="Cuotas" required onchange="calcularMonto()">
                    <option value="">Seleccionar...</option>
                    <option value="6">6</option>
                    <option value="12">12</option>
                    <option value="18">18</option>
                </select>
            </div>
            <div class="form-group">
                <label for="FechaInicio">Fecha de Inicio:</label>
                <input type="date" id="fechaInicio" name="FechaInicio" value="<?php echo date('Y-m-d'); ?>" readonly required>
            </div>
            <div class="form-group">
                <label for="Saldo_Pendiente">Cantidad del Préstamo:</label>
                <input type="number" step="0.01" id="saldoPendiente" name="Saldo_Pendiente" required oninput="calcularMonto()">
            </div>
            <div class="form-group">
                <label for="ID_Empleado">Empleado:</label>
                <select id="idEmpleado" name="ID_Empleado" required>
                    <option value="">Seleccionar...</option>
                    <?php foreach ($empleados as $empleado) : ?>
                        <option value="<?php echo htmlspecialchars($empleado->getIdEmpleado(), ENT_QUOTES, 'UTF-8'); ?>">
                            <?php echo htmlspecialchars($empleado->getNombre() . ' ' . $empleado->getApellido(), ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group form-buttons">
                <button type="submit" class="btn-submit">Crear Préstamo</button>
            </div>
        </form>
    </section>
</main>
<footer>
    <p>© 2024 TConsulting. Todos los derechos reservados.</p>
</footer>
</body>
</html>
