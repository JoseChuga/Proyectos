<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Ausencia</title>
    <link rel="stylesheet" href="../Styles/Styles_Ausencia_Autorizacion.css">
    <script>
        function toggleCuentaSalario() {
            const estado = document.getElementById('Estado').value;
            const cuentaSalarioDiv = document.getElementById('cuentaSalarioDiv');
            const descuentoDiv = document.getElementById('descuentoDiv');

            if (estado === 'Aceptada') {
                cuentaSalarioDiv.style.display = 'block';
            } else {
                cuentaSalarioDiv.style.display = 'none';
                descuentoDiv.style.display = 'none';
            }
        }

        function toggleDescuento() {
            const cuentaSalario = document.getElementById('Cuenta_Salario').value;
            const descuentoDiv = document.getElementById('descuentoDiv');

            if (cuentaSalario === '1') {
                descuentoDiv.style.display = 'block';
            } else {
                descuentoDiv.style.display = 'none';
            }
        }

        window.onload = function() {
            toggleCuentaSalario(); // Asegurarse que el estado actual del combo box sea el correcto al cargar
            toggleDescuento(); // Asegurarse que el campo de descuento aparezca si es necesario
        };
    </script>
</head>
<body>
<header>
    <h1>Modificar Ausencia</h1>
</header>

<main>
    <?php if (isset($ausencia)): ?>
        <form action="/Nomina_Sistemas/Controller/C_AusenciaAutorizacion.php" method="POST">
        <input type="hidden" name="ID_Solicitud" value="<?php echo $ausencia['ID_Solicitud']; ?>">

            <!-- Campos adicionales -->
            <div>
                <label for="FechaSolicitud">Fecha de Solicitud:</label>
                <input type="date" id="FechaSolicitud" name="FechaSolicitud" value="<?php echo $ausencia['FechaSolicitud']; ?>" required>
            </div>

            <div>
                <label for="Fecha_Inicio">Fecha de Inicio:</label>
                <input type="date" id="Fecha_Inicio" name="Fecha_Inicio" value="<?php echo $ausencia['Fecha_Inicio']; ?>" required>
            </div>

            <div>
                <label for="Fecha_Fin">Fecha de Fin:</label>
                <input type="date" id="Fecha_Fin" name="Fecha_Fin" value="<?php echo $ausencia['Fecha_Fin']; ?>" required>
            </div>

            <div>
                <label for="Motivo">Motivo:</label>
                <input type="text" id="Motivo" name="Motivo" value="<?php echo $ausencia['Motivo']; ?>" required>
            </div>

            <div>
                <label for="Descripcion">Descripción:</label>
                <textarea id="Descripcion" name="Descripcion" required><?php echo $ausencia['Descripcion']; ?></textarea>
            </div>

            <!-- Estado, Cuenta_Salario y Descuento -->
            <div>
                <label for="Estado">Estado:</label>
                <select id="Estado" name="Estado" onchange="toggleCuentaSalario()">
                    <option value="Pendiente" <?php echo ($ausencia['Estado'] == 'Pendiente') ? 'selected' : ''; ?>>Pendiente</option>
                    <option value="Aceptada" <?php echo ($ausencia['Estado'] == 'Aceptada') ? 'selected' : ''; ?>>Aceptada</option>
                    <option value="Denegada" <?php echo ($ausencia['Estado'] == 'Denegada') ? 'selected' : ''; ?>>Denegada</option>
                </select>
            </div>

            <div id="cuentaSalarioDiv" style="display: none;">
                <label for="Cuenta_Salario">¿A cuenta de salario?</label>
                <select id="Cuenta_Salario" name="Cuenta_Salario" onchange="toggleDescuento()">
                    <option value="0" <?php echo ($ausencia['Cuenta_Salario'] == 0) ? 'selected' : ''; ?>>No</option>
                    <option value="1" <?php echo ($ausencia['Cuenta_Salario'] == 1) ? 'selected' : ''; ?>>Sí</option>
                </select>
            </div>

            <div id="descuentoDiv" style="display: none;">
                <label for="Descuento">Descuento:</label>
                <input type="number" step="0.01" id="Descuento" name="Descuento" value="<?php echo $ausencia['Descuento']; ?>">
            </div>

            <input type="hidden" name="ID_Empleado" value="<?php echo $ausencia['ID_Empleado']; ?>">

            <button type="submit" name="modificar">Modificar Ausencia</button>
        </form>
    <?php else: ?>
        <p>No se encontró la ausencia.</p>
    <?php endif; ?>
</main>

<footer>
    <p>&copy; 2024 Nomina Sistemas</p>
</footer>
</body>
</html>
