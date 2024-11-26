<?php

class Seccion {
    private $idSeccion;
    private $nombre;
    private $descripcion;

    public function __construct($nombre, $descripcion, $idSeccion = null) {
        $this->idSeccion = $idSeccion;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
    }

    // Getters
    public function getIdSeccion() {
        return $this->idSeccion;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    // Setters
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }
}