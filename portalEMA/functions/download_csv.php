<?php

require(__DIR__ . '/../functions/banco.php');
$conexao = conectarBanco();

$idrelatorio = $_GET['idrelatorio'];

// Consulta SQL para obter os dados
$sql = "SELECT * FROM relatorios WHERE idrelatorio = $idrelatorio";
$result = $conexao->query($sql);

if ($result->num_rows > 0) {
    // Cabeçalho do arquivo CSV
    $csvData = "ID,Data,Hora,"
            . "Temperatura,Unidade Temperatura,"
            . "Umidade,Unidade Umidade,"
            . "Velocidade do Vento, Unidade da Velocidade do Vento,"
            . "Direção do Vento, Unidade da Direção do Vento,"
            . "Radiação Solar, Unidade da Radiação Solar,"
            . "Pressão Atmosférica, Unidade da Pressão Atmosférica,"
            . "Volume da Chuva, Unidade do Volume da Chuva,"
            . "Frequência da Chuva, Unidade da Frequência da Chuva,"
            . "Não Previstos\r\n";

    // Loop para obter os dados
    while ($row = $result->fetch_assoc()) {
        $dataFormatada = date('d/m/Y', strtotime($row['data']));

        // Adiciona os dados formatados ao arquivo CSV
        $csvData .= $row['idrelatorio'] . ','
                . $dataFormatada . ','
                . $row['hora'] . ','
                . $row['temperatura'] . ','
                . $row['unidade_tem'] . ','
                . $row['umidade'] . ','
                . $row['unidade_um'] . ','
                . $row['vento_velocidade'] . ','
                . $row['unidade_vv'] . ','
                . $row['vento_direcao'] . ','
                . $row['unidade_vd'] . ','
                . $row['radiacao_solar'] . ','
                . $row['unidade_rs'] . ','
                . $row['volume_chuva'] . ','
                . $row['unidade_vc'] . ','
                . $row['frequencia_chuva'] . ','
                . $row['unidade_fc'] . ','
                . $row['nao_previstos'] . "\r\n";
                $hora = $row['hora'];
    }

    // Define o nome do arquivo e o tipo de conteúdo
    $filename = 'Relatório ' . $idrelatorio . ' de ' . $dataFormatada . ' as ' . $hora . '.csv';
    $contentType = 'text/csv';

    // Configura os headers para o download
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Content-Type: $contentType");

    // Envia os dados do arquivo CSV
    echo $csvData;
} else {
    echo 'Nenhum dado encontrado.';
}

// Fecha a conexão com o banco de dados
$conexao->close();
?>