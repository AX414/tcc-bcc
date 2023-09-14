<!DOCTYPE html>
<?php
require '../portalEMA/functions/geral.php';
?>
<html>
    <head>
        <meta charset="utf-8">
        <title>Portal EMA - Tela Principal</title>
        <link rel="stylesheet" type="text/css" href="./css/style.css"><link rel="stylesheet" type="text/css" href="./css/style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script> 
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
            height: 10%;
            z-index: 2;
            filter: drop-shadow(2px 2px 2px grey);
        }

        .leaflet-control-zoom {
            display: none;
        }
    </style>
    <body>
        <?php
        menu();
        ?>
        <div id="map-container">
            <div id="map"></div>
        </div>
        <script>
            var map = L.map('map').setView([-21.7583, -52.1153], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
            $.ajax({
                url: '../portalEMA/functions/plotar_mapa.php',
                type: 'GET',
                dataType: 'json',
                success: function (locations) {
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
                        
                        popupContent += "<br><a href='../portalEMA/Tela_Listar_Relatorios.php?idema=" + location.idema + "'><button style='width: 100%; margin-top: 5%;' class='btn btn-primary btn-block'><i class='fas fa-file'></i> Relatórios</button></a>";

                        var LeafIcon = L.Icon.extend({
                            options: {
                                    iconSize: [48, 48], // Tamanho do ícone
                                    iconAnchor: [16, 37], // Ponto de ancoragem do ícone
                                    popupAnchor: [8, -34], // Ponto de ancoragem do popup
                                    shadowSize: [41, 41]  // Tamanho da sombra (se aplicável)
                                }
                        });
                        var greenIcon = new LeafIcon({iconUrl: '../portalEMA/resources/imgs/blue.png'});
                        var redIcon = new LeafIcon({iconUrl: '../portalEMA/resources/imgs/red.png'});

                        if (location.publica == "1") {
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
        </script>
    </body>
</html>
