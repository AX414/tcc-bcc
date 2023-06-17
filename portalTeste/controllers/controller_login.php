<?php

// session_start inicia a sessão
require '../functions/banco.php';
require '../functions/funcoesLogin.php';
session_start();
// as variáveis login e senha recebem os dados digitados na página anterior
$conexao = conectarBanco();
$res = verificarLogin($conexao, $_POST);

if ($res == true) {
    $_SESSION ['email'] = $_POST['email'];
    $_SESSION ['senha'] = $_POST['senha'];
    $_SESSION ['nivel'] = $_POST['nivel'];
    header("Location:../Tela_Principal.php");
} else {
    header("Location:../Tela_Login.php");
}

if ($acao == "logout") {
    session_destroy();
    header("Location:../Tela_Login.php");
}
?>
