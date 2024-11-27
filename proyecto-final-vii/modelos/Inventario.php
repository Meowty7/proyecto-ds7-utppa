<?php
require_once '../config/ConexionBD.php';
class Inventario {
    private $idInventario;
    private $parte;
    private $marca;
    private $modelo;
    private $fecha;  // Año representado como un número entero de 4 dígitos
    private $cantidad;
    private $costo;
    private $idSeccion;
    private $imagen;

    public function __construct($parte, $marca, $modelo, $fecha, $cantidad, $costo, $idSeccion, $imagen = null, $idInventario = null) {
        $this->idInventario = $idInventario;
        $this->parte = $parte;
        $this->marca = $marca;
        $this->modelo = $modelo;
        $this->fecha = $fecha;  // Año como un número entero de 4 dígitos (ejemplo: 2023)
        $this->cantidad = $cantidad;
        $this->costo = $costo;
        $this->idSeccion = $idSeccion;  // Sección a la que pertenece esta parte del inventario
        $this->imagen = $imagen;  // Ruta o URL de la imagen de la parte del auto
    }

    // Getters y Setters
    public function getIdInventario() { return $this->idInventario; }
    public function getParte() { return $this->parte; }
    public function getMarca() { return $this->marca; }
    public function getModelo() { return $this->modelo; }
    public function getFecha() { return $this->fecha; }
    public function getCantidad() { return $this->cantidad; }
    public function getCosto() { return $this->costo; }
    public function getIdSeccion() { return $this->idSeccion; }
    public function getImagen() { return $this->imagen; }

    public function setParte($parte) { $this->parte = $parte; }
    public function setMarca($marca) { $this->marca = $marca; }
    public function setModelo($modelo) { $this->modelo = $modelo; }

    /**
     * @throws Exception
     */
    public function setFecha($fecha) {
        if (is_int($fecha) && $fecha >= 1901 && $fecha <= 2155) {
            $this->fecha = $fecha;
        } else {
            throw new Exception("La fecha debe ser un número entero de 4 dígitos con una fecha real.");
        }
    }

    public function setCantidad($cantidad) { $this->cantidad = $cantidad; }
    public function setCosto($costo) { $this->costo = $costo; }
    public function setIdSeccion($idSeccion) { $this->idSeccion = $idSeccion; }
    public function setImagen($imagen) { $this->imagen = $imagen; }
}

class InventarioAcciones {

    // Obtener un inventario por su ID
    public function obtenerPorId($idInventario): array
    {
        $conexion = ConexionBD::obtenerConexion();
        $sql = "SELECT * FROM inventario WHERE idInventario = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $idInventario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        $inventarios = []; // Lista que almacenará los resultados

        if ($resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $inventarios[] = new Inventario(
                    $fila['parte'],
                    $fila['marca'],
                    $fila['modelo'],
                    $fila['fecha'],
                    $fila['cantidad'],
                    $fila['costo'],
                    $fila['idSeccion'],
                    $fila['imagen'],
                    $fila['idInventario']
                );
            }
        }

        return $inventarios;  // Retorna una lista (array) de objetos Inventario
    }

    // Crear un nuevo inventario
    public function crear(Inventario $inventario): bool
    {
        $conexion = ConexionBD::obtenerConexion();
        $sql = "INSERT INTO inventario (parte, marca, modelo, fecha, cantidad, costo, idSeccion, imagen) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);

        // Los parámetros que insertaremos
        $params = [
            $inventario->getParte(),
            $inventario->getMarca(),
            $inventario->getModelo(),
            $inventario->getFecha(),
            $inventario->getCantidad(),
            $inventario->getCosto(),
            $inventario->getIdSeccion(),
            $inventario->getImagen()
        ];

        $stmt->bind_param("ssssiiis", ...$params);  // "ssssiiis" corresponde a los tipos de datos (strings e integers)

        if ($stmt->execute()) {
            // Si el inventario se crea correctamente, devolver el ID asignado
            $inventario->setIdInventario($stmt->insert_id);
            return true;
        } else {
            return false;
        }
    }

    // Actualizar un inventario existente
    public function actualizar(Inventario $inventario): bool
    {
        if ($inventario->getIdInventario()) {
            $conexion = ConexionBD::obtenerConexion();
            $sql = "UPDATE inventario SET parte = ?, marca = ?, modelo = ?, fecha = ?, cantidad = ?, costo = ?, 
                    idSeccion = ?, imagen = ? WHERE idInventario = ?";
            $stmt = $conexion->prepare($sql);

            // Los parámetros que vamos a actualizar
            $params = [
                $inventario->getParte(),
                $inventario->getMarca(),
                $inventario->getModelo(),
                $inventario->getFecha(),
                $inventario->getCantidad(),
                $inventario->getCosto(),
                $inventario->getIdSeccion(),
                $inventario->getImagen(),
                $inventario->getIdInventario()
            ];

            $stmt->bind_param("ssssiiisi", ...$params);  // "ssssiiisi" corresponde a los tipos de datos (strings e integers)

            return $stmt->execute();  // Retorna si la ejecución fue exitosa
        }
        return false;
    }

    // Obtener todos los inventarios
    public function obtenerTodos(): array
    {
        $conexion = ConexionBD::obtenerConexion();
        $sql = "SELECT * FROM inventario";
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->get_result();

        $inventarios = []; // Lista que almacenará los resultados

        while ($fila = $resultado->fetch_assoc()) {
            $inventarios[] = new Inventario(
                $fila['parte'],
                $fila['marca'],
                $fila['modelo'],
                $fila['fecha'],
                $fila['cantidad'],
                $fila['costo'],
                $fila['idSeccion'],
                $fila['imagen'],
                $fila['idInventario']
            );
        }

        return $inventarios;  // Devuelve todos los inventarios como un array de objetos Inventario
    }
}