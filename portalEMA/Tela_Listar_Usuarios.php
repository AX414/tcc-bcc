<!DOCTYPE html>
<?php
require '../portalEMA/functions/geral.php';
session_start();
?>
<html>
    <head>
        <meta charset="utf-8">
        <title>Portal EMA - Lista de Usuários</title>
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
            <h1>Lista de Usuários</h1>
            <form method="POST" action="">
                <div>
                    <label for="dataInicial">Nome do usuário</label>
                    <input type="text" name="nome_usuario" class="form-control">
                </div>
            </form>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome do Usuário</th>
                            <th>Nome de login</th>
                            <th>E-mail</th>
                            <th>Nível de Acesso</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require '../portalEMA/functions/listar_usuario.php';
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>
