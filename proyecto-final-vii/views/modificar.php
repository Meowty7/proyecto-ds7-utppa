<?php
require_once '../models/Usuarios.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idUsuario = $_POST['idUsuario'];
    $usuario = $_POST['usuario'];
    $apellido = $_POST['apellido'];
    $contrasena = $_POST['contrasena'];
    $correo = $_POST['correo'];
    $cedula = $_POST['cedula'];
    $activo = $_POST['activo'];

    $usuariosTabla = new UsuariosTabla();
    $usuariosTabla->actualizarUsuario($idUsuario, $usuario, $apellido, $contrasena, $correo, $cedula, $activo);
} else {
    $idUsuario = $_GET['idUsuario'];
    // Recuperar datos del usuario para prellenar el formulario
    $conexion = ConexionBD::obtenerConexion();
    $sql = "SELECT * FROM usuarios WHERE idUsuario = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();
    $stmt->close();
    $conexion->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modificar Usuario</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-cover bg-center h-screen" style="background-image: url('https://wallpaper.dog/large/20624419.jpg');">
    <button onclick="window.location.href='index.php'"
        class="absolute top-4 left-8 bg-teal-600 text-white px-4 py-2 rounded hover:bg-teal-700 transition">
        Página Inicio
    </button>

    <?php
    // Mostrar el mensaje de sesión si existe
    if (isset($_SESSION['mensaje'])) {
        echo "<p class='text-center text-lg text-green-600 font-semibold'>" . $_SESSION['mensaje'] . "</p>";
        unset($_SESSION['mensaje']); // Limpiar el mensaje después de mostrarlo
    }
    ?>

    <h1 class="text-center text-3xl font-bold text-white mt-8">Modificar Usuario</h1>

    <div class="bg-white max-w-md mx-auto mt-10 p-6 shadow-lg rounded-lg">
        <form method="post" action="" class="space-y-4">
            <input type="hidden" name="idUsuario" value="<?php echo $usuario['idUsuario']; ?>">

            <div>
                <label for="usuario" class="block text-gray-700 font-semibold">Usuario:</label>
                <input type="text" id="usuario" name="usuario" value="<?php echo $usuario['usuario']; ?>" 
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-teal-300" required>
            </div>

            <div>
                <label for="apellido" class="block text-gray-700 font-semibold">Apellido:</label>
                <input type="text" id="apellido" name="apellido" value="<?php echo $usuario['apellido']; ?>" 
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-teal-300" required>
            </div>

            <div>
                <label for="contrasena" class="block text-gray-700 font-semibold">Contraseña:</label>
                <input type="password" id="contrasena" name="contrasena" value ="<?php echo $usuario['contrasena']; ?>"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-teal-300" required>
            </div>

            <div>
                <label for="correo" class="block text-gray-700 font-semibold">Correo:</label>
                <input type="email" id="correo" name="correo" value="<?php echo $usuario['correo']; ?>" 
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-teal-300" required>
            </div>

            <div>
                <label for="cedula" class="block text-gray-700 font-semibold">Cédula:</label>
                <input type="text" id="cedula" name="cedula" value="<?php echo $usuario['cedula']; ?>" 
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-teal-300" required>
            </div>

            <div>
                <label for="activo" class="block text-gray-700 font-semibold">Activo:</label>
                <input type="number" id="activo" name="activo" min="0" max="1" value="<?php echo $usuario['activo']; ?>" 
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-teal-300" required>
            </div>

            <div class="text-center">
                <input type="submit" value="Actualizar" 
                    class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 transition cursor-pointer">
            </div>
        </form>
    </div>
</body>
</html>
