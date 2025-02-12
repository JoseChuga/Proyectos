<?php
require_once '../Data/PolizaODB.php';
require_once '../Model/Comisiones.php';
require_once '../Data/ComisionesODB.php';

$polizaContableODB = new PolizaODB();

$idPoliza = $_GET['ID_Poliza'] ?? null;

if ($idPoliza) {
    $poliza = $polizaContableODB->getById($idPoliza);
    // Obtener las comisiones relacionadas con la póliza usando la clase Comisiones
    $comisiones = $polizaContableODB->getComisionesByPoliza($idPoliza);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idPoliza = $_POST['ID_Poliza'];
    $fecha = $_POST['Fecha'];
    $descripcion = $_POST['Descripción'];
    $monto = $_POST['Monto'];
    $cuentaContable = $_POST['cuenta_Contable'];

    $result = $polizaContableODB->update($idPoliza, $fecha, $descripcion, $monto, $cuentaContable);

    if ($result) {
        echo "<script>
            Swal.fire({
                title: 'Éxito',
                text: 'La póliza ha sido modificada correctamente.',
                icon: 'success'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'v.polizas.php';
                }
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                title: 'Error',
                text: 'Hubo un problema al modificar la póliza.',
                icon: 'error'
            });
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comisiones Relacionadas</title>
    <link rel="stylesheet" href="../Styles/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<header>
    <h1>Comisiones Relacionadas</h1>
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

        <h2>Comisiones Relacionadas</h2>
        <?php if (!empty($comisiones)): ?>
            <?php foreach ($comisiones as $comision): ?>
                <div class="comision-detalles">
                    <div class="form-group">
                        <label><strong>ID Comisión:</strong></label>
                        <span><?php echo htmlspecialchars($comision->getIDComision()); ?></span>
                    </div>
                    <div class="form-group">
                        <label><strong>Mes:</strong></label>
                        <span><?php echo htmlspecialchars($comision->getMes()); ?></span>
                    </div>
                    <div class="form-group">
                        <label><strong>Año:</strong></label>
                        <span><?php echo htmlspecialchars($comision->getAnio()); ?></span>
                    </div>
                    <div class="form-group">
                        <label><strong>Monto Ventas:</strong></label>
                        <span><?php echo htmlspecialchars($comision->getMontoVentas()); ?></span>
                    </div>
                    <div class="form-group">
                        <label><strong>Porcentaje:</strong></label>
                        <span><?php echo htmlspecialchars($comision->getPorcentaje()); ?></span>
                    </div>
                    <div class="form-group">
                        <label><strong>Comisión:</strong></label>
                        <span><?php echo htmlspecialchars($comision->getComision()); ?></span>
                    </div>
                </div>
                <hr>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay comisiones relacionadas con esta póliza.</p>
        <?php endif; ?>
    </section>
</main>

<footer>
    <p>© 2024 TConsulting. Todos los derechos reservados.</p>
</footer>

</body>
</html>
