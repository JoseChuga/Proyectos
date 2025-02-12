<?php
require_once '../Data/TiendaODB.php';
require_once '../Model/Tienda.php';

$tiendaODB = new TiendaODB();

$Compra = $_GET['Compra'] ?? null;

if ($Compra) {
    $compra = $tiendaODB->getCompraPorID($Compra);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $compra) {
    $total = $_POST['Total'];
    $idCompra = $_POST['ID_Compra'];
    $fecha = $compra['Fecha'];  // Usamos la fecha original
    $idEmpleado = $compra['ID_Empleado'];  // Usamos el empleado original

    $result = $tiendaODB->updateCompra($compra['Compra'], $fecha, $total, $idCompra, $idEmpleado);
    if ($result) {
        // Redirigir a otra vista al ser exitosa la actualización
        header("Location: v.HistorialCompras.php?ID_Empleado=" . urlencode($idEmpleado)); // Asegúrate de usar urlencode si hay caracteres especiales
        exit(); // Detener la ejecución del script
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Compra</title>
    <link rel="stylesheet" href="../Styles/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<header>
    <h1>Editar Compra</h1>
    <nav>
        <ul>
            <li><a id="regresarButton" href="v.tienda.php">REGRESAR</a></li>
        </ul>
    </nav>
</header>

<main>
    <section class="form-section">
        <h2>Detalles de la Compra</h2>
        <form method="POST" action="v.editar.compra.php?Compra=<?php echo htmlspecialchars($Compra); ?>">
            <input type="hidden" name="ID_Compra" value="<?php echo htmlspecialchars($compra['Compra']); ?>">
            <div class="form-group">
                <label><strong>Nombre del Empleado:</strong></label>
                <span><?php echo htmlspecialchars($compra['NombreCompleto']); ?></span>
            </div>
            <div class="form-group">
                <label><strong>Cuenta Contable:</strong></label>
                <span><?php echo htmlspecialchars($compra['Cuenta_Contable']); ?></span>
            </div>
            <div class="form-group">
                <label><strong>Fecha:</strong></label>
                <span><?php echo htmlspecialchars($compra['Fecha']); ?></span>
            </div>
            <div class="form-group">
                <label for="Total"><strong>Total (Modificable):</strong></label>
                <input type="text" name="Total" id="Total" value="<?php echo htmlspecialchars($compra['Total']); ?>">
            </div>
            <div class="form-group">
                <button type="submit" class="btn-submit">Actualizar Compra</button>
            </div>
        </form>
    </section>
</main>

<footer>
    <p>© 2024 TConsulting. Todos los derechos reservados.</p>
</footer>

</body>
</html>


