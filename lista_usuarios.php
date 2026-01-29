<?php
/**
 * Listagem de Usuários
 * 
 * @file lista_usuarios.php
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

$successMsg = '';
$errorMsg = '';
$usuarios = [];
$filtro = trim($_GET['filtro'] ?? '');
$pagina = max(1, intval($_GET['page'] ?? 1));
$itens_por_pagina = 10;

try {
    $db = DatabaseConnection::getInstance()->getDB();
    
    // Contar total
    $queryCount = "SELECT COUNT(*) as total FROM usuarios WHERE 1=1";
    if ($filtro) {
        $queryCount .= " AND (nome LIKE :filtro OR login LIKE :filtro OR email LIKE :filtro)";
    }
    
    $stmt = $db->prepare($queryCount);
    if ($filtro) {
        $stmt->execute([':filtro' => "%{$filtro}%"]);
    } else {
        $stmt->execute();
    }
    $total = $stmt->fetchColumn();
    $total_paginas = ceil($total / $itens_por_pagina);
    $offset = ($pagina - 1) * $itens_por_pagina;
    
    // Buscar usuários
    $query = "SELECT id, nome, login, email, ativo, data_criacao 
              FROM usuarios WHERE 1=1";
    if ($filtro) {
        $query .= " AND (nome LIKE :filtro OR login LIKE :filtro OR email LIKE :filtro)";
    }
    $query .= " ORDER BY data_criacao DESC LIMIT :offset, :limit";
    
    $stmt = $db->prepare($query);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $itens_por_pagina, PDO::PARAM_INT);
    if ($filtro) {
        $stmt->execute([':filtro' => "%{$filtro}%"]);
    } else {
        $stmt->execute();
    }
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Processa exclusão
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
        if (!AuthValidator::validateCSRFToken($_POST['csrf_token'] ?? '')) {
            $errorMsg = 'Token de segurança inválido!';
        } else {
            $id = intval($_POST['id']);
            // Previne deletar o próprio usuário
            if ($id === $currentUser['id']) {
                $errorMsg = 'Você não pode deletar sua própria conta!';
            } else {
                $stmt = $db->prepare("DELETE FROM usuarios WHERE id = :id");
                if ($stmt->execute([':id' => $id])) {
                    $successMsg = 'Usuário removido com sucesso!';
                    header("Location: lista_usuarios.php?page=1");
                    exit;
                }
            }
        }
    }
} catch (Exception $e) {
    $errorMsg = 'Erro ao buscar usuários: ' . $e->getMessage();
}

$csrfToken = AuthValidator::generateCSRFToken();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuários - Mural Eletrônico</title>
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
            <div class="col-md-6">
                <h1 class="mb-2">👥 Usuários do Sistema</h1>
                <p class="text-muted">Total: <strong><?php echo $total; ?></strong> usuário(s)</p>
            </div>
            <div class="col-md-6 text-end">
                <a href="form_cadastro_usuario.php" class="btn btn-primary">
                    ➕ Novo Usuário
                </a>
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

        <!-- Filtro -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-6">
                        <input type="text" name="filtro" class="form-control" placeholder="Buscar por nome, login ou email..."
                            value="<?php echo htmlspecialchars($filtro); ?>">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-outline-primary w-100">🔍 Buscar</button>
                    </div>
                    <?php if ($filtro): ?>
                        <div class="col-md-2">
                            <a href="lista_usuarios.php" class="btn btn-outline-secondary w-100">✕ Limpar</a>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <!-- Tabela -->
        <?php if (empty($usuarios)): ?>
            <div class="alert alert-info text-center py-5">
                <h5>Nenhum usuário encontrado</h5>
                <p class="mb-0">
                    <a href="form_cadastro_usuario.php" class="btn btn-sm btn-primary mt-2">
                        ➕ Cadastrar primeiro usuário
                    </a>
                </p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Login</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Cadastro</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $usuario_item): 
                            $ativo = $usuario_item['ativo'] ? '✅ Ativo' : '⛔ Inativo';
                            $statusClass = $usuario_item['ativo'] ? 'status-active' : 'status-inactive';
                            $isCurrentUser = $usuario_item['id'] === $currentUser['id'];
                        ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($usuario_item['nome']); ?></strong>
                                    <?php if ($isCurrentUser): ?>
                                        <br><span class="badge badge-info">Você</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($usuario_item['login']); ?></td>
                                <td><?php echo htmlspecialchars($usuario_item['email']); ?></td>
                                <td>
                                    <span class="<?php echo $statusClass; ?>"><?php echo $ativo; ?></span>
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($usuario_item['data_criacao'])); ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="form_cadastro_usuario.php?id=<?php echo $usuario_item['id']; ?>" 
                                            class="btn btn-warning">
                                            ✏️ Editar
                                        </a>
                                        <?php if (!$isCurrentUser): ?>
                                            <form method="POST" style="display:inline;" 
                                                onsubmit="return confirm('Tem certeza que deseja remover este usuário?');">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?php echo $usuario_item['id']; ?>">
                                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
                                                <button type="submit" class="btn btn-danger">🗑️ Remover</button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            <?php if ($total_paginas > 1): ?>
                <nav class="d-flex justify-content-center mt-4" aria-label="Paginação">
                    <ul class="pagination">
                        <?php if ($pagina > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="lista_usuarios.php?page=1&filtro=<?php echo urlencode($filtro); ?>">Primeira</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="lista_usuarios.php?page=<?php echo $pagina - 1; ?>&filtro=<?php echo urlencode($filtro); ?>">Anterior</a>
                            </li>
                        <?php endif; ?>
                        
                        <?php for ($i = max(1, $pagina - 2); $i <= min($total_paginas, $pagina + 2); $i++): ?>
                            <li class="page-item <?php echo $i === $pagina ? 'active' : ''; ?>">
                                <a class="page-link" href="lista_usuarios.php?page=<?php echo $i; ?>&filtro=<?php echo urlencode($filtro); ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                        
                        <?php if ($pagina < $total_paginas): ?>
                            <li class="page-item">
                                <a class="page-link" href="lista_usuarios.php?page=<?php echo $pagina + 1; ?>&filtro=<?php echo urlencode($filtro); ?>">Próxima</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="lista_usuarios.php?page=<?php echo $total_paginas; ?>&filtro=<?php echo urlencode($filtro); ?>">Última</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Botões de Ação -->
        <div class="mt-4 d-flex gap-2">
            <a href="principal.php" class="btn btn-outline-secondary">← Voltar</a>
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
