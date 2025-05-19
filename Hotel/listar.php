<?php
require_once 'conexao.php';

$conexao = conectar();

$sql = "SELECT h.cpf, h.nome, h.sobrenome, c.paisOrigem 
        FROM hospede h 
        JOIN controle c ON h.cpf = c.hospedeCpf
        ORDER BY h.nome";
$stmt = $conexao->query($sql);
$hospedes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Bassan - Lista de Hóspedes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
    body {
        background-image: url('imagens/lista.jpg');
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

    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.1);
    }

    .table-actions {
        white-space: nowrap;
    }

    .card {
        background-color: rgba(255, 255, 255, 0.95);
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-white">Lista de Hóspedes</h2>
            <a href="cadastrar.php" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Novo Hóspede
            </a>
        </div>

        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="table-primary">
                            <tr>
                                <th>CPF</th>
                                <th>Nome</th>
                                <th>Sobrenome</th>
                                <th>País de Origem</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($hospedes as $hospede): ?>
                            <tr>
                                <td><?= htmlspecialchars($hospede['cpf']) ?></td>
                                <td><?= htmlspecialchars($hospede['nome']) ?></td>
                                <td><?= htmlspecialchars($hospede['sobrenome']) ?></td>
                                <td><?= htmlspecialchars($hospede['paisOrigem']) ?></td>
                                <td class="table-actions text-end">
                                    <a href="consultar.php?cpf=<?= $hospede['cpf'] ?>" class="btn btn-sm btn-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="editar_hospede.php?cpf=<?= $hospede['cpf'] ?>"
                                        class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="excluir.php?cpf=<?= $hospede['cpf'] ?>" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Tem certeza que deseja excluir este hóspede?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>