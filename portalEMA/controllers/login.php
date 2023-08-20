<?php
require '../functions/banco.php';
session_start();
$email = $_POST['email'];
$senha = $_POST['senha'];
$nivel_acesso = $_POST['nivel_acesso'];
if (count($_POST) > 0) {
    $conexao = conectarBanco();
    $query = "SELECT * FROM usuarios WHERE email =  '$email' AND senha = '$senha' AND nivel_acesso = '$nivel_acesso'";
    $result = mysqli_query($conexao, $query);
    $row = mysqli_fetch_array($result);
    if (is_array($row)) {
        $_SESSION["idusuario"] = $row['idusuario'];
        $_SESSION["nome_usuario"] = $row['nome_usuario'];
        $_SESSION["nome_login"] = $row['nome_login'];
        $_SESSION["email"] = $row['email'];
        $_SESSION["senha"] = $row['senha'];
        $_SESSION["nivel_acesso"] = $row['nivel_acesso'];
        header("Location:../Tela_Principal.php");
    } else {
        echo"<script language='javascript' type='text/javascript'>
        alert('Dados incorretos');window.location.href='../Tela_Login.php';</script>";
        die();
    }
}
?>