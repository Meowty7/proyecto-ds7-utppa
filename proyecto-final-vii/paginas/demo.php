<?php
require_once '../config/ConexionBD.php'; // Cambia a la ruta correcta de tu archivo de conexión

// Obtén la conexión a la base de datos
$conexion = ConexionBD::obtenerConexion();

// Verifica si la conexión se obtuvo correctamente
if ($conexion) {
    // Consulta para obtener todos los registros de la tabla 'inventario'
    $consulta = "SELECT * FROM inventario";
    $resultado = $conexion->query($consulta);

    if ($resultado->num_rows > 0) {
        // Recorre los resultados y los muestra
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Marcas</th><th>Partes de Autos</th><th>Modelo</th><th>Año</th></tr>";
        while ($fila = $resultado->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $fila['idInventario'] . "</td>";
            echo "<td>" . $fila['Marcas'] . "</td>";
            echo "<td>" . $fila['Partes_de_Autos'] . "</td>";
            echo "<td>" . $fila['Modelo'] . "</td>";
            echo "<td>" . $fila['Año'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No se encontraron registros en la tabla 'inventario'.";
    }

    // Libera el resultado
    $resultado->free();
} else {
    echo "Error al conectar con la base de datos.";
}

// Cierra la conexión a la base de datos (opcional)
$conexion->close();