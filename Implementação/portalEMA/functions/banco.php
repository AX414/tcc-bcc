<?php

function conectarBanco() {
    $local = "localhost";
    $usuario = "root";
    $senha = "ifsp";
    $banco = "awsmqtt";
    $conexao = mysqli_connect($local, $usuario, $senha);
    $db = mysqli_select_db($conexao, $banco);

    if (mysqli_connect_errno() != 0) { //Se a quantidade de erros for diferente de 0, há um erro.
        echo "Erro ao estabelecer uma conexão com o Banco.<br>";
        echo mysqli_connect_error(); //Mostra o erro do banco.
        die(); //para a conexão caso haja erro.
    }
    return $conexao;
}

?>
