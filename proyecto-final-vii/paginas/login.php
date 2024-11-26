<?php
require_once '../modelos/Usuarios.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correoOUsuario = $_POST['correoOUsuario'] ?? '';
    $contrasena = $_POST['contrasena'] ?? '';

    try {
        $accionesUsuario = new UsuarioAcciones();

        // Llamar al método para iniciar sesión
        $usuarioLogueado = $accionesUsuario->iniciarSesion($correoOUsuario, $contrasena);

        // Almacenar datos del usuario en la sesión
        $_SESSION['usuario'] = [
            'idUsuario' => $usuarioLogueado->getIdUsuario(),
            'usuario' => $usuarioLogueado->getUsuario(),
            'correo' => $usuarioLogueado->getCorreo(),
            'activo' => $usuarioLogueado->getActivo(),
        ];

        // Redirigir al usuario a la página de inicio
        header('Location: dashboard.php');
        exit;

    } catch (Exception $e) {
        // Manejar errores
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
<div class="w-full max-w-md bg-white shadow-md rounded-lg p-6">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Iniciar Sesión</h2>

    <!-- Mostrar error si existe -->
    <?php if (!empty($error)): ?>
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form action="login.php" method="POST" class="space-y-4">
        <!-- Campo para usuario o correo -->
        <div>
            <label for="correoOUsuario" class="block text-sm font-medium text-gray-700">Correo o Usuario</label>
            <input type="text" id="correoOUsuario" name="correoOUsuario" required
                   class="w-full mt-1 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        <!-- Campo para contraseña -->
        <div>
            <label for="contrasena" class="block text-sm font-medium text-gray-700">Contraseña</label>
            <input type="password" id="contrasena" name="contrasena" required
                   class="w-full mt-1 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        <!-- Botón para iniciar sesión -->
        <div>
            <button type="submit"
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300">
                Iniciar Sesión
            </button>
        </div>
    </form>
</div>
</body>
</html>