<?php
require_once '../Data/ExpedienteODB.php';

$expedienteODB = new ExpedienteODB();

$idExpediente = $_GET['ID_Expediente'] ?? null;

if ($idExpediente) {
    $expediente = $expedienteODB->getById($idExpediente);
    $archivoActual = $expediente->getArchivo(); // Obtiene el archivo actual
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idExpediente = $_POST['ID_Expediente'];
    $tipoDocumento = $_POST['Tipo_Documento'];
    $idEmpleado = $_POST['ID_Empleado'];
    $quitarArchivo = $_POST['Quitar_Archivo'] ?? false;  // Verifica si se quiere quitar el archivo

    // Manejo del archivo
    $archivoActual = $expediente->getArchivo(); // Obtener la ruta del archivo actual
    $archivo = $archivoActual; // Inicializar con el archivo actual

    // Verifica si hay un nuevo archivo para subir
    if (!empty($_FILES['Archivo']['tmp_name'])) {
        $nombreArchivo = $_FILES['Archivo']['name'];
        $temporal = $_FILES['Archivo']['tmp_name'];
        $directorio = '../Expedientes/';
        $archivoRuta = $directorio . uniqid() . '_' . basename($nombreArchivo);

        // Intentar mover el archivo subido
        if (move_uploaded_file($temporal, $archivoRuta)) {
            $archivo = $archivoRuta; // Nueva ruta del archivo
        } else {
            $archivo = $archivoActual; // Mantener el archivo actual si falla el upload
        }
    } elseif ($quitarArchivo) {
        $archivo = null; // Si se quita el archivo, establecer a null
    }

    // Actualizar expediente
    $result = $expedienteODB->update($idExpediente, $tipoDocumento, $archivo, $idEmpleado);
    if ($result) {
        header("Location: v.Expediente.php?action=updated");
        exit();
    } else {
        header("Location: v.Expediente.php?action=error");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Expediente</title>
    <link rel="stylesheet" href="../Styles/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<header>
    <h1>Modificar Expediente</h1>
    <nav>
        <ul>
            <li><a href="v.Expediente.php">REGRESAR</a></li>
        </ul>
    </nav>
</header>

<main>
    <section class="form-section">
        <h2>Modificar Expediente</h2>
        <form action="v.editar.expediente.php?ID_Expediente=<?php echo htmlspecialchars($idExpediente); ?>" method="POST" enctype="multipart/form-data" class="form-crear-editar">

            <input type="hidden" name="ID_Expediente" value="<?php echo htmlspecialchars($expediente->getIdExpediente()); ?>">
            <input type="hidden" name="ID_Empleado" value="<?php echo htmlspecialchars($expediente->getIdEmpleado()); ?>">

            <div class="form-group">
                <label for="Tipo_Documento">Tipo de Documento:</label>
                <input type="text" id="tipo_documento" name="Tipo_Documento" value="<?php echo htmlspecialchars($expediente->getTipoDocumento()); ?>" required maxlength="50" title="El tipo de documento no puede tener más de 50 caracteres.">
            </div>

            <div class="form-group">
                <label for="Archivo">Archivo:</label>
                <input type="file" id="archivo" name="Archivo" accept=".pdf">
                <!-- Mostrar archivo existente -->
                <?php if ($archivoActual) : ?>
                    <a href="../Expedientes/<?php echo htmlspecialchars($archivoActual); ?>" target="_blank">Ver Archivo Actual</a>
                <?php endif; ?>
            </div>

            <div class="form-group form-buttons">
                <button type="submit" class="btn-submit">Actualizar Expediente</button>
            </div>
        </form>
    </section>
</main>

<footer>
    <p>© 2024 TConsulting. Todos los derechos reservados.</p>
</footer>
</body>
</html>
