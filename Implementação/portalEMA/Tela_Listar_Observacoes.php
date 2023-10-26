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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
        <link rel="stylesheet" type="text/css" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
        <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    </head>
    <style>
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 2;
            filter: drop-shadow(2px 2px 2px grey);
        }
    </style>
    <body>
        <?php
        menu();
        ?>
        <div class="container" style="height: 100%;margin-top: 10%;">
            <h1 class="mb-4">Histórico de Observações Meteorológicas da:<br> <?php echo $ema['nome'] ?></h1>
                <div class="row justify-content-between">
                    <div class="col" style="margin-bottom: 30px">
                        <a href="../portalEMA/functions/download_historico_csv.php?idema=<?php echo $emaID; ?>&&nome=<?php echo $ema['nome']; ?>"
                           class="btn btn-primary">
                            <i class="fas fa-download"></i>
                            Histórico em CSV
                        </a>
                    </div>
                </div>

            <table id="observacoesDT" class="table table-hover" style="width:100%;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Data da Leitura</th>
                        <th> Hora da Leitura</th>
                        <th>Temperatura</th>
                        <th>Umidade</th>
                        <th>Velocidade do Vento</th>
                        <th>Direção do Vento</th>
                        <th>Visualizar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    listarObservacoes($ema['idema']);
                    ?>
                </tbody>
            </table>
        </div>
        <script>
            $(document).ready(function () {
                $('#observacoesDT').DataTable({
                    "language": {
                        "lengthMenu": "Exibir _MENU_ registros por página",
                        "paginate": {
                            "next": "Próxima",
                            "previous": "Anterior",
                        },
                        "info": "Exibindo _START_ a _END_ de _TOTAL_ registros",
                        "search": "Pesquisar:", // Traduzindo o rótulo de pesquisa
                    },
                    "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]], // Personalizando o seletor de registros
                    "search": {
                        "search": "", // Desativar o cache de pesquisa
                    }
                });
            });
        </script>
    </body>
</html>
