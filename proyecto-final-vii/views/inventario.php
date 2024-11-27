<?php
session_start(); // Iniciar sesión para manejar mensajes

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

require_once '../models/Inventario.php'; // Clase para manejar inventario

$accionesInventario = new InventarioAcciones();

// Obtener todos los inventarios para mostrar en la tabla
$inventarios = $accionesInventario->obtenerTodos();

// Procesar el formulario de actualización de inventario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idInventario = $_POST['idInventario'];
    $parte = $_POST['parte'];
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $fecha = $_POST['fecha'];
    $cantidad = $_POST['cantidad'];
    $costo = $_POST['costo'];
    $idSeccion = $_POST['idSeccion'];
    $imagen = $_POST['imagen'];
    $descripcion = $_POST['descripcion'];

    try {
        // Obtener el inventario a actualizar
        $inventario = new Inventario($parte, $marca, $modelo, $fecha, $cantidad, $costo, $idSeccion, $imagen, $idInventario, $descripcion);
        $exito = $accionesInventario->actualizar($inventario);

        if ($exito) {
            $_SESSION['mensaje'] = "Inventario actualizado exitosamente.";
        } else {
            $_SESSION['mensaje'] = "Error al actualizar el inventario.";
        }
    } catch (Exception $e) {
        $_SESSION['mensaje'] = "Error al actualizar el inventario: " . $e->getMessage();
    }

    // Redirigir para evitar reenvío de formulario
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!-- HTML de la página de actualización de inventario -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Inventario</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function llenarFormulario(inventario) {
            document.getElementById('idInventario').value = inventario.id;
            document.getElementById('parte').value = inventario.parte;
            document.getElementById('marca').value = inventario.marca;
            document.getElementById('modelo').value = inventario.modelo;
            document.getElementById('fecha').value = inventario.fecha;
            document.getElementById('cantidad').value = inventario.cantidad;
            document.getElementById('costo').value = inventario.costo;
            document.getElementById('idSeccion').value = inventario.idSeccion;
            document.getElementById('imagen').value = inventario.imagen;
            document.getElementById('descripcion').value = inventario.descripcion;
        }
    </script>
    <style>
        body {
            text-align: center;
            font-family: Arial, sans-serif;

            background-image: url('https://wallpaper.dog/large/20624419.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
    </style>
</head>
<body class="bg-gray-100 p-5">
<div class="container mx-auto">
    <h2 class="text-center text-2xl font-bold mb-5">Actualizar Inventario</h2>
    <button onclick="window.location.href='index.php'"style="position: absolute; top: 10px; left: 110px; background-color: #04858c; color: white; border: none; padding: 10px 20px; text-align: center; text-decoration: none; font-size: 16px; cursor: pointer;">Pagina Inicio</button>
    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="bg-green-200 text-green-700 p-2 mb-4 rounded">
            <?= $_SESSION['mensaje']; ?>
            <?php unset($_SESSION['mensaje']); ?>
        </div>
    <?php endif; ?>

    <!-- Tabla de Inventarios -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white">
            <thead>
            <tr class="w-full bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                <th class="py-3 px-6 text-left">ID</th>
                <th class="py-3 px-6 text-left">Parte</th>
                <th class="py-3 px-6 text-left">Marca</th>
                <th class="py-3 px-6 text-left">Modelo</th>
                <th class="py-3 px-6 text-left">Cantidad</th>
                <th class="py-3 px-6 text-left">Acciones</th>
            </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
            <?php foreach ($inventarios as $inventario): ?>
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-left"><?= $inventario->getIdInventario(); ?></td>
                    <td class="py-3 px-6 text-left"><?= $inventario->getParte(); ?></td>
                    <td class="py-3 px-6 text-left"><?= $inventario->getMarca(); ?></td>
                    <td class="py-3 px-6 text-left"><?= $inventario->getModelo(); ?></td>
                    <td class="py-3 px-6 text-left"><?= $inventario->getCantidad(); ?></td>
                    <td class="py-3 px-6 text-left">
                        <button onclick='llenarFormulario(<?= json_encode(["id" => $inventario->getIdInventario(), "parte" => $inventario->getParte(), "marca" => $inventario->getMarca(), "modelo" => $inventario->getModelo(), "fecha" => $inventario->getFecha(), "cantidad" => $inventario->getCantidad(), "costo" => $inventario->getCosto(), "idSeccion" => $inventario->getIdSeccion(), "imagen" => $inventario->getImagen(), "descripcion" => $inventario->getDescripcion()]); ?>)' class="bg-blue-500 text-white px-4 py-2 rounded">Editar</button>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Formulario de Actualización -->
    <form action="" method="POST" class="bg-white p-6 rounded shadow-md mt-6">
        <input type="hidden" id="idInventario" name="idInventario">

        <div class="mb-4">
            <label for="parte" class="block text-sm font-medium text-gray-700">Parte:</label>
            <input type="text" id="parte" name="parte" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
        </div>

        <div class="mb-4">
            <label for="marca" class="block text-sm font-medium text-gray-700">Marca:</label>
            <input type="text" id="marca" name="marca" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
        </div>

        <div class="mb-4">
            <label for="modelo" class="block text-sm font-medium text-gray-700">Modelo:</label>
            <input type="text" id="modelo" name="modelo" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
        </div>

        <div class="mb-4">
            <label for="fecha" class="block text-sm font-medium text-gray-700">Año:</label>
            <input type="number" id="fecha" name="fecha" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
        </div>

        <div class="mb-4">
            <label for="cantidad" class="block text-sm font-medium text-gray-700">Cantidad:</label>
            <input type="number" id="cantidad" name="cantidad" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
        </div>

        <div class="mb-4">
            <label for="costo" class="block text-sm font-medium text-gray-700">Costo:</label>
            <input type="number" step="0.01" id="costo" name="costo" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
        </div>

        <div class="mb-4">
            <label for="idSeccion" class="block text-sm font-medium text-gray-700">Sección:</label>
            <input type="number" id="idSeccion" name="idSeccion" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
        </div>

        <div class="mb-4">
            <label for="imagen" class="block text-sm font-medium text-gray-700">Imagen:</label>
            <input type="text" id="imagen" name="imagen" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
        </div>

        <div class="mb-4">
            <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción:</label>
            <textarea id="descripcion" name="descripcion" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm"></textarea>
        </div>

        <button type="submit" class="w-full py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Actualizar Inventario</button>
    </form>
</div>
</body>
</html>