<?php
require_once '../Data/PolizaODB.php';
require_once '../Model/Produccion.php';
require_once '../Data/ProduccionODB.php';

$produccionODB = new ProduccionODB();
$polizaContableODB = new PolizaODB();

$idPoliza = $_GET['ID_Poliza'] ?? null;

if ($idPoliza) {
    $poliza = $polizaContableODB->getById($idPoliza);
    // Obtener las producciones relacionadas con la póliza usando la clase Produccion
    $producciones = $produccionODB->getProduccionesByPoliza($idPoliza);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idPoliza = $_POST['ID_Poliza'];
    $fecha = $_POST['Fecha'];
    $descripcion = $_POST['Descripción'];
    $monto = $_POST['Monto'];
    $cuentaContable = $_POST['cuenta_Contable'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Producciones Relacionadas</title>
    <link rel="stylesheet" href="../Styles/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<header>
    <h1>Producciones Relacionadas</h1>
    <nav>
        <ul>
            <li><a id="regresarButton" href="v.Poliza.php">REGRESAR</a></li>
        </ul>
    </nav>
</header>

<main>
    <section class="form-section">
        <h2>Detalles de la Póliza</h2>
        <div class="form-group">
            <label><strong>Nombre del Empleado:</strong></label>
            <span><?php echo htmlspecialchars($poliza->getNombreCompleto()); ?></span>
        </div>
        <div class="form-group">
            <label><strong>Cuenta Contable:</strong></label>
            <span><?php echo htmlspecialchars($poliza->getCuentaContable()); ?></span>
        </div>

        <h2>Producciones Relacionadas</h2>
        <?php if (!empty($producciones)): ?>
            <?php foreach ($producciones as $produccion): ?>
                <div class="produccion-detalles">
                    <div class="form-group">
                        <label><strong>ID Producción:</strong></label>
                        <span><?php echo htmlspecialchars($produccion->getIDProduccion()); ?></span>
                    </div>
                    <div class="form-group">
                        <label><strong>Fecha:</strong></label>
                        <span><?php echo htmlspecialchars($produccion->getFecha()); ?></span>
                    </div>
                    <div class="form-group">
                        <label><strong>Piezas Elaboradas:</strong></label>
                        <span><?php echo htmlspecialchars($produccion->getPiezasElaboradas()); ?></span>
                    </div>
                    <div class="form-group">
                        <label><strong>Bonificación:</strong></label>
                        <span><?php echo htmlspecialchars($produccion->getBonificacion()); ?></span>
                    </div>
                    <div class="form-group">
                        <label><strong>ID Empleado:</strong></label>
                        <span><?php echo htmlspecialchars($produccion->getIDEmpleado()); ?></span>
                    </div>
                </div>
                <hr>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay producciones relacionadas con esta póliza.</p>
        <?php endif; ?>
    </section>
</main>

<footer>
    <p>© 2024 TConsulting. Todos los derechos reservados.</p>
</footer>

</body>
</html>

