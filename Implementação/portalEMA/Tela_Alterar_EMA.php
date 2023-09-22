<!DOCTYPE html>
<?php
require '../portalEMA/functions/geral.php';
require '../portalEMA/controllers/controller_ema.php';
?>
<html>
    <head>
        <meta charset="utf-8">
        <title>Portal EMA - Alterar EMA</title>
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

        $emaId = $_GET['idema'];

        $ema = buscarEMAPorId($emaId);

        if (!$ema) {
            echo "Estação não encontrada.";
            exit;
        }

        echo '<div class="card col-6">';
        echo '<div class="card-header">';
        echo '<i style="padding-right: 1%;" class="fas fa-microchip"></i><b>Alterar dados da EMA</b>';
        echo '</div>';
        echo '<div class="card-body">';
        echo '<form method="POST" action="../portalEMA/controllers/controller_ema.php?idema='.$ema['idema'].'">';
        echo '<div class="form-row">';
        echo '<div class="form-group">';
        echo '<label>Nome da Estação</label>';
        echo '<input value="' . $ema['nome'] . '" name="nome" type="text" class="form-control" placeholder="Nome da Estação">';
        echo '<label>IP</label>';
        echo '<input value="' . $ema['ip'] . '" name="ip" type="text" class="form-control" placeholder="IP da Estação">         ';
        echo '<label>Latitude</label>';
        echo '<input value="' . $ema['latitude'] . '" name="latitude" type="text" class="form-control" placeholder="Latidude da posição da Estação">';
        echo '<label>Longitude</label>';
        echo '<input value="' . $ema['longitude'] . '" name="longitude" type="text" class="form-control" placeholder="Longitude da posição da Estação">';
        echo '<label>Deseja que os relatórios da estação sejam compartilhados com outros usuários?</label>';
        echo '<select name="publica" class="form-control form-select">';
        echo '<option value="0"' . ($ema['publica'] == 0 ? ' selected' : '') . '>Não</option>';
        echo '<option value="1"' . ($ema['publica'] == 1 ? ' selected' : '') . '>Sim</option>';
        echo '</select>';
        echo '</div>';
        echo '<br>';
        echo '<button name="btn-alterar-ema" style="width: 100%" class="btn btn-warning btn-block" type="submit"><i style="padding-right: 1%;" class="fas fa-pencil"></i>Alterar</button>';
        echo '</div>';
        echo '</form>';
        echo '</div>';
        echo '</div>';
        ?>



    </body>
</html>
