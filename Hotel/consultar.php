<?php
require_once 'conexao.php';

$conexao = conectar();

$cpf = isset($_GET['cpf']) ? $_GET['cpf'] : '';

if ($cpf) {
    $sql = "SELECT h.*, c.* FROM hospede h JOIN controle c ON h.cpf = c.hospedeCpf WHERE h.cpf = :cpf";
    $stmt = $conexao->prepare($sql);
    $stmt->bindValue(':cpf', $cpf);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $hospede = $stmt->fetch(PDO::FETCH_ASSOC);
        $ciasAereas = explode(", ", $hospede['ciasAereas']);
    } else {
        $naoEncontrado = true;
    }
}

if (isset($_GET['success'])) {
    $mensagem = "Operação realizada com sucesso!";
    $tipoMensagem = "success";
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Bassan - Consultar Hóspede</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
    body {
        background-image: url('imagens/hospedes.png');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        min-height: 100vh;
    }

    body::before {
        content: "";
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.4);
        z-index: -1;
    }

    .info-card {
        border-left: 5px solid #0d6efd;
    }

    .cias-aereas-badge {
        margin-right: 5px;
        margin-bottom: 5px;
    }

    .card {
        background-color: rgba(255, 255, 255, 0.95);
    }

    h2 {
        color: white;
    }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="menu.php">HOTEL BASSAN</a>
            <div class="navbar-text text-white">
                <a href="menu.php" class="text-white">Voltar ao Menu</a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <h2 class="mb-4">Consultar Hóspede</h2>

        <?php if (isset($mensagem)): ?>
        <div class="alert alert-<?= $tipoMensagem ?>">
            <?= $mensagem ?>
        </div>
        <?php endif; ?>

        <div class="card mb-4">
            <div class="card-body">
                <form method="get">
                    <div class="input-group">
                        <input type="text" class="form-control form-control-lg" name="cpf"
                            placeholder="Digite o CPF do hóspede" value="<?= htmlspecialchars($cpf) ?>">
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-search"></i> Buscar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <?php if (isset($naoEncontrado)): ?>
        <div class="alert alert-warning">
            Nenhum hóspede encontrado com o CPF informado.
        </div>
        <?php elseif (isset($hospede)): ?>
        <div class="card info-card mb-4">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Informações do Hóspede</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Dados Pessoais</h5>
                        <ul class="list-group list-group-flush mb-4">
                            <li class="list-group-item">
                                <strong>CPF:</strong> <?= htmlspecialchars($hospede['cpf']) ?>
                            </li>
                            <li class="list-group-item">
                                <strong>Nome Completo:</strong> <?= htmlspecialchars($hospede['nome']) ?>
                                <?= htmlspecialchars($hospede['sobrenome']) ?>
                            </li>
                            <li class="list-group-item">
                                <strong>Sexo:</strong> <?= $hospede['sexo'] == 'M' ? 'Masculino' : 'Feminino' ?>
                            </li>
                            <li class="list-group-item">
                                <strong>Data de Nascimento:</strong>
                                <?= date('d/m/Y', strtotime($hospede['dataNascimento'])) ?>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h5>Informações da Estadia</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <strong>País de Origem:</strong> <?= htmlspecialchars($hospede['paisOrigem']) ?>
                            </li>
                            <li class="list-group-item">
                                <strong>Previsão de Estadia:</strong>
                                <?= htmlspecialchars($hospede['previsaoEstadia']) ?>
                            </li>
                            <li class="list-group-item">
                                <strong>Companhias Aéreas:</strong><br>
                                <?php foreach ($ciasAereas as $cia): ?>
                                <span
                                    class="badge bg-info text-dark cias-aereas-badge"><?= htmlspecialchars($cia) ?></span>
                                <?php endforeach; ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-light">
                <div class="d-flex justify-content-end gap-2">
                    <a href="editar_hospede.php?cpf=<?= $hospede['cpf'] ?>" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Editar Dados
                    </a>
                    <a href="editar_controle.php?cpf=<?= $hospede['cpf'] ?>" class="btn btn-info">
                        <i class="bi bi-calendar-event"></i> Editar Estadia
                    </a>
                    <a href="excluir.php?cpf=<?= $hospede['cpf'] ?>" class="btn btn-danger"
                        onclick="return confirm('Tem certeza que deseja excluir este hóspede?')">
                        <i class="bi bi-trash"></i> Excluir
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>