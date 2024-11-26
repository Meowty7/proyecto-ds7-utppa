<?php

final class Encriptador {

    /**
     * @throws Exception
     */
    public static function encriptarContrasena($password) {
        try {
            return password_hash($password, PASSWORD_BCRYPT);
        } catch (Exception $e) {
            throw new Exception("Error al encriptar la contraseña: " . $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public static function verificarContrasena($password, $hash): bool
    {
        try {
            return password_verify($password, $hash);
        } catch (Exception $e) {
            throw new Exception("Error al verificar la contraseña: " . $e->getMessage());
        }
    }

}