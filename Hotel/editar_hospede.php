<?php
require_once 'conexao.php';

$conexao = conectar(); 
$mensagem = '';
$tipoMensagem = '';

if(isset($_GET['cpf'])) {
    $cpf = $_GET['cpf'];
    $sql = "SELECT h.*, c.* FROM hospede h JOIN controle c ON h.cpf = c.hospedeCpf WHERE h.cpf = :cpf";
    $stmt = $conexao->prepare($sql);
    $stmt->bindValue(':cpf', $cpf);
    $stmt->execute();
    
    if($stmt->rowCount() > 0) {
        $hospede = $stmt->fetch(PDO::FETCH_ASSOC);
        $ciasAereas = explode(", ", $hospede['ciasAereas']);
    } else {
        $mensagem = "Hóspede não encontrado!";
        $tipoMensagem = "danger";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cpf = $_POST['cpf'];
    $nome = $_POST['nome'];
    $sobrenome = $_POST['sobrenome'];
    $sexo = $_POST['sexo'];
    $dataNascimento = $_POST['dataNascimento'];
    
    try {
        $sql = "UPDATE hospede SET nome = :nome, sobrenome = :sobrenome, sexo = :sexo, dataNascimento = :dataNascimento WHERE cpf = :cpf";
        $stmt = $conexao->prepare($sql);
        $stmt->bindValue(':nome', $nome);
        $stmt->bindValue(':sobrenome', $sobrenome);
        $stmt->bindValue(':sexo', $sexo);
        $stmt->bindValue(':dataNascimento', $dataNascimento);
        $stmt->bindValue(':cpf', $cpf);
        
        if($stmt->execute()) {
            $mensagem = "Dados do hóspede atualizados com sucesso!";
            $tipoMensagem = "success";
            header("Location: consultar.php?cpf=$cpf&success=1");
            exit();
        } else {
            $mensagem = "Erro ao atualizar os dados do hóspede";
            $tipoMensagem = "danger";
        }
    } catch(PDOException $e) {
        $mensagem = "Erro: " . $e->getMessage();
        $tipoMensagem = "danger";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Bassan - Editar Hóspede</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
    .form-section {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
    }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="menu.php">HOTEL BASSAN</a>
            <div class="navbar-text text-white">
                <a href="consultar.php?cpf=<?= $cpf ?? '' ?>" class="text-white">Voltar</a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <h2 class="mb-4">Editar Hóspede</h2>

        <?php if ($mensagem): ?>
        <div class="alert alert-<?= $tipoMensagem ?>">
            <?= $mensagem ?>
        </div>
        <?php endif; ?>

        <?php if(isset($hospede)): ?>
        <form method="post">
            <div class="form-section">
                <h5>Informações Pessoais</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="cpf" class="form-label">CPF</label>
                        <input type="text" class="form-control" id="cpf" name="cpf"
                            value="<?= htmlspecialchars($hospede['cpf'] ?? '') ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="nome" name="nome"
                            value="<?= htmlspecialchars($hospede['nome'] ?? '') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="sobrenome" class="form-label">Sobrenome</label>
                        <input type="text" class="form-control" id="sobrenome" name="sobrenome"
                            value="<?= htmlspecialchars($hospede['sobrenome'] ?? '') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Sexo</label>
                        <select class="form-select" name="sexo" required>
                            <option value="M" <?= ($hospede['sexo'] ?? '') == 'M' ? 'selected' : '' ?>>Masculino
                            </option>
                            <option value="F" <?= ($hospede['sexo'] ?? '') == 'F' ? 'selected' : '' ?>>Feminino</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="dataNascimento" class="form-label">Data de Nascimento</label>
                        <input type="date" class="form-control" id="dataNascimento" name="dataNascimento"
                            value="<?= htmlspecialchars($hospede['dataNascimento'] ?? '') ?>" required>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                <button type="reset" class="btn btn-secondary me-md-2">
                    <i class="bi bi-x-circle"></i> Limpar
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Salvar Alterações
                </button>
            </div>
        </form>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>