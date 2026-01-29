<?php
/**
 * Formulário de Limpeza de Dados
 * Remove mídias vencidas do sistema
 * 
 * @file form_limpa_dados.php
 * @version 2.0
 */

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
$midias_vencidas_count = 0;

try {
    $db = DatabaseConnection::getInstance()->getDB();
    $agora = date('Y-m-d H:i:s');
    
    // Contar mídias vencidas
    $stmt = $db->prepare("SELECT COUNT(*) as total FROM midias WHERE data_fim < :agora");
    $stmt->execute([':agora' => $agora]);
    $midias_vencidas_count = $stmt->fetchColumn();
} catch (Exception $e) {
    $errorMsg = 'Erro ao contar mídias vencidas: ' . $e->getMessage();
}

// Processa limpeza
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!AuthValidator::validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $errorMsg = 'Token de segurança inválido!';
    } else {
        try {
            $acao = trim($_POST['acao'] ?? '');
            
            if ($acao === 'limpar_vencidas') {
                $stmt = $db->prepare("DELETE FROM midias WHERE data_fim < NOW()");
                if ($stmt->execute()) {
                    $successMsg = 'Mídias vencidas removidas com sucesso!';
                    $midias_vencidas_count = 0;
                } else {
                    throw new Exception('Erro ao remover mídias vencidas');
                }
            } elseif ($acao === 'limpar_todos') {
                // Confirmação adicional necessária
                if (!isset($_POST['confirmacao']) || $_POST['confirmacao'] !== 'SIM') {
                    throw new Exception('Confirmação não foi digitada. Operação cancelada.');
                }
                
                // Manter apenas dados de auditoria
                $stmt = $db->prepare("DELETE FROM midias");
                $stmt->execute();
                
                $successMsg = 'Todas as mídias foram removidas com sucesso!';
                $midias_vencidas_count = 0;
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
    <title>Limpeza de Dados - Mural Eletrônico</title>
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
                        <span class="nav-link text-white">👤 <?php echo htmlspecialchars($currentUser['nome']); ?></span>
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
                <h1 class="mb-2">🧹 Limpeza de Dados</h1>
                <p class="text-muted">Remove mídias vencidas ou todos os dados do sistema</p>
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

        <!-- Opções de Limpeza -->
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <!-- Opção 1: Limpar Vencidas -->
                <div class="card mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">⚠️ Remover Mídias Vencidas</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">
                            Remove todas as mídias cuja data de término já passou.
                        </p>
                        
                        <div class="alert alert-info mb-3">
                            <strong>Mídias vencidas encontradas:</strong> <span class="badge bg-info"><?php echo $midias_vencidas_count; ?></span>
                        </div>

                        <?php if ($midias_vencidas_count > 0): ?>
                            <form method="POST" class="needs-validation">
                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
                                <input type="hidden" name="acao" value="limpar_vencidas">
                                
                                <div class="alert alert-warning mb-3">
                                    <strong>⚠️ Aviso:</strong> Esta ação removerá permanentemente <?php echo $midias_vencidas_count; ?> mídia(s) vencida(s). 
                                    Esta ação NÃO pode ser desfeita!
                                </div>

                                <button type="submit" class="btn btn-warning" 
                                    onclick="return confirm('Tem certeza? Mídias vencidas serão removidas permanentemente!');">
                                    🗑️ Remover Mídias Vencidas
                                </button>
                            </form>
                        <?php else: ?>
                            <div class="alert alert-success mb-0">
                                ✅ Nenhuma mídia vencida encontrada. Sistema limpo!
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Opção 2: Limpar Tudo -->
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">🚨 Remover Todas as Mídias</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">
                            Remove TODAS as mídias cadastradas no sistema, independente da data.
                        </p>

                        <div class="alert alert-danger mb-3">
                            <strong>⚠️ ATENÇÃO!</strong> Esta ação removerá PERMANENTEMENTE todas as mídias!<br>
                            Esta ação NÃO pode ser desfeita!
                        </div>

                        <form method="POST" class="needs-validation" onsubmit="return validateLimparTudo();">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
                            <input type="hidden" name="acao" value="limpar_todos">

                            <div class="mb-3">
                                <label for="confirmacao" class="form-label">
                                    Para confirmar, digite: <strong>SIM</strong>
                                </label>
                                <input type="text" class="form-control border-danger" id="confirmacao" 
                                    name="confirmacao" placeholder="Digite SIM para confirmar" maxlength="3">
                            </div>

                            <button type="submit" class="btn btn-danger">
                                🗑️ Remover Todas as Mídias
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Botão Voltar -->
                <div class="mt-4 d-flex gap-2">
                    <a href="principal.php" class="btn btn-outline-secondary">← Voltar</a>
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
    
    <script>
    function validateLimparTudo() {
        const confirmacao = document.getElementById('confirmacao').value.trim().toUpperCase();
        if (confirmacao !== 'SIM') {
            alert('Por favor, digite SIM para confirmar a limpeza de dados.');
            return false;
        }
        return confirm('⚠️ ÚLTIMA CONFIRMAÇÃO\n\nTodos os dados serão removidos permanentemente!\n\nDeseja continuar?');
    }
    </script>
</body>
</html>
