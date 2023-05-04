<?php
    $host = 'localhost';
    $user = 'root';
    $pwd = 'root';
    $db_name = 'aws-mqtt';
    
    $mysqli = new mysqli($host, $user, $pwd, $db_name);
    
    # Se ocorreu um erro, mata o código
    if($mysqli->error){
        die("Falha ao conectar ao banco de dados: ".$mysqli->error);
    }

?>
