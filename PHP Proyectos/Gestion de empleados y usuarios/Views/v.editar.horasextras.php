<?php
require_once '../Data/HorasExtrasODB.php';
require_once '../Data/EmpleadoODB.php';

$horasExtrasODB = new HorasExtrasODB();
$empleadoODB = new EmpleadoODB();

$idHoraExtra = $_GET['ID_HoraExtra'] ?? null;

if ($idHoraExtra) {
    $horaExtra = $horasExtrasODB->getById($idHoraExtra);
    $empleados = $empleadoODB->getAll();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idHoraExtra = $_POST['ID_HoraExtra'];
    $idEmpleado = $_POST['ID_Empleado'];
    $fecha = $_POST['Fecha'];
    $horasNormales = $_POST['Horas_Normales'];
    $horasDobles = $_POST['Horas_Dobles'];

    // Obtener la fecha actual
    $fechaActual = date('Y-m-d');
    $salarioBase = $empleadoODB->getSalarioBase($idEmpleado);
    // Calcular el total normal y doble
    $totalNormal = ($salarioBase / 30 / 8 * 1.5) * $horasNormales;
    $totalDoble = ($salarioBase / 30 / 8 * 2) * $horasDobles;

    // Crear el objeto HorasExtras con todos los parámetros
    $horasExtras = new HorasExtras($idHoraExtra, $fechaActual, $horasNormales, $horasDobles, $totalNormal, $totalDoble, $idEmpleado);


    // Llamada al método de actualización
    $result = $horasExtrasODB->update($horasExtras);

        if ($result) {
            header("Location: v.horasextras.php?action=created");
            exit(); // Termina el script después de la redirección
        } else {
            header("Location: v.horasextras.php?action=error");
            exit();
        }
}
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Horas Extras</title>
    <link rel="stylesheet" href="../Styles/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<header>
    <h1>Editar Horas Extras</h1>
    <nav>
        <ul>
            <li><a href="v.horasextras.php">REGRESAR</a></li>
        </ul>
    </nav>
</header>

<main>
    <section class="form-section">
        <h2>Modificar Horas Extras</h2>
        <form id="horasExtrasForm" action="v.editar.horasextras.php?ID_HoraExtra=<?php echo htmlspecialchars($idHoraExtra); ?>" method="POST" enctype="multipart/form-data" class="form-crear-editar">
            <input type="hidden" name="ID_HoraExtra" value="<?php echo htmlspecialchars($idHoraExtra); ?>">

            <div class="form-group">
                <label for="ID_Empleado">Empleado:</label>
                <select id="id_empleado" name="ID_Empleado" required>
                    <option value="">Seleccionar...</option>
                    <?php foreach ($empleados as $empleado) : ?>
                        <option value="<?php echo htmlspecialchars($empleado->getIdEmpleado()); ?>"
                            <?php echo $horaExtra->getIdEmpleado() === $empleado->getIdEmpleado() ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($empleado->getNombre() . ' ' . $empleado->getApellido()); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="Fecha">Fecha:</label>
                <input type="date" id="fecha" name="Fecha" value="<?php echo htmlspecialchars($horaExtra->getFecha()); ?>" required>
            </div>

            <div class="form-group">
                <label for="Horas_Normales">Horas Normales:</label>
                <input type="number" id="horas_normales" name="Horas_Normales" value="<?php echo htmlspecialchars($horaExtra->getHoraNormal()); ?>" required>
            </div>

            <div class="form-group">
                <label for="Horas_Dobles">Horas Dobles:</label>
                <input type="number" id="horas_dobles" name="Horas_Dobles" value="<?php echo htmlspecialchars($horaExtra->getHoraDoble()); ?>" required>
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
