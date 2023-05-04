<?php
    # Iniciando a sessão
    if(!isset($_SESSION)){
        session_start();
    }
    
    if(!isset($_SESSION['id'])){
        die("Acesso não autenticado identificado."
                . "<br><br><p><a href=\"Tela_Login.php\">Efetuar Login</a></p>");
    }
?>
