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

    private $descripcion;

    public function __construct($parte, $marca, $modelo, $fecha, $cantidad, $costo, $idSeccion, $imagen = null, $idInventario = null, $descripcion = "") {
        $this->descripcion = $descripcion;
        $this->idInventario = $idInventario;
        $this->parte = $parte;
        $this->marca = $marca;
        $this->modelo = $modelo;
        $this->fecha = $fecha;
        $this->cantidad = $cantidad;
        $this->costo = $costo;
        $this->idSeccion = $idSeccion;
        $this->imagen = $imagen;
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

    public function setParte($parte): void
    { $this->parte = $parte; }
    public function setMarca($marca): void
    { $this->marca = $marca; }
    public function setModelo($modelo): void
    { $this->modelo = $modelo; }

    /**
     * @throws Exception
     */
    public function setFecha($fecha): void
    {
        if (is_int($fecha) && $fecha >= 1901 && $fecha <= 2155) {
            $this->fecha = $fecha;
        } else {
            throw new Exception("La fecha debe ser un número entero de 4 dígitos con una fecha real.");
        }
    }

    public function setCantidad($cantidad): void
    { $this->cantidad = $cantidad; }
    public function setCosto($costo): void
    { $this->costo = $costo; }
    public function setIdSeccion($idSeccion): void
    { $this->idSeccion = $idSeccion; }
    public function setImagen($imagen): void
    { $this->imagen = $imagen; }

    public function setIdInventario($idInventario): void
    {
        $this->idInventario = $idInventario;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion): void
    {
        $this->descripcion = $descripcion;
    }
}

class InventarioAcciones {

    // Obtener un inventario por su ID
    public function obtenerPorId($idInventario): ?Inventario
    {
        $conexion = ConexionBD::obtenerConexion();
        $sql = "SELECT * FROM inventario WHERE idInventario = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $idInventario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $fila = $resultado->fetch_assoc();
                return new Inventario(
                    $fila['parte'],
                    $fila['marca'],
                    $fila['modelo'],
                    $fila['fecha'],
                    $fila['cantidad'],
                    $fila['costo'],
                    $fila['idSeccion'],
                    $fila['imagen'],
                    $fila['idInventario'],
                    $fila['descripcion']
                );
        }
        return null;
    }

    public function obtenerInventarioPorParteYSeccion($parte, $idSeccion): ?Inventario
    {
        $conexion = ConexionBD::obtenerConexion();
        $sql = "SELECT * FROM inventario WHERE parte = ? AND idSeccion = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("si", $parte, $idSeccion);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $fila = $resultado->fetch_assoc();

            // Devolvemos un objeto Inventario con todos los parámetros necesarios
            return new Inventario(
                $fila['parte'],            // Parte
                $fila['marca'],            // Marca
                $fila['modelo'],           // Modelo
                $fila['fecha'],            // Fecha
                $fila['cantidad'],         // Cantidad
                $fila['costo'],            // Costo
                $fila['idSeccion'],        // idSeccion
                $fila['imagen'],           // Imagen
                $fila['idInventario'],     // idInventario
                $fila['descripcion']       // Descripción
            );
        }
        return null;  // No existe inventario para esa parte en esa sección
    }

    // Crear un nuevo inventario
    public function crear(Inventario $inventario): bool
    {
        $conexion = ConexionBD::obtenerConexion();
        $sql = "INSERT INTO inventario (parte, marca, modelo, fecha, cantidad, costo, idSeccion, imagen, descripcion) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
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
            $inventario->getImagen(),
            $inventario->getDescripcion()
        ];

        $stmt->bind_param("ssssiiiss", ...$params);  // "ssssiiis" corresponde a los tipos de datos (strings e integers)

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
                idSeccion = ?, imagen = ?, descripcion = ? WHERE idInventario = ?";
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
                $inventario->getDescripcion(),
                $inventario->getIdInventario()  // Este parámetro solo lo debes pasar una vez al final
            ];

            // Ajustar los tipos en bind_param:
            // "ssssiiissi" corresponde a los tipos de los parámetros
            $stmt->bind_param("ssssiiissi", ...$params);  // Ahora hay 10 parámetros, y el tipo es el correcto.
            if(!$stmt->execute()){
                echo 'PAYALA BESTIA';
            }
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
                $fila['idInventario'],
                $fila['descripcion']
            );
        }

        return $inventarios;  // Devuelve todos los inventarios como un array de objetos Inventario
    }
}
