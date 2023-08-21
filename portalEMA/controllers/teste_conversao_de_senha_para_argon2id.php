<?php
$senhaAntiga = "123porcel";
$hash = password_hash($senhaAntiga, PASSWORD_ARGON2ID);

echo "Senha original: $senhaAntiga<br>";
echo "Senha hash: $hash";
?>

