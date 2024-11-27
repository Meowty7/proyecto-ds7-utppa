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
    <style>
            body {
                text-align: center;
                font-family: Arial, sans-serif;
                background-image: url('https://wallpaper.dog/large/20624419.jpg');
                background-size: cover;
                background-repeat: no-repeat;
                background-attachment: fixed;
            }
            .container {
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
<button onclick="window.location.href='index.php'"style="position: absolute; top: 10px; left: 110px; background-color: #04858c; color: white; border: none; padding: 10px 20px; text-align: center; text-decoration: none; font-size: 16px; cursor: pointer;">Pagina Inicio</button>
   
<?php
    // Mostrar el mensaje de sesión si existe
    if (isset($_SESSION['mensaje'])) {
        echo "<p>" . $_SESSION['mensaje'] . "</p>";
        unset($_SESSION['mensaje']); // Limpiar el mensaje después de mostrarlo
    }
    ?>
    <h1>Modificar Usuario</h1>
    <div class="container">
    <form method="post" action="">
        <input type="hidden" name="idUsuario" value="<?php echo $usuario['idUsuario']; ?>">
        Usuario: <input type="text" name="usuario" value="<?php echo $usuario['usuario']; ?>" required><br><br>
        Apellido: <input type="text" name="apellido" value="<?php echo $usuario['apellido']; ?>" required><br><br>
        Contraseña: <input type="text" name="contrasena" value="<?php echo $usuario['contrasena']; ?>" required><br><br>
        Correo: <input type="text" name="correo" value="<?php echo $usuario['correo']; ?>" required><br><br>
        Cédula: <input type="text" name="cedula" value="<?php echo $usuario['cedula']; ?>" required><br><br>
        Activo: <input type="number" name="activo" min="0" max="1" value="<?php echo $usuario['activo']; ?>" required><br><br>

        <input type="submit" value="Actualizar">
    </form>
    </div>
</body>
</html>
