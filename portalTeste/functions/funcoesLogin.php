<?php

function verificarLogin($conexao, $dados) {
    $email = $dados['email'];
    $senha = $dados['senha'];
    $nivel = $dados['nivel'];
    $consultaSQL = "SELECT * FROM usuarios WHERE email='$email' AND senha='$senha' AND nivel='$nivel'";
    $resultadoConsulta = mysqli_query($conexao, $consultaSQL);
    
    if (mysqli_num_rows($resultadoConsulta) > 0) { 
            echo "<script>console.log('$resultadoConsulta')</script>";
            return true;
    } else {
       return false;
    }
}

?>
