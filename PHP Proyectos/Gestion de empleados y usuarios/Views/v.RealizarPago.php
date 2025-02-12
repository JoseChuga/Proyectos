<?php

require_once '../Data/PolizaODB.php';
require_once '../Data/PrestamoODB.php';
require_once '../Model/HistorialPagosPrestamos.php';
require_once '../Data/HistorialPagosPrestamosODB.php';
require_once '../Data/EmpleadoODB.php';

$idPrestamo = $_GET['ID_Prestamo'] ?? null;

$monto = $noCuota = $saldoPendiente = $nombreEmpleado = $idPoliza = $idEmpleado = $cuentaContable = null;

// Verificar si se obtuvo el ID_Prestamo y buscar los datos asociados
if ($idPrestamo) {
    $prestamoODB = new PrestamoODB();
    $prestamo = $prestamoODB->getPagoPorPrestamoId($idPrestamo); // Obtener datos del préstamo mediante el ID_Prestamo
    if ($prestamo) {
        $monto = $prestamo->getMonto();
        $noCuota = $prestamo->getCuotasRestantes();
        $saldoPendiente = $prestamo->getSaldoPendiente();
        $idEmpleado = $prestamo->getIdEmpleado();
        $idPoliza = $prestamo->getIdPoliza();
        $cuentaContable = $prestamo->getCuentaContable();

        // Obtener el nombre del empleado
        $nombreEmpleado = $prestamo->getNombreCompleto(); // Concatenamos nombre y apellido del empleado
    }
}

// Calcular nuevo saldo
$nuevoSaldo = $saldoPendiente - $monto;

// Si se envió el formulario para registrar el pago
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Registrar_Pago'])) {
    $fecha = date('Y-m-d'); // Fecha actual del sistema

    $historialPagosODB = new HistorialPagosPrestamosODB();

    // Crear objeto de pago
    $nuevoPago = new HistorialPagosPrestamos(
        null, $fecha, $monto, $noCuota, $nuevoSaldo, $idEmpleado, $idPoliza, $idPrestamo, null, $cuentaContable
    );

    // Verificar si el objeto fue creado correctamente
    if ($nuevoPago) {
        // Insertar el nuevo pago
        $result = $historialPagosODB->insert($nuevoPago);
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
    <title>Realizar Pago</title>
    <link rel="stylesheet" href="../Styles/styles.css">
</head>
<body>
<header>
    <h1>Realizar Pago</h1>
    <nav>
        <ul>
            <li><a href="v.prestamo.php">REGRESAR</a></li>
        </ul>
    </nav>
</header>
<main>
    <form method="POST" action="v.RealizarPago.php?ID_Prestamo=<?php echo $idPrestamo; ?>">
        <div class="form-group">
            <label for="No_Cuota">Cuotas Restantes:</label>
            <input type="text" id="No_Cuota" name="No_Cuota" value="<?php echo $noCuota; ?>" readonly>
        </div>
        <div class="form-group">
            <label for="Nombre_Empleado">Nombre Empleado:</label>
            <input type="text" id="Nombre_Empleado" name="Nombre_Empleado" value="<?php echo $nombreEmpleado; ?>" readonly>
        </div>
        <div class="form-group">
            <label for="Monto">Monto:</label>
            <input type="text" id="Monto" name="Monto" value="<?php echo $monto; ?>" readonly>
        </div>
        <div class="form-group">
            <label for="Saldo_Pendiente">Saldo Pendiente:</label>
            <input type="text" id="Saldo_Pendiente" name="Saldo_Pendiente" value="<?php echo $saldoPendiente; ?>" readonly>
        </div>
        <div class="form-group">
            <label for="Nuevo_Saldo">Nuevo Saldo Pendiente:</label>
            <input type="text" id="Nuevo_Saldo" name="Nuevo_Saldo" value="<?php echo $nuevoSaldo; ?>" readonly>
        </div>
        <div class="form-group">
            <label for="Cuenta_Contable">Cuenta Contable:</label>
            <input type="text" id="Cuenta_Contable" name="Cuenta_Contable" value="<?php echo htmlspecialchars($cuentaContable); ?>" readonly>
        </div>
        <div class="form-group">
            <button type="submit" name="Registrar_Pago" class="btn-submit">Registrar Pago</button>
        </div>
    </form>
</main>
<footer>
    <p>© 2024 TConsulting. Todos los derechos reservados.</p>
</footer>
</body>
</html>
