<?php
require_once '../Data/TiendaODB.php';
require_once '../Data/EmpleadoODB.php';

$tiendaODB = new TiendaODB();
$empleadoODB = new EmpleadoODB();

// Obtener la lista de empleados
$empleados = $empleadoODB->getAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idEmpleado = intval($_POST['ID_Empleado']);

    // Obtener el salario base del empleado seleccionado
    $empleado = $empleadoODB->getById($idEmpleado);
    $creditoDisponible = $empleado->getSalarioBase();

    // Insertar la tienda con el crédito disponible igual al salario base
    $result = $tiendaODB->insertarTienda($cuotas = 6, $maxCredit = 200, $saldoPendiente = 0, $creditoDisponible, $idEmpleado);

    if ($result) {
        header("Location: v.tienda.php?action=created");
        exit();
    } else {
        header("Location: v.tienda.php?action=error");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Tienda</title>
    <link rel="stylesheet" href="../Styles/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<header>
    <h1>Registrar Tienda</h1>
    <nav>
        <ul>
            <li><a href="v.tienda.php">REGRESAR</a></li>
        </ul>
    </nav>
</header>
<main>
    <section class="form-section">
        <h2>Registrar Nueva Tienda</h2>
        <form id="tiendaForm" action="v.nueva.tienda.php" method="POST" class="form-crear-editar">
            <div class="form-group">
                <label for="ID_Empleado">Empleado:</label>
                <select id="idEmpleado" name="ID_Empleado" required>
                    <option value="">Seleccionar...</option>
                    <?php foreach ($empleados as $empleado) : ?>
                        <option value="<?php echo htmlspecialchars($empleado->getIdEmpleado(), ENT_QUOTES, 'UTF-8'); ?>">
                            <?php echo htmlspecialchars($empleado->getNombre() . ' ' . $empleado->getApellido(), ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group form-buttons">
                <button type="submit" class="btn-submit">Registrar Tienda</button>
            </div>
        </form>
    </section>
</main>
<footer>
    <p>© 2024 TConsulting. Todos los derechos reservados.</p>
</footer>
</body>
</html>