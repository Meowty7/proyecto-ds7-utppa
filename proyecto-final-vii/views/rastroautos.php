<?php

require_once '../models/Inventario.php';

$accionesInventario = new InventarioAcciones();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

}

?>

<form action="rastroautos.php" method="POST" enctype="multipart/form-data">
    <label for="imagen">Subir Imagen:</label>
    <input type="file" name="imagen" id="imagen" required>
    <input type="text" name="nombreParte" placeholder="Nombre de la Parte" required>
    <button type="submit">Subir</button>
</form>
