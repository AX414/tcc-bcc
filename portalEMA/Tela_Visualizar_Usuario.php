<!DOCTYPE html>
<?php
require '../portalEMA/functions/geral.php';
?>
<html>
    <head>
        <meta charset="utf-8">
        <title>Portal EMA - Dados do Usuário</title>
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
            background-color: #28b498;
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
        ?>

        <div class="card col-6">
            <div class="card-header">
                <i style="padding-right: 1%;" class="fas fa-user"></i><b>Cadastro do Usuário</b>
            </div>
            <div class="card-body">
                <form method="POST" action="../portalEMA/controllers/controller_usuario.php">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Nome do Usuário</label>
                            <input name="nome_usuario" type="text" class="form-control" placeholder="Nome do Usuário">

                            <label>Nome de Login</label>
                            <input name="nome_login" type="text" class="form-control" placeholder="Nome do Usuário">

                            <label>Email</label>
                            <input name="email" type="text" class="form-control" placeholder="Email">

                            <label>Senha</label>
                            <input name="senha" type="password" class="form-control" placeholder="Senha">

                            <label>Nível de Acesso</label>
                            <select name="nivel_acesso" class="form-control form-select">
                                <?php
                                if ($_SESSION['nivel_acesso'] == 1) {
                                    echo '<option value="1" selected>Administrador</option>';
                                }
                                ?>
                                <option value="2">Cliente</option>
                            </select>
                        </div>
                        <br>
                        <button name="btn-cadastro" style="width: 100%" class="btn btn-primary btn-block" type="submit"><i style="padding-right: 1%;" class="fas fa-circle-plus"></i>Cadastrar</button>
                    </div>
                </form>
            </div>
        </div>

    </body>
</html>
