<?php
/**
 * Formulário de Cadastro de Mídia
 * 
 * @file form_cadastro_midia.php
 * @version 2.0
 */

// Verifica autenticação
session_start();

try {
    require_once __DIR__ . '/config/validar.php';
    require_once __DIR__ . '/config/conexao.php';
    
    AuthValidator::requireLogin();
    $currentUser = AuthValidator::getCurrentUser();
} catch (Exception $e) {
    header('Location: login.php?error=1');
    exit;
}

$csrfToken = AuthValidator::generateCSRFToken();
$successMsg = '';
$errorMsg = '';

// Processa formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!AuthValidator::validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $errorMsg = 'Token de segurança inválido!';
    } else {
        try {
            $titulo = trim($_POST['titulo'] ?? '');
            $descricao = trim($_POST['descricao'] ?? '');
            $arquivo = trim($_POST['arquivo'] ?? '');
            $tipo = trim($_POST['tipo'] ?? '');
            $data_inicio = trim($_POST['data_inicio'] ?? '');
            $data_fim = trim($_POST['data_fim'] ?? '');
            $ativo = isset($_POST['ativo']) ? 1 : 0;

            // Validação
            if (empty($titulo)) {
                throw new Exception('Título é obrigatório');
            }
            if (empty($tipo)) {
                throw new Exception('Tipo é obrigatório');
            }
            if (empty($data_inicio)) {
                throw new Exception('Data de início é obrigatória');
            }
            if (empty($data_fim)) {
                throw new Exception('Data de término é obrigatória');
            }

            // Validar datas
            $start = strtotime($data_inicio);
            $end = strtotime($data_fim);
            if ($start === false || $end === false) {
                throw new Exception('Formato de data inválido');
            }
            if ($start > $end) {
                throw new Exception('Data de início deve ser anterior à data de término');
            }

            // Insert no banco
            $db = DatabaseConnection::getInstance()->getDB();
            $stmt = $db->prepare("
                INSERT INTO midias (titulo, descricao, arquivo, tipo, data_inicio, data_fim, ativo, data_criacao)
                VALUES (:titulo, :descricao, :arquivo, :tipo, :data_inicio, :data_fim, :ativo, NOW())
            ");

            $result = $stmt->execute([
                ':titulo' => $titulo,
                ':descricao' => $descricao,
                ':arquivo' => $arquivo,
                ':tipo' => $tipo,
                ':data_inicio' => $data_inicio,
                ':data_fim' => $data_fim,
                ':ativo' => $ativo
            ]);

            if ($result) {
                $successMsg = 'Mídia cadastrada com sucesso!';
                // Limpar form
                $_POST = [];
            }
        } catch (Exception $e) {
            $errorMsg = $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Mídia - Mural Eletrônico</title>
    <link href="css/bootstrap-5.3.min.css" rel="stylesheet">
    <link href="css/estilo.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg" style="background: linear-gradient(135deg, #37b637 0%, #37b637 100%);">
        <div class="container-fluid">
            <a class="navbar-brand text-white" href="principal.php">📺 Mural Eletrônico</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <span class="nav-link text-white">
                            👤 <?php echo htmlspecialchars($currentUser['nome']); ?>
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="logout.php">Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row mb-4">
            <div class="col-md-12">
                <h1 class="mb-2">📹 Cadastro de Mídia</h1>
                <p class="text-muted">Adicione uma nova mídia ao mural eletrônico</p>
            </div>
        </div>

        <!-- Mensagens -->
        <?php if ($successMsg): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                ✅ <?php echo htmlspecialchars($successMsg); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if ($errorMsg): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                ❌ <?php echo htmlspecialchars($errorMsg); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Formulário -->
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-body p-4">
                        <form method="POST" action="" class="needs-validation" novalidate>
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">

                            <!-- Título -->
                            <div class="mb-3">
                                <label for="titulo" class="form-label">Título da Mídia</label>
                                <input type="text" class="form-control" id="titulo" name="titulo" 
                                    value="<?php echo htmlspecialchars($_POST['titulo'] ?? ''); ?>"
                                    maxlength="255" required>
                                <small class="form-text text-muted">Máximo 255 caracteres</small>
                            </div>

                            <!-- Descrição -->
                            <div class="mb-3">
                                <label for="descricao" class="form-label">Descrição</label>
                                <textarea class="form-control" id="descricao" name="descricao" rows="4" 
                                    maxlength="1000"><?php echo htmlspecialchars($_POST['descricao'] ?? ''); ?></textarea>
                                <small class="form-text text-muted">Máximo 1000 caracteres</small>
                            </div>

                            <!-- Arquivo -->
                            <div class="mb-3">
                                <label for="arquivo" class="form-label">Arquivo/URL</label>
                                <input type="text" class="form-control" id="arquivo" name="arquivo" 
                                    value="<?php echo htmlspecialchars($_POST['arquivo'] ?? ''); ?>"
                                    placeholder="caminho/arquivo.mp4 ou URL">
                            </div>

                            <!-- Tipo de Mídia -->
                            <div class="mb-3">
                                <label for="tipo" class="form-label">Tipo de Mídia</label>
                                <select class="form-select" id="tipo" name="tipo" required>
                                    <option value="">Selecione um tipo...</option>
                                    <option value="video" <?php echo ($_POST['tipo'] ?? '') === 'video' ? 'selected' : ''; ?>>Vídeo</option>
                                    <option value="imagem" <?php echo ($_POST['tipo'] ?? '') === 'imagem' ? 'selected' : ''; ?>>Imagem</option>
                                    <option value="audio" <?php echo ($_POST['tipo'] ?? '') === 'audio' ? 'selected' : ''; ?>>Áudio</option>
                                    <option value="pdf" <?php echo ($_POST['tipo'] ?? '') === 'pdf' ? 'selected' : ''; ?>>PDF</option>
                                </select>
                            </div>

                            <!-- Datas -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="data_inicio" class="form-label">Data de Início</label>
                                    <input type="datetime-local" class="form-control" id="data_inicio" name="data_inicio" 
                                        value="<?php echo htmlspecialchars($_POST['data_inicio'] ?? ''); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="data_fim" class="form-label">Data de Término</label>
                                    <input type="datetime-local" class="form-control" id="data_fim" name="data_fim" 
                                        value="<?php echo htmlspecialchars($_POST['data_fim'] ?? ''); ?>" required>
                                </div>
                            </div>

                            <!-- Ativo -->
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="ativo" name="ativo" value="1"
                                    <?php echo (($_POST['ativo'] ?? '') == '1') ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="ativo">
                                    Ativo (exibir no mural)
                                </label>
                            </div>

                            <!-- Botões -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    ✅ Cadastrar Mídia
                                </button>
                                <a href="lista_midias.php" class="btn btn-outline-secondary">
                                    ← Voltar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="mt-5 py-4">
        <div class="container text-center">
            <p class="mb-0">&copy; 2025 Sistema Mural Eletrônico - IFRO</p>
        </div>
    </footer>

    <script src="js/bootstrap-5.3.bundle.min.js"></script>
    <script src="js/bootstrap-init.js"></script>
</body>
</html>
