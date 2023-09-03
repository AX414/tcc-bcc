<?php

session_start();

require(__DIR__ . '/../functions/banco.php');

$conexao = conectarBanco();

if ($_SESSION['idusuario']!=null) {
    $idUsuario = $_SESSION['idusuario'];
    $sql = "SELECT idema, nome, ip, publica, latitude, longitude, usuarios_idusuario FROM emas WHERE usuarios_idusuario = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $idUsuario); // "i" significa que é um valor inteiro
    $stmt->execute();

    $result = $stmt->get_result();

    $locations = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $locations[] = $row;
        }
    }
}

$flagPublicos = 1;
$sql = "SELECT idema, nome, ip, publica, latitude, longitude, usuarios_idusuario FROM emas WHERE publica = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $flagPublicos); // "i" significa que é um valor inteiro
$stmt->execute();

$result = $stmt->get_result();

$publicLocations = array(); // Usamos um novo array para armazenar os locais públicos
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $publicLocations[] = $row;
    }
}

$conexao->close();

// Junte os dois arrays de locais (usuário e públicos)
$allLocations = array_merge($locations, $publicLocations);

$locations_json = json_encode($allLocations);
echo $locations_json;
?>
