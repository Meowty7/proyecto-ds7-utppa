<?php
require_once '../Encriptador.php';
require_once '../config/ConexionBD.php';

class Usuario
{
    private $idUsuario;
    private $usuario;
    private $apellido;
    private $contrasena;
    private $correo;
    private $cedula;
    private $activo;

    public function __construct($usuario = "", $apellido = "", $contrasena = "", $correo = "", $cedula = "", $activo = null, $idUsuario = null)
    {
        $this->idUsuario = $idUsuario;
        $this->usuario = $usuario;
        $this->apellido = $apellido;
        $this->contrasena = $contrasena;
        $this->correo = $correo;
        $this->cedula = $cedula;
        $this->activo = $activo;
    }

    // Getters y Setters
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    public function getUsuario()
    {
        return $this->usuario;
    }

    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }

    public function getApellido()
    {
        return $this->apellido;
    }

    public function setApellido($apellido)
    {
        $this->apellido = $apellido;
    }

    public function getContrasena()
    {
        return $this->contrasena;
    }

    /**
     * @throws Exception
     */
    public function setContrasena($contrasena)
    {
        $this->contrasena = Encriptador::encriptarContrasena($contrasena);
    }

    public function getCorreo()
    {
        return $this->correo;
    }

    public function setCorreo($correo)
    {
        $this->correo = $correo;
    }

    public function getCedula()
    {
        return $this->cedula;
    }

    public function setCedula($cedula)
    {
        $this->cedula = $cedula;
    }

    public function getActivo()
    {
        return $this->activo;
    }

    public function setActivo($activo)
    {
        $this->activo = $activo;
    }

    public function setIdUsuario($idUsuario)
    {
        $this->idUsuario = $idUsuario;
    }
}

class UsuarioAcciones
{

    // Crear un nuevo usuario en la base de datos
    public function crear(Usuario $usuario): bool
    {
        $conexion = ConexionBD::obtenerConexion();
        $sql = "INSERT INTO usuarios (usuario, apellido, contrasena, correo, cedula, activo) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $usuarioParams = [
            $usuario->getUsuario(),
            $usuario->getApellido(),
            $usuario->getContrasena(),
            $usuario->getCorreo(),
            $usuario->getCedula(),
            $usuario->getActivo()
        ];
        $stmt->bind_param("sssssi", ...$usuarioParams);
        if ($stmt->execute()) {
            $usuario->setIdUsuario($stmt->insert_id);
            return true;
        } else {
            return false;
        }
    }

    // Actualizar un usuario existente
    public function actualizar(Usuario $usuario): bool
    {
        if ($usuario->getIdUsuario()) {
            $conexion = ConexionBD::obtenerConexion();
            $sql = "UPDATE usuarios SET usuario = ?, apellido = ?, correo = ?, cedula = ?, activo = ? WHERE idUsuario = ?";
            $stmt = $conexion->prepare($sql);
            $usuarioParams = [
                $usuario->getUsuario(),
                $usuario->getApellido(),
                $usuario->getCorreo(),
                $usuario->getCedula(),
                $usuario->getActivo(),
                $usuario->getIdUsuario()
            ];
            $stmt->bind_param("ssssii", ...$usuarioParams);
            return $stmt->execute();
        }
        return false;
    }

    /**
     * @throws Exception
     */
    public function iniciarSesion($correo, $contrasena): Usuario
    {
        $conexion = ConexionBD::obtenerConexion();

        // Verificar si la entrada es un correo o un usuario
        $sql = "SELECT * FROM usuarios WHERE (correo = ? OR usuario = ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ss", $correo, $correo);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $fila = $resultado->fetch_assoc();
            if ($fila['activo'] == 1) {
                // Verificar contraseña
                if (Encriptador::verificarContrasena($contrasena, $fila['contrasena'])) {
                    // Verificar si el usuario está activo

                    // Usuario válido y activo
                    return new Usuario(
                        $fila['usuario'],
                        $fila['apellido'],
                        $fila['contrasena'],
                        $fila['correo'],
                        $fila['cedula'],
                        $fila['activo'],
                        $fila['idUsuario']
                    );
                } else {
                    throw new Exception("Contraseña incorrecta.");
                }
            } else {
                throw new Exception("Este usuario no puede ingresar al sistema.");
            }
        } else {
            throw new Exception("Usuario o correo no registrado.");
        }
    }

    // Obtener un usuario por su ID
    public function obtenerPorId($idUsuario)
    {
        $conexion = ConexionBD::obtenerConexion();
        $sql = "SELECT * FROM usuarios WHERE idUsuario = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $resultado = $stmt->get_result();
        if ($resultado->num_rows > 0) {
            $fila = $resultado->fetch_assoc();
            return new Usuario($fila['usuario'], $fila['apellido'], $fila['contrasena'], $fila['correo'], $fila['cedula'], $fila['activo'], $fila['idUsuario']);
        }
        return null;
    }

}


class UsuariosTabla {

    // Función para generar las filas de usuarios en la tabla HTML
    function generarFilasUsuarios() {
        $conexion = ConexionBD::obtenerConexion();
        $sql = "SELECT * FROM usuarios";
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                      <td>{$row['idUsuario']}</td>
                        <td>{$row['usuario']}</td>
                        <td>{$row['apellido']}</td>
                        <td>{$row['contrasena']}</td>
                        <td>{$row['correo']}</td>
                        <td>{$row['cedula']}</td>
                        <td>{$row['activo']}</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No se encontraron resultados</td></tr>";
        }
    
        $stmt->close();
        $conexion->close();
    }
    
    
    // Función para procesar la modificación de la contraseña de un usuario
    // Función para procesar la modificación de la contraseña de un usuario
    function procesarModificacionContraseña($contraseñaActual, $nuevaContraseña, $conexion) {
        // Obtener el ID del usuario activo desde la sesión
        $idUsuario = $conexion->getUsuario(); // O puedes acceder directamente desde la sesión si ya está guardado
    
        // Consulta para obtener la contraseña actual del usuario
        $query = "SELECT contraseña FROM registro_usuarios WHERE idUsuario = ?";
        $stmt = $conexion->getConnection()->prepare($query);  // Asegúrate de que 'getConnection()' está accesible
    
        if (!$stmt) {
            return "Error en la preparación de la consulta: " . $conexion->getConnection()->error;
        }
    
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $stmt->bind_result($contraseñaEncriptada);
        $stmt->fetch();
        $stmt->close();
    
        // Verificar la contraseña actual
        if (password_verify($contraseñaActual, $contraseñaEncriptada)) {
            // Encriptar la nueva contraseña
            $nuevaContraseñaHash = password_hash($nuevaContraseña, PASSWORD_DEFAULT);
    
            // Actualizar la contraseña en la base de datos
            $updateQuery = "UPDATE registro_usuarios SET contraseña = ? WHERE idUsuario = ?";
            $updateStmt = $conexion->getConnection()->prepare($updateQuery);
    
            if (!$updateStmt) {
                return "Error en la preparación de la actualización: " . $conexion->getConnection()->error;
            }
    
            $updateStmt->bind_param("si", $nuevaContraseñaHash, $idUsuario);
    
            if ($updateStmt->execute()) {
                $updateStmt->close();
                return "Contraseña actualizada correctamente.";
            } else {
                $updateStmt->close();
                return "Error al actualizar la contraseña.";
            }
        } else {
            return "La contraseña actual es incorrecta.";
        }
    }
    
    
    
    
    }