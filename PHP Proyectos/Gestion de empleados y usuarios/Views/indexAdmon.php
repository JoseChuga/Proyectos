<?php
session_start();

// Asegúrate de que el rol se ha almacenado en la sesión al iniciar sesión
$rolUsuario = $_SESSION['rol'] ?? null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Inicio - T Consulting</title>
    <link rel="stylesheet" href="../Styles/home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
<header>
    <h1>Bienvenido a T Consulting S.A</h1>
</header>
<nav>
    <ul>
        <?php
        switch ((int)$rolUsuario) {
            case 1:
            case 6: // RRHH
                ?>
                <li>
                    <a href="#">RRHH</a>
                    <ul>
                        <li><a href="v.usuarios.php">Usuarios</a></li>
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
                <?php
                break;

            case 5: // Usuarios RRHH
                ?>
                <li><a href="v.usuariosRRHH.php">Usuarios</a></li>
                <li><a href="v.empleadosRRHH.php">Empleados</a></li>
                <li><a href="v.ExpedienteRRHH.php">Expedientes</a></li>
                <li><a href="v.ausenciasRRHH.php">Permisos</a></li>
                <?php
                break;

            case 4: // Nómina y Contabilidad
                ?>
                <li><a href="v.nomina.php">Pagos de Nómina</a></li>
                <li><a href="v.Poliza.php">Polizas Contables</a></li>
                <li><a href="v.horasextras.php">Horas Extras</a></li>
                <li><a href="v.comisiones.php">Comisiones sobre ventas</a></li>
                <li><a href="v.produccion.php">Bonificaciones por producción</a></li>
                <?php
                break;

            case 7: // BANTRAB
                ?>
                <li><a href="v.prestamoP.php">Prestamos</a></li>
                <li><a href="v.nuevo.prestamo.php">Nuevo Prestamo</a></li>
                <?php
                break;

            case 8: // Tienda
                ?>
                <li><a href="v.tiendaT.php">Registros de Tienda</a></li>
                <li><a href="v.nueva.tiendaT.php">Nueva Cuenta de Tienda</a></li>
                <?php
                break;

            default:
                // Mensaje para roles no coincidentes
                echo "<li><a href='#'>Acceso Restringido</a></li>";
                break;
        }
        ?>
    </ul>
</nav>
<main>
    <h2>Políticas de la Empresa</h2>
    <p>
        En esta sección se detallan las políticas y normativas de T Consulting. Nuestro objetivo es
        garantizar que cada empleado y área de la empresa cuente con un entorno estructurado y alineado a las mejores
        prácticas. Aquí encontrarás información sobre procedimientos, códigos de conducta, y mucho más.
    </p>
</main>

<footer>
    <p>&copy; 2024 T Consulting S.A. Todos los derechos reservados.</p>
</footer>

</body>
</html>

