<?php
require_once '../config/ConexionBD.php';
class Ubicacion {
    private int $idSeccion;
    private string $nombre;
    private string $ubicacion;

    public function __construct($nombre = "", $ubicacion = "", $idSeccion = null) {
        $this->idSeccion = $idSeccion;
        $this->nombre = $nombre;
        $this->ubicacion = $ubicacion;
    }

    // Getters
    public function getIdSeccion() {
        return $this->idSeccion;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getUbicacion() {
        return $this->ubicacion;
    }

    // Setters
    public function setNombre($nombre): void
    {
        $this->nombre = $nombre;
    }

    public function setUbicacion($ubicacion): void
    {
        $this->ubicacion = $ubicacion;
    }
}


class UbicacionAcciones
{

    // Insertar una nueva ubicación en la base de datos
    public function insertar(Ubicacion $ubicacion): bool
    {
        $conexion = ConexionBD::obtenerConexion();
        $sql = "INSERT INTO rastro.secciones (nombre, ubicacion) VALUES (?, ?)";
        $stmt = $conexion->prepare($sql);
        $params = [
            $ubicacion->getNombre(),
            $ubicacion->getUbicacion()
        ];
        $stmt->bind_param("ss", ...$params);
        return $stmt->execute();
    }

    // Obtener una ubicación por su ID
    public function obtenerPorId(int $idSeccion): ?Ubicacion
    {
        $conexion = ConexionBD::obtenerConexion();
        $sql = "SELECT * FROM rastro.secciones WHERE idSeccion = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $idSeccion);
        $stmt->execute();
        $resultado = $stmt->get_result();
        if ($resultado->num_rows > 0) {
            $fila = $resultado->fetch_assoc();
            return new Ubicacion(
                $fila['nombre'],
                $fila['ubicacion'],
                $fila['idSeccion']
            );
        }
        return null;
    }

    // Obtener todas las ubicaciones
    public function obtenerTodas(): array
    {
        $conexion = ConexionBD::obtenerConexion();
        $sql = "SELECT * FROM rastro.secciones";
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $ubicaciones = [];

        while ($fila = $resultado->fetch_assoc()) {
            $ubicaciones[] = new Ubicacion($fila['nombre'], $fila['ubicacion'], $fila['idSeccion']);
        }
        return $ubicaciones;
    }

    // Actualizar una ubicación existente
    public function actualizar(Ubicacion $ubicacion): bool
    {
        $conexion = ConexionBD::obtenerConexion();
        $sql = "UPDATE rastro.secciones SET nombre = ?, ubicacion = ? WHERE idSeccion = ?";
        $stmt = $conexion->prepare($sql);
        $params = [
            $ubicacion->getNombre(),
            $ubicacion->getUbicacion(),
            $ubicacion->getIdSeccion()
        ];
        $stmt->bind_param("ssi", ...$params);
        return $stmt->execute();
    }

    // Eliminar una ubicación por su ID
    public function eliminar(int $idSeccion): bool
    {
        $conexion = ConexionBD::obtenerConexion();
        $sql = "DELETE FROM rastro.secciones WHERE idSeccion = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $idSeccion);
        return $stmt->execute();
    }
}