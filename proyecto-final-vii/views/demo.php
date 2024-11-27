<?php
session_start();

require_once '../models/Inventario.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        // Obtener los datos del formulario
        $nombreParte = $_POST['nombreParte'];
        $marca = $_POST['marca'];
        $modelo = $_POST['modelo'];
        $fecha = (int)$_POST['fecha']; // Convertir el año a entero
        $cantidad = (int)$_POST['cantidad'];
        $costo = (float)$_POST['costo'];
        $idSeccion = (int)$_POST['idSeccion'];

        // Validar el año
        if ($fecha < 1900 || $fecha > 2100) {
            $_SESSION['mensaje'] = "El año ingresado no es válido.";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }

        // Manejar la imagen subida
        $archivo = $_FILES['imagen'];
        $nombreArchivo = basename($archivo['name']);
        $rutaTemporal = $archivo['tmp_name'];

        $directorioDestino = '../images/';
        $rutaDestino = $directorioDestino . $nombreArchivo;

        if (!is_dir($directorioDestino)) {
            mkdir($directorioDestino, 0777, true);
        }

        if (move_uploaded_file($rutaTemporal, $rutaDestino)) {
            $inventario = new Inventario($nombreParte, $marca, $modelo, $fecha, $cantidad, $costo, $idSeccion, $rutaDestino);

            $accionesInventario = new InventarioAcciones();

            try {
                if ($accionesInventario->crear($inventario)) {
                    $_SESSION['mensaje'] = "Inventario creado exitosamente.";
                } else {
                    $_SESSION['mensaje'] = "Error al guardar en la base de datos.";
                }
            } catch (Exception $e) {
                $_SESSION['mensaje'] = "Error: " . $e->getMessage();
            }
        } else {
            $_SESSION['mensaje'] = "Error al mover la imagen al servidor.";
        }
    } else {
        $_SESSION['mensaje'] = "Error al subir la imagen.";
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Imagen e Inventario</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="bg-white shadow-md rounded-lg p-8 w-full max-w-lg">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Agregar Inventario</h2>

    <!-- Mostrar mensaje de éxito o error -->
    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="p-4 mb-4 text-sm text-white bg-green-500 rounded-lg">
            <?= htmlspecialchars($_SESSION['mensaje']); unset($_SESSION['mensaje']); ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
        <div>
            <label for="nombreParte" class="block text-sm font-medium text-gray-700">Nombre de la Parte:</label>
            <input type="text" name="nombreParte" id="nombreParte" required
                   class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
            <label for="marca" class="block text-sm font-medium text-gray-700">Marca:</label>
            <input type="text" name="marca" id="marca" required
                   class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
            <label for="modelo" class="block text-sm font-medium text-gray-700">Modelo:</label>
            <input type="text" name="modelo" id="modelo" required
                   class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
            <label for="cantidad" class="block text-sm font-medium text-gray-700">Cantidad:</label>
            <input type="number" name="cantidad" id="cantidad" required
                   class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
            <label for="costo" class="block text-sm font-medium text-gray-700">Costo:</label>
            <input type="number" step="0.01" name="costo" id="costo" required
                   class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
            <label for="fecha" class="block text-sm font-medium text-gray-700">Año:</label>
            <input type="number" name="fecha" id="fecha" required placeholder="Ingrese el año (ej: 2024)"
                   min="1900" max="2100"
                   class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
            <label for="idSeccion" class="block text-sm font-medium text-gray-700">Sección:</label>
            <select name="idSeccion" id="idSeccion" required
                    class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                <option value="1">Motor</option>
                <option value="2">Suspensión</option>
            </select>
        </div>

        <div>
            <label for="imagen" class="block text-sm font-medium text-gray-700">Imagen:</label>
            <input type="file" name="imagen" id="imagen" required
                   class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
        </div>

        <button type="submit"
                class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300">
            Agregar Inventario
        </button>
    </form>
</div>

</body>
</html>