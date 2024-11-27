<?php
require_once '../config/ConexionBD.php';
class MovimientoInventario {
    private $idMovimiento;
    private $idInventario;
    private $tipoMovimiento;
    private $cantidad;
    private $fechaMovimiento;

    public function __construct($idInventario, $tipoMovimiento, $cantidad, $fechaMovimiento = null, $idMovimiento = null) {
        $this->idMovimiento = $idMovimiento;
        $this->idInventario = $idInventario;
        $this->tipoMovimiento = $tipoMovimiento;
        $this->cantidad = $cantidad;
        $this->fechaMovimiento = $fechaMovimiento ?: date('Y-m-d H:i:s');
    }

    // Getters y Setters
    public function getIdMovimiento() { return $this->idMovimiento; }
    public function getIdInventario() { return $this->idInventario; }
    public function getTipoMovimiento() { return $this->tipoMovimiento; }
    public function getCantidad() { return $this->cantidad; }
    public function getFechaMovimiento() { return $this->fechaMovimiento; }

    public function setTipoMovimiento($tipoMovimiento) { $this->tipoMovimiento = $tipoMovimiento; }
    public function setCantidad($cantidad): void
    { $this->cantidad = $cantidad; }
}

class MovimientosAcciones {

    // Método para insertar un nuevo movimiento
    public function insertarMovimiento($idInventario, $tipoMovimiento, $cantidad, $fechaMovimiento): bool
    {
        $conexion = ConexionBD::obtenerConexion();

        // Preparar la consulta para insertar el movimiento
        $sql = "INSERT INTO movimientos_inventario (idInventario, tipoMovimiento, cantidad, fechaMovimiento) 
                VALUES (?, ?, ?, ?)";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("isis", $idInventario, $tipoMovimiento, $cantidad, $fechaMovimiento);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            return true; // Movimiento insertado correctamente
        } else {
            return false; // Error al insertar el movimiento
        }
    }

    // Método para obtener todos los movimientos de un inventario
    public function obtenerMovimientosPorInventario($idInventario): array
    {
        $conexion = ConexionBD::obtenerConexion();

        // Consultar los movimientos de un inventario específico
        $sql = "SELECT * FROM movimientos_inventario WHERE idInventario = ? ORDER BY fechaMovimiento DESC";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $idInventario);
        $stmt->execute();

        // Obtener los resultados
        $resultado = $stmt->get_result();

        // Crear un array para almacenar los movimientos
        $movimientos = [];

        while ($fila = $resultado->fetch_assoc()) {
            // Crear un objeto Movimiento con los datos obtenidos
            $movimiento = new MovimientoInventario(
                $fila['idMovimiento'],
                $fila['idInventario'],
                $fila['tipoMovimiento'],
                $fila['cantidad'],
                $fila['fechaMovimiento']
            );
            $movimientos[] = $movimiento; // Agregar el movimiento a la lista
        }

        return $movimientos; // Retornar el array de movimientos
    }

    // Método para obtener todos los movimientos (general)
    public function obtenerTodosLosMovimientos(): array
    {
        $conexion = ConexionBD::obtenerConexion();

        // Consultar todos los movimientos
        $sql = "SELECT * FROM movimientos_inventario ORDER BY fechaMovimiento DESC";

        $resultado = $conexion->query($sql);

        // Crear un array para almacenar los movimientos
        $movimientos = [];

        while ($fila = $resultado->fetch_assoc()) {
            // Crear un objeto Movimiento con los datos obtenidos
            $movimiento = new MovimientoInventario(
                $fila['idMovimiento'],
                $fila['idInventario'],
                $fila['tipoMovimiento'],
                $fila['cantidad'],
                $fila['fechaMovimiento']
            );
            $movimientos[] = $movimiento; // Agregar el movimiento a la lista
        }

        return $movimientos; // Retornar el array de movimientos
    }
}