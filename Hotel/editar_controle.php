<?php
require_once 'conexao.php';
$conexao = conectar();
$mensagem = '';
$tipoMensagem = '';

if(isset($_GET['cpf'])) {
    $cpf = $_GET['cpf'];
    $sql = "SELECT * FROM controle WHERE hospedeCpf = :cpf";
    $stmt = $conexao->prepare($sql);
    $stmt->bindValue(':cpf', $cpf);
    $stmt->execute();
    
    if($stmt->rowCount() > 0) {
        $controle = $stmt->fetch(PDO::FETCH_ASSOC);
        $ciasAereas = explode(", ", $controle['ciasAereas']);
    } else {
        $mensagem = "Registro de controle não encontrado!";
        $tipoMensagem = "danger";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cpf = $_POST['cpf'];
    $paisOrigem = $_POST['paisOrigem'];
    $previsaoEstadia = $_POST['previsaoEstadia'];
    $ciasAereas = isset($_POST['ciasAereas']) ? implode(", ", $_POST['ciasAereas']) : '';
    
    try {
        $sql = "UPDATE controle SET 
                paisOrigem = :paisOrigem, 
                previsaoEstadia = :previsaoEstadia, 
                ciasAereas = :ciasAereas 
                WHERE hospedeCpf = :cpf";
        $stmt = $conexao->prepare($sql);
        $stmt->bindValue(':paisOrigem', $paisOrigem);
        $stmt->bindValue(':previsaoEstadia', $previsaoEstadia);
        $stmt->bindValue(':ciasAereas', $ciasAereas);
        $stmt->bindValue(':cpf', $cpf);
        
        if($stmt->execute()) {
            $mensagem = "Informações de controle atualizadas com sucesso!";
            $tipoMensagem = "success";
            header("Location: consultar.php?cpf=$cpf&success=1");
            exit();
        } else {
            $mensagem = "Erro ao atualizar as informações de controle";
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
    <title>Hotel Bassan - Editar Controle</title>
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
        <h2 class="mb-4">Editar Informações de Controle</h2>

        <?php if ($mensagem): ?>
        <div class="alert alert-<?= $tipoMensagem ?>">
            <?= $mensagem ?>
        </div>
        <?php endif; ?>

        <?php if(isset($controle)): ?>
        <form method="post">
            <input type="hidden" name="cpf" value="<?= htmlspecialchars($controle['hospedeCpf'] ?? '') ?>">

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
                                    <?= ($controle['paisOrigem'] ?? '') == $pais ? 'checked' : '' ?> required>
                                <label class="form-check-label" for="<?= strtolower($pais) ?>"><?= $pais ?></label>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="previsaoEstadia" class="form-label">Previsão de Estadia</label>
                        <select class="form-select" id="previsaoEstadia" name="previsaoEstadia" required>
                            <option value="" disabled>Selecione...</option>
                            <?php
                            $estadias = ['3 dias', '5 dias', '1 semana', '2 semanas', '3 semanas ou mais'];
                            foreach ($estadias as $estadia): ?>
                            <option value="<?= $estadia ?>"
                                <?= ($controle['previsaoEstadia'] ?? '') == $estadia ? 'selected' : '' ?>>
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
                            $ciasSelecionadas = isset($ciasAereas) ? $ciasAereas : [];
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
                    <i class="bi bi-save"></i> Salvar Alterações
                </button>
            </div>
        </form>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>