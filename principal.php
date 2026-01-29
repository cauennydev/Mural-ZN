<?php
/**
 * Dashboard Principal - Painel Administrativo
 * Acesso restrito a usuários autenticados
 * 
 * @author Sistema Mural Eletrônico
 * @version 2.0
 */

// Incluir configurações
require_once __DIR__ . '/config/conexao.php';
require_once __DIR__ . '/config/validar.php';

// Verificar autenticação
AuthValidator::requireLogin();

// Obter usuário atual
$usuario_atual = AuthValidator::getCurrentUser();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Dashboard Administrativo do Mural Eletrônico">
    
    <title>Dashboard - Mural Eletrônico</title>
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="css/bootstrap-5.3.min.css" rel="stylesheet"> <!-- integrity e crossorigin removidos para simplificação -->
    <link rel="stylesheet" href="css/estilo.css"> <!-- CSS Personalizado -->
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon"> <!-- Favicon (img)-->
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        
        .navbar {
            background: linear-gradient(135deg, #37b637 0%, #37b637 100%);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.3rem;
        }
        
        .sidebar {
            background: white;
            border-radius: 8px;
            padding: 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            height: fit-content;
            position: sticky;
            top: 20px;
        }
        
        .nav-item {
            border-bottom: 1px solid #f0f0f0;
        }
        
        .nav-item:last-child {
            border-bottom: none;
        }
        
        .nav-link {
            color: #333;
            padding: 1rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .nav-link:hover {
            background-color: #f8f9fa;
            color: #37b637;
            padding-left: 1.5rem;
        }
        
        .dashboard-card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border-left: 4px solid #00bb00;
        }
        
        .dashboard-card h3 {
            color: #333;
            margin-bottom: 1rem;
            font-weight: 600;
        }
        
        .btn-menu {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            justify-content: space-between;
            padding: 1rem;
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s ease;
            margin-bottom: 1rem;
        }
        
        .btn-menu:hover {
            background: linear-gradient(135deg, #37b637 0%, #37b637 100%);
            color: white;
            border-color: transparent;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
            text-decoration: none;
        }
        
        .welcome-section {
            background: linear-gradient(135deg, #37b637 0%, #37b637 100%);
            color: white;
            padding: 2rem;
            border-radius: 8px;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">🎯 Mural Eletrônico</a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            👤 <?= htmlspecialchars($usuario_atual['nome']) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#">Perfil</a></li>
                            <li><a class="dropdown-item" href="#">Configurações</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php">🚪 Sair</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Conteúdo Principal -->
    <main class="container-lg py-4">
        <!-- Seção de Boas-vindas -->
        <div class="welcome-section">
            <h1>👋 Bem-vindo, <?= htmlspecialchars($usuario_atual['nome']) ?>!</h1>
            <p class="mb-0">Gerencie o conteúdo do seu mural eletrônico de forma simples e eficiente.</p>
        </div>
        
        <!-- Grid Principal -->
        <div class="row">
            <!-- Sidebar (Navegação) -->
            <aside class="col-lg-3 mb-4 mb-lg-0">
                <div class="sidebar">
                    <div class="nav-item">
                        <a href="lista_midias.php" class="nav-link">
                            📋 Listar Mídias
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="form_cadastro_midia.php" class="nav-link">
                            ➕ Cadastrar Mídia
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="lista_midias_hoje.php" class="nav-link">
                            📅 Mídias de Hoje
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="lista_midias_vencidas.php" class="nav-link">
                            ⏰ Mídias Vencidas
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="lista_usuarios.php" class="nav-link">
                            👥 Usuários
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="form_cadastro_usuario.php" class="nav-link">
                            🆕 Novo Usuário
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="form_limpa_dados.php" class="nav-link">
                            🗑️ Limpar Dados
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="sobre.html" class="nav-link">
                            ℹ️ Sobre
                        </a>
                    </div>
                </div>
            </aside>
            
            <!-- Conteúdo Principal -->
            <section class="col-lg-9">
                <!-- Ações Rápidas -->
                <div class="dashboard-card">
                    <h3>🚀 Ações Rápidas</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <a href="form_cadastro_midia.php" class="btn-menu">
                                <span>Adicionar Mídia</span>
                                <span>→</span>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="lista_midias.php" class="btn-menu">
                                <span>Ver Todas as Mídias</span>
                                <span>→</span>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="form_cadastro_usuario.php" class="btn-menu">
                                <span>Novo Usuário</span>
                                <span>→</span>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="mural.php" class="btn-menu">
                                <span>Visualizar Mural</span>
                                <span>→</span>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Informações do Sistema -->
                <div class="dashboard-card">
                    <h3>ℹ️ Informações do Sistema</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Versão:</strong> 2.0</p>
                            <p><strong>Framework:</strong> Bootstrap 5.3</p>
                            <p><strong>PHP Version:</strong> 7.4+</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Banco de Dados:</strong> MySQL</p>
                            <p><strong>Segurança:</strong> PDO + CSRF Token</p>
                            <p><strong>Último Login:</strong> Agora</p>
                        </div>
                    </div>
                </div>
                
                <!-- Links de Documentação -->
                <div class="dashboard-card">
                    <h3>📚 Recursos Úteis</h3>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <a href="sobre.html" class="text-decoration-none">Sobre o Sistema</a>
                        </li>
                        <li class="list-group-item">
                            <a href="#" class="text-decoration-none">Documentação</a>
                        </li>
                        <li class="list-group-item">
                            <a href="#" class="text-decoration-none">Contato - Suporte</a>
                        </li>
                    </ul>
                </div>
            </section>
        </div>
    </main>
    
    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-4 mt-5">
        <p class="mb-0">&copy; 2024 Mural Eletrônico IFRO | Sistema Modernizado v2.0</p>
    </footer>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/bootstrap-init.js"></script>
</body>
</html>
