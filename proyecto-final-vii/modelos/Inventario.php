<?php
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