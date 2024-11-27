<?php
require_once '../models/Inventario.php';

// Obtener los datos del inventario
$accionesInventario = new InventarioAcciones();
$inventarios = $accionesInventario->obtenerTodos();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rastro de Auto Partes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center justify-center">
<div class="container mx-auto p-6">
    <h1 class="text-4xl font-bold text-center mb-8">Rastro de Auto Partes</h1>

    <!-- Contenedor de registros -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <?php foreach ($inventarios as $inventario): ?>
            <div
                    class="bg-white shadow-md hover:shadow-lg rounded-lg overflow-hidden cursor-pointer"
                    onclick="mostrarDetalle(
                            '<?= htmlspecialchars(json_encode([
                        'idInventario' => $inventario->getIdInventario(),
                        'parte' => $inventario->getParte(),
                        'marca' => $inventario->getMarca(),
                        'modelo' => $inventario->getModelo(),
                        'descripcion' => $inventario->getDescripcion(),
                        'cantidad' => $inventario->getCantidad(),
                        'costo' => $inventario->getCosto(),
                        'imagen' => $inventario->getImagen()
                    ])) ?>'
                            )"
            >
                <img src="<?= htmlspecialchars($inventario->getImagen()) ?>" alt="<?= htmlspecialchars($inventario->getParte()) ?>" class="w-full h-40 object-cover">
                <div class="p-4">
                    <h2 class="font-bold text-lg"><?= htmlspecialchars($inventario->getParte()) ?></h2>
                    <p class="text-gray-600"><?= htmlspecialchars($inventario->getMarca()) ?> - <?= htmlspecialchars($inventario->getModelo()) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Modal de Detalle -->
<div id="detalleModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-lg max-w-lg w-full">
        <div class="relative">
            <button onclick="cerrarDetalle()" class="absolute top-2 right-2 bg-gray-200 hover:bg-gray-300 rounded-full p-2 text-gray-600">
                &times;
            </button>
            <img id="detalleImagen" src="" alt="Detalle de la Parte" class="w-full h-64 object-cover rounded-t-lg">
        </div>
        <div class="p-6">
            <h2 id="detalleTitulo" class="text-2xl font-bold mb-2"></h2>
            <p id="detalleDescripcion" class="text-gray-700 mb-4"></p>
            <p class="text-lg"><strong>Costo:</strong> $<span id="detalleCosto"></span></p>
            <p class="text-lg"><strong>Unidades Disponibles:</strong> <span id="detalleCantidad"></span></p>
            <p class="text-lg"><strong>Modelo:</strong> <span id="detalleModelo"></span></p>
            <p class="text-lg"><strong>Marca:</strong> <span id="detalleMarca"></span></p>
        </div>
    </div>
</div>

<script>
    // Mostrar el modal con los detalles de la parte
    function mostrarDetalle(inventarioString) {
        const inventario = JSON.parse(inventarioString);

        document.getElementById('detalleImagen').src = inventario.imagen;
        document.getElementById('detalleTitulo').textContent = inventario.parte;
        document.getElementById('detalleDescripcion').textContent = inventario.descripcion;
        document.getElementById('detalleCosto').textContent = parseFloat(inventario.costo).toFixed(2);
        document.getElementById('detalleCantidad').textContent = inventario.cantidad;
        document.getElementById('detalleModelo').textContent = inventario.modelo;
        document.getElementById('detalleMarca').textContent = inventario.marca;

        document.getElementById('detalleModal').classList.remove('hidden');
    }

    // Cerrar el modal de detalles
    function cerrarDetalle() {
        document.getElementById('detalleModal').classList.add('hidden');
    }
</script>
</body>
</html>