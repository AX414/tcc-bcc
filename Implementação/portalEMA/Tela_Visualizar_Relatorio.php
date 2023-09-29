<!DOCTYPE html>
<?php
require '../portalEMA/functions/geral.php';
require '../portalEMA/controllers/controller_relatorio.php';
?>
<html>
    <head>
        <meta charset="utf-8">
        <title>Portal EMA - Visualizar Relatorio</title>
        <link rel="stylesheet" type="text/css" href="../portalEMA/css/style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    </head>
    <style>
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            height: 10%;
            z-index: 2;
            filter: drop-shadow(2px 2px 2px grey);
        }

        .form-control{
            margin-bottom: 3%;
        }
        
        body{
            height: 160%;
        }
        
        #relatorio{
            width: 60%;
        }
    </style>
    <body>
        <?php
        menu();

        $relatorioId = $_GET['idrelatorio'];

        $relatorio = buscarRelatorioPorId($relatorioId);

        if (!$relatorio) {
            echo "Relatório não encontrado.";
            exit;
        } else {
            echo '<div id="relatorio" style="padding-top: 30%; padding-bottom: 10%">';
            echo '<div class="card col-12">';
            echo '<div class="card-header">';
            echo '<div style="display: inline;"><i style="padding-right: 1%;" class="fas fa-file"></i><b>Relatório</b></div>';
            echo '<div style="display: inline-flex; margin-left: 65%;"><form method="POST"><a href="../portalEMA/functions/download_csv.php?idrelatorio=' . $relatorioId . '" class="btn btn-primary"><i class="fas fa-download"></i> Download CSV </a></form></div>';
            echo '</div>';
            echo '<div class="card-body">';
            echo '<form>';
            echo '<div class="row">';
            echo '<div class="col">';
            echo '<label>Data da Leitura</label>';
            echo '<input disabled="true" value="' . $relatorio['data'] . '" type="date" class="form-control">';
            echo '</div>';
            echo '<div class="col">';
            echo '<label>Hora da Leitura</label>';
            echo '<input disabled="true" value="' . $relatorio['hora'] . '" type="text" class="form-control">';
            echo '</div>';
            echo '</div>';
            
            echo '<br><h3><b>Dados Obrigatórios</b></h3><br>';
            echo '<div class="row">';
            echo '<div class="col">';
            echo '<label>Temperatura</label>';
            if($relatorio['erro_tem'] == false){
                echo '<input disabled="true" value="' . $relatorio['temperatura'] . ' ' . $relatorio['unidade_tem'] . '" type="text" class="form-control">';
            }else{
                echo '<input disabled="true" title="Valor com erro" style="color: red;" value="' . $relatorio['temperatura'] . ' ' . $relatorio['unidade_tem'] . '" type="text" class="form-control">';            
            }
            echo '</div>';
            echo '<div class="col">';
            echo '<label>Umidade</label>';
            if($relatorio['erro_um'] == false){
                echo '<input disabled="true" value="' . $relatorio['umidade'] . ' ' . $relatorio['unidade_um'] . '" type="text" class="form-control">';
            }else{
                echo '<input disabled="true" title="Valor com erro" style="color: red;" value="' . $relatorio['umidade'] . ' ' . $relatorio['unidade_um'] . '" type="text" class="form-control">';
            }
            echo '</div>';
            echo '</div>';
            
            echo '<div class="row">';
            echo '<div class="col">';
            echo '<label>Velocidade do Vento</label>';
            if($relatorio['erro_vv'] == false){
                echo '<input disabled="true" value="' . $relatorio['vento_velocidade'] . ' ' . $relatorio['unidade_vv'] . '" type="text" class="form-control">';
            }else{
                echo '<input disabled="true" title="Valor com erro" style="color: red;" value="' . $relatorio['vento_velocidade'] . ' ' . $relatorio['unidade_vv'] . '" type="text" class="form-control">';
            }
            echo '</div>';
            echo '<div class="col">';
            echo '<label>Direção do Vento</label>';
            if($relatorio['erro_vd'] == false){
                echo '<input disabled="true" value="' . $relatorio['vento_direcao'] . ' ' . $relatorio['unidade_vd'] . '" type="text" class="form-control">';
            }else{
                echo '<input disabled="true" title="Valor com erro" style="color: red;" value="' . $relatorio['vento_direcao'] . ' ' . $relatorio['unidade_vd'] . '" type="text" class="form-control">';
            }
            echo '</div>';
            echo '</div>';
            
            echo '<br><h3><b>Dados Opcionais</b></h3><br>';
            echo '<div class="row">';
            echo '<div class="col">';
            echo '<label>Radiação Solar</label>';
            if($relatorio['erro_rs'] == false){
                echo '<input disabled="true" value="' . $relatorio['radiacao_solar'] . ' ' . $relatorio['unidade_rs'] . '" type="text" class="form-control">';
            }else{
                echo '<input disabled="true" title="Valor com erro" style="color: red;" value="' . $relatorio['radiacao_solar'] . ' ' . $relatorio['unidade_rs'] . '" type="text" class="form-control">';
            }
            echo '</div>';
            echo '<div class="col">';
            echo '<label>Pressão Atmosférica</label>';
            if($relatorio['erro_pa'] == false){
                echo '<input disabled="true" value="' . $relatorio['pressao_atmos'] . ' ' . $relatorio['unidade_pa'] . '" type="text" class="form-control">';
            }else{
                echo '<input disabled="true" title="Valor com erro" style="color: red;" value="' . $relatorio['pressao_atmos'] . ' ' . $relatorio['unidade_pa'] . '" type="text" class="form-control">';
            }
            echo '</div>';
            echo '</div>';
            echo '<div class="row">';
            echo '<div class="col">';
            echo '<label>Volume de Chuva</label>';
            if($relatorio['erro_vc'] == false){
                echo '<input disabled="true" value="' . $relatorio['volume_chuva'] . ' ' . $relatorio['unidade_vc'] . '" type="text" class="form-control">';
            }else{
                echo '<input disabled="true" title="Valor com erro" style="color: red;" value="' . $relatorio['volume_chuva'] . ' ' . $relatorio['unidade_vc'] . '" type="text" class="form-control">';
            }
            echo '</div>';
            echo '<div class="col">';
            echo '<label>Frequência de Chuva</label>';
            if($relatorio['erro_fc'] == false){
                echo '<input disabled="true" value="' . $relatorio['frequencia_chuva'] . ' ' . $relatorio['unidade_fc'] . '" type="text" class="form-control">';
            }else{
                echo '<input disabled="true" title="Valor com erro" style="color: red;" value="' . $relatorio['frequencia_chuva'] . ' ' . $relatorio['unidade_fc'] . '" type="text" class="form-control">';
            }
            echo '</div>';
            echo '</div>';
            echo '<br><h3><b>Dados Não Previstos</b></h3><br>';
            echo '<textarea style="font-family: "Courier New";" value="' . $relatorio['nao_previstos'] .'" class="form-control"></textarea>';
            echo '<br><h3><b>Erros do Relatório</b></h3><br>';
            echo '<textarea style="font-family: "Courier New";" value="' . $relatorio['erros'] .'" class="form-control"></textarea>';
            echo '</form>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        ?>
    </body>
</html>
