<?php
session_start();
require(__DIR__ . '/../functions/banco.php');
$conexao = conectarBanco();

$nivel_acesso = isset($_SESSION['nivel_acesso']) ? $_SESSION['nivel_acesso'] : null;

$allLocations = array();

if ($nivel_acesso == 1) {
    $sql = "SELECT e.*, u.nome_usuario, u.idusuario AS iddono FROM emas e
            LEFT JOIN usuarios u ON e.usuarios_idusuario = u.idusuario
            WHERE e.ativa = 1";

    $result = $conexao->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $allLocations[] = $row;
        }
    }
} else {
    $idUsuario = isset($_SESSION['idusuario']) ? $_SESSION['idusuario'] : null;
    
    if ($idUsuario != null) {
        $sql = "SELECT e.*, u.nome_usuario, u.idusuario AS iddono FROM emas e
                LEFT JOIN usuarios u ON e.usuarios_idusuario = u.idusuario
                WHERE e.usuarios_idusuario = '$idUsuario' AND e.ativa = 1";

        $result = $conexao->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $allLocations[] = $row;
            }
        }
    }

    $sql = "SELECT e.*, u.nome_usuario, u.idusuario AS iddono FROM emas e
            LEFT JOIN usuarios u ON e.usuarios_idusuario = u.idusuario
            WHERE e.publica = 1 AND e.ativa = 1";

    $result = $conexao->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $allLocations[] = $row;
        }
    }
}

$conexao->close();

$locations_json = json_encode($allLocations);
echo $locations_json;

?>
