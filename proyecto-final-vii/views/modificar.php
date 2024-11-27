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
</head>
<body>
    <h1>Modificar Usuario</h1>
    <form method="post" action="">
        <input type="hidden" name="idUsuario" value="<?php echo $usuario['idUsuario']; ?>">
        Usuario: <input type="text" name="usuario" value="<?php echo $usuario['usuario']; ?>" required><br><br>
        Apellido: <input type="text" name="apellido" value="<?php echo $usuario['apellido']; ?>" required><br><br>
        Contraseña: <input type="text" name="contrasena" value="<?php echo $usuario['contrasena']; ?>" required><br><br>
        Correo: <input type="text" name="correo" value="<?php echo $usuario['correo']; ?>" required><br><br>
        Cédula: <input type="text" name="cedula" value="<?php echo $usuario['cedula']; ?>" required><br><br>
        Activo: <input type="text" name="activo" value="<?php echo $usuario['activo']; ?>" required><br><br>
        <input type="submit" value="Actualizar">
    </form>
</body>
</html>
