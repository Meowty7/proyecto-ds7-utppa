<?php
session_start(); // Iniciar la sesión al principio
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Registro de Partes de Autos</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="form-container">
    <button onclick="window.location.href='PaginaInicio.php'"style="position: absolute; top: 10px; left: 110px; background-color: #04858c; color: white; border: none; padding: 10px 20px; text-align: center; text-decoration: none; font-size: 16px; cursor: pointer;">Pagina Inicio</button>
   
    <?php
    // Mostrar el mensaje de sesión si existe
    if (isset($_SESSION['mensaje'])) {
        echo "<p>" . $_SESSION['mensaje'] . "</p>";
        unset($_SESSION['mensaje']); // Limpiar el mensaje después de mostrarlo
    }
    ?>
      
        <h2>Registro de Partes de Autos</h2>
        <form id="miFormulario" method="post" action="RegistroInventario.php">
            
        <label for="Marcas">Marcas del Auto:</label>
            <select name="Marcas">
            <option value="TOYOTA">TOYOTA</option>
            <option value="NISSAN" selected>NISSAN</option>
            <option value="CHEVROLET">CHEVROLET</option>
            <option value="LEXUS">LEXUS</option>
            <option value="FORD" selected>FORD</option>
            <option value="HONDA">HONDA</option>
            <option value="BMW" selected>BMW</option>
            <option value="MAZDA">MAZDA</option>

            </select><br><br>
            <label for="Partes_de_Autos">Partes de Autos:</label>
            <select name="Partes_de_Autos">
            <option value="Motor"selected>Motor</option>
            <option value="Transmision" selected>Transmision</option>
            <option value="Suspension">Suspension</option>
            <option value="Carroceria">Carroceria</option>
            <option value="Radiador" selected>Radiador</option>
            <option value="Bateria">Bateria</option>
            <option value="CajaDeCambios" selected>CajaDeCambios</option>
            <option value="Ruedas">Ruedas</option>
            </select><br><br>

            <label for="Modelo">Modelo:</label>
           <input type="text" id="Modelo" name="Modelo" placeholder="Modelo..." required>

           <label for="Costo">Costo:</label>
           <input type="number" id="Costo" name="Costo" placeholder="Costo..." required>

            <label for="Año">Año:</label>
            <input type="number" id="Año" name="Año" placeholder="Ej. 2020" required min="1900" max="2024">

            <button type="submit">Registrar Parte</button>
        </form>
    </div>

    <script>
        // Añadir acción al formulario (puedes personalizarla para que guarde los datos)
        document.getElementById('auto-part-form').addEventListener('submit', function(event) {
            event.preventDefault();
            alert('Parte registrada correctamente.');
        });
    </script>
</body>
</html>
