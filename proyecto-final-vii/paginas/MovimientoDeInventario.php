<?php
session_start(); // Iniciar sesión para manejar mensajes

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

require_once '../modelos/Inventario.php'; // Clase para manejar inventario
require_once '../modelos/MovimientoInventario.php'; // Clase para manejar movimientos de inventario
require_once '../modelos/Ubicacion.php'; // Clase para manejar secciones

$accionesInventario = new InventarioAcciones();
$accionesMovimientos = new MovimientosAcciones();
$accionesUbicacion = new UbicacionAcciones();

// Obtener datos de inventario para mostrar en la tabla
$inventarios = $accionesInventario->obtenerTodos();

// Obtener todas las ubicaciones (sucursales)
$sucursales = $accionesUbicacion->obtenerTodas();

// Procesar el formulario de movimiento de inventario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idInventario = $_POST['idInventario'];
    $modelo = $_POST['modelo'];
    $sucursalOrigen = $_POST['sucursalOrigen'];
    $sucursalDestino = $_POST['sucursalDestino'];
    $cantidad = 1; // Asume que se mueve 1 unidad por defecto, puedes ajustar esto según tu lógica

    try {
        // Registrar el movimiento de salida en la sucursal de origen
        $accionesMovimientos->insertarMovimiento($idInventario, 'salida', $cantidad, date('Y-m-d H:i:s'));

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

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movimiento de Inventario</title>
    <style>
        body {
            text-align: center;
            font-family: Arial, sans-serif;
            background-image: url('https://stories.weroad.es/wp-content/uploads/2019/11/Fuji.jpg');
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
    </style>
</head>
<body>
<h2 style="text-align: center;">Movimiento de Inventario</h2>

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
</div>

<!-- Formulario para mover inventario -->
<form action="" method="POST">
    <label for="idInventario">ID del Inventario:</label>
    <input type="number" id="idInventario" name="idInventario" required placeholder="Ingrese el ID del inventario">

    <label for="modelo">Modelo del ITEM:</label>
    <input type="text" id="modelo" name="modelo" required placeholder="Ingrese el modelo del ITEM">

    <label for="sucursalOrigen">Sucursal de Origen:</label>
    <select id="sucursalOrigen" name="sucursalOrigen" required>
        <option value="" disabled selected>Seleccione la sucursal de origen</option>
        <?php foreach ($sucursales as $sucursal): ?>
            <option value="<?= $sucursal->getIdSeccion(); ?>">
                <?= $sucursal->getNombre(); ?> - <?= $sucursal->getUbicacion(); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="sucursalDestino">Sucursal de Destino:</label>
    <select id="sucursalDestino" name="sucursalDestino" required>
        <option value="" disabled selected>Seleccione la sucursal de destino</option>
        <?php foreach ($sucursales as $sucursal): ?>
            <option value="<?= $sucursal->getIdSeccion(); ?>">
                <?= $sucursal->getNombre(); ?> - <?= $sucursal->getUbicacion(); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit">Mover Inventario</button>
</form>

<?php if (isset($_SESSION['mensaje'])): ?>
    <div class="message"><?= $_SESSION['mensaje']; unset($_SESSION['mensaje']); ?></div>
<?php endif; ?>
</body>
</html>