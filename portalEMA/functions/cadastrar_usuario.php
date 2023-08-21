<?php
session_start();
require '../functions/banco.php';

if (isset($_POST['nome_usuario']) && isset($_POST['nome_login']) && isset($_POST['email']) && isset($_POST['senha']) && isset($_POST['nivel_acesso'])
) {
    $nome_usuario = $_POST['nome_usuario'];
    $nome_login = $_POST['nome_login'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $hash = password_hash($senha, PASSWORD_ARGON2ID);
    $nivel_acesso = $_POST['nivel_acesso'];

    $conexao = conectarBanco();

    $query_select = "SELECT nome_usuario FROM usuarios WHERE nome_usuario = '$nome_usuario' OR nome_login = '$nome_login' OR email = '$email'";
    $select = mysqli_query($conexao, $query_select);

    if (mysqli_num_rows($select) > 0) {
        echo "<script>alert('Nome de usuário, nome de login ou email já existe.');window.location.href='../Tela_Cadastro_Usuario.php';</script>";
        die();
    } else {
        $query = "INSERT INTO usuarios(nome_usuario, nome_login, email, senha, nivel_acesso) VALUES ('$nome_usuario', '$nome_login', '$email', '$hash', '$nivel_acesso')";
        $insert = mysqli_query($conexao, $query);

        if ($insert) {
            echo "<script>alert('Usuário cadastrado com sucesso!');window.location.href='../Tela_Cadastro_Usuario.php';</script>";
        } else {
            echo "<script>alert('Não foi possível cadastrar esse usuário');window.location.href='../Tela_Cadastro_Usuario.php';</script>";
        }
    }

    mysqli_close($conexao);
}
