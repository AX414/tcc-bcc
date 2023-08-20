<?php

function estalogado() {
    if (isset($_SESSION['nome_login'])) {
        return true;
    }
}

function menu_tela_principal() {
    if (estalogado() == true) {
        echo '<nav class="navbar navbar-expand-lg">
            <a class="navbar-brand" style="margin-left: 2%">Bem vindo, ' . $_SESSION['nome_login'] . '!</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Usuários
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="#">Cadastrar</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Visualizar</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            EMAs
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="#">Cadastrar</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Apresentar Relatórios</a>
                        </div>
                    </li>
               <li class="nav-item">
                <form action="../portalEMA/controllers/logout.php">
                    <button style="position: absolute; top: 20%; right: 10px;" class="btn btn-danger" type="submit">Logout</button>
                </form>
                </li>
                </ul>
            </div>
        </nav>';
    } else {
        echo '<nav class="navbar navbar-expand-lg">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                <form action="../portalEMA/Tela_Login.php">
                    <button style="position: absolute; top: 20%; right: 10px;" class="btn btn-outline-light" type="submit">Login</button>
                </form>
                </li>
                </ul>
            </div>
        </nav>';
    }
}
?>

