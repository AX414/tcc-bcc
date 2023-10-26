<!DOCTYPE html>
<?php
require '../portalEMA/functions/geral.php';
require '../portalEMA/controllers/controller_ema.php';
?>
<html>
    <head>
        <meta charset="utf-8">
        <title>Portal EMA - Lista de EMAs</title>
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
            <h1 class="mb-4">Lista de EMAs</h1>
            <table id="emasDT" class="table table-hover" style="width:100%;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>IP</th>
                        <th>Pública/Privada</th>
                        <th>Latitude</th> 
                        <th>Longitude</th>
                        <th>Nome do Dono</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    listarEMAs();
                    ?>
                </tbody>
            </table>
        </div>
        <script>
            function excluirEMA(idema) {
                if (confirm('Tem certeza de que deseja excluir esta estação?')) {
                    $.ajax({
                        type: 'POST',
                        url: '../portalEMA/controllers/controller_ema.php',
                        data: {idema: idema},
                        success: function (response) {
                            alert(response);
                            // Recarregar a página ou atualizar a tabela das estações após a exclusão
                            location.reload();
                        },
                        error: function () {
                            alert('Erro ao excluir a estação.');
                        }
                    });
                }
            }
            
            $(document).ready(function () {
                $('#emasDT').DataTable({
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
