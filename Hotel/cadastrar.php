<?php
require_once 'conexao.php';

$conexao = conectar();
$mensagem = '';
$tipoMensagem = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cpf = $_POST['cpf'];
    $nome = $_POST['nome'];
    $sobrenome = $_POST['sobrenome'];
    $sexo = $_POST['sexo'];
    $dataNascimento = $_POST['dataNascimento'];
    $paisOrigem = $_POST['paisOrigem'];
    $previsaoEstadia = $_POST['previsaoEstadia'];
    $ciasAereas = isset($_POST['ciasAereas']) ? implode(", ", $_POST['ciasAereas']) : '';

    try {
        $sqlHospede = "INSERT INTO hospede (cpf, nome, sobrenome, sexo, dataNascimento) 
                      VALUES (:cpf, :nome, :sobrenome, :sexo, :dataNascimento)";
        
        $stmtHospede = $conexao->prepare($sqlHospede);
        $stmtHospede->bindValue(':cpf', $cpf);
        $stmtHospede->bindValue(':nome', $nome);
        $stmtHospede->bindValue(':sobrenome', $sobrenome);
        $stmtHospede->bindValue(':sexo', $sexo);
        $stmtHospede->bindValue(':dataNascimento', $dataNascimento);
        
        if ($stmtHospede->execute()) {
            $sqlControle = "INSERT INTO controle (hospedeCpf, paisOrigem, previsaoEstadia, ciasAereas) 
                           VALUES (:cpf, :paisOrigem, :previsaoEstadia, :ciasAereas)";
            
            $stmtControle = $conexao->prepare($sqlControle);
            $stmtControle->bindValue(':cpf', $cpf);
            $stmtControle->bindValue(':paisOrigem', $paisOrigem);
            $stmtControle->bindValue(':previsaoEstadia', $previsaoEstadia);
            $stmtControle->bindValue(':ciasAereas', $ciasAereas);
            
            if ($stmtControle->execute()) {
                $mensagem = "Hóspede cadastrado com sucesso!";
                $tipoMensagem = "success";
                $_POST = array(); 
            } else {
                $mensagem = "Erro ao cadastrar informações de controle";
                $tipoMensagem = "danger";
            }
        } else {
            $mensagem = "Erro ao cadastrar hóspede";
            $tipoMensagem = "danger";
        }
    } catch (PDOException $e) {
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
    <title>Hotel Bassan - Cadastrar Hóspede</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
    body {
        background-image: url('imagens/cadastro.jpg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        min-height: 100vh;
    }

    h2 {
        color: white;
        text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.8);
    }

    .form-section {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .form-section h5 {
        color: #2c3e50;
        border-bottom: 2px solid #dee2e6;
        padding-bottom: 10px;
        margin-bottom: 20px;
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
        <h2 class="mb-4">Cadastrar Novo Hóspede</h2>

        <?php if ($mensagem): ?>
        <div class="alert alert-<?= $tipoMensagem ?>">
            <?= $mensagem ?>
        </div>
        <?php endif; ?>

        <form method="post">
            <div class="form-section">
                <h5>Informações Pessoais</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="cpf" class="form-label">CPF</label>
                        <input type="text" class="form-control" id="cpf" name="cpf" placeholder="000.000.000-00"
                            value="<?= $_POST['cpf'] ?? '' ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="nome" name="nome"
                            value="<?= $_POST['nome'] ?? '' ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="sobrenome" class="form-label">Sobrenome</label>
                        <input type="text" class="form-control" id="sobrenome" name="sobrenome"
                            value="<?= $_POST['sobrenome'] ?? '' ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Sexo</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sexo" id="sexoM" value="M"
                                    <?= ($_POST['sexo'] ?? '') == 'M' ? 'checked' : '' ?> required>
                                <label class="form-check-label" for="sexoM">Masculino</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sexo" id="sexoF" value="F"
                                    <?= ($_POST['sexo'] ?? '') == 'F' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="sexoF">Feminino</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="dataNascimento" class="form-label">Data de Nascimento</label>
                        <input type="date" class="form-control" id="dataNascimento" name="dataNascimento"
                            value="<?= $_POST['dataNascimento'] ?? '' ?>" required>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h5>Informações da Estadia</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">País de Origem</label>
                        <div class="d-flex flex-wrap gap-3">
                            <?php
                            $paises = ['Brasil', 'Argentina', 'Paraguai', 'Uruguai', 'Chile', 'Peru'];
                            foreach ($paises as $pais): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="paisOrigem"
                                    id="<?= strtolower($pais) ?>" value="<?= $pais ?>"
                                    <?= ($_POST['paisOrigem'] ?? '') == $pais ? 'checked' : '' ?> required>
                                <label class="form-check-label" for="<?= strtolower($pais) ?>"><?= $pais ?></label>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="previsaoEstadia" class="form-label">Previsão de Estadia</label>
                        <select class="form-select" id="previsaoEstadia" name="previsaoEstadia" required>
                            <option value="" disabled selected>Selecione...</option>
                            <?php
                            $estadias = ['3 dias', '5 dias', '1 semana', '2 semanas', '3 semanas ou mais'];
                            foreach ($estadias as $estadia): ?>
                            <option value="<?= $estadia ?>"
                                <?= ($_POST['previsaoEstadia'] ?? '') == $estadia ? 'selected' : '' ?>>
                                <?= $estadia ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Companhias Aéreas Utilizadas</label>
                        <div class="d-flex flex-wrap gap-3">
                            <?php
                            $companhias = ['GOL', 'AZUL', 'TRIP', 'AVIANCA', 'RISSETTI', 'GLOBAL'];
                            $ciasSelecionadas = isset($_POST['ciasAereas']) ? $_POST['ciasAereas'] : [];
                            foreach ($companhias as $cia): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="ciasAereas[]"
                                    id="<?= strtolower($cia) ?>" value="<?= $cia ?>"
                                    <?= in_array($cia, $ciasSelecionadas) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="<?= strtolower($cia) ?>"><?= $cia ?></label>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                <button type="reset" class="btn btn-secondary me-md-2">
                    <i class="bi bi-x-circle"></i> Limpar
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Cadastrar Hóspede
                </button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>