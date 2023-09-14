<!DOCTYPE html>
<?php
require '../portalEMA/functions/geral.php';
require '../portalEMA/controllers/controller_usuario.php';
?>
<html>
    <head>
        <meta charset="utf-8">
        <title>Portal EMA - Alterar Usuário</title>
        <link rel="stylesheet" type="text/css" href="./css/style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    </head>
    <style>
        #map-container {
            position: relative;
            width: 100%;
            height: 100vh;
        }

        #map {
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            height: 10%;
            z-index: 2;
            filter: drop-shadow(2px 2px 2px grey);
        }

        .card{
            margin-top: 5%;
        }

        .form-control{
            margin-bottom: 3%;
        }
    </style>
    <body>
        <?php
        menu();

        $userId = $_GET['idusuario'];

        $usuario = buscarUsuarioPorId($userId);

        if (!$usuario) {
            echo "Usuário não encontrado.";
            exit;
        }

        echo '<div class="card col-6">';
        echo '<div class="card-header">';
        echo '<i style="padding-right: 1%;" class="fas fa-user"></i><b>Alterar dados do Usuário</b>';
        echo '</div>';
        echo '<div class="card-body">';
        echo '<form method="POST" action="../portalEMA/controllers/controller_usuario.php?idusuario='.$usuario['idusuario'].'">';
        echo '<div class="form-row">';
        echo '<div class="form-group">';
        echo '<label>Nome do Usuário</label>';
        echo '<input value="' . $usuario['nome_usuario'] . '" name="nome_usuario" type="text" class="form-control" placeholder="Nome do Usuário">';
        echo '<label>Nome de Login</label>';
        echo '<input value="' . $usuario['nome_login'] . '" name="nome_login" type="text" class="form-control" placeholder="Nome de Login">';
        echo '<label>Email</label>';
        echo '<input value="' . $usuario['email'] . '" name="email" type="text" class="form-control" placeholder="Email">';
        echo '</div>';
        echo '<br>';
        echo '<button name="btn-alterar-usuario" style="width: 100%" class="btn btn-warning btn-block" type="submit"><i style="padding-right: 1%;" class="fas fa-pencil"></i>Alterar</button>';
        echo '</div>';
        echo '</form>';
        echo '</div>';
        echo '</div>';
        ?>



    </body>
</html>
