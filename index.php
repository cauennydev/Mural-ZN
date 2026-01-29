<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Sistema de Mural Eletrônico - Gerenciamento de conteúdo para displays">
    <meta name="author" content="IFRO - Sistema de Mural Digital">
    <meta name="theme-color" content="#37b637">
    
    <title>Mural Eletrônico - Sistema Principal</title>
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="css/bootstrap-5.3.min.css" rel="stylesheet" 
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsb8hLrzVSFS2uLUftVo9" 
          crossorigin="anonymous">
    
    <!-- CSS Personalizado -->
    <link rel="stylesheet" href="css/estilo.css">
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
    
    <style>
        body {
            background: linear-gradient(135deg, #37b637 0%, #37b637 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .hero-section {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 3rem 2rem;
            max-width: 900px;
            width: 100%;
        }
        
        .logo-section {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .logo-section img {
            max-width: 200px;
            height: auto;
            margin-bottom: 1rem;
        }
        
        .hero-section h1 {
            color: #333;
            margin-bottom: 0.5rem;
            font-weight: 700;
        }
        
        .hero-section p {
            color: #666;
            margin-bottom: 2rem;
            font-size: 1.1rem;
        }
        
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .menu-card {
            background: linear-gradient(135deg, #37b637 0%, #37b637 100%);
            color: white;
            padding: 2rem;
            border-radius: 10px;
            text-decoration: none;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            min-height: 150px;
        }
        
        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(102, 126, 234, 0.4);
            color: white;
            text-decoration: none;
        }
        
        .menu-card i {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
        
        .menu-card h3 {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <!-- Seção Principal -->
    <div class="hero-section">
        <!-- Logo e Título -->
        <div class="logo-section">
            <?php if (file_exists('img/logo_mural.jpg')): ?>
                <img src="img/logo_mural.jpg" alt="Logo Mural Eletrônico">
            <?php else: ?>
                <div class="alert alert-warning">Logo não encontrado em img/logo_mural.jpg</div>
            <?php endif; ?>
            
            <h1>🎯 Mural Eletrônico</h1>
            <p>Sistema de Gerenciamento de Conteúdo para Displays Digitais</p>
        </div>
        
        <!-- Menu em Grid -->
        <div class="menu-grid">
            <!-- Opção 1: Exibir Mural 1366x768 -->
            <a href="mural.php" class="menu-card" title="Exibe o mural em resolução 1366x768">
                <span style="font-size: 2.5rem;">📺</span>
                <h3>Mural 1366×768</h3>
            </a>
            
            <!-- Opção 2: Exibir Mural 1440x900 -->
            <a href="mural1440x900.php" class="menu-card" title="Exibe o mural em resolução 1440x900">
                <span style="font-size: 2.5rem;">🖥️</span>
                <h3>Mural 1440×900</h3>
            </a>
            
            <!-- Opção 3: Login e Administração -->
            <a href="formlogin.php" class="menu-card" title="Acesso ao painel administrativo">
                <span style="font-size: 2.5rem;">🔐</span>
                <h3>Administração</h3>
            </a>
        </div>
        
        <!-- Informações Adicionais -->
        <hr>
        
        <div class="row">
            <div class="col-md-8">
                <div class="alert alert-info">
                    <strong>ℹ️ Bem-vindo!</strong> Escolha uma opção acima para visualizar o mural ou acessar a administração do sistema.
                </div>
            </div>
            <div class="col-md-4 text-end">
                <a href="../" class="btn btn-outline-secondary">
                    ← Voltar para Dashboard Principal
                </a>
            </div>
        </div>
        
        <!-- Footer -->
        <footer class="text-center mt-4 text-muted">
            <small>
                Sistema de Mural Eletrônico IFRO © 2024 | 
                <a href="sobre.html" class="text-muted">Sobre o Sistema</a>
            </small>
        </footer>
    </div>
    
    <!-- Bootstrap 5.3 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-geWF76RCwLtnZ8qwWbSxccPQtF3EpF3fnJHog6LaEVF6d1uS/E1pSJHVjJWS1J1w" 
            crossorigin="anonymous"></script>
    
    <!-- Script de inicialização -->
    <script src="js/bootstrap-init.js"></script>
</body>
</html>
