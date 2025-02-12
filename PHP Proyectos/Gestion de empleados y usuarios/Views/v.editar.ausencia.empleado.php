<?php
require_once '../Data/AusenciaODB.php';
require_once '../Data/EmpleadoODB.php';

$ausenciaODB = new AusenciaODB();
$empleadoODB = new EmpleadoODB();

$idAusencia = $_GET['ID_Solicitud'] ?? null;

if ($idAusencia) {
    $ausencia = $ausenciaODB->getById($idAusencia);
    $empleados = $empleadoODB->getAll();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idAusencia = $_POST['ID_Ausencia'];
    $idEmpleado = $_POST['ID_Empleado'];
    $fechaSolicitud = $_POST['FechaSolicitud'];  // Capturamos la fecha de solicitud
    $fechaInicio = $_POST['Fecha_Inicio'];
    $fechaFin = $_POST['Fecha_Fin'];
    $motivo = $_POST['Motivo'];
    $descripcion = $_POST['Descripcion'];
    $estado = empty($_POST['Estado']) ? null : $_POST['Estado'];
    $cuentaSalario = $_POST['Cuenta_Salario'];

    // Obtener el salario base del empleado seleccionado
    $empleado = null;
    foreach ($empleados as $emp) {
        if ($emp->getIdEmpleado() == $idEmpleado) {
            $empleado = $emp;
            break;
        }
    }

    if ($empleado) {
        // Inicializar el descuento
        $descuento = 0;

        // Solo calcular el descuento si se selecciona "Sí" (Cuenta_Salario == 1)
        if ($cuentaSalario == '1') {
            $salarioBase = $empleado->getSalarioBase();  // Obtener salario base

            // Calcular los días del mes de la fecha de inicio
            $diasDelMes = date('t', strtotime($fechaInicio));  // Número de días del mes

            // Calcular el salario diario
            $salarioDiario = $salarioBase / $diasDelMes;

            // Calcular los días de la ausencia
            $fechaInicioObj = new DateTime($fechaInicio);
            $fechaFinObj = new DateTime($fechaFin);
            $diasAusencia = $fechaFinObj->diff($fechaInicioObj)->days + 1;  // Número de días de ausencia

            // Calcular el descuento
            $descuento = $salarioDiario * $diasAusencia;
        }

        // Ahora puedes utilizar el valor del descuento en el proceso de actualización
        $result = $ausenciaODB->update($idAusencia, $idEmpleado, $fechaSolicitud, $fechaInicio, $fechaFin, $motivo, $descripcion, $estado, $cuentaSalario, $descuento, $NombreCompleto = null);

        if ($result) {
            header("Location: v.ausencias.php?action=updated");
            exit();
        } else {
            header("Location: v.ausencias.php?action=error");
            exit();
        }
    } else {
        // Manejar el caso donde no se encontró el empleado
        echo "Empleado no encontrado.";
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Ausencia</title>
    <link rel="stylesheet" href="../Styles/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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
    <h1>Editar Ausencia</h1>
    <nav>
        <ul>
            <li><a href="v.ausencias.php">REGRESAR</a></li>
        </ul>
    </nav>
</header>

<main>
    <section class="form-section">
        <form id="ausenciaForm" action="v.editar.ausencia.empleado.php?ID_Solicitud=<?php echo htmlspecialchars($idAusencia); ?>" method="POST" class="form-crear-editar">
        <input type="hidden" name="ID_Ausencia" value="<?php echo htmlspecialchars($idAusencia); ?>">
            <input type="hidden" name="ID_Empleado" value="<?php echo htmlspecialchars($ausencia->getIdEmpleado()); ?>"> <!-- Campo oculto para ID_Empleado -->
            <input type="hidden" name="FechaSolicitud" value="<?php echo htmlspecialchars($ausencia->getFechaSolicitud()); ?>">


            <div class="form-group">
                <label for="Empleado">Empleado:</label>
                <input type="text" id="empleado" value="<?php echo htmlspecialchars($empleadoODB->getById($ausencia->getIdEmpleado())->getNombre() . ' ' . $empleadoODB->getById($ausencia->getIdEmpleado())->getApellido()); ?>" disabled> <!-- Mostrar nombre sin modificar -->
            </div>

            <div class="form-group">
                <label for="Fecha_Inicio">Fecha de Inicio:</label>
                <input type="date" id="fecha_inicio" name="Fecha_Inicio" value="<?php echo htmlspecialchars($ausencia->getFechaInicio()); ?>" required oninput="validarFechaInicio()">
            </div>

            <div class="form-group">
                <label for="Fecha_Fin">Fecha de Fin:</label>
                <input type="date" id="fecha_fin" name="Fecha_Fin" value="<?php echo htmlspecialchars($ausencia->getFechaFin()); ?>" required oninput="validarFechaFin()">
            </div>

            <div class="form-group">
                <label for="Motivo">Motivo:</label>
                <select id="motivo" name="Motivo" required>
                    <option value="Enfermedad" <?php echo $ausencia->getMotivo() === 'Enfermedad' ? 'selected' : ''; ?>>Enfermedad</option>
                    <option value="Cita Medica" <?php echo $ausencia->getMotivo() === 'Cita Medica' ? 'selected' : ''; ?>>Cita Médica</option>
                    <option value="Vacaciones" <?php echo $ausencia->getMotivo() === 'Vacaciones' ? 'selected' : ''; ?>>Vacaciones</option>
                    <option value="Dia Personal" <?php echo $ausencia->getMotivo() === 'Dia Personal' ? 'selected' : ''; ?>>Día Personal</option>
                    <option value="Accidente" <?php echo $ausencia->getMotivo() === 'Accidente' ? 'selected' : ''; ?>>Accidente</option>
                    <option value="Permiso Familiar" <?php echo $ausencia->getMotivo() === 'Permiso Familiar' ? 'selected' : ''; ?>>Permiso Familiar</option>
                    <option value="Otro" <?php echo $ausencia->getMotivo() === 'Otro' ? 'selected' : ''; ?>>Otro (Puede colocar el motivo en la descripción.)</option>
                </select>
            </div>

            <div class="form-group">
                <label for="Descripcion">Descripción:</label>
                <textarea id="descripcion" name="Descripcion" rows="4" cols="25" required maxlength="100" oninput="validarLongitud(this, 100)" title="La Descripción no puede tener más de 50 caracteres."><?php echo htmlspecialchars($ausencia->getDescripcion()); ?></textarea>
            </div>
            <div class="form-group">
                <label for="Estado">Estado:</label>
                <select id="estado" name="Estado">
                    <option value="">Seleccionar...</option>
                    <option value="Aprobado" <?php echo $ausencia->getEstado() === 'Aprobado' ? 'selected' : ''; ?>>Aprobado</option>
                    <option value="Pendiente" <?php echo $ausencia->getEstado() === 'Pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                    <option value="Rechazado" <?php echo $ausencia->getEstado() === 'Rechazado' ? 'selected' : ''; ?>>Rechazado</option>
                </select>
            </div>

            <div class="form-group">
                <label for="Cuenta_Salario">¿Se realizará descuento de salario al empleado?:</label>
                <div>
                    <label>
                        <input type="radio" id="cuenta_salario_si" name="Cuenta_Salario" value="1"
                            <?php echo $ausencia->getCuentaSalario() ? 'checked' : ''; ?>>
                        Sí
                    </label>
                    <label>
                        <input type="radio" id="cuenta_salario_no" name="Cuenta_Salario" value="0"
                            <?php echo !$ausencia->getCuentaSalario() ? 'checked' : ''; ?>>
                        No
                    </label>
                </div>
            </div>

            <div class="form-group form-buttons">
                <button type="submit" class="btn-submit">Guardar Cambios</button>
            </div>
        </form>
    </section>
</main>

<footer>
    <p>© 2024 TConsulting. Todos los derechos reservados.</p>
</footer>
</body>
</html>


