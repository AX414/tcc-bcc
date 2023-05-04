<?php
    # Iniciando a sessão
    if(!isset($_SESSION)){
        session_start();
    }
    
    session_destroy();
    
    header('Location: Tela_Login.php');
?>