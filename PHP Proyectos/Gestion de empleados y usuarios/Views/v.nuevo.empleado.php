<?php
require_once '../Model/Empleado.php';
require_once '../Data/EmpleadoODB.php';
require_once '../Data/DepartamentoODB.php';

$empleadoODB = new EmpleadoODB();
$departamentoODB = new DepartamentoODB();
$departamentos = $departamentoODB->getAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = isset($_POST['Nombre']) ? trim($_POST['Nombre']) : null;
    $apellido = isset($_POST['Apellido']) ? trim($_POST['Apellido']) : null;
    $fechaNacimiento = isset($_POST['Fecha_Nac']) ? $_POST['Fecha_Nac'] : null;
    $fechaContratacion = isset($_POST['Fecha_Contra']) ? $_POST['Fecha_Contra'] : null;
    $salarioBase = isset($_POST['Salario_Base']) ? $_POST['Salario_Base'] : null;
    $departamentoID = isset($_POST['Depto_ID']) ? $_POST['Depto_ID'] : null;
    $Cuenta_Contable = isset($_POST['Cuenta_Contable']) ? trim($_POST['Cuenta_Contable']) : null;

    $nombreFoto = $_FILES['Foto']['name'];
    $temporal = $_FILES['Foto']['tmp_name'];
    $carpeta = '../Imagenes';
    $rutaFoto = $carpeta . '/' . basename($nombreFoto);

    if ($salarioBase < 0) {
        echo "El salario base no puede ser un valor negativo.";
    }

    if (!empty($nombreFoto)) {
        if (move_uploaded_file($temporal, $rutaFoto)) {
            $foto = $rutaFoto;
        } else {
            $foto = null;
            // Aquí podrías manejar un error si el archivo no se pudo mover
        }
    } else {
        $foto = null; // No se seleccionó una foto
    }

    // Verificar que los campos obligatorios estén completos
    if ($nombre && $apellido && $fechaNacimiento && $fechaContratacion && $salarioBase && $departamentoID && $Cuenta_Contable) {
        // Crear el objeto de Departamento
        $departamento = new Departamento($departamentoID, "Nombre del departamento");

        // Crear el objeto de Empleado
        $empleado = new Empleado(null, $nombre, $apellido, $fechaNacimiento, $fechaContratacion, $salarioBase, $departamento, $foto, $activo = null,  $Cuenta_Contable);

        // Insertar el empleado en la base de
        if ($empleadoODB->insert($empleado)) {
            header("Location: v.empleados.php?action=created");
            exit(); // Termina el script después de la redirección
        } else {
            header("Location: v.empleados.php?action=error");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nuevo Empleado</title>
    <link rel="stylesheet" href="../Styles/styles.css">
    <script>
        // Validaciones del formulario
        function validarLongitud(input, maxLength) {
            if (input.value.length > maxLength) {
                input.setCustomValidity("Este campo no puede tener más de " + maxLength + " caracteres.");
            } else {
                input.setCustomValidity("");
            }
        }

        function validarNumeros(input) {
            const regex = /^[0-9]*$/;
            if (!regex.test(input.value)) {
                input.setCustomValidity("Solo se permiten números.");
            } else {
                input.setCustomValidity("");
            }
        }

        function validarFechaNacimiento() {
            var fechaNacimiento = new Date(document.getElementById('fecha_nac').value);
            var fechaActual = new Date();
            var fechaMinima = new Date();
            fechaMinima.setFullYear(fechaActual.getFullYear() - 70);
            var fechaMaxima = new Date();
            fechaMaxima.setFullYear(fechaActual.getFullYear() - 18);

            if (fechaNacimiento < fechaMinima || fechaNacimiento > fechaMaxima) {
                document.getElementById('fecha_nac').setCustomValidity("Debe ser mayor de 18 años y menor de 70.");
            } else {
                document.getElementById('fecha_nac').setCustomValidity("");
            }
        }

        function validarFechaContratacion() {
            var fechaContratacion = new Date(document.getElementById('fecha_contra').value);
            var fechaActual = new Date();

            if (fechaContratacion > fechaActual) {
                document.getElementById('fecha_contra').setCustomValidity("La fecha de contratación no puede ser futura.");
            } else {
                document.getElementById('fecha_contra').setCustomValidity("");
            }
        }

        function validarFormulario() {
            return validarFechaNacimiento() && validarFechaContratacion();
        }
    </script>
</head>
<body>
<header>
    <h1>Crear Nuevo Empleado</h1>
    <nav>
        <ul>
            <li><a href="v.empleados.php">REGRESAR</a></li>
        </ul>
    </nav>
</header>

<main>
    <section class="form-section">
        <h2>Registrar Empleado</h2>
        <form id="empleadoForm" action="v.nuevo.empleado.php" method="POST" enctype="multipart/form-data" class="form-crear-editar" onsubmit="return validarFormulario()">
            <div class="form-group">
                <label for="Nombre">Nombre:</label>
                <input type="text" id="nombre" name="Nombre" required maxlength="50" oninput="validarLongitud(this, 50)" title="El nombre no puede tener más de 50 caracteres.">
            </div>

            <div class="form-group">
                <label for="Apellido">Apellido:</label>
                <input type="text" id="apellido" name="Apellido" required maxlength="50" oninput="validarLongitud(this, 50)" title="El apellido no puede tener más de 50 caracteres.">
            </div>

            <div class="form-group">
                <label for="Fecha_Nac">Fecha de Nacimiento:</label>
                <input type="date" id="fecha_nac" name="Fecha_Nac" required title="Debe ser mayor de 18 años y menor de 100." oninput="validarFechaNacimiento()">
            </div>

            <div class="form-group">
                <label for="Fecha_Contra">Fecha de Contratación:</label>
                <input type="date" id="fecha_contra" name="Fecha_Contra" required title="La fecha de contratación no puede ser futura.">
            </div>

            <div class="form-group">
                <label for="Salario_Base">Salario Base:</label>
                <input type="number" id="salario_base" name="Salario_Base" required min="1000" step="0.01">
            </div>

            <div class="form-group">
                <label for="Depto_ID">Departamento:</label>
                <select id="depto_id" name="Depto_ID" required>
                    <option value="">Seleccionar...</option>
                    <?php foreach ($departamentos as $departamento) : ?>
                        <option value="<?php echo $departamento->getIdDepartamento(); ?>">
                            <?php echo $departamento->getNombre(); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="Cuenta_Contable">Cuenta Contable:</label>
                <input type="text" name="Cuenta_Contable" id="Cuenta_Contable" required oninput="validarNumeros(this)"/>
            </div>

            <div class="form-group">
                <label for="Foto">Foto:</label>
                <input type="file" id="foto" name="Foto" accept=".jpg,.jpeg,.png,.gif">
            </div>

            <div class="form-group form-buttons">
                <button type="submit" class="btn-submit">Crear Empleado</button>
            </div>
        </form>
    </section>
</main>

<footer>
    <p>&copy; 2024 TConsulting. Todos los derechos reservados.</p>
</footer>
</body>
</html>
