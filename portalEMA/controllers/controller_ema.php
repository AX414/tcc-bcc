<?php

if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require(__DIR__ . '/../functions/banco.php');
require(__DIR__ . '/../functions/gerar_certificado_ssl.php');

if (isset($_POST['btn-cadastro-ema'])) {
    cadastrarEMA();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    excluirEMA();
}

if (isset($_POST['btn-alterar-ema'])) {
    alterarEMA();
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
        //Ainda tenho que configurar o certificado ssl e tenho que criar um tópico no kafka novo para cada ema.
        //$certificado_ssl = gerarCertificadoSSL($nome);

        $conexao = conectarBanco();

        $query = "INSERT INTO emas(nome, ip, publica, latitude, longitude, usuarios_idusuario, certificado_ssl, ativa, topico_kafka) "
                . "VALUES ('$nome', '$ip', '$publica', '$latitude', '$longitude', '$id_dono', '', 1, '')";
        $insert = mysqli_query($conexao, $query);

        if ($insert) {
            echo "<script>alert('Estação cadastrada com Sucesso!');window.location.href='../Tela_Listar_EMAs.php';</script>";
        } else {
            echo "Erro do mysqli:" . mysqli_errno($conexao);
            echo "<script>alert('Não foi possível cadastrar essa estação, algo deu errado.');!');window.location.href='../Tela_Cadastro_EMA.php';</script>";
        }

        $conexao->close();
    }
}

function listarEMAs() {
    $conexao = conectarBanco();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nome = $_POST['nome'];
        
        $sql = "SELECT * FROM emas WHERE 1 = 1 AND ativa = 1";

        if (!empty($nome)) {
            $sql .= " AND nome LIKE '%$nome%'";
        }

        $result = $conexao->query($sql);
    } else {
        $sql = 'SELECT * FROM emas WHERE ativa = 1';
        $result = $conexao->query($sql);
    }

    if ($result->num_rows >= 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<tr id="row-' . $row['idema'] . '">';
            echo '<td>' . $row['idema'] . '</td>';
            echo '<td>' . $row['nome'] . '</td>';
            echo '<td>' . $row['ip'] . '</td>';
            if ($row['publica'] == 0) {
                echo '<td>Não</td>';
            } else {
                echo'<td>Sim</td>';
            }
            echo '<td>' . $row['latitude'] . '</td>';
            echo '<td>' . $row['longitude'] . '</td>';
            
            $iddono = $row['usuarios_idusuario'];
            $query = "SELECT nome_usuario FROM usuarios WHERE idusuario = '$iddono'";
            $resultado = $conexao->query($query);
            if (!$resultado) {
                die("Erro na consulta: " . $conexao->error);
            }else if ($resultado) {
                $rowDono = $resultado->fetch_assoc(); 
                
                if ($rowDono) {
                    $nome_dono = $rowDono['nome_usuario'];
                    echo '<td>' . $nome_dono . '</td>';
                } else {
                    echo '<td>Não encontrado</td>';
                }
            } else {
                echo '<td>Erro na consulta</td>';
            }

            echo '<td>' . $row['certificado_ssl'] . '</td>';
            echo '<td>';
            echo '<button name="btn-excluir-ema" type="button" class="btn btn-danger btn-sm" onclick="excluirEMA(' . $row['idema'] . ')">';
            echo '<i class="fas fa-trash"></i>';
            echo '</button>';
            echo '<a href="Tela_Alterar_EMA.php?idema=' . $row['idema'] . '"><button name="btn-alterar-ema" type="button" class="btn btn-warning btn-sm" onclick="">';
            echo '<i class="fas fa-pencil"></i>';
            echo '</button>';
            echo '<a href="Tela_Visualizar_EMA.php?idema=' . $row['idema'] . '"><button name="btn-visualizar-ema" type="button" class="btn btn-primary btn-sm" onclick="">';
            echo '<i class="fas fa-eye"></i>';
            echo '</button></a>';
            echo '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="7">Nenhum dado encontrado.</td></tr>';
    }

    $conexao->close();
}

function excluirEMA() {
    if (isset($_POST['idema'])) {
        $idema = $_POST['idema'];

        $conexao = conectarBanco();

        $query = "UPDATE emas SET ativa = 0 WHERE idema = '$idema'";
        $exclusaoLogica = mysqli_query($conexao, $query);

        if ($exclusaoLogica) {
            echo 'EMA excluída com sucesso!';
        } else {
            echo 'Erro ao excluir EMA.';
        }

        $conexao->close();
    }
}

function alterarEMA() {
    if (isset($_POST['btn-alterar-ema'])) {
        $emaId = $_GET['idema'];
        $novoNome = $_POST['nome'];
        $novoIP = $_POST['ip'];
        $novaLatitude = $_POST['latitude'];
        $novaLongitude = $_POST['longitude'];
        $publica = $_POST['publica'];

        $conexao = conectarBanco();

        $query = "UPDATE emas SET nome = '$novoNome', ip = '$novoIP', latitude = '$novaLatitude', longitude = '$novaLongitude', publica = '$publica' WHERE idema = '$emaId'";
        $update = mysqli_query($conexao, $query);

        if ($update) {
            echo "<script>alert('Estação alterada com sucesso!');window.location.href='../Tela_Listar_EMAs.php';</script>";
        } else {
            echo "<script>alert('Erro ao alterar a estação.');window.location.href='../Tela_Alterar_EMA.php';</script>";
        }

        $conexao->close();
    }
}

function buscarEMAPorID($idema) {
    $conexao = conectarBanco();

    $idema = mysqli_real_escape_string($conexao, $idema);

    $query = "SELECT * FROM emas WHERE idema = '$idema'";
    $resultado = mysqli_query($conexao, $query);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $usuario = mysqli_fetch_assoc($resultado);
        return $usuario;
    } else {
        return false;
    }

    $conexao->close();
}

?>