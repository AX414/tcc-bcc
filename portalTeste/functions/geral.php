<?php

function estaLogado() {
    if (isset($_SESSION['email'])&&isset($_SESSION['senha'])&&isset($_SESSION['nivel'])) {
        return true;
    } else {
        return false;
    }
}

?>