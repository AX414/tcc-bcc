<?php
session_start();
function estalogado() {
    if (isset($_SESSION['nome_login'])) {
        return true;
    }
}

function menu() {
    if (estalogado()) {
        echo '<nav class="navbar navbar-expand-lg">
            <a class="navbar-brand" style="margin-left: 2%" href="../portalEMA/Tela_Principal.php">Bem vindo, ' . $_SESSION['nome_login'] . '!</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">';
                if($_SESSION['nivel_acesso'] == "1" ){ 
                    echo'<li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Usuários
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="../portalEMA/Tela_Cadastro_Usuario.php">Cadastrar Usuário</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="../portalEMA/Tela_Listar_Usuarios.php">Listar Usuários</a>
                        </div>
                    </li>';
                }
                echo'    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            EMAs
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="#">Cadastrar EMA</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Apresentar EMAs</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Efetuar Diagnóstico</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Apresentar Relatórios</a>
                        </div>
                    </li>
               <li class="nav-item">
                <form action="../portalEMA/functions/logout.php">
                    <button style="position: absolute; top: 20%; right: 10px; width:8%;" class="btn btn-danger" type="submit"><i style="padding-right: 3%;" class="fas fa-sign-out"></i>Logout</button>
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
                    <button style="position: absolute; top: 20%; right: 10px; width:8%;" class="btn btn-outline-light" type="submit"><i style="padding-right: 3%;" class="fas fa-sign-in"></i>Login</button>
                </form>
                </li>
                </ul>
            </div>
        </nav>';
    }
}
?>

