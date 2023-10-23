<!DOCTYPE html>
<?php
require '../portalEMA/functions/geral.php';
?>
<html>
    <head>
        <meta charset="utf-8">
        <title>Portal EMA - Tela Principal</title>
        <link rel="stylesheet" type="text/css" href="./css/style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script> 
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    </head>
    <style>
        #map-container {
            position: relative;
            width: 100%;
            height: 100vh;
        }

        #map {
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 2;
            filter: drop-shadow(2px 2px 2px grey);
        }

        .leaflet-control-zoom {
            display: none;
        }
        
        
    </style>
   <body>
        <div id="map-container">
            <?php
                menu();
            ?>
            <div id="map"></div>
        </div>
        <script>
            var map = L.map('map').setView([-21.7583, -52.1153], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
            var locations; // Variável para armazenar os dados das estações

            $.ajax({
                url: '../portalEMA/functions/plotar_mapa.php',
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    locations = data; // Armazena os dados das estações na variável locations
                    console.log(locations);
                    for (var i = 0; i < locations.length; i++) {
                        var location = locations[i];
                        var popupContent = "";
                        if (location.publica == "1") {
                            popupContent += "<br><b style='color: blue;'>Esta é uma estação pública</b>";
                        } else {
                            popupContent += "<br><b style='color: green;'>Esta estação é sua</b>";
                        }
                        popupContent += "<br><b>Nome: </b>" + location.nome;
                        popupContent += "<br><b>Latitude: </b> " + location.latitude;
                        popupContent += "<br><b>Longitude: </b>" + location.longitude;

                        popupContent += "<br><a href='../portalEMA/Tela_Listar_Observacoes.php?idema=" + location.idema + "'><button style='width: 100%; margin-top: 5%;' class='btn btn-primary btn-block'><i class='fas fa-file'></i> Relatórios</button></a>";

                        // Adiciona o botão de Diagnóstico
                        popupContent += "<button id='diagnosticoBtn' style='width: 100%; margin-top: 5%;' class='btn btn-primary btn-block' data-toggle='modal' data-target='#diagnosticoModal' data-location-index='" + i + "'><i class='fas fa-stethoscope'></i> Diagnóstico</button";

                        var LeafIcon = L.Icon.extend({
                            options: {
                                iconSize: [48, 48],
                                iconAnchor: [16, 37],
                                popupAnchor: [8, -34],
                                shadowSize: [41, 41]
                            }
                        });
                        var greenIcon = new LeafIcon({iconUrl: '../portalEMA/resources/imgs/blue.png'});
                        var redIcon = new LeafIcon({iconUrl: '../portalEMA/resources/imgs/red.png'});

                        if (location.publica === "1") {
                            L.marker([location.latitude, location.longitude], {icon: greenIcon}).bindPopup(popupContent).addTo(map);
                        } else {
                            L.marker([location.latitude, location.longitude], {icon: redIcon}).bindPopup(popupContent).addTo(map);
                        }
                    }
                },
                error: function (xhr, status, error) {
                    console.error(error);
                }
            });

            // Define um evento de clique para o botão de diagnóstico
            $('#map').on('click', '#diagnosticoBtn', function () {
                var locationIndex = $(this).data('location-index');
                var location = locations[locationIndex];
                var statusEma = location.status_ema;
                var carga = location.carga_bateria;
                var uptime = location.uptime;
                var diagnostico_nao_previsto = location.diagnostico_nao_previsto;

                if (diagnostico_nao_previsto === null) {
                    diagnostico_nao_previsto = "Nenhum";
                }

                // Preenche o conteúdo do modal com o status da bateria
                $('#diagnosticoModal .modal-body').html('<b>Status da EMA:</b> ' + statusEma + '.<br>\n\
                                                         <b>Carga da Bateria:</b> ' + carga + '%<br>\n\
                                                         <b>Tempo Online:</b> ' + uptime + '.<br>\n\
                                                         <b>Dados não previstos:</b> ' + diagnostico_nao_previsto + '.<br>');

            });
        </script>


        <!-- Modal -->
        <div class="modal fade" id="diagnosticoModal" tabindex="-1" role="dialog" aria-labelledby="diagnosticoModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="diagnosticoModalLabel">Diagnóstico</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
