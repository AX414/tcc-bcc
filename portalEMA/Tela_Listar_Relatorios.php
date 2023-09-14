<!DOCTYPE html>
<?php
require '../portalEMA/functions/geral.php';
require '../portalEMA/controllers/controller_relatorio.php';
$emaID = $_GET['idema'];
$ema = buscarEMAPorID($emaID);
?>
<html>
    <head>
        <meta charset="utf-8">
        <title>Portal EMA - Histórico de Relatórios</title>
        <link rel="stylesheet" type="text/css" href="../portalEMA/css/style.css">
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
        <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    </head>
    <style>
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            height: 10%;
            z-index: 2;
            filter: drop-shadow(2px 2px 2px grey);
        }
    </style>
    <body>
        <?php
        menu();
        ?>
        <div class="container" style="margin-top: 5%;">
            <h1>Histórico de Relatórios da <?php echo $ema['nome']?></h1>
            <form method="POST" action="">
                <div class="row justify-content-between">
                    <div class="col">
                        <label for="data_inicial">Data Inicial:</label>
                        <input type="date" name="data_inicial" id="dataInicial" class="form-control">
                    </div>
                    <div class="col">
                        <label for="data_final">Data Final:</label>
                        <input type="date" name="data_final" id="dataFinal" class="form-control">
                    </div>
                    <div class="col">
                        <label for="hora_leitura">Hora:</label>
                        <input type="time" name="hora_leitura" id="hora" class="form-control">
                    </div>
                    <div class="col" style="align-self: flex-end;">
                        <button name="btn-filtrar" type="submit" class="btn btn-primary">Filtrar</button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Data da Leitura</th>
                            <th>Hora da Leitura</th>
                            <th>Umidade</th>
                            <th>Chuva</th>
                            <th>Velocidade do Vento</th>
                            <th>Direção do Vento</th>
                            <th>Visualizar a Leitura</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        listarRelatorios($ema['idema']);
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>
