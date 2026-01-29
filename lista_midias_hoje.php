<?php
/**
 * Listagem de Mídias do Dia Atual
 * 
 * @file lista_midias_hoje.php
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

$errorMsg = '';
$midias = [];

try {
    $db = DatabaseConnection::getInstance()->getDB();
    $hoje = date('Y-m-d');
    
    // Buscar mídias ativas hoje
    $stmt = $db->prepare("
        SELECT id, titulo, descricao, tipo, data_inicio, data_fim, ativo, data_criacao 
        FROM midias 
        WHERE ativo = 1
        AND DATE(data_inicio) <= :hoje 
        AND DATE(data_fim) >= :hoje
        ORDER BY data_inicio ASC
    ");
    
    $stmt->execute([':hoje' => $hoje]);
    $midias = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $errorMsg = 'Erro ao buscar mídias: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mídias de Hoje - Mural Eletrônico</title>
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
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-12">
                <h1 class="mb-2">📹 Mídias de Hoje</h1>
                <p class="text-muted">
                    Data: <strong><?php echo date('d/m/Y'); ?></strong> - 
                    Total: <strong><?php echo count($midias); ?></strong> mídia(s) ativa(s)
                </p>
            </div>
        </div>

        <!-- Mensagens -->
        <?php if ($errorMsg): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                ❌ <?php echo htmlspecialchars($errorMsg); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Lista -->
        <?php if (empty($midias)): ?>
            <div class="alert alert-info text-center py-5">
                <h5>Nenhuma mídia ativa para hoje</h5>
                <p class="text-muted mb-3">Não há mídias agendadas para serem exibidas hoje.</p>
                <a href="lista_midias.php" class="btn btn-sm btn-primary">Ver todas as mídias</a>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($midias as $midia): ?>
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 feature-card">
                            <div class="card-body">
                                <h5 class="card-title mb-2">
                                    <?php echo htmlspecialchars($midia['titulo']); ?>
                                </h5>
                                
                                <?php if ($midia['descricao']): ?>
                                    <p class="card-text text-muted mb-3">
                                        <?php echo htmlspecialchars(substr($midia['descricao'], 0, 150)); ?>
                                        <?php echo strlen($midia['descricao']) > 150 ? '...' : ''; ?>
                                    </p>
                                <?php endif; ?>

                                <div class="mb-3">
                                    <span class="badge badge-primary"><?php echo htmlspecialchars($midia['tipo']); ?></span>
                                    <span class="badge bg-success">✅ Ativo</span>
                                </div>

                                <div class="small text-muted">
                                    <div class="mb-1">
                                        <strong>Início:</strong> <?php echo date('H:i', strtotime($midia['data_inicio'])); ?>
                                    </div>
                                    <div>
                                        <strong>Fim:</strong> <?php echo date('H:i', strtotime($midia['data_fim'])); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Botões de Ação -->
        <div class="mt-4 d-flex gap-2">
            <a href="principal.php" class="btn btn-outline-secondary">← Voltar</a>
            <a href="lista_midias.php" class="btn btn-outline-primary">📋 Ver Todas</a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="mt-5 py-4">
        <div class="container text-center">
            <p class="mb-0">&copy; 2026 Sistema Mural Eletrônico - IFRO</p>
        </div>
    </footer>

    <script src="js/bootstrap-5.3.bundle.min.js"></script>
    <script src="js/bootstrap-init.js"></script>
</body>
</html>
