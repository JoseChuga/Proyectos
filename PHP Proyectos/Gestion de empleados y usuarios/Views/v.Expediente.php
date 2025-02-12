<?php
session_start();  // Iniciar la sesión para manejar los datos del usuario

require_once '../Model/Expediente.php';
require_once '../Data/ExpedienteODB.php';
require_once '../Model/Empleado.php';

$expedienteODB = new ExpedienteODB();

// Verifica si ya se ha recibido el nombre del empleado desde una solicitud GET o una sesión
if (isset($_GET['nombreCompleto'])) {
    // Si se proporciona un nombre a través de GET, lo guardamos en la sesión
    $_SESSION['nombreCompleto'] = $_GET['nombreCompleto'];
}

$nombreCompleto = isset($_SESSION['nombreCompleto']) ? $_SESSION['nombreCompleto'] : null;
$idEmpleado = isset($_SESSION['ID_Empleado']) ? $_SESSION['ID_Empleado'] : null;

if ($nombreCompleto) {
    // Buscar expedientes por el nombre almacenado en la sesión
    $expedientes = $expedienteODB->buscarPorNombre($nombreCompleto);
} else {
    // Si no hay nombre aún, no buscamos ningún expediente
    $expedientes = [];
}

if (count($expedientes) > 0) {
    $expediente = $expedientes[0]; // Obtener el primer expediente, si existe
} else {
    $expediente = null; // No se encontraron expedientes
}

if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['ID_Expediente'])) {
    $idExpediente = $_GET['ID_Expediente'];
    $expedienteODB->delete($idExpediente); // Llama a tu método de eliminación
    header("Location: v.Expediente.php?nombreCompleto=" . urlencode($nombreCompleto)); // Redirige a la lista de expedientes
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Expedientes</title>
    <link rel="stylesheet" href="../Styles/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.1/dist/sweetalert2.min.css" rel="stylesheet">
</head>
<body>
<header>
    <h1>Gestión de Expedientes</h1>
    <button onclick="cerrarSesion()" class="btn btn-eliminar">Cerrar Sesión</button>
</header>
<nav>
    <ul>
        <li>
            <a href="#">RRHH</a>
            <ul>
                <li><a href="v.empleados.php">Empleados</a></li>
                <li><a href="v.Expediente.php">Expedientes</a></li>
                <li><a href="v.ausencias.php">Permisos</a></li>
            </ul>
        </li>
        <li>
            <a href="#">Nómina</a>
            <ul>
                <li><a href="v.nomina.php">Pagos</a></li>
            </ul>
        </li>
        <li>
            <a href="#">Contabilidad</a>
            <ul>
                <li><a href="v.Poliza.php">Polizas Contables</a></li>
                <li><a href="v.horasextras.php">Horas Extras</a></li>
                <li><a href="v.comisiones.php">Comisiones sobre ventas</a></li>
                <li><a href="v.produccion.php">Bonificaciones por producción</a></li>
            </ul>
        </li>
        <li>
            <a href="#">BANTRAB</a>
            <ul>
                <li><a href="v.prestamo.php">Prestamos</a></li>
            </ul>
        </li>
        <li>
            <a href="#">Tienda</a>
            <ul>
                <li><a href="v.tienda.php">Registro de Tienda</a></li>
            </ul>
        </li>
    </ul>
</nav>
<main>
    <div class="header-right">
        <button class="btn-buscar" onclick="buscarEmpleado()">Buscar</button>
    </div>
    <section class="Expedientes">
        <!-- Mostrar expedientes si se ha proporcionado el nombre del empleado -->
        <?php if ($nombreCompleto && count($expedientes) > 0): ?>
                <h2>Expedientes Registrados para: <?php echo htmlspecialchars($nombreCompleto); ?></h2>
                <h3>Visualización de Documentos</h3>
                <ul id="listaArchivos">
                    <?php foreach ($expedientes as $expediente) : ?>
                        <?php if ($expediente->getArchivo()) : ?>
                            <li>
                                <h4><?php echo htmlspecialchars($expediente->getTipoDocumento()); ?></h4>
                                <iframe src="<?php echo $expediente->getArchivo(); ?>" width="100%" height="600px"></iframe>

                                <!-- Botones Editar y Eliminar debajo del iframe -->
                                <div class="acciones-expediente">
                                    <a href="v.editar.expediente.php?ID_Expediente=<?php echo $expediente->getIdExpediente(); ?>" class="btn btn-editar">Editar</a>
                                    <button class="btn btn-eliminar" data-id="<?php echo $expediente->getIdExpediente(); ?>">Eliminar</button>
                                </div>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            <?php elseif ($nombreCompleto): ?>
                <p>No se encontraron expedientes para el empleado <?php echo htmlspecialchars($nombreCompleto); ?>.</p>
            <?php endif; ?>
        </section>
</main>
<?php if ($expediente): ?>
    <a href="v.nuevo.expediente.php?ID_Empleado=<?php echo $expediente->getIdEmpleado(); ?>" class="btn btn-nuevo">Agregar Nuevo Archivo</a>
<?php else: ?>
    <p>No se encontró el expediente</p>
<?php endif; ?>
<footer>
    <p>© 2024 TConsulting. Todos los derechos reservados.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        <?php if (!$nombreCompleto): ?>
        Swal.fire({
            title: 'Buscar Expedientes',
            text: 'Por favor, ingresa el nombre del empleado:',
            input: 'text',
            inputPlaceholder: 'Nombre Completo',
            showCancelButton: true,
            confirmButtonText: 'Buscar',
            cancelButtonText: 'Cancelar',
            preConfirm: (nombreCompleto) => {
                if (!nombreCompleto) {
                    Swal.showValidationMessage('Debes ingresar el nombre del empleado');
                }
                return nombreCompleto;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirige a la misma página con el nombre del empleado como parámetro GET
                window.location.href = `v.Expediente.php?nombreCompleto=${encodeURIComponent(result.value)}`;
            }
        });
        <?php endif; ?>
    });

    const deleteButtons = document.querySelectorAll('.btn-eliminar');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const expedienteId = this.getAttribute('data-id');

            Swal.fire({
                text: "Seguro que quieres eliminar el registro, no podrás revertir esta acción.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `?ID_Expediente=${expedienteId}&action=delete`;
                }
            });
        });
    });

    function buscarEmpleado() {
        Swal.fire({
            title: 'Buscar Expedientes',
            text: 'Por favor, ingresa el nombre del empleado:',
            input: 'text',
            inputPlaceholder: 'Nombre Completo',
            showCancelButton: true,
            confirmButtonText: 'Buscar',
            cancelButtonText: 'Cancelar',
            preConfirm: (nombreCompleto) => {
                if (!nombreCompleto) {
                    Swal.showValidationMessage('Debes ingresar el nombre del empleado');
                }
                return nombreCompleto;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirige a la misma página con el nombre del empleado como parámetro GET
                window.location.href = `v.Expediente.php?nombreCompleto=${encodeURIComponent(result.value)}`;
            }
        });
    }

    function cerrarSesion() {
        // Redirige al usuario a la página index.php
        window.location.href = '../index.html';
    }

</script>
</body>
</html>
