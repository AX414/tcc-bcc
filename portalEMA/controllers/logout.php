<?php
   session_start();
   if(isset($_SESSION['nome_login'])){
       session_destroy();
       header('location: ../Tela_Principal.php');
   }
?>