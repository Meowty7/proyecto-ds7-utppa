<?php
session_start(); // Iniciar sesión para manejar mensajes

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

require_once '../models/Inventario.php'; // Clase para manejar inventario
require_once '../models/MovimientoInventario.php'; // Clase para manejar movimientos de inventario
require_once '../models/Ubicacion.php'; // Clase para manejar secciones

$accionesInventario = new InventarioAcciones();
$accionesMovimientos = new MovimientosAcciones();
$accionesUbicacion = new UbicacionAcciones();

// Obtener todas las ubicaciones (secciones)
$sucursales = $accionesUbicacion->obtenerTodas();

// Procesar el formulario de movimiento de inventario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idInventario = $_POST['idInventario'];
    $cantidad = $_POST['cantidad'];
    $sucursalOrigen = $_POST['sucursalOrigen'];
    $sucursalDestino = $_POST['sucursalDestino'];

    // Verificar si el movimiento es válido (cantidad > 0)
    if ($cantidad <= 0) {
        $_SESSION['mensaje'] = "La cantidad a mover debe ser mayor a 0.";
        header("Location: movimientos.php");
        exit();
    }

    // Obtener el inventario original
    $inventarioOriginal = $accionesInventario->obtenerPorId($idInventario);
    $parte = $inventarioOriginal->getParte();
    $marca = $inventarioOriginal->getMarca();
    $modelo = $inventarioOriginal->getModelo();
    $fecha = $inventarioOriginal->getFecha();
    $costo = $inventarioOriginal->getCosto();
    $imagen = $inventarioOriginal->getImagen();
    $descripcion = $inventarioOriginal->getDescripcion();

    try {
        // Registrar movimiento de salida en la sucursal de origen
        $accionesMovimientos->insertarMovimiento($idInventario, 'salida', $cantidad, date('Y-m-d H:i:s'));

        // Actualizar inventario en la sucursal de origen
        $nuevaCantidadOrigen = $inventarioOriginal->getCantidad() - $cantidad;
        if ($nuevaCantidadOrigen >= 0) {
            $inventarioOriginal->setCantidad($nuevaCantidadOrigen);
            $accionesInventario->actualizar($inventarioOriginal);
        } else {
            $_SESSION['mensaje'] = "No hay suficiente cantidad en la sucursal de origen.";
            header("Location: movimientos.php");
            exit();
        }

        // Obtener inventario de la sucursal de destino
        $inventarioDestino = $accionesInventario->obtenerInventarioPorParteYSeccion($parte, $sucursalDestino);

        // Si el inventario existe en la sección de destino, solo actualizar la cantidad
        if ($inventarioDestino) {
            // Actualizar la cantidad sumando la cantidad que se movió
            $nuevaCantidadDestino = $inventarioDestino->getCantidad() + $cantidad;
            $inventarioDestino->setCantidad($nuevaCantidadDestino);
            $accionesInventario->actualizar($inventarioDestino);
        } else {
            // Si no existe, crear un nuevo registro de inventario con todos los datos
            $nuevoInventario = new Inventario(
                $parte,            // Parte
                $marca,            // Marca
                $modelo,           // Modelo
                $fecha,            // Fecha
                $cantidad,         // Cantidad
                $costo,            // Costo
                $sucursalDestino,  // Sección de destino
                $imagen,           // Imagen
                null,              // idInventario (esto lo deja el sistema autoincrementado)
                $descripcion       // Descripción
            );
            $accionesInventario->crear($nuevoInventario);
        }

        // Registrar el movimiento de entrada en la sucursal de destino
        $accionesMovimientos->insertarMovimiento($idInventario, 'entrada', $cantidad, date('Y-m-d H:i:s'));

        // Mensaje de éxito
        $_SESSION['mensaje'] = "Movimiento registrado exitosamente.";
    } catch (Exception $e) {
        // Mensaje de error
        $_SESSION['mensaje'] = "Error al registrar el movimiento: " . $e->getMessage();
    }

    // Redirigir para evitar reenvío de formulario
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!-- HTML de la página de movimientos -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movimiento de Inventario</title>
<<<<<<< HEAD
    
    <style>
        body {
            text-align: center;
            font-family: Arial, sans-serif;
            background-image: url('https://wallpaper.dog/large/20624419.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        .form-container {
            background-color: white;
            width: 70%;
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.3);
            border-radius: 10px;
        }
        form, table {
            max-width: 800px;
            margin: 20px auto;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        input, select, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .message {
            text-align: center;
            font-size: 14px;
            color: red;
        }
        button2 {
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
<h2 class="text-4xl font-bold text-center text-white mb-6">Movimiento de Inventario</h2>
<button2 onclick="window.location.href='index.php'"style="position: absolute; top: 10px; left: 110px; background-color: #04858c; color: white; border: none; padding: 10px 20px; text-align: center; text-decoration: none; font-size: 16px; cursor: pointer;">Pagina Inicio</button2>
   
<div class="form-container">
    <!-- Tabla de Inventarios -->
    <table>
        <thead>
        <tr>
            <th>ID Inventario</th>
            <th>Ubicación</th>
            <th>Marca</th>
            <th>Partes de Autos</th>
            <th>Modelo</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($inventarios as $inventario): ?>
            <tr>
                <td><?= $inventario->getIdInventario(); ?></td>
                <td><?= $accionesUbicacion->obtenerPorId($inventario->getIdSeccion())->getUbicacion(); ?></td>
                <td><?= $inventario->getMarca(); ?></td>
                <td><?= $inventario->getParte(); ?></td>
                <td><?= $inventario->getModelo(); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
=======
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-5">
<div class="container mx-auto">
    <h2 class="text-center text-2xl font-bold mb-5">Movimiento de Inventario</h2>

    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="bg-green-200 text-green-700 p-2 mb-4 rounded">
            <?= $_SESSION['mensaje']; ?>
            <?php unset($_SESSION['mensaje']); ?>
        </div>
    <?php endif; ?>

    <!-- Formulario de Movimiento -->
    <form action="MovimientoDeInventario.php" method="POST" class="bg-white p-6 rounded shadow-md">
        <div class="mb-4">
            <label for="idInventario" class="block text-sm font-medium text-gray-700">ID del Inventario:</label>
            <input type="number" id="idInventario" name="idInventario" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" placeholder="Ingrese el ID del inventario">
        </div>
        <div class="mb-4">
            <label for="cantidad" class="block text-sm font-medium text-gray-700">Cantidad a mover:</label>
            <input type="number" id="cantidad" name="cantidad" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" placeholder="Cantidad a mover">
        </div>
        <div class="mb-4">
            <label for="sucursalOrigen" class="block text-sm font-medium text-gray-700">Sucursal de Origen:</label>
            <select id="sucursalOrigen" name="sucursalOrigen" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                <option value="" disabled selected>Seleccione la sucursal de origen</option>
                <?php foreach ($sucursales as $sucursal): ?>
                    <option value="<?= $sucursal->getIdSeccion(); ?>">
                        <?= $sucursal->getNombre(); ?> - <?= $sucursal->getUbicacion(); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-4">
            <label for="sucursalDestino" class="block text-sm font-medium text-gray-700">Sucursal de Destino:</label>
            <select id="sucursalDestino" name="sucursalDestino" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                <option value="" disabled selected>Seleccione la sucursal de destino</option>
                <?php foreach ($sucursales as $sucursal): ?>
                    <option value="<?= $sucursal->getIdSeccion(); ?>">
                        <?= $sucursal->getNombre(); ?> - <?= $sucursal->getUbicacion(); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="w-full py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Mover Inventario</button>
    </form>
>>>>>>> ecdf32d898a4281e236adc15c0ae28c969da8a7d
</div>
</body>
</html>