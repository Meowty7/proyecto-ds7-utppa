<?php
require_once 'config.php';

class ConexionBD {
    private static $conexion = null;

    public static function obtenerConexion() {
        if (self::$conexion == null) {
            self::$conexion = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

            if (self::$conexion->connect_error) {
                die("Error de conexión: " . self::$conexion->connect_error);
            }
        }
        return self::$conexion;
    }
}