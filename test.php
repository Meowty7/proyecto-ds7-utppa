<?php

require_once  "proyecto-final-vii/Encriptador.php";

try {
    echo Encriptador::encriptarContrasena('1234');
} catch (Exception $e) {

}