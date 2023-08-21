<!DOCTYPE html>
<html>
<head>
    <title>Lista de Dados</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
<script defer>
        // Função para recarregar a página a cada 1 segundo
        setInterval(function() {
            location.reload();
        }, 1000);
    </script>
    <div class="container">
        <h1 style="margin-top: 20px">Lista de Dados da EMA 01</h1>
        <form method="POST" action="">
            <div class="row justify-content-between">
                <div class="col">
                    <label for="dataInicial">Data Inicial:</label>
                    <input type="date" name="dataInicial" id="dataInicial" class="form-control">
                </div>
                <div class="col">
                    <label for="dataFinal">Data Final:</label>
                    <input type="date" name="dataFinal" id="dataFinal" class="form-control">
                </div>
                <div class="col">
                    <label for="hora">Hora:</label>
                    <input type="time" name="hora" id="hora" class="form-control">
                </div>
                <div class="col" style="align-self: flex-end;">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                    <a href="download.php" class="btn btn-primary">
                        <i class="fas fa-download"></i> Download CSV
                    </a>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Data</th>
                        <th>Hora</th>
                        <th>Temperatura</th>
                        <th>Pluviômetro</th>
                        <th>Velocidade do Vento</th>
                        <th>Direção do Vento</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Configurações do banco de dados
                    $host = 'localhost';
                    $user = 'root';
                    $password = 'ifsp';
                    $database = 'awsmqtt';

                    // Conectando ao banco de dados
                    $conn = new mysqli($host, $user, $password, $database);
                    if ($conn->connect_error) {
                        die('Erro na conexão com o banco de dados: ' . $conn->connect_error);
                    }

                    // Verifica se o formulário foi enviado
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        // Obtém os valores dos campos de filtro
                        $dataInicial = $_POST['dataInicial'];
                        $dataFinal = $_POST['dataFinal'];
                        $hora = $_POST['hora'];

                        // Monta a consulta SQL com base nos filtros
                        $sql = "SELECT idrelatorio, data, hora, temperatura, pluviometro, vel_vento, dir_vento FROM relatorios WHERE 1 = 1";

                        if (!empty($dataInicial)) {
                            $sql .= " AND data >= '$dataInicial'";
                        }

                        if (!empty($dataFinal)) {
                            $sql .= " AND data <= '$dataFinal'";
                        }

                        if (!empty($hora)) {
                            $sql .= " AND hora >= '$hora'";
                        }

                        $result = $conn->query($sql);
                    } else {
                        // Consulta SQL para obter todos os dados
                        $sql = 'SELECT idrelatorio, data, hora, temperatura, pluviometro, vel_vento, dir_vento FROM relatorios';
                        $result = $conn->query($sql);
                    }

                    // Verifica se há resultados e exibe na tabela
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            // Formata a data como dd/mm/YYYY
                            $dataFormatada = date('d/m/Y', strtotime($row['data']));
                            
                            echo '<tr>';
                            echo '<td>' . $row['idrelatorio'] . '</td>';
                            echo '<td>' . $dataFormatada . '</td>';
                            echo '<td>' . $row['hora'] . '</td>';
                            echo '<td>' . $row['temperatura'] . '</td>';
                            echo '<td>' . $row['pluviometro'] . '</td>';
                            echo '<td>' . $row['vel_vento'] . '</td>';
                            echo '<td>' . $row['dir_vento'] . '</td>';
                            echo '<td>';
                            echo '<button type="button" class="btn btn-danger btn-sm" onclick="excluirRelatorio(' . $row['idrelatorio'] . ')">';
                            echo '<i class="fas fa-trash"></i>';
                            echo '</button>';
                            echo '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="7">Nenhum dado encontrado.</td></tr>';
                    }

                    // Fecha a conexão com o banco de dados
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function excluirRelatorio(id) {
            if (confirm("Tem certeza de que deseja excluir o relatório?")) {
                // Enviar a requisição para excluir o relatório usando AJAX
                fetch('excluir_relatorio.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'id=' + id,
                })
                .then(response => response.json())
                .then(data => {
                    // Processar a resposta do servidor
                    if (data.success) {
                        // Excluir a linha da tabela
                        var row = document.getElementById('row-' + id);
                        row.parentNode.removeChild(row);
                    } else {
                        alert('Erro ao excluir o relatório.');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                });
            }
        }
    </script>
</body>
</html>
