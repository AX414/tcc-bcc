<?php
session_start();
require(__DIR__ . '/../functions/banco.php');
$conexao = conectarBanco();

// Pega as estações do usuário que está logado (se houver)
$idUsuario = isset($_SESSION['idusuario']) ? $_SESSION['idusuario'] : null;
if ($idUsuario != null) {
    $sql = "SELECT * FROM emas WHERE usuarios_idusuario = '$idUsuario'";
    $result = $conexao->query($sql);
    $locations = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $locations[] = $row;
        }
    }
}

// Pega as estações que forem públicas e armazena em um JSON
$sql = "SELECT * FROM emas WHERE publica = 1";
$result = $conexao->query($sql);
$publicLocations = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $publicLocations[] = $row;
    }
}

$conexao->close();

// Junte os dois arrays de locais (usuário e públicos) caso haja um usuário logado
$allLocations = isset($locations) ? array_merge($locations, $publicLocations) : $publicLocations;
$locations_json = json_encode($allLocations);
echo $locations_json;
?>
