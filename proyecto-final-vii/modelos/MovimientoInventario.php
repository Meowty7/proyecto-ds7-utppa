<?php
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
    public function setCantidad($cantidad) { $this->cantidad = $cantidad; }
}