<?php
/**
 * Listagem de Mídias Vencidas
 * 
 * @file lista_midias_vencidas.php
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
    $agora = date('Y-m-d H:i:s');
    
    // Buscar mídias vencidas (data_fim < agora)
    $stmt = $db->prepare("
        SELECT id, titulo, descricao, tipo, data_inicio, data_fim, ativo, data_criacao 
        FROM midias 
        WHERE data_fim < :agora
        ORDER BY data_fim DESC
    ");
    
    $stmt->execute([':agora' => $agora]);
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
    <title>Mídias Vencidas - Mural Eletrônico</title>
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
                <h1 class="mb-2">⏰ Mídias Vencidas</h1>
                <p class="text-muted">Total: <strong><?php echo count($midias); ?></strong> mídia(s) vencida(s)</p>
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
            <div class="alert alert-success text-center py-5">
                <h5>✅ Nenhuma mídia vencida</h5>
                <p class="text-muted mb-0">Todas as mídias cadastradas estão dentro do período de exibição.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Tipo</th>
                            <th>Venceu em</th>
                            <th>Dias atrás</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($midias as $midia): 
                            $dataFim = new DateTime($midia['data_fim']);
                            $agora = new DateTime();
                            $diff = $agora->diff($dataFim);
                            $diasAtraso = $diff->days;
                        ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($midia['titulo']); ?></strong>
                                    <?php if ($midia['descricao']): ?>
                                        <br><small class="text-muted"><?php echo htmlspecialchars(substr($midia['descricao'], 0, 50)); ?>...</small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge badge-primary"><?php echo htmlspecialchars($midia['tipo']); ?></span>
                                </td>
                                <td><?php echo date('d/m/Y H:i', strtotime($midia['data_fim'])); ?></td>
                                <td>
                                    <span class="badge bg-warning text-dark"><?php echo $diasAtraso; ?> dia(s)</span>
                                </td>
                                <td>
                                    <span class="status-inactive">⛔ Vencida</span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
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
