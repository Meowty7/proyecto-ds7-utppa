<?php
session_start(); // Iniciar la sesión al principio
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Registro de Partes de Autos</title>
    <style>
        /* Estilos para el formulario */
            body {
                text-align: center;
                font-family: Arial, sans-serif;
                background-image: url('https://stories.weroad.es/wp-content/uploads/2019/11/Fuji.jpg');
                background-size: cover;
                background-repeat: no-repeat;
                background-attachment: fixed;
            }

            .form-container {
                background-color: white;
                width: 70%;
                max-width: 500px;
                margin: 50px auto;
                padding: 20px;
                box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.3);
                border-radius: 10px;
            }

            .container {
            width: 85%;
            margin: auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #04858c;
            color: white;
        }
        .search-bar {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }
           

        </style>
</head>
<body>

    <button onclick="window.location.href='index.php'"style="position: absolute; top: 10px; left: 110px; background-color: #04858c; color: white; border: none; padding: 10px 20px; text-align: center; text-decoration: none; font-size: 16px; cursor: pointer;">Pagina Inicio</button>
   
    <?php
    // Mostrar el mensaje de sesión si existe
    if (isset($_SESSION['mensaje'])) {
        echo "<p>" . $_SESSION['mensaje'] . "</p>";
        unset($_SESSION['mensaje']); // Limpiar el mensaje después de mostrarlo
    }
    ?>
      
      <div class="container">
    <table>
        <thead>
            <tr>
                <th>ID Usuario</th>
                <th>Usuario</th>
                <th>Apellido</th>
                <th>Contraseña</th>
                <th>Cédula</th>
                <th>Correo</th>
                <th>Activo</th>
             
            </tr>
        </thead>
        <tbody>
        <?php
        require_once '../models/Usuarios.php';

        $usuariosTabla = new UsuariosTabla();
        $usuariosTabla->generarFilasUsuarios();
        ?>

            </tbody>
    </table>
   
    </div>

    <script>
        // Añadir acción al formulario (puedes personalizarla para que guarde los datos)
        document.getElementById('auto-part-form').addEventListener('submit', function(event) {
            event.preventDefault();
            alert('Parte registrada correctamente.');
        });
    </script>
</body>
</html>
