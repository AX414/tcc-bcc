<?php
include('./connection_controller.php');

$email = $mysqli->real_escape_string($POST['email']);
$senha = $mysqli->real_escape_string($POST['senha']);
$nivel_senha = $mysqli->real_escape_string($POST['nivel_acesso']);

if(isset($email)||isset($senha)){
    if(strlen($email)==0){
        echo "<script>window.alert('Preencha o campo de email.')</script>";
    }else if(strlen($senha)==0){
        echo "<script>window.alert('Preencha o campo da senha.')</script>";
    }else{
        $code = "SELECT * FROM usuarios WHERE email = '$email' "
                . "AND senha = '$senha' "
                . "AND nivel_acesso = '$nivel_acesso'";
        
        $query = $mysqli->query($code) or die('Falha na execução do código SQL: '. $mysqli->error);
        $qtd = $query->num_rows;
        
        if($qtd == 1){
            $usuario = $query->fetch_assoc();
            if(!isset($_SESSION)){
                session_start();
            }
            
            # Criando sessão do usuário
            $_SESSION['id'] = $usuario['id'];
            $_SESSION['nome_login'] = $usuario['nome_login'];
            
            # Redirecionando o usuário
            header('Location: Tela_Principal.php');
            
            
        }else{
            echo "Falha ao efetuar o login.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <link rel="icon" href="https://static.thenounproject.com/png/851351-200.png">
        <title>Portal EMA - Tela de Login</title>
        <link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    </head>
    <style>
        html,
        body {
            height: 100%;
        }

        body {
            display: -ms-flexbox;
            display: -webkit-box;
            display: flex;
            -ms-flex-align: center;
            -ms-flex-pack: center;
            -webkit-box-align: center;
            align-items: center;
            -webkit-box-pack: center;
            justify-content: center;
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #f5f5f5;
        }

        .form-signin {
            width: 100%;
            max-width: 330px;
            padding: 15px;
            margin: 0 auto;
        }
        .form-signin .form-control {
            position: relative;
            box-sizing: border-box;
            height: auto;
            padding: 10px;
            font-size: 16px;
        }
        .form-signin .form-control:focus {
            z-index: 2;
        }
        .form-signin input[type="email"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }
        .form-signin input[type="password"] {
            border-top-left-radius: 0;
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }
        .form-signin select{
            margin-bottom: 20px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
    </style>
    <body class="text-center">
        <form class="form-signin" method="POST" action="Tela_Login.php">
            <img class="mb-4" src="https://static.thenounproject.com/png/851351-200.png" alt="" width="72" height="72">
            <h1 class="h3 mb-3 font-weight-normal">Login</h1>
            <label for="email" class="sr-only">Email</label>
            <input type="text" name="email" class="form-control" placeholder="Email" required autofocus>
            <label for="senha" class="sr-only">Senha</label>
            <input type="senha" name="senha" class="form-control" placeholder="Senha" required>
            <select name="nivel_acesso" class="form-control form-select">
                <option value="1" selected>Administrador</option>
                <option value="2">Cliente</option>
            </select>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Entrar</button>
        </form>
    </body>

</html>
