<?php

if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require(__DIR__ . '/../functions/banco.php');


function listarObservacoes($idema) {
    $conexao = conectarBanco();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        /*
        $dataInicial = $_POST['data_inicial'];
        $dataFinal = $_POST['data_final'];
        $horaLeitura = $_POST['hora_leitura'];
        */
        $sql = "SELECT * FROM observacoes WHERE 1=1 AND emas_idema = '$idema'";

        /*
        if (!empty($dataInicial)) {
            $sql .= " AND data >= '$dataInicial'";
        }
        
        if (!empty($dataFinal)) {
            $sql .= " AND data <= '$dataFinal'";
        }
        
        if (!empty($horaLeitura)) {
            $sql .= " AND hora >= '$horaLeitura'";
        }
        */
        $result = $conexao->query($sql);
    } else {
        $sql = "SELECT * FROM observacoes WHERE 1 = 1 AND emas_idema = '$idema'";
        $result = $conexao->query($sql);
    }

    if ($result->num_rows >= 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<tr id="row-' . $row['idobservacao'] . '">';
            echo '<td>' . $row['idobservacao'] . '</td>';
            echo '<td>' . $row['data'] . '</td>';
            echo '<td>' . $row['hora'] . '</td>';
            if($row['erro_tem'] == false){
                echo '<td>' . $row['temperatura'] . $row['unidade_tem'] . '</td>';
            }else{
                echo '<td title="Valor com erro" style="color: red;">' . $row['temperatura'] . $row['unidade_tem'] . '</td>';
            }
            if($row['erro_um'] == false){
                echo '<td>' . $row['umidade'] . $row['unidade_um'] . '</td>';
            }else{
                echo '<td title="Valor com erro" style="color: red;">' . $row['umidade'] . $row['unidade_um'] . '</td>';
            }
            if($row['erro_vv'] == false){
                echo '<td>' . $row['vento_velocidade'] . $row['unidade_vv'] . '</td>';
            }else{
                echo '<td title="Valor com erro" style="color: red;">' . $row['vento_velocidade'] . $row['unidade_vv'] . '</td>';
            }
            if($row['erro_vd'] == false){
                echo '<td>' . $row['vento_direcao'] . ' ' . $row['unidade_vd'] . '</td>';
            }else{
                echo '<td title="Valor com erro" style="color: red;">' . $row['vento_direcao'] . ' ' . $row['unidade_vd'] . '</td>';
            }
            echo '<td>';
            echo '<a href="Tela_Visualizar_Observacao.php?idobservacao=' . $row['idobservacao'] . '"><button name="btn-visualizar-relatorio" type="button" class="btn btn-primary btn-sm" onclick="">';
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

function buscarEMAPorID($idema) {
    $conexao = conectarBanco();

    $idema = mysqli_real_escape_string($conexao, $idema);

    $query = "SELECT * FROM emas WHERE idema = '$idema'";
    $resultado = mysqli_query($conexao, $query);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $ema = mysqli_fetch_assoc($resultado);
        return $ema;
    } else {
        return false;
    }

    $conexao->close();
}

function buscarObservacaoPorID($idobservacao) {
    $conexao = conectarBanco();

    $idobservacao = mysqli_real_escape_string($conexao, $idobservacao);

    $query = "SELECT * FROM observacoes WHERE idobservacao = '$idobservacao'";
    $resultado = mysqli_query($conexao, $query);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $observacao = mysqli_fetch_assoc($resultado);
        return $observacao;
    } else {
        return false;
    }

    $conexao->close();
}

?>