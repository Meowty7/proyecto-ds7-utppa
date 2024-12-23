<?php
session_start();

// Verificar si el usuario ya está autenticado
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenidos</title>
    <style>
        body {
            text-align: center;
            font-family: Arial, sans-serif;

            background-image: url('https://wallpaper.dog/large/20624419.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        /* Estilos para los cuadros */
        .cuadro {
            width: 400px;
            height: 150px;
            background-image: url('https://www.shutterstock.com/image-photo/admin-word-written-on-wooden-260nw-1948964320.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            text-align: center;
            line-height: 150px;
            margin: 20px;
            font-size: 18px;
            font-weight: bold;
            float: left;
            border-radius: 10px;
            transition: 0.3s;
            position: relative;
        }

        .cuadro a {
            color: white;
            text-decoration: none;
            display: block;
            height: 100%;
            width: 100%;
            font-size: 20px; /* Tamaño de letra */
            font-weight: 700; 
        }



        .cuadro:hover {
            background-color: #45a049;
            transform: scale(1.05);
        }

        /* Para asegurarnos de que los cuadros estén alineados correctamente */
        .container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }
        nav {
            background-color: #04858c;
            color: white;
            padding: 10px 20px;
        }
        footer {
            margin-top: 40px;
            padding: 20px;
            background-color:#04858c;
            color: white;
            border-radius: 8px;
            width: 100%;
            text-align: left;
        }

    </style>
</head>
<body>
<nav>
    <center><h1 style="color:white; font-size: 30px;">Bienvenidos</h1></center>

</nav>
<center><h1 style="color:white";>Redirecciónes</h1></center>

<div class="container">
    <div class="cuadro">
        <a href="ModificarUsuarios.php">Lista Administradores</a>
    </div>
    <div class="cuadro">
        <a href="demo.php">Registro de inventario de partes de autos </a>
    </div>
    <div class="cuadro">
        <a href="Registro.php">Registro Usuarios </a>
    </div>
    
</div>
<div class="container">

    <div class="cuadro">
        <a href="MovimientoDeInventario.php"> Movimientos de inventario del rastro</a>
    </div>
    <div class="cuadro">
        <a href="cerrar_sesion.php">Salir</a>
    </div>
    <div class="cuadro">
        <a href="inventario.php">Gestion Inventario</a>
    </div>
</div>

<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
</div>
<footer>
    © <span id="year"></span> PROYECTO FINAL. All rights reserved. <br>
    Contacto: contacto@itech.com
</footer>
<script>
    document.getElementById("year").textContent = new Date().getFullYear();
</script>
</body>
</html>