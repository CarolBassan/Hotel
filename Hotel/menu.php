<?php
require_once 'conexao.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Bassan - Menu Principal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
    body {
        background-image: url('imagens/menu.jpg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        background-repeat: no-repeat;
        min-height: 100vh;
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .nav-link {
        transition: all 0.3s ease;
    }

    .nav-link:hover {
        transform: translateY(-3px);
        background: rgba(13, 110, 253, 0.1);
    }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="menu.php">HOTEL BASSAN</a>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card glass-card shadow-lg">
                    <div class="card-body text-center py-5">
                        <h1 class="display-4 mb-4">Menu Principal</h1>

                        <div class="list-group">
                            <a href="cadastrar.php" class="list-group-item list-group-item-action">
                                <i class="bi bi-person-plus me-2"></i> Cadastrar Hóspede
                            </a>
                            <a href="listar.php" class="list-group-item list-group-item-action">
                                <i class="bi bi-list-ul me-2"></i> Listar Hóspedes
                            </a>
                            <a href="consultar.php" class="list-group-item list-group-item-action">
                                <i class="bi bi-search me-2"></i> Consultar Hóspede
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>