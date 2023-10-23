<!DOCTYPE html>
<?php
require '../portalEMA/functions/geral.php';
require '../portalEMA/controllers/controller_observacao.php';
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
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
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
            <h1>Histórico de Observações Meteorológicas da <?php echo $ema['nome'] ?></h1>
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
                    <div class="col">
                        <label></label>
                        <a href="../portalEMA/functions/download_historico_csv.php?idema=<?php echo $emaID; ?>&&nome=<?php echo $ema['nome']; ?>" class="btn btn-primary form-control">
                            <i class="fas fa-download"></i> 
                            Histórico em CSV
                        </a>
                    </div>
                    <div class="col">
                        <label></label>
                        <button name="btn-filtrar" type="submit" class="btn btn-primary form-control">Filtrar</button>
                    </div>
                </div>
            </form>

            <table id="datatable" class="table table-responsive" style="width:100%"
                   <thead>
                    <tr>
                        <th>ID</th>
                        <th>Data da Leitura</th>
                        <th>Hora da Leitura</th>
                        <th>Temperatura</th>
                        <th>Umidade</th>
                        <th>Velocidade do Vento</th>
                        <th>Direção do Vento</th>
                        <th>Visualizar a Leitura</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    listarObservacoes($ema['idema']);
                    ?>
                </tbody>
            </table>
        </div>
        <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script>
            //new DataTable('#datatable');
        </script>
    </body>
</html>

