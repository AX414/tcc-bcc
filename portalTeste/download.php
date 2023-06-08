<?php
// Configurações do banco de dados
$host = 'localhost';
$user = 'root';
$password = 'root';
$database = 'awsmqtt';

// Conectando ao banco de dados
$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die('Erro na conexão com o banco de dados: ' . $conn->connect_error);
}

// Consulta SQL para obter os dados
$sql = 'SELECT idrelatorio, data, hora, temperatura, pluviometro, vel_vento, dir_vento FROM relatorios';
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Cabeçalho do arquivo CSV
    $csvData = "ID,Data,Hora,Temperatura,Pluviometro,Velocidade do Vento,Direcao do Vento\r\n";

    // Loop para obter os dados
    while ($row = $result->fetch_assoc()) {
        $dataFormatada = date('d/m/Y', strtotime($row['data']));

        // Adiciona os dados formatados ao arquivo CSV
        $csvData .= $row['idrelatorio'] . ',' . $dataFormatada . ',' . $row['hora'] . ',' . $row['temperatura'] . ',' . $row['pluviometro'] . ',' . $row['vel_vento'] . ',' . $row['dir_vento'] . "\r\n";
    }

    // Define o nome do arquivo e o tipo de conteúdo
    $filename = 'dados.csv';
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
$conn->close();