<?php
require_once '../Data/FamiliarODB.php';
require_once '../Data/EmpleadoODB.php';


$familiarODB = new FamiliarODB();
$empleadoODB = new EmpleadoODB();

$idFamiliar = $_GET['ID_Familiar'] ?? null;

if ($idFamiliar) {
    $familiar = $familiarODB->getById($idFamiliar);
    $empleados = $empleadoODB->getAll();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idFamiliar = $_POST['ID_Familiar'];
    $nombre = $_POST['Nombre'];
    $apellido = $_POST['Apellido'];
    $relacion = $_POST['Relacion'];
    $fechaNacimiento = $_POST['Fecha_Nacimiento'];
    $idEmpleado = $_POST['ID_Empleado'];

    $result = $familiarODB->update($idFamiliar, $nombre, $apellido, $relacion, $fechaNacimiento, $idEmpleado);
    if ($result) {
        echo "<script>Swal.fire('Éxito', 'Familiar actualizado correctamente', 'success');</script>";
        header("Location: v.familiar.php?ID_Empleado=$idEmpleado"); // Redirigir a otra vista
        exit();
    } else {
        echo "<script>Swal.fire('Error', 'No se pudo actualizar el familiar', 'error');</script>";
    }

}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Familiar</title>
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
        fechaMaxima.setFullYear(fechaActual.getFullYear() - 18);

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
    <h1>Editar Familiar</h1>
    <nav>
        <ul>
            <li><a id="nuevoEmpleadoButton" data-id-empleado="<?php echo $familiar->getIdEmpleado(); ?>">REGRESAR</a></li>
        </ul>
    </nav>
</header>

<main>
    <section class="form-section">
        <h2>Modificar Familiar</h2>
        <form id="familiarForm" action="v.editar.familiar.php?ID_Familiar=<?php echo htmlspecialchars($idFamiliar); ?>" method="POST" class="form-crear-editar">
            <input type="hidden" name="ID_Familiar" value="<?php echo htmlspecialchars($idFamiliar); ?>">

            <div class="form-group">
                <label for="Nombre">Nombre:</label>
                <input type="text" id="Nombre" name="Nombre" value="<?php echo htmlspecialchars($familiar->getNombre()); ?>" required maxlength="50" oninput="validarLongitud(this, 50)" title="El nombre no puede tener más de 50 caracteres.">
            </div>

            <div class="form-group">
                <label for="Apellido">Apellido:</label>
                <input type="text" id="Apellido" name="Apellido" value="<?php echo htmlspecialchars($familiar->getApellido()); ?>" required maxlength="50" oninput="validarLongitud(this, 50)" title="El apellido no puede tener más de 50 caracteres.">
            </div>
            <div class="form-group">
                <label for="Relacion">Relacion:</label>
                <input type="text" id="relacion" name="Relacion" value="<?php echo htmlspecialchars($familiar->getRelacion()); ?>" required>
            </div>
            <div class="form-group">
                <label for="Fecha_Nacimiento">Fecha de Nacimiento:</label>
                <input type="date" id="fechaNacimiento" name="Fecha_Nacimiento" value="<?php echo htmlspecialchars($familiar->getFechaNacimiento()); ?>" required title="Debe ser mayor de 18 años y menor de 100." oninput="validarFechaNacimiento()">
            </div>
            <div class="form-group">
                <label for="ID_Empleado">Empleado:</label>
                <select id="id_empleado" name="ID_Empleado" required>
                    <option value="">Seleccionar...</option>
                    <?php foreach ($empleados as $empleado) : ?>
                        <option value="<?php echo htmlspecialchars($empleado->getIdEmpleado()); ?>" <?php echo $familiar->getIdEmpleado() === $empleado->getIdEmpleado() ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($empleado->getNombre() . ' ' . $empleado->getApellido()); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group form-buttons">
                <button id="actualizarFamiliarButton" data-id-empleado="<?php echo $familiar->getIdEmpleado(); ?>" type="submit" class="btn-submit">Actualizar Familiar</button>
            </div>
        </form>

    </section>
</main>

<footer>
    <p>© 2024 TConsulting. Todos los derechos reservados.</p>
</footer>
</body>
</html>
