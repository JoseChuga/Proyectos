<?php
require_once '../Data/TiendaODB.php'; // Asegúrate de tener el archivo correcto
require_once '../Model/Tienda.php'; // Asegúrate de tener el archivo correcto

$idCompra = $_GET['ID_Compra'] ?? null;
$idEmpleado = $_GET['ID_Empleado'] ?? null;

$fecha = date('Y-m-d');
$total = $nombreEmpleado = $cuentaContable = null;
$error = $_GET['error'] ?? null; // Definición de la variable $error

// Verificar si se obtuvo el ID_Compra y buscar los datos asociados
if ($idCompra) {
    $tiendaODB = new TiendaODB();
    $datosCompra = $tiendaODB->getDatosCompra($idCompra); // Obtener datos de la compra mediante el ID_Compra
    if ($datosCompra) {
        $total = $datosCompra->getTotal(); // Cambia esto si necesitas otro valor
        $nombreEmpleado = $datosCompra->getNombreCompleto();
        $cuentaContable = $datosCompra->getCuentaContable();
    }
}

// Si se envió el formulario para registrar la compra
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Registrar_Compra'])) {
    $total = $_POST['Total']; // Obtener el total del formulario
    $tiendaODB = new TiendaODB();

    try {
        // Registrar la compra y actualizar la tienda
        $result = $tiendaODB->insertCompraYActualizarTienda($fecha, $total, $idCompra, $idEmpleado);

        if ($result) {
            error_log("Registro exitoso."); // Mensaje de depuración
            header("Location: v.tienda.php?action=created");
            exit();
        }
    } catch (PDOException $e) {
        error_log("Error en la inserción: " . $e->getMessage()); // Mensaje de error
        // Redirigir a la misma vista con un mensaje de error
        $errorMessage = "El total de la compra supera el crédito disponible para el empleado debido a que tiene un préstamo vigente.";
        header("Location: v.compra.php?ID_Compra=$idCompra&ID_Empleado=$idEmpleado&error=" . urlencode($errorMessage));
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Compra</title>
    <link rel="stylesheet" href="../Styles/styles.css">
</head>
<body>
<header>
    <h1>Registrar Compra</h1>
    <nav>
        <ul>
            <li><a href="v.tienda.php">REGRESAR</a></li>
        </ul>
    </nav>
</header>
<main>
    <form method="POST" action="v.compra.php?ID_Compra=<?php echo $idCompra; ?>&ID_Empleado=<?php echo $idEmpleado; ?>">
        <div class="form-group">
            <label for="Nombre_Empleado">Nombre Empleado:</label>
            <input type="text" id="Nombre_Empleado" name="Nombre_Empleado" value="<?php echo htmlspecialchars($nombreEmpleado); ?>" readonly>
        </div>
        <div class="form-group">
            <label for="Fecha">Fecha:</label>
            <input type="date" id="Fecha" name="Fecha" value="<?php echo htmlspecialchars($fecha); ?>" readonly>
        </div>
        <div class="form-group">
            <label for="Total">Total:</label>
            <input type="text" id="Total" name="Total" value="<?php echo htmlspecialchars($total); ?>" required>
        </div>
        <div class="form-group">
            <label for="Cuenta_Contable">Cuenta Contable:</label>
            <input type="text" id="Cuenta_Contable" name="Cuenta_Contable" value="<?php echo htmlspecialchars($cuentaContable); ?>" readonly>
        </div>
        <div class="form-group">
            <button type="submit" name="Registrar_Compra" class="btn-submit">Registrar Compra</button>
        </div>
    </form>

    <?php if ($error): ?>
        <div class="tooltip">
            <span class="tooltiptext"><?php echo htmlspecialchars($error); ?></span>
            <button style="background: none; border: none; color: red; cursor: pointer;">Error</button>
        </div>
    <?php endif; ?>
</main>
<footer>
    <p>© 2024 TConsulting. Todos los derechos reservados.</p>
</footer>
</body>
</html>
