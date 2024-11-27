<?php
session_start(); // Iniciar la sesión al principio
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <style>
        body {
            text-align: center;
            font-family: Arial, sans-serif;
            background-image: url('https://stories.weroad.es/wp-content/uploads/2019/11/Fuji.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .contenedor {
            background-color: white;
            width: 70%;
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.3);
            border-radius: 10px;
        }
    </style>
</head>
<body>
<?php
// Mostrar mensaje de sesión si existe
if (isset($_SESSION['mensaje'])) {
    echo "<p>" . $_SESSION['mensaje'] . "</p>";
    unset($_SESSION['mensaje']); // Limpiar el mensaje
}
?>
<center><h1 style="color:white; font-size: 50px;">Registrate</h1></center>
<div class="contenedor">
    <form method="post" action="">
        Usuario: <input type="text" name="usuario" required><br><br>
        Apellido: <input type="text" name="apellido" required><br><br>
        Contraseña: <input type="password" name="contraseña" required><br><br>
        Cedula: <input type="text" name="cedula" required><br><br>
        Correo: <input type="email" name="correo" required><br><br>
        <input type="submit" value="Confirmar">
    </form>
</div>
</body>
</html>

<?php
// Clase para manejar la conexión
include '../config/ConexionBD.php'; // Asegúrate de que esta ruta sea correcta

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $apellido = $_POST['apellido'];
    $contraseña = $_POST['contraseña'];
    $cedula = $_POST['cedula'];
    $correo = $_POST['correo'];
    $activo = 0;

    // Validar campos (opcional, pero recomendado)
    if (empty($usuario) || empty($apellido) || empty($contraseña) || empty($cedula) || empty($correo)) {
        $_SESSION['mensaje'] = "Por favor, completa todos los campos.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    // Generar hash de la contraseña
    $hashedPassword = password_hash($contraseña, PASSWORD_DEFAULT);

    // Guardar en la base de datos
    try {
        $conexion = ConexionBD::obtenerConexion();
        $sql = "INSERT INTO usuarios (usuario, apellido, contrasena, correo, cedula, activo) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("sssssi", $usuario, $apellido, $hashedPassword, $correo, $cedula, $activo);
        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "Usuario registrado con éxito.";
        } else {
            $_SESSION['mensaje'] = "Error al registrar el usuario.";
        }
        $stmt->close();
        $conexion->close();
    } catch (Exception $e) {
        $_SESSION['mensaje'] = "Error: " . $e->getMessage();
    }

    // Redirigir después de registrar
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>
