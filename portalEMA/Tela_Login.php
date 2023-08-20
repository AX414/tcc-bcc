<!DOCTYPE html>
<?php
session_start();
?>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <link rel="icon" href="https://static.thenounproject.com/png/851351-200.png">
        <title>Portal EMA - Tela de Login</title>
        <link rel="stylesheet" type="text/css" href="./css/style.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    </head>
    <body class="text-center">
        <form class="form-signin" method="POST" action="./controllers/login.php">
            <img class="mb-4" src="https://static.thenounproject.com/png/851351-200.png" alt="" width="72" height="72">
            <h1 class="h3 mb-3 font-weight-normal">Login</h1>
            <input type="text" name="email" class="form-control" placeholder="Email" required>
            <input type="password" name="senha" class="form-control" placeholder="Senha" required>
            <select name="nivel_acesso" class="form-control form-select">
                <option value="1" selected>Administrador</option>
                <option value="2">Cliente</option>
            </select>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Entrar</button>
        </form>
    </body>
</html>
