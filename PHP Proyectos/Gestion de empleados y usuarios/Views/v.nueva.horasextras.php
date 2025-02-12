<?php
require_once '../Data/HorasExtrasODB.php';
require_once '../Data/EmpleadoODB.php';

$horasExtrasODB = new HorasExtrasODB();
$empleadoODB = new EmpleadoODB();

// Obtener la lista de empleados
$empleados = $empleadoODB->getAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $horasNormales = intval($_POST['Horas_Normales']);
    $horasDobles = intval($_POST['Horas_Dobles']);
    $idEmpleado = intval($_POST['ID_Empleado']);

    // Validación en el servidor
    if (empty($horasNormales) || empty($horasDobles) || empty($idEmpleado)) {
        echo "<script>
            Swal.fire({
                title: 'Error',
                text: 'Todos los campos son obligatorios.',
                icon: 'error'
            });
        </script>";
    } else {
        // Obtener el salario base del empleado seleccionado
        $salarioBase = $empleadoODB->getSalarioBase($idEmpleado);

        if ($salarioBase === null) {
            echo "<script>
                Swal.fire({
                    title: 'Error',
                    text: 'No se pudo obtener el salario base del empleado.',
                    icon: 'error'
                });
            </script>";
        } else {
            // Obtener la fecha actual
            $fechaActual = date('Y-m-d');
            // Calcular el total normal y doble
            $totalNormal = ($salarioBase / 30 / 8 * 1.5) * $horasNormales;
            $totalDoble = ($salarioBase / 30 / 8 * 2) * $horasDobles;

            // Crear el objeto HorasExtras
            $horasExtras = new HorasExtras(null, $fechaActual, $horasNormales, $horasDobles, $totalNormal, $totalDoble, $idEmpleado);
            // Llamar al método insert
            $result = $horasExtrasODB->insert($horasExtras);

            if ($result) {
                header("Location: v.horasextras.php?action=created");
                exit(); // Termina el script después de la redirección
            } else {
                header("Location: v.horasextras.php?action=error");
                exit();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Horas Extras</title>
    <link rel="stylesheet" href="../Styles/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<header>
    <h1>Registrar Horas Extras</h1>
    <nav>
        <ul>
            <li><a href="v.horasextras.php">REGRESAR</a></li>
        </ul>
    </nav>
</header>
<main>
    <section class="form-section">
        <h2>Registrar Nuevas Horas Extras</h2>
        <form id="horasExtrasForm" action="v.nueva.horasextras.php" method="POST" class="form-crear-editar">
            <div class="form-group">
                <label for="Horas_Normales">Horas Normales:</label>
                <input type="number" id="horasNormales" name="Horas_Normales" min="0" required>
            </div>
            <div class="form-group">
                <label for="Horas_Dobles">Horas Dobles:</label>
                <input type="number" id="horasDobles" name="Horas_Dobles" min="0" required>
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
            <div class="form-group form-buttons">
                <button type="submit" class="btn-submit">Registrar Horas Extras</button>
            </div>
        </form>
    </section>
</main>
<footer>
    <p>© 2024 TConsulting. Todos los derechos reservados.</p>
</footer>
</body>
</html>

