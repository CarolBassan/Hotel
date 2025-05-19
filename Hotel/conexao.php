<?php
function conectar() {
    $dbname = 'mysql:host=localhost;dbname=bdhotel';
    $usuario = 'root';
    $senha = '';

    try {
        $conexao = new PDO($dbname, $usuario, $senha);
        $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conexao;
    } catch (PDOException $e) {
        echo 'Erro na conexão: ' . $e->getMessage();
        return null;
    }
}

function encerrar(&$conexao) {
    $conexao = null;
}
?>