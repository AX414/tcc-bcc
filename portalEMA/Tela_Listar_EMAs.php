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
            background-color: #28b498;
            z-index: 2;
            filter: drop-shadow(2px 2px 2px grey);
        }
    </style>
    <body>
        <?php
        menu();
        ?>
        <div class="container">
            <h1>Lista de EMAs</h1>
            <form method="POST" action="">
                <div>
                    <label for="nome">Nome da Estação</label>
                    <input type="text" name="nome" class="form-control">
                </div>
            </form>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>IP</th>
                            <th>Pública/Privada</th>
                            <th>Latitude</th> 
                            <th>Logitude</th>
                            <th>Nome do Dono</th>
                            <th>Certificado SSL</th>
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
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            //Excluir, e tauz
        </script>
    </body>
</html>
