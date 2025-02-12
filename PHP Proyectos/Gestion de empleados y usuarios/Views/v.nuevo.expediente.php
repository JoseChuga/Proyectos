<?php
require_once '../Model/Expediente.php';
require_once '../Data/ExpedienteODB.php';
require_once '../Data/EmpleadoODB.php';

$expedienteODB = new ExpedienteODB();
$empleadoODB = new EmpleadoODB();
$empleados = $empleadoODB->getAll();

$idEmpleado = isset($_GET['ID_Empleado']) ? $_GET['ID_Empleado'] : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipoDocumento = isset($_POST['Tipo_Documento']) ? $_POST['Tipo_Documento'] : null;
    $idEmpleado = $_POST['ID_Empleado'];
    $quitarArchivo = $_POST['QuitarArchivo'] ?? false;  // Verifica si se quiere quitar el archivo

    // Si se quiere subir un archivo y no se ha marcado quitar el archivo
    $archivoRuta = null;
    if (!empty($_FILES['Archivo']['tmp_name']) && !$quitarArchivo) {
        // Establecer la carpeta donde se guardará el archivo
        $directorio = '../Expedientes/';
        $nombreArchivo = basename($_FILES['Archivo']['name']);
        $archivoRuta = $directorio . uniqid() . '_' . $nombreArchivo;

        // Mover el archivo subido al directorio 'Expedientes'
        if (move_uploaded_file($_FILES['Archivo']['tmp_name'], $archivoRuta)) {
            // El archivo fue subido exitosamente
        } else {
            echo "<script>Swal.fire('Error', 'No se pudo subir el archivo', 'error');</script>";
        }
    }

    // Crear una instancia de Expediente con la ruta del archivo
    $expediente = new Expediente($tipoDocumento, $idEmpleado, $archivoRuta, $quitarArchivo);
    $expediente->setTipoDocumento($tipoDocumento);
    $expediente->setArchivo($archivoRuta);  // Guardar la ruta en la base de datos
    $expediente->setIdEmpleado($idEmpleado);

    // Llamar al método insert
    $result = $expedienteODB->insert($expediente);
    if ($result) {
        echo "<script>Swal.fire('Éxito', 'Expediente creado correctamente', 'success');</script>";
        header("Location: v.expediente.php"); // Redirigir a otra vista
        exit();
    } else {
        echo "<script>Swal.fire('Error', 'No se pudo crear el expediente', 'error');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nuevo Expediente</title>
    <link rel="stylesheet" href="../Styles/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function validarLongitud(input, maxLength) {
            if (input.value.length > maxLength) {
                input.setCustomValidity("Este campo no puede tener más de " + maxLength + " caracteres.");
            } else {
                input.setCustomValidity(""); // Restablecer si es válido
            }
        }
    </script>
</head>
<body>
<header>
    <h1>Crear Nuevo Expediente</h1>
    <nav>
        <ul>
            <li><a href="v.Expediente.php">REGRESAR</a></li>
        </ul>
    </nav>
</header>

<main>
    <section class="form-section">
        <h2>Registrar Expediente</h2>
        <form id="expedienteForm" action="v.nuevo.expediente.php" method="POST" enctype="multipart/form-data" class="form-crear-editar">
            <div class="form-group">
                <label for="Tipo_Documento">Tipo de Documento:</label>
                <input type="text" id="tipo_documento" name="Tipo_Documento" required maxlength="30" oninput="validarLongitud(this, 30)" title="El tipo de documento no puede tener más de 30 caracteres.">
            </div>
            <input type="hidden" name="ID_Empleado" value="<?php echo htmlspecialchars($idEmpleado, ENT_QUOTES, 'UTF-8'); ?>">
            <div class="form-group">
                <label for="Archivo">Archivo (solo PDF):</label>
                <input type="file" id="archivo" name="Archivo" accept=".pdf">
            </div>
            <div class="form-group form-buttons">
                <button type="submit" class="btn-submit">Crear Expediente</button>
            </div>
        </form>
    </section>
</main>

<footer>
    <p>&copy; 2024 TConsulting. Todos los derechos reservados.</p>
</footer>

</body>
</html>

