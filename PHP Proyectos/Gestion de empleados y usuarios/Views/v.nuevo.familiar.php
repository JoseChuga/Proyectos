<?php
require_once '../Data/FamiliarODB.php';
require_once '../Data/EmpleadoODB.php';

$familiarODB = new FamiliarODB();
$empleadoODB = new EmpleadoODB();

$idEmpleado = $_GET['ID_Empleado'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['Nombre']);
    $apellido = trim($_POST['Apellido']);
    $relacion = trim($_POST['Relacion']);
    $fechaNacimiento = trim($_POST['Fecha_Nacimiento']);
    $idEmpleado = trim($_POST['ID_Empleado']);

    // Perform server-side validation
    if (empty($nombre) || empty($apellido) || empty($relacion) || empty($fechaNacimiento) || empty($idEmpleado)) {
        echo "<script>
            Swal.fire({
                title: 'Error',
                text: 'Todos los campos son obligatorios.',
                icon: 'error'
            });
        </script>";
    } else {
        // Create a Familiar object
        $familiar = new Familiar($idfamiliar = null, $nombre, $apellido, $relacion, $fechaNacimiento, $idEmpleado);

        // Llamada al método de inserción
        $result = $familiarODB->insert($familiar);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Familiar</title>
    <link rel="stylesheet" href="../Styles/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Validar la longitud de caracteres en Nombre y Apellido
        function validarLongitud(input, maxLength) {
            if (input.value.length > maxLength) {
                input.setCustomValidity("Este campo no puede tener más de " + maxLength + " caracteres.");
            } else {
                input.setCustomValidity(""); // Restablecer si es válido
            }
        }

        // Validar fecha de nacimiento
        function validarFechaNacimiento() {
            var fechaNacimiento = new Date(document.getElementById('fechaNacimiento').value);
            var fechaActual = new Date();

            var fechaMinima = new Date();
            fechaMinima.setFullYear(fechaActual.getFullYear() - 100);

            var fechaMaxima = new Date();
            fechaMaxima.setFullYear(fechaActual.getFullYear() - 1);

            if (fechaNacimiento < fechaMinima || fechaNacimiento > fechaMaxima) {
                document.getElementById('fechaNacimiento').setCustomValidity("Debe ser mayor de 18 años y menor de 100.");
                return false;
            } else {
                document.getElementById('fechaNacimiento').setCustomValidity(""); // Restablecer si es válido
                return true;
            }
        }

        // Validación del formulario al enviar
        function validarFormulario() {
            return validarFechaNacimiento();
        }

        // Redirigir al crear nuevo familiar
        const nuevoEmpleadoButton = document.querySelector('.btn-submit');

        document.addEventListener('DOMContentLoaded', function() {
            const nuevoEmpleadoButton = document.getElementById('nuevoEmpleadoButton');
            if (nuevoEmpleadoButton) {
                nuevoEmpleadoButton.addEventListener('click', function(event) {
                    const idEmpleado = this.getAttribute('data-id-empleado');
                    const form = document.getElementById('familiarForm');
                    form.action = `v.familiar.php?ID_Empleado=${idEmpleado}`; // Setear la acción del formulario
                    form.submit(); // Enviar el formulario
                });
            }
        });

    </script>
</head>
<body>
<header>
    <h1>Crear Familiar</h1>
    <nav>
        <ul>
            <li><a id="nuevoEmpleadoButton" data-id-empleado="<?php echo htmlspecialchars($idEmpleado); ?>">REGRESAR</a></li>
        </ul>
    </nav>
</header>
<main>
    <section class="form-section">
        <h2>Registrar Nuevo Familiar</h2>
        <form id="familiarForm" action="v.nuevo.familiar.php" method="POST" class="form-crear-editar" onsubmit="return validarFormulario();">
            <div class="form-group">
                <label for="Nombre">Nombre:</label>
                <input type="text" id="nombre" name="Nombre" required maxlength="50"
                       oninput="validarLongitud(this, 50)" title="El nombre no puede tener más de 50 caracteres.">
            </div>

            <div class="form-group">
                <label for="Apellido">Apellido:</label>
                <input type="text" id="apellido" name="Apellido" required maxlength="50"
                       oninput="validarLongitud(this, 50)" title="El apellido no puede tener más de 50 caracteres.">
            </div>

            <div class="form-group">
                <label for="Relacion">Relación:</label>
                <input type="text" id="relacion" name="Relacion" required>
            </div>

            <div class="form-group">
                <label for="Fecha_Nacimiento">Fecha de Nacimiento:</label>
                <input type="date" id="fechaNacimiento" name="Fecha_Nacimiento" required
                       title="Debe ser mayor de 18 años y menor de 100." oninput="validarFechaNacimiento()">
            </div>

            <input type="hidden" name="ID_Empleado" value="<?php echo $idEmpleado; ?>">

            <div class="form-group form-buttons">
                <button id="nuevoEmpleadoButton" data-id-empleado="<?php echo htmlspecialchars($idEmpleado); ?>" type="submit" class="btn-submit">Crear Familiar</button>
            </div>
        </form>
    </section>
</main>
<footer>
    <p>© 2024 TConsulting. Todos los derechos reservados.</p>
</footer>
</body>
</html>


