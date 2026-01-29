<?php
/**
 * Formulário de Cadastro de Usuário
 * 
 * @file form_cadastro_usuario.php
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
$usuarioId = intval($_GET['id'] ?? 0);
$usuario = null;
$isEdit = false;

try {
    $db = DatabaseConnection::getInstance()->getDB();
    
    // Se é edição, busca usuário
    if ($usuarioId > 0) {
        $stmt = $db->prepare("SELECT id, login, nome, email, ativo FROM usuarios WHERE id = :id");
        $stmt->execute([':id' => $usuarioId]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$usuario) {
            throw new Exception('Usuário não encontrado');
        }
        $isEdit = true;
    }
} catch (Exception $e) {
    $errorMsg = $e->getMessage();
}

// Processa formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!AuthValidator::validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $errorMsg = 'Token de segurança inválido!';
    } else {
        try {
            $nome = trim($_POST['nome'] ?? '');
            $login = trim($_POST['login'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $senha = trim($_POST['senha'] ?? '');
            $confirma_senha = trim($_POST['confirma_senha'] ?? '');
            $ativo = isset($_POST['ativo']) ? 1 : 0;

            // Validações
            if (empty($nome)) {
                throw new Exception('Nome é obrigatório');
            }
            if (empty($login)) {
                throw new Exception('Login é obrigatório');
            }
            if (empty($email)) {
                throw new Exception('Email é obrigatório');
            }
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Email inválido');
            }

            // Validar login único (exceto se for edição do mesmo usuário)
            $stmt = $db->prepare("SELECT id FROM usuarios WHERE login = :login" . ($isEdit ? " AND id != :id" : ""));
            $params = [':login' => $login];
            if ($isEdit) {
                $params[':id'] = $usuarioId;
            }
            $stmt->execute($params);
            if ($stmt->fetchColumn()) {
                throw new Exception('Login já existe no sistema');
            }

            // Se é novo usuário ou mudou a senha
            if (!$isEdit || !empty($senha)) {
                if (empty($senha)) {
                    throw new Exception('Senha é obrigatória para novo usuário');
                }
                if (strlen($senha) < 6) {
                    throw new Exception('Senha deve ter no mínimo 6 caracteres');
                }
                if ($senha !== $confirma_senha) {
                    throw new Exception('Senhas não conferem');
                }
                $senhaHash = password_hash($senha, PASSWORD_BCRYPT);
            }

            if ($isEdit) {
                // Atualizar usuário
                $query = "UPDATE usuarios SET nome = :nome, login = :login, email = :email, ativo = :ativo";
                if (!empty($senha)) {
                    $query .= ", senha = :senha";
                }
                $query .= " WHERE id = :id";
                
                $stmt = $db->prepare($query);
                $params = [
                    ':nome' => $nome,
                    ':login' => $login,
                    ':email' => $email,
                    ':ativo' => $ativo,
                    ':id' => $usuarioId
                ];
                if (!empty($senha)) {
                    $params[':senha'] = $senhaHash;
                }
                $stmt->execute($params);
                $successMsg = 'Usuário atualizado com sucesso!';
            } else {
                // Inserir novo usuário
                $stmt = $db->prepare("
                    INSERT INTO usuarios (nome, login, email, senha, ativo, data_criacao)
                    VALUES (:nome, :login, :email, :senha, :ativo, NOW())
                ");
                $stmt->execute([
                    ':nome' => $nome,
                    ':login' => $login,
                    ':email' => $email,
                    ':senha' => $senhaHash,
                    ':ativo' => $ativo
                ]);
                $successMsg = 'Usuário cadastrado com sucesso!';
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
    <title><?php echo $isEdit ? 'Editar' : 'Cadastrar'; ?> Usuário - Mural Eletrônico</title>
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
                <h1 class="mb-2">👤 <?php echo $isEdit ? 'Editar Usuário' : 'Novo Usuário'; ?></h1>
                <p class="text-muted"><?php echo $isEdit ? 'Atualize as informações do usuário' : 'Cadastre um novo usuário no sistema'; ?></p>
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

                            <!-- Nome -->
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome Completo</label>
                                <input type="text" class="form-control" id="nome" name="nome" 
                                    value="<?php echo htmlspecialchars($usuario['nome'] ?? $_POST['nome'] ?? ''); ?>"
                                    maxlength="100" required>
                            </div>

                            <!-- Login -->
                            <div class="mb-3">
                                <label for="login" class="form-label">Login/Usuário</label>
                                <input type="text" class="form-control" id="login" name="login" 
                                    value="<?php echo htmlspecialchars($usuario['login'] ?? $_POST['login'] ?? ''); ?>"
                                    maxlength="50" required
                                    <?php echo $isEdit ? 'readonly' : ''; ?>>
                                <small class="form-text text-muted">Identificação única do usuário</small>
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                    value="<?php echo htmlspecialchars($usuario['email'] ?? $_POST['email'] ?? ''); ?>"
                                    maxlength="100" required>
                            </div>

                            <!-- Senha -->
                            <div class="mb-3">
                                <label for="senha" class="form-label">
                                    Senha
                                    <?php echo $isEdit ? '<span class="text-muted">(deixar em branco para manter a mesma)</span>' : ''; ?>
                                </label>
                                <input type="password" class="form-control" id="senha" name="senha" 
                                    minlength="6"
                                    <?php echo !$isEdit ? 'required' : ''; ?>>
                                <small class="form-text text-muted">Mínimo 6 caracteres</small>
                            </div>

                            <!-- Confirmar Senha -->
                            <div class="mb-3">
                                <label for="confirma_senha" class="form-label">Confirmar Senha</label>
                                <input type="password" class="form-control" id="confirma_senha" name="confirma_senha" 
                                    minlength="6"
                                    <?php echo !$isEdit ? 'required' : ''; ?>>
                            </div>

                            <!-- Ativo -->
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="ativo" name="ativo" value="1"
                                    <?php echo (($usuario['ativo'] ?? $_POST['ativo'] ?? '') == '1') ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="ativo">
                                    Usuário Ativo (pode fazer login)
                                </label>
                            </div>

                            <!-- Botões -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    ✅ <?php echo $isEdit ? 'Atualizar Usuário' : 'Cadastrar Usuário'; ?>
                                </button>
                                <a href="lista_usuarios.php" class="btn btn-outline-secondary">
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
