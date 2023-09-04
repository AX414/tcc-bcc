<?php

if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require(__DIR__ . '/../functions/banco.php');

if (isset($_POST['btn-cadastro-usuario'])) {
    cadastrarUsuario();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    excluirUsuario();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    visualizarUsuario();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    alterarUsuario();
}

function cadastrarUsuario() {
    if (isset($_POST['nome_usuario']) && isset($_POST['nome_login']) && isset($_POST['email']) && isset($_POST['senha']) && isset($_POST['nivel_acesso'])
    ) {
        $nome_usuario = $_POST['nome_usuario'];
        $nome_login = $_POST['nome_login'];
        $email = $_POST['email'];
        $senha = $_POST['senha'];
        $hash = password_hash($senha, PASSWORD_ARGON2ID);
        $nivel_acesso = $_POST['nivel_acesso'];

        $conexao = conectarBanco();

        $query_select = "SELECT nome_usuario FROM usuarios WHERE nome_usuario = '$nome_usuario' OR nome_login = '$nome_login' OR email = '$email'";
        $select = mysqli_query($conexao, $query_select);

        if (mysqli_num_rows($select) > 0) {
            echo "<script>alert('Nome de usuário, nome de login ou email já existe.');window.location.href='../Tela_Cadastro_Usuario.php';</script>";
            die();
        } else {
            $query = "INSERT INTO usuarios(nome_usuario, nome_login, email, senha, nivel_acesso) VALUES ('$nome_usuario', '$nome_login', '$email', '$hash', '$nivel_acesso')";
            $insert = mysqli_query($conexao, $query);

            if ($insert) {
                echo "<script>alert('Usuário cadastrado com sucesso!');window.location.href='../Tela_Cadastro_Usuario.php';</script>";
            } else {
                echo "<script>alert('Não foi possível cadastrar esse usuário');window.location.href='../Tela_Cadastro_Usuario.php';</script>";
            }
        }

        $conexao->close();
    }
}

function listarUsuarios() {
    $conexao = conectarBanco();
    // Verifica se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Obtém os valores dos campos de filtro
        $nome_usuario = $_POST['nome_usuario'];

        // Monta a consulta SQL com base nos filtros
        $sql = "SELECT * FROM usuarios WHERE 1 = 1";

        if (!empty($nome_usuario)) {
            $sql .= " AND nome_usuario LIKE '%$nome_usuario%'";
        }

        $result = $conexao->query($sql);
    } else {
        // Consulta SQL para obter todos os dados
        $sql = 'SELECT * FROM usuarios';
        $result = $conexao->query($sql);
    }

// Verifica se há resultados e exibe na tabela
    if ($result->num_rows >= 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<tr id="row-' . $row['idusuario'] . '">';
            echo '<td>' . $row['idusuario'] . '</td>';
            echo '<td>' . $row['nome_usuario'] . '</td>';
            echo '<td>' . $row['nome_login'] . '</td>';
            echo '<td>' . $row['email'] . '</td>';
            if ($row['nivel_acesso'] == 1) {
                echo '<td>Administrador</td>';
            } else {
                echo '<td>Cliente</td>';
            }
            echo '<td style="display: flex">';
            if ($row['idusuario'] != $_SESSION['idusuario']) {
                echo '<button name="btn-excluir" type="button" class="btn btn-danger btn-sm" onclick="excluirUsuario(' . $row['idusuario'] . ')">';
                echo '<i class="fas fa-trash"></i>';
                echo '</button>';
            } else {
                echo '<button name="btn-excluir" type="button" class="btn btn-disabled btn-sm">';
                echo '<i class="fas fa-trash"></i>';
                echo '</button>';
            }
            /*
            echo '<button type="button" class="btn btn-warning btn-sm" onclick="alterarUsuario(' . $row['idusuario'] . ')">';
            echo '<i class="fas fa-pencil"></i>';
            echo '</button>';
            echo '<button type="button" class="btn btn-primary btn-sm" onclick="visualizarUsuario(' . $row['idusuario'] . ')">';
            echo '<i class="fas fa-eye"></i>';
            echo '</button>';
            */
            echo '<button type="button" class="btn btn-warning btn-sm" onclick="">';
            echo '<i class="fas fa-pencil"></i>';
            echo '</button>';
            echo '<button type="button" class="btn btn-primary btn-sm" onclick="">';
            echo '<i class="fas fa-eye"></i>';
            echo '</button>';
            echo '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="7">Nenhum dado encontrado.</td></tr>';
    }

    // Fecha a conexão com o banco de dados
    $conexao->close();
}

function excluirUsuario() {
    if (isset($_POST['idusuario'])) {
        $idusuario = $_POST['idusuario'];

        $conexao = conectarBanco();

        $query = "DELETE FROM usuarios WHERE idusuario = '$idusuario'";
        $delete = mysqli_query($conexao, $query);

        if ($delete) {
            echo 'Usuário excluído com sucesso!';
        } else {
            echo 'Erro ao excluir o usuário.';
        }

        $conexao->close();
    }
}

function visualizarUsuario() {
    if (isset($_POST['idusuario'])) {
        $idusuario = $_POST['idusuario'];

        $conexao = conectarBanco();

        $query = "SELECT * FROM usuarios WHERE idusuario = '$idusuario'";
        $select = mysqli_query($conexao, $query);


        $conexao->close();
    }
}

function alterarUsuario(){
    
}
?>
