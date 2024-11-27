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
 
        public function generarFilasUsuarios($usuarioActual = null) {
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
                            <td>*************</td>
                            <td>{$row['correo']}</td>
                            <td>{$row['cedula']}</td>
                            <td>{$row['activo']}</td>";
                    
                    // Solo `Mulino` puede ver el enlace de modificar
                    if ($usuarioActual === 'Mulino') {
                        echo "<td><a href='modificar.php?idUsuario={$row['idUsuario']}'>Modificar</a></td>";
                    }
    
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No se encontraron resultados</td></tr>";
            }
    
            $stmt->close();
            $conexion->close();
        
    }
    

    public function actualizarUsuario($idUsuario, $usuario, $apellido, $contrasena, $correo, $cedula, $activo) {
        $conexion = ConexionBD::obtenerConexion();
        $sql = "UPDATE usuarios 
                SET usuario = ?, apellido = ?, contrasena = ?, correo = ?, cedula = ?, activo = ? 
                WHERE idUsuario = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ssssssi", $usuario, $apellido, Encriptador::encriptarContrasena($contrasena), $correo, $cedula, $activo, $idUsuario);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
        
            $_SESSION['mensaje'] = "Usuario actualizado correctamente.";
            header("Location: ../ModificarUsuarios.php");
            exit();
        } else {
           
            $_SESSION['mensaje'] = "No se pudo actualizar el usuario.";
            header("Location: ../ModificarUsuarios.php");
            exit();
        }

        $stmt->close();
        $conexion->close();
    }
}
