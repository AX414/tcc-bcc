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
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.awesome-markers/2.1.0/leaflet.awesome-markers.css">
    <link rel="stylesheet" href="http://code.ionicframework.com/ionicons/1.5.2/css/ionicons.min.css">
    <link rel="stylesheet" href="css/leaflet.awesome-markers.css">
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.awesome-markers/2.1.0/leaflet.awesome-markers.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="js/leaflet.awesome-markers.js"></script>
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
        background-color: #28b498;
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
                var popupContent = "<b>Nome: </b>" + location.nome
                    + "<br><b>Latitude: </b> " + location.latitude
                    + "<br><b>Longitude: </b>" + location.longitude
                    + "<br><button style='width: 100%; margin-top: 5%;' class='btn btn-primary btn-block'><i class='fas fa-file'></i> Relatórios</button>";

                var marker = L.marker([location.latitude, location.longitude])
                    .bindPopup(popupContent)
                    .addTo(map);
            }
        },
        error: function (xhr, status, error) {
            console.error(error);
        }
    });
</script>
</body>
</html>
