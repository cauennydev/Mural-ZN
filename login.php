<?php
/**
 * Login Seguro - Autenticação com PDO e Proteção CSRF
 * Prático, seguro e moderno
 * 
 * @author Sistema Mural Eletrônico
 * @version 2.0
 */

// Incluir configurações
require_once __DIR__ . '/config/conexao.php';
require_once __DIR__ . '/config/validar.php';

// Inicializar variáveis
$erro = null;
$sucesso = false;

// Processar login (método POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar token CSRF
    if (!isset($_POST['csrf_token']) || !AuthValidator::validateCSRFToken($_POST['csrf_token'])) {
        $erro = "Token de segurança inválido. Tente novamente.";
    } else {
        // Sanitizar entradas
        $login = trim($_POST['login'] ?? '');
        $senha = $_POST['senha'] ?? '';
        
        // Validação básica
        if (empty($login) || empty($senha)) {
            $erro = "Usuário e senha são obrigatórios.";
        } else if (strlen($login) < 3) {
            $erro = "Usuário deve ter no mínimo 3 caracteres.";
        } else if (strlen($senha) < 4) {
            $erro = "Senha deve ter no mínimo 4 caracteres.";
        } else {
            try {
                // Preparar query com bind parameters (previne SQL injection)
                $db = getDB();
                $stmt = $db->prepare("SELECT id, login, nome, senha FROM usuarios WHERE login = :login LIMIT 1");
                $stmt->execute([':login' => $login]);
                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Verificar usuário e senha
                if ($usuario && password_verify($senha, $usuario['senha'])) {
                    // Login bem-sucedido
                    $_SESSION['user_id'] = $usuario['id'];
                    $_SESSION['login'] = $usuario['login'];
                    $_SESSION['nome'] = $usuario['nome'] ?? $usuario['login'];
                    $_SESSION['login_time'] = time();
                    
                    // Regenerar ID da sessão por segurança
                    session_regenerate_id(true);
                    
                    // Redirecionar
                    $return_url = $_GET['return'] ?? 'principal.php';
                    $return_url = filter_var(urldecode($return_url), FILTER_SANITIZE_URL);
                    
                    // Validar URL para evitar open redirect
                    if (strpos($return_url, 'http') === 0) {
                        $return_url = 'principal.php'; // URL absoluta = insegura
                    }
                    
                    header("Location: " . $return_url);
                    exit;
                } else {
                    // Falha na autenticação (registrar tentativa)
                    $erro = "Usuário ou senha inválidos.";
                    // Log: registrar tentativa de login falha (em produção)
                    error_log("Login falho para usuário: $login | IP: " . $_SERVER['REMOTE_ADDR']);
                }
            } catch (PDOException $e) {
                $erro = "Erro ao processar login. Tente novamente mais tarde.";
                error_log("Erro PDO no login: " . $e->getMessage());
            }
        }
    }
}

// Gerar token CSRF para o formulário
$csrf_token = AuthValidator::generateCSRFToken();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login - Mural Eletrônico</title>
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="css/bootstrap-5.3.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
    
    <style>
        body {
            background: linear-gradient(135deg, #37b637 0%, #37b637 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 2rem;
            max-width: 400px;
            width: 100%;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-header h1 {
            color: #333;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .form-control:focus {
            border-color: #37b637;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .btn-login {
            background: linear-gradient(135deg, #37b637 0%, #37b637 100%);
            border: none;
            padding: 0.75rem;
            font-weight: 600;
        }
        
        .btn-login:hover {
            background: linear-gradient(135deg, #37b637 0%, #37b637 100%);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Header -->
        <div class="login-header">
            <h1>🔐 Login</h1>
            <p class="text-muted">Sistema de Mural Eletrônico</p>
        </div>
        
        <!-- Alertas -->
        <?php if ($erro): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>⚠️ Erro!</strong> <?= htmlspecialchars($erro) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <!-- Formulário -->
        <form method="POST" action="" class="needs-validation" novalidate>
            <!-- CSRF Token -->
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
            
            <!-- Campo Usuário -->
            <div class="mb-3">
                <label for="login" class="form-label">Usuário</label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="login" 
                    name="login" 
                    placeholder="Digite seu usuário"
                    required 
                    minlength="3"
                    maxlength="50"
                    autocomplete="username"
                >
                <div class="form-text">Mínimo 3 caracteres</div>
            </div>
            
            <!-- Campo Senha -->
            <div class="mb-3">
                <label for="senha" class="form-label">Senha</label>
                <input 
                    type="password" 
                    class="form-control" 
                    id="senha" 
                    name="senha" 
                    placeholder="Digite sua senha"
                    required 
                    minlength="4"
                    maxlength="50"
                    autocomplete="current-password"
                >
                <div class="form-text">Mínimo 4 caracteres</div>
            </div>
            
            <!-- Botão Login -->
            <button type="submit" class="btn btn-login w-100 text-white mb-3">
                ✓ Entrar
            </button>
        </form>
        
        <!-- Links Adicionais -->
        <div class="text-center">
            <small class="text-muted">
                <a href="index.php" class="text-decoration-none">← Voltar</a>
            </small>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Validação do formulário
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('.needs-validation');
            form.addEventListener('submit', function(e) {
                if (!form.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                form.classList.add('was-validated');
            });
        });
    </script>
</body>
</html>
