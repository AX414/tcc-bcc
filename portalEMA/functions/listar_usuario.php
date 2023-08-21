<?php

require '../portalEMA/functions/banco.php';

$conexao = conectarBanco();

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém os valores dos campos de filtro
    $nome_usuario = $_POST['nome_usuario'];

    // Monta a consulta SQL com base nos filtros
    $sql = "SELECT * FROM usuarios WHERE 1 = 1";

    if (!empty($nome_usuario)) {
        $sql .= " AND nome_usuario LIKE '$nome_usuario'";
    }

    $result = $conexao->query($sql);
} else {
    // Consulta SQL para obter todos os dados
    $sql = 'SELECT * FROM usuarios';
    $result = $conexao->query($sql);
}

// Verifica se há resultados e exibe na tabela
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
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
        echo '<button type="button" class="btn btn-danger btn-sm" onclick="excluirUsuario()">';
        echo '<i class="fas fa-trash"></i>';
        echo '</button>';
        echo '<button type="button" class="btn btn-warning btn-sm" onclick="alterarUsuario()">';
        echo '<i class="fas fa-pencil"></i>';
        echo '</button>';
        echo '</td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="7">Nenhum dado encontrado.</td></tr>';
}

// Fecha a conexão com o banco de dados
$conexao->close();
?>
