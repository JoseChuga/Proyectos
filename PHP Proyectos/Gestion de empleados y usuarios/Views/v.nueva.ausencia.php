<?php
require_once '../Model/Ausencia.php';
require_once '../Data/EmpleadoODB.php';
require_once '../Data/AusenciaODB.php';

$empleadoODB = new EmpleadoODB();
$empleados = $empleadoODB->getAll();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idEmpleado = $_POST['ID_Empleado'];
    $fechaSolicitud = date('Y-m-d');
    $fechaInicio = $_POST['Fecha_Inicio'];
    $fechaFin = $_POST['Fecha_Fin'];
    $motivo = $_POST['Motivo'];
    $descripcion = $_POST['Descripcion'];

    // Enviar cuenta salario, estado y descuento como NULL
    $cuentaSalario = null;
    $estado = null;
    $descuento = null;

    $ausencia = new Ausencia(null, $fechaSolicitud, $fechaInicio, $fechaFin, $motivo, $descripcion, $estado, $cuentaSalario, $descuento, $idEmpleado, $NombreCompleto = null
    );

    $ausenciaODB = new AusenciaODB();

    $result = $ausenciaODB->insert($ausencia);

    if ($result) {
        header("Location: v.ausencias.php?action=updated");
        exit();
    } else {
        header("Location: v.ausencias.php?action=error");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insertar Ausencia</title>
    <link rel="stylesheet" href="../Styles/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.1/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dayjs/1.10.4/dayjs.min.js"></script>
    <script>

        function validarLongitud(input, maxLength) {
            if (input.value.length > maxLength) {
                input.setCustomValidity("Este campo no puede tener más de " + maxLength + " caracteres.");
            } else {
                input.setCustomValidity(""); // Restablecer si es válido
            }
        }

        // Validar fecha de nacimiento
        function validarFechaInicio() {
            var fechaInicio = dayjs(document.getElementById('fecha_inicio').value);
            var fechaActual = dayjs();

            // Verificar si la fecha de inicio es anterior a la fecha actual
            if (fechaInicio.isBefore(fechaActual, 'day')) {
                document.getElementById('fecha_inicio').setCustomValidity("La fecha de inicio no puede ser anterior a la fecha actual.");
                return false;
            }

            // Calcular la diferencia en meses
            var diferenciaMeses = fechaInicio.diff(fechaActual, 'month', true); // true para obtener valores decimales

            if (diferenciaMeses > 3) {
                document.getElementById('fecha_inicio').setCustomValidity("La fecha de inicio debe ser dentro de los próximos 3 meses.");
                return false;
            } else {
                document.getElementById('fecha_inicio').setCustomValidity(""); // Restablecer si es válido
                return true;
            }
        }



        function validarFechaFin() {
            var fechaInicio = dayjs(document.getElementById('fecha_inicio').value);
            var fechaFin = dayjs(document.getElementById('fecha_fin').value);
            var motivo = document.getElementById('motivo').value;

            // Definir los límites de días según el motivo
            var limitesDias = {
                "Enfermedad": 10,
                "Cita Medica": 1,
                "Vacaciones": 15,
                "Dia Personal": 5,
                "Accidente": 30,
                "Permiso Familiar": 7,
                "Otro": 5
            };

            // Obtener el límite de días correspondiente al motivo seleccionado
            var limiteDias = limitesDias[motivo] || 15; // Por defecto, 15 días si no hay motivo válido

            // Verificar si la fecha de fin es anterior a la fecha de inicio
            if (fechaFin.isBefore(fechaInicio, 'day')) {
                document.getElementById('fecha_fin').setCustomValidity("La fecha de fin no puede ser anterior a la fecha de inicio.");
                return false;
            }

            // Calcular la diferencia en días
            var diferenciaDias = fechaFin.diff(fechaInicio, 'day');

            if (diferenciaDias > limiteDias) {
                document.getElementById('fecha_fin').setCustomValidity("El período no puede superar los " + limiteDias + " días para el motivo seleccionado.");
                return false;
            } else {
                document.getElementById('fecha_fin').setCustomValidity(""); // Restablecer si es válido
                return true;
            }
        }


        // Validación del formulario al enviar
        function validarFormulario() {
            return validarFechaInicio() && validarFechaFin();
        }
    </script>
</head>
<body>
<header>
    <h1>Gestión de Ausencias</h1>
    <nav>
        <ul>
            <li><a href="v.ausencias.php">REGRESAR</a></li>
        </ul>
    </nav>
</header>

<main>
    <section class="Ausencias">
        <h2>Crear Nueva Ausencia</h2>

        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <form id="ausenciaForm" action="v.nueva.ausencia.php" method="POST" class="form-crear-editar">
            <div class="form-group">
                <label for="ID_Empleado">Empleado:</label>
                <select id="id_empleado" name="ID_Empleado" required>
                    <option value="">Seleccionar...</option>
                    <?php foreach ($empleados as $empleado) : ?>
                        <option value="<?php echo htmlspecialchars($empleado->getIdEmpleado()); ?>">
                            <?php echo htmlspecialchars($empleado->getNombre() . ' ' . $empleado->getApellido()); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <input type="hidden" name="Fecha_Solicitud" value="<?php echo date('Y-m-d'); ?>">

            <div class="form-group">
                <label for="Fecha_Inicio">Fecha de Inicio:</label>
                <input type="date" id="fecha_inicio" name="Fecha_Inicio" required title="No debe sobrepasar los 15 días de Ausencia" oninput="validarFechaInicio()">
            </div>

            <div class="form-group">
                <label for="Fecha_Fin">Fecha de Fin:</label>
                <input type="date" id="fecha_fin" name="Fecha_Fin" required title="No debe sobrepasar los 15 días de Ausencia" oninput="validarFechaFin()">
            </div>

            <div class="form-group">
                <label for="Motivo">Motivo:</label>
                <select id="motivo" name="Motivo" required>
                    <option value="" disabled selected>Seleccione un motivo</option>
                    <option value="Enfermedad">Enfermedad</option>
                    <option value="Cita Medica">Cita Médica</option>
                    <option value="Vacaciones">Vacaciones</option>
                    <option value="Dia Personal">Día Personal</option>
                    <option value="Accidente">Accidente</option>
                    <option value="Permiso Familiar">Permiso Familiar</option>
                    <option value="Otro">Otro (Puede colocar el motivo en la descripción.)</option>
                </select>
            </div>

            <div class="form-group">
                <label for="Descripcion">Descripción:</label>
                <textarea id="descripcion" name="Descripcion" rows="4" cols="25" required maxlength="100" oninput="validarLongitud(this, 100)" title="La Descripción no puede tener más de 50 caracteres."></textarea>
            </div>

            <input type="hidden" name="Cuenta_Salario" value="NULL">
            <input type="hidden" name="Estado" value="NULL">
            <input type="hidden" name="Descuento" value="NULL">

            <div class="form-group form-buttons">
                <button type="submit" class="btn-submit">Guardar Ausencia</button>
            </div>
        </form>
    </section>
</main>

<footer>
    <p>© 2024 TConsulting. Todos los derechos reservados.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>



