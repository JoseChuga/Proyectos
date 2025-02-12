<?php
require_once '../Data/ComisionesODB.php';
require_once '../Data/EmpleadoODB.php';
require_once '../Data/PolizaODB.php';

$comisionesODB = new ComisionesODB();
$empleadoODB = new EmpleadoODB();
$polizaODB = new PolizaODB();

$idComision = $_GET['ID_Comision'] ?? null;

if ($idComision) {
    // Obtener los datos de la comisión y la póliza relacionada
    $comision = $comisionesODB->getByID($idComision);
    $idEmpleado = $comision["ID_Empleado"];

    // Obtener el empleado para acceder a la cuenta contable
    $empleado = $empleadoODB->getById($idEmpleado);

    // Obtener la póliza relacionada
    $idPoliza = $comision["ID_Poliza"];
    $poliza = $polizaODB->getById($idPoliza);

    $empleados = $empleadoODB->getAll(); // Obtener todos los empleados
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger datos del formulario
    $idComision = $_POST['ID_Comision'];
    $mes = $_POST['Mes'];
    $anio = $_POST['Anio'];
    $montoVentas = $_POST['Monto_Ventas'];
    $porcentaje = $_POST['Porcentaje'];
    $idEmpleado = $_POST['ID_Empleado'];
    $descripcion = $_POST['Descripcion'];
    $cuentaContable = $_POST['Cuenta_Contable'];

    $porcentaje = 0;

// Calcular la comisión según los tramos de ventas
    if ($montoVentas > 0) {
        if ($montoVentas > 400000) {
            $porcentaje = 4.5;
        }
        if ($montoVentas > 200000) {
            $porcentaje = 3.5;
        }
        if ($montoVentas > 100000) {
            $porcentaje = 2.5;
        }
        // Para la parte de ventas hasta 100000, la comisión es 0%
    }
    // Llamar al método de actualización
    $result = $comisionesODB->update($idComision, $mes, $anio, $montoVentas, $porcentaje, $idEmpleado, $descripcion, $cuentaContable);

    if ($result) {
        header("Location: v.comisiones.php?action=created");
        exit(); // Termina el script después de la redirección
    } else {
        header("Location: v.comisiones.php?action=error");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Comisión</title>
    <link rel="stylesheet" href="../Styles/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Actualizar el campo de cuenta contable cuando se cambie el empleado seleccionado
        function actualizarCuentaContable(empleados) {
            const empleadoSelect = document.getElementById('id_empleado');
            const cuentaContableInput = document.getElementById('cuentaContable');

            empleadoSelect.addEventListener('change', function () {
                const empleadoSeleccionado = this.value;

                // Buscar la cuenta contable del empleado seleccionado en el array de empleados
                const empleado = empleados.find(emp => emp.id === empleadoSeleccionado);

                if (empleado) {
                    cuentaContableInput.value = empleado.cuentaContable;
                }
            });
        }

        // Datos de los empleados obtenidos de PHP
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
    <h1>Editar Comisión</h1>
    <nav>
        <ul>
            <li><a href="v.comisiones.php">REGRESAR</a></li>
        </ul>
    </nav>
</header>

<main>
    <section class="form-section">
        <h2>Modificar Comisión</h2>
        <form id="comisionForm" action="v.editar.comisiones.php?ID_Comision=<?php echo htmlspecialchars($idComision); ?>" method="POST" class="form-crear-editar">
            <input type="hidden" name="ID_Comision" value="<?php echo htmlspecialchars($idComision); ?>">

            <div class="form-group">
                <label for="Mes">Mes:</label>
                <input type="text" id="mes" name="Mes" value="<?php echo htmlspecialchars($comision['Mes']); ?>" required>
            </div>
            <div class="form-group">
                <label for="Anio">Año:</label>
                <input type="text" id="anio" name="Anio" value="<?php echo htmlspecialchars($comision['Anio']); ?>" required>
            </div>
            <div class="form-group">
                <label for="Monto_Ventas">Monto de Ventas:</label>
                <input type="text" id="montoVentas" name="Monto_Ventas" value="<?php echo htmlspecialchars($comision['Monto_Ventas']); ?>" required>
            </div>
            <div class="form-group">
                <label for="Porcentaje">Porcentaje:</label>
                <input type="text" id="porcentaje" name="Porcentaje" value="<?php echo htmlspecialchars($comision['Porcentaje']); ?>" required readonly>
            </div>
            <div class="form-group">
                <label for="Descripcion">Descripción:</label>
                <textarea id="descripcion" name="Descripcion" readonly required>Comisión sobre Ventas.</textarea>
            </div>

            <input type="hidden" id="cuentaContable" name="Cuenta_Contable" value="<?php echo htmlspecialchars($empleado->getCuentaContable()); ?>">

            <div class="form-group">
                <label for="ID_Empleado">Empleado:</label>
                <select id="id_empleado" name="ID_Empleado" required>
                    <option value="">Seleccionar...</option>
                    <?php foreach ($empleados as $empleado) : ?>
                        <option value="<?php echo htmlspecialchars($empleado->getIdEmpleado()); ?>" <?php echo $comision['ID_Empleado'] == $empleado->getIdEmpleado() ? 'selected' : ''; ?>>
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

