<?php
require_once '../Data/ProduccionODB.php';
require_once '../Data/EmpleadoODB.php';

$produccionODB = new ProduccionODB();
$empleadoODB = new EmpleadoODB();

// Obtener la lista de empleados
$empleados = $empleadoODB->getAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $piezasElaboradas = intval($_POST['Piezas_Elaboradas']);
    $idEmpleado = intval($_POST['ID_Empleado']);
    $descripcion = $_POST['Descripcion'];

    // Calcular la bonificación basada en las piezas elaboradas
    $bonificacion = $piezasElaboradas * 0.01; // Ejemplo: 1% del total de piezas

    // Obtener la cuenta contable relacionada al empleado
    $empleadoSeleccionado = $empleadoODB->getById($idEmpleado);
    $cuentaContable = $empleadoSeleccionado ? $empleadoSeleccionado->getCuentaContable() : '0'; // valor predeterminado si no se encuentra

    // Insertar la producción y la póliza
    $result = $produccionODB->insertarProduccionYPoliza($piezasElaboradas, $bonificacion, $idEmpleado, $descripcion, $cuentaContable);

    if ($result) {
        header("Location: v.produccion.php?action=created");
        exit(); // Termina el script después de la redirección
    } else {
        header("Location: v.produccion.php?action=error");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Producción</title>
    <link rel="stylesheet" href="../Styles/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<header>
    <h1>Registrar Producción</h1>
    <nav>
        <ul>
            <li><a href="indexAdmon.php">Inicio</a></li>
            <li><a href="v.produccion.php">Producción</a></li>
        </ul>
    </nav>
</header>
<main>
    <section class="form-section">
        <h2>Registrar Nueva Producción</h2>
        <form id="produccionForm" action="v.nueva.produccion.php" method="POST" class="form-crear-editar">
            <div class="form-group">
                <label for="Piezas_Elaboradas">Piezas Elaboradas:</label>
                <input type="number" id="piezasElaboradas" name="Piezas_Elaboradas" min="0" required>
            </div>
            <div class="form-group">
                <label for="ID_Empleado">Empleado:</label>
                <select id="id_empleado" name="ID_Empleado" required>
                    <option value="">Seleccionar...</option>
                    <?php foreach ($empleados as $empleado) : ?>
                        <option value="<?php echo htmlspecialchars($empleado->getIdEmpleado(), ENT_QUOTES, 'UTF-8'); ?>">
                            <?php echo htmlspecialchars($empleado->getNombre() . ' ' . $empleado->getApellido(), ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="Descripcion">Descripción:</label>
                <textarea id="descripcion" name="Descripcion" readonly required>Bonificación por Producción.</textarea>
            </div>
            <input type="hidden" name="Cuenta_Contable" id="cuentaContable" value="0"> <!-- Campo oculto para la cuenta contable -->
            <div class="form-group form-buttons">
                <button type="submit" class="btn-submit">Registrar Producción</button>
            </div>
        </form>
    </section>
</main>
<footer>
    <p>© 2024 TConsulting. Todos los derechos reservados.</p>
</footer>
</body>
</html>
