<?php

require_once '../Model/Empleado.php';
require_once '../Data/EmpleadoODB.php';

$empleadoODB = new EmpleadoODB();

// Verificar si se ha enviado un ID_Empleado para eliminar
if (isset($_GET['ID_Empleado'])) {
    $idEmpleado = $_GET['ID_Empleado'];

    // Llamar al método para eliminar el empleado en el objeto de acceso a datos
    $empleadoODB->delete($idEmpleado);

    // Redirigir con un parámetro de éxito
    header('Location: ' . $_SERVER['PHP_SELF'] . '?action=deleted');
    exit();
}

// Obtener todos los empleados para mostrar en la tabla
$empleados = $empleadoODB->getAll();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Lista de Empleados</title>
    <link rel="stylesheet" href="../Styles/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.1/dist/sweetalert2.min.css" rel="stylesheet">
</head>

<body>
<header>
    <h1>Gestión de Empleados</h1>
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
    <section class="Empleados">
        <h2>Empleados Registrados</h2>
        <div class="table-container">
            <table>
                <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th class="text-wrap">Fecha de Nacimiento</th>
                    <th class="text-wrap">Fecha de Contratación</th>
                    <th>Salario Base</th>
                    <th>Departamento</th>
                    <th>No. Cuenta</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($empleados as $empleado) : ?>
                    <tr>
                        <td class="nombre"><?php echo htmlspecialchars($empleado->getNombre()); ?></td>
                        <td class="apellido"><?php echo htmlspecialchars($empleado->getApellido()); ?></td>
                        <td><?php echo htmlspecialchars($empleado->getFechaNacimiento()); ?></td>
                        <td><?php echo htmlspecialchars($empleado->getFechaContratacion()); ?></td>
                        <td><?php echo htmlspecialchars($empleado->getSalarioBase()); ?></td>
                        <td><?php echo htmlspecialchars($empleado->getDepartamento()->getNombre()); ?></td>
                        <td><?php echo htmlspecialchars($empleado->getCuentaContable()); ?></td>

                        <td>
                            <a href="v.editar.empleado.php?ID_Empleado=<?php echo $empleado->getIdEmpleado(); ?>" class="btn btn-editar">Editar</a>
                            <button class="btn btn-eliminar" data-id="<?php echo $empleado->getIdEmpleado(); ?>">Eliminar</button>
                            <button class="employee-button" onclick="showEmployeePhoto('<?php echo $empleado->getFoto(); ?>')">Ver Foto</button>
                            <a href="v.familiar.php?ID_Empleado=<?php echo $empleado->getIdEmpleado(); ?>" class="btn btn-editar">Familiares</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <button class="btn-nuevo">Agregar Nuevo Empleado +</button>
        <div id="photoModal" class="photo-modal" style="display: none;">
            <span class="close" onclick="hideEmployeePhoto()">×</span>
            <div class="modal-content">
                <img id="employeePhoto" src="" alt="Foto del Empleado">
            </div>
        </div>
    </section>
</main>

<footer>
    <p>© 2024 TConsulting. Todos los derechos reservados.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const deleteButtons = document.querySelectorAll('.btn-eliminar');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const empleadoId = this.getAttribute('data-id');

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
                    window.location.href = `?ID_Empleado=${empleadoId}`;
                }
            });
        });
    });

    const nuevoEmpleadoButton = document.querySelector('.btn-nuevo');

    if (nuevoEmpleadoButton) {
        nuevoEmpleadoButton.addEventListener('click', function() {
            window.location.href = 'v.nuevo.empleado.php';
        });
    }

    function showEmployeePhoto(photoUrl) {
        const modal = document.getElementById("photoModal");
        const img = document.getElementById("employeePhoto");

        if (photoUrl && photoUrl !== '../Imagenes/Sin Foto.jpeg') {
            // Si la foto existe y no es la predeterminada, se asigna la ruta pasada
            img.src = photoUrl;
        } else {
            // Si no existe una foto, se muestra la imagen predeterminada
            img.src = '../Imagenes/Sin Foto.jpeg';
        }

        img.onload = function() {
            modal.style.display = "flex"; // Mostrar modal después de que cargue la imagen
        }

        // Redimensionar la imagen al hacer clic sobre ella
        img.addEventListener('click', function() {
            if (img.classList.contains('large')) {
                img.classList.remove('large'); // Vuelve al tamaño pequeño
            } else {
                img.classList.add('large'); // Agranda la imagen
            }
        });
    }

    function hideEmployeePhoto() {
        const modal = document.getElementById("photoModal");
        modal.style.display = "none";
        const img = document.getElementById("employeePhoto");
        img.src = ""; // Limpia la fuente de la imagen

        // Asegúrate de restablecer el tamaño de la imagen cuando se cierre el modal
        img.classList.remove('large');
    }

    function cerrarSesion() {
        // Redirige al usuario a la página index.php
        window.location.href = '../index.html';
    }

</script>

</body>
</html>

