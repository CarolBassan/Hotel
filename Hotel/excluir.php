<?php
require_once 'conexao.php';

$conexao = conectar();
$mensagem = '';
$tipoMensagem = '';

if(isset($_GET['cpf'])) {
    $cpf = $_GET['cpf'];
    
    try {
        $conexao->beginTransaction();  
        $sqlControle = "DELETE FROM controle WHERE hospedeCpf = :cpf";
        $stmtControle = $conexao->prepare($sqlControle);
        $stmtControle->bindValue(':cpf', $cpf);
        $stmtControle->execute();
        $sqlHospede = "DELETE FROM hospede WHERE cpf = :cpf";
        $stmtHospede = $conexao->prepare($sqlHospede);
        $stmtHospede->bindValue(':cpf', $cpf);
        $stmtHospede->execute();
        $conexao->commit();
        
        $mensagem = "Hóspede excluído com sucesso!";
        $tipoMensagem = "success";

        header("Refresh: 2; URL=listar.php");
    } catch(PDOException $e) {
        $conexao->rollBack();
        $mensagem = "Erro ao excluir hóspede: " . $e->getMessage();
        $tipoMensagem = "danger";
    }
} else {
    $mensagem = "CPF não informado!";
    $tipoMensagem = "danger";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Bassan - Excluir Hóspede</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="menu.php">HOTEL BASSAN</a>
            <div class="navbar-text text-white">
                <a href="listar.php" class="text-white">Voltar para Lista</a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="card">
            <div class="card-body text-center">
                <?php if ($mensagem): ?>
                <div class="alert alert-<?= $tipoMensagem ?>">
                    <?= $mensagem ?>
                </div>
                <?php endif; ?>

                <div class="spinner-border text-primary mb-3" role="status"
                    style="<?= $tipoMensagem == 'success' ? '' : 'display: none;' ?>">
                    <span class="visually-hidden">Carregando...</span>
                </div>
                <p>Redirecionando para a lista de hóspedes...</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>