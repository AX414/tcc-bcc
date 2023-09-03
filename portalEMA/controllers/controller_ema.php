<?php
session_start();
require(__DIR__ . '/../functions/banco.php');
require(__DIR__ . '/../functions/gerar_certificado_ssl.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    cadastrarEMA();
}

function cadastrarEMA() {
    if (isset($_POST['nome']) && isset($_POST['ip']) && isset($_POST['publica']) && isset($_POST['latitude']) && isset($_POST['longitude'])
    ) {
        $nome = $_POST['nome'];
        $ip = $_POST['ip'];
        $publica = $_POST['publica'];
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];
        $id_dono = $_SESSION['idusuario'];
        $certificado_ssl = gerarCertificadoSSL($nome);

        $conexao = conectarBanco();

        $query = "INSERT INTO emas(nome, ip, publica, latitude, longitude, usuarios_idusuario, certificado_ssl) "
                . "VALUES ('$nome', '$ip', '$publica', '$latitude', '$longitude', '$id_dono', '$certificado_ssl')";
        $insert = mysqli_query($conexao, $query);

        if ($insert) {
            echo "<script>alert('Estação Cadastrada com Sucesso!');window.location.href='../Tela_Cadastro_EMA.php';</script>";
        } else {
            echo mysqli_errno($conexao);
            echo "<script>alert('Não foi possível cadastrar essa estação, algo deu errado.');window.location.href='../Tela_Cadastro_EMA.php';</script>";
        }

        $conexao->close();
    }
}

function listarEMAs() {
    $conexao = conectarBanco();
    // Verifica se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Obtém os valores dos campos de filtro
        $nome = $_POST['nome'];

        // Monta a consulta SQL com base nos filtros
        $sql = "SELECT * FROM emas WHERE 1 = 1";

        if (!empty($nome_usuario)) {
            $sql .= " AND nome LIKE '%$nome%'";
        }

        $result = $conexao->query($sql);
    } else {
        // Consulta SQL para obter todos os dados
        $sql = 'SELECT * FROM emas';
        $result = $conexao->query($sql);
    }

    // Verifica se há resultados e exibe na tabela
    if ($result->num_rows >= 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<tr id="row-' . $row['idema'] . '">';
            echo '<td>' . $row['idema'] . '</td>';
            echo '<td>' . $row['nome'] . '</td>';
            echo '<td>' . $row['ip'] . '</td>';
            echo '<td>' . $row['publica'] . '</td>';
            echo '<td>' . $row['latitude'] . '</td>';
            echo '<td>' . $row['longitude'] . '</td>';
            echo '<td>' . $row['usuarios_idusuario'] . '</td>';
            echo '<td>' . $row['certificado_ssl'] . '</td>';
            echo '<td>';
            echo '<button name="btn-excluir" type="button" class="btn btn-danger btn-sm" onclick="">';
            echo '<i class="fas fa-trash"></i>';
            echo '</button>';
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

?>