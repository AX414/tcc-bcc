<?php

function gerarCertificadoSSL($nome) {
    // Defina os detalhes do certificado
    $validadeDias = 365; // Validade do certificado em dias

    // Gere uma chave privada
    $chavePrivada = openssl_pkey_new([
        "private_key_bits" => 2048,
        "private_key_type" => OPENSSL_KEYTYPE_RSA,
    ]);

    if (!$chavePrivada) {
        die("Erro ao gerar a chave privada." . openssl_error_string());
    }

    // Gere um certificado autoassinado
    $certificado = openssl_csr_new([
        "commonName" => $nome,
    ], $chavePrivada);

    if (!$certificado) {
        die("Erro ao gerar o certificado CSR.");
    }

    $certificadoAssinado = openssl_csr_sign($certificado, null, $chavePrivada, $validadeDias);

    if (!$certificadoAssinado) {
        die("Erro ao assinar o certificado.");
    }

    // Gere nomes de arquivo únicos com base no nome da estação
    $chavePrivadaArquivo = "chave_privada_$nome.pem";
    $certificadoArquivo = "certificado_$nome.pem";

    // Salve a chave privada e o certificado em arquivos
    if (!openssl_pkey_export_to_file($chavePrivada, $chavePrivadaArquivo)) {
        die("Erro ao salvar a chave privada em arquivo.");
    }

    if (!openssl_x509_export($certificadoAssinado, $certificadoArquivo)) {
        die("Erro ao salvar o certificado em arquivo.");
    }

    echo "Certificado gerado com sucesso para a estação com nome $nome!";
    return $certificadoAssinado;
}


?>


