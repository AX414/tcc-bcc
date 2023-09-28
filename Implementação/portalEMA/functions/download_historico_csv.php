<?php

require(__DIR__ . '/../functions/banco.php');
$conexao = conectarBanco();

$idema = $_GET['idema'];
$nome = $_GET['nome'];

//echo"<script>alert(".$idema.")</script>";
$sql = "SELECT * FROM relatorios WHERE emas_idema = $idema";
$result = $conexao->query($sql);

if ($result->num_rows > 0) {
    // Cabeçalho do arquivo CSV
    $csvData = "ID,Data,Hora,"
            . "Temperatura,Unidade Temperatura, Erro_TEM,"
            . "Umidade,Unidade Umidade, Erro_UM,"
            . "Velocidade do Vento, Unidade da Velocidade do Vento, Erro_VV,"
            . "Direção do Vento, Unidade da Direção do Vento, Erro_VD,"
            . "Radiação Solar, Unidade da Radiação Solar, Erro_RS,"
            . "Pressão Atmosférica, Unidade da Pressão Atmosférica, Erro_PA,"
            . "Volume da Chuva, Unidade do Volume da Chuva, Erro_VC,"
            . "Frequência da Chuva, Unidade da Frequência da Chuva, Erro_FC,"
            . "Não Previstos,"
            . "Erro na Leitura\r\n";

    // Loop para obter os dados
    while ($row = $result->fetch_assoc()) {
        $dataFormatada = date('d/m/Y', strtotime($row['data']));
        $erro_tem = "";
        $erro_um = "";
        $erro_vv = "";
        $erro_vd = "";
        $erro_rs = "";
        $erro_pa = "";
        $erro_vc = "";
        $erro_fc = "";
        if($row['erro_tem'] == True){
            $erro_tem = "Erro no sensor de Temperatura.";
        }
        if($row['erro_um'] == True){
            $erro_um = "Erro no sensor de Umidade.";
        }
        if($row['erro_vv'] == True){
            $erro_vv = "Erro no sensor de Velocidade do Vento.";
        }
        if($row['erro_vd'] == True){
            $erro_vd = "Erro no sensor de Direção do Vento.";
        }if($row['erro_rs'] == True){
            $erro_rs = "Erro no sensor de Radiação Solar.";
        }
        if($row['erro_pa'] == True){
            $erro_pa = "Erro no sensor de Pressão Atmosférica.";
        }
        if($row['erro_vc'] == True){
            $erro_vc = "Erro no sensor de Volume de Chuva.";
        }
        if($row['erro_fc'] == True){
            $erro_fc = "Erro no sensor de Frequência de Chuva.";
        }
        // Adiciona os dados formatados ao arquivo CSV
        $csvData .= $row['idrelatorio'] . ','
                . $dataFormatada . ','
                . $row['hora'] . ','
                . $row['temperatura'] . ','
                . $row['unidade_tem'] . ','
                . $erro_tem . ','
                . $row['umidade'] . ','
                . $row['unidade_um'] . ','
                . $erro_um . ','
                . $row['vento_velocidade'] . ','
                . $row['unidade_vv'] . ','
                . $erro_vv . ','
                . $row['vento_direcao'] . ','
                . $row['unidade_vd'] . ','
                . $erro_vd . ','
                . $row['radiacao_solar'] . ','
                . $row['unidade_rs'] . ','
                . $erro_rs . ','
                . $row['volume_chuva'] . ','
                . $row['unidade_vc'] . ','
                . $erro_vc . ','
                . $row['frequencia_chuva'] . ','
                . $row['unidade_fc'] . ','
                . $erro_fc . ','
                . $row['nao_previstos'] . ','
                . $row['erros'] . "\r\n";
        $hora = $row['hora'];
    }

    $filename = 'Histórico de Relatórios da ' . $nome . '.csv';

    $contentType = 'text/csv';

    // Configura os headers para o download
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Content-Type: $contentType");

    // Envia os dados do arquivo CSV
    echo $csvData;
} else {
    echo '<script>alert("Nenhum dado encontrado.");window.location.href="../Tela_Listar_Relatorios.php?idema='.$idema.'";</script>';
}

// Fecha a conexão com o banco de dados
$conexao->close();
?>