<?php

require_once '../Data/TiendaODB.php'; // Asegúrate de tener el archivo correcto
require_once '../Model/Tienda.php'; // Asegúrate de tener el archivo correcto

$idCompra = $_GET['ID_Compra'] ?? null;

$monto = $noCuota = $saldoPendiente = $nombreEmpleado = $cuentaContable = null;

// Verificar si se obtuvo el ID_Compra y buscar los datos asociados
if ($idCompra) {
    $tiendaODB = new TiendaODB();
    $datosCompra = $tiendaODB->getDatosCompra($idCompra); // Obtener datos de la compra mediante el ID_Compra
    if ($datosCompra) {
        $monto = $datosCompra->getSaldoPendiente()/6; // Cambia esto si necesitas otro valor
        $noCuota = $datosCompra->getCuotas(); // Asegúrate de que esta columna exista en tu SP
        $saldoPendiente = $datosCompra->getSaldoPendiente();
        $nombreEmpleado = $datosCompra->getNombreCompleto();
        $cuentaContable = $datosCompra->getCuentaContable();
    }
}

// Si se envió el formulario para registrar el pago
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Registrar_Pago'])) {
    $pago = $monto; // Asignar el monto a la variable de pago
    $tiendaODB = new TiendaODB();

    // Registrar el pago y actualizar la tienda
    $result = $tiendaODB->insertPagoYActualizarTienda($pago, $idCompra, $datosCompra->getIdEmpleado());

    if ($result) {
        header("Location: v.tienda.php?action=created");
        exit(); // Termina el script después de la redirección
    } else {
        header("Location: v.tienda.php?action=error");
        exit();
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
            <li><a href="v.tienda.php">REGRESAR</a></li>
        </ul>
    </nav>
</header>
<main>
    <form method="POST" action="v.PagoTienda.php?ID_Compra=<?php echo $idCompra; ?>">
        <div class="form-group">
            <label for="Nombre_Empleado">Nombre Empleado:</label>
            <input type="text" id="Nombre_Empleado" name="Nombre_Empleado" value="<?php echo htmlspecialchars($nombreEmpleado); ?>" readonly>
        </div>
        <div class="form-group">
            <label for="Monto">Monto:</label>
            <input type="text" id="Monto" name="Monto" value="<?php echo htmlspecialchars($monto); ?>" readonly>
        </div>
        <div class="form-group">
            <label for="Saldo_Pendiente">Saldo Pendiente:</label>
            <input type="text" id="Saldo_Pendiente" name="Saldo_Pendiente" value="<?php echo htmlspecialchars($saldoPendiente); ?>" readonly>
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

