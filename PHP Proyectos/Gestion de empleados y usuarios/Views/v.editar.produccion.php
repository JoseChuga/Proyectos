<?php
require_once '../Data/ProduccionODB.php';
require_once '../Data/EmpleadoODB.php';
require_once '../Data/PolizaODB.php';

$produccionODB = new ProduccionODB();
$empleadoODB = new EmpleadoODB();
$polizaODB = new PolizaODB();

$idProduccion = $_GET['ID_Produccion'] ?? null;

if ($idProduccion) {
    $produccion = $produccionODB->buscarProduccionPorId($idProduccion);
    if ($produccion) {
        $idPoliza = $produccion->getIDPoliza();
        $idEmpleado = $produccion->getIDEmpleado();
        $poliza = $polizaODB->getById($idPoliza);
        $empleados = $empleadoODB->getAll();
        $empleado = $empleadoODB->getById($idEmpleado);
    } else {
        echo "Error: Producción no encontrada.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger datos del formulario
    $idProduccion = $_POST['ID_Produccion'];
    $piezasElaboradas = $_POST['Piezas_Elaboradas'];
    $bonificacion = $piezasElaboradas * 0.01; // Cálculo de la bonificación
    $idEmpleado = $_POST['ID_Empleado'];
    $descripcion = $_POST['Descripcion'];
    $CuentaContable = $_POST['Cuenta_Contable'];

    // Llamar al método de actualización
    $result = $produccionODB->modificarProduccion($idProduccion, $piezasElaboradas, $bonificacion, $idEmpleado, $descripcion);

    if ($result) {
        header("Location: v.produccion.php?action=updated");
        exit();
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
    <title>Modificar Producción</title>
    <link rel="stylesheet" href="../Styles/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function actualizarCuentaContable(empleados) {
            const empleadoSelect = document.getElementById('id_empleado');
            const cuentaContableInput = document.getElementById('cuentaContable');

            empleadoSelect.addEventListener('change', function () {
                const empleadoSeleccionado = this.value;
                const empleado = empleados.find(emp => emp.id === empleadoSeleccionado);

                if (empleado) {
                    cuentaContableInput.value = empleado.cuentaContable;
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const empleados = <?php echo json_encode(array_map(function($empleado) {
                return [
                    'id' => $empleado->getIdEmpleado(),
                    'cuentaContable' => $empleado->getCuentaContable()
                ];
            }, $empleados)); ?>;

            actualizarCuentaContable(empleados);
        });
    </script>
</head>
<body>
<header>
    <h1>Modificar Producción</h1>
    <nav>
        <ul>
            <li><a href="v.produccion.php">REGRESAR</a></li>
        </ul>
    </nav>
</header>

<main>
    <section class="form-section">
        <h2>Modificar Registro de Producción</h2>
        <form id="produccionForm" action="v.editar.produccion.php?ID_Produccion=<?php echo htmlspecialchars($idProduccion); ?>" method="POST" class="form-crear-editar">
            <input type="hidden" name="ID_Produccion" value="<?php echo htmlspecialchars($idProduccion); ?>">

            <div class="form-group">
                <label for="Piezas_Elaboradas">Piezas Elaboradas:</label>
                <input type="number" id="piezasElaboradas" name="Piezas_Elaboradas" value="<?php echo htmlspecialchars($produccion->getPiezasElaboradas()); ?>" required>
            </div>
            <div class="form-group">
                <label for="Descripcion">Descripción:</label>
                <textarea id="descripcion" name="Descripcion" readonly required>Bonificación por Producción.</textarea>
            </div>
            <div class="form-group">
                <input type="hidden" id="cuentaContable" name="Cuenta_Contable" value="<?php echo htmlspecialchars($empleado->getCuentaContable()); ?>">
            </div>
            <div class="form-group">
                <label for="ID_Empleado">Empleado:</label>
                <select id="id_empleado" name="ID_Empleado" required>
                    <option value="">Seleccionar...</option>
                    <?php foreach ($empleados as $empleado) : ?>
                        <option value="<?php echo htmlspecialchars($empleado->getIdEmpleado()); ?>" <?php echo $produccion->getIDEmpleado() == $empleado->getIdEmpleado() ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($empleado->getNombre() . ' ' . $empleado->getApellido()); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
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
</body>
</html>
