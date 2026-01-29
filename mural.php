<?php
/**
 * Exibição do Mural Eletrônico (Resolução: 1366x768)
 * 
 * @file mural.php
 * @version 2.0
 */

try {
    require_once __DIR__ . '/config/conexao.php';
} catch (Exception $e) {
    die('Erro ao conectar ao banco de dados');
}

$midias = [];
$erro = '';

try {
    $db = DatabaseConnection::getInstance()->getDB();
    $agora = date('Y-m-d H:i:s');
    
    // Buscar mídias ativas e dentro do período
    $stmt = $db->prepare("
        SELECT id, titulo, descricao, arquivo, tipo, data_inicio, data_fim, ativo, data_criacao 
        FROM midias 
        WHERE ativo = 1
        AND data_inicio <= :agora 
        AND data_fim >= :agora
        ORDER BY data_criacao DESC
    ");
    
    $stmt->execute([':agora' => $agora]);
    $midias = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $erro = 'Erro ao carregar mídias: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="60">
    <title>Mural Eletrônico 1366x768</title>
    <link href="css/bootstrap-5.3.min.css" rel="stylesheet">
    <link href="css/estilo.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #37b637 0%, #37b637 100%);
            height: 100vh;
            overflow: hidden;
        }
        
        .mural-container {
            height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 20px;
            gap: 20px;
        }
        
        .mural-header {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            padding: 20px;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            backdrop-filter: blur(10px);
        }
        
        .mural-header h1 {
            color: white;
            margin: 0;
            font-size: 2rem;
        }
        
        .mural-clock {
            font-size: 1.5rem;
            font-weight: bold;
        }
        
        .mural-content {
            flex: 1;
            overflow: hidden;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .carousel-wrapper {
            width: 100%;
            height: 100%;
            position: relative;
            overflow: hidden;
            border-radius: 12px;
        }
        
        .media-item {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 0.8s ease-in-out;
            display: flex;
            flex-direction: column;
            background: white;
            overflow: hidden;
        }
        
        .media-item.active {
            opacity: 1;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        
        .media-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #37b637 0%, #37b637 100%);
            padding: 20px;
            text-align: center;
            width: 100%;
            overflow: hidden;
        }
        
        .media-content img,
        .media-content video {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 0;
        }
        
        .media-info {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 15px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            z-index: 10;
        }
        
        .media-title {
            font-weight: 600;
            color: white;
            margin: 0 0 5px 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .media-type {
            display: inline-block;
            padding: 3px 10px;
            background: linear-gradient(135deg, #37b637 0%, #37b637 100%);
            color: white;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .mural-footer {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            font-size: 0.9rem;
            backdrop-filter: blur(10px);
        }
        
        .mural-error {
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 1;
        }
        
        .error-box {
            background: white;
            padding: 40px;
            border-radius: 12px;
            text-align: center;
        }
        
        .error-box h2 {
            color: #dc3545;
            margin-top: 0;
        }
    </style>
</head>
<body>
    <div class="mural-container">
        <!-- Header -->
        <div class="mural-header">
            <div>
                <h1>📺 Mural Eletrônico IFRO</h1>
                <p style="margin: 5px 0 0 0; opacity: 0.9;">Resolução: 1366x768</p>
            </div>
            <div class="mural-clock" id="clock">00:00:00</div>
        </div>

        <!-- Conteúdo Carrossel -->
        <div class="mural-content">
            <?php if ($erro): ?>
                <div class="mural-error">
                    <div class="error-box">
                        <h2>⚠️ Erro</h2>
                        <p><?php echo htmlspecialchars($erro); ?></p>
                    </div>
                </div>
            <?php elseif (empty($midias)): ?>
                <div class="mural-error">
                    <div class="error-box">
                        <h2>📭 Nenhuma Mídia</h2>
                        <p>Não há mídias agendadas para exibição no momento.</p>
                    </div>
                </div>
            <?php else: ?>
                <div class="carousel-wrapper">
                    <?php foreach ($midias as $index => $midia): 
                        $tipo_classe = strtolower($midia['tipo']);
                        $arquivo_path = htmlspecialchars($midia['arquivo']);
                        $ativo = $index === 0 ? 'active' : '';
                    ?>
                        <div class="media-item <?php echo $ativo; ?>" data-index="<?php echo $index; ?>">
                            <div class="media-content">
                                <?php if ($tipo_classe === 'video'): ?>
                                    <video muted loop>
                                        <source src="<?php echo $arquivo_path; ?>" type="video/mp4">
                                        Seu navegador não suporta vídeos.
                                    </video>
                                <?php elseif ($tipo_classe === 'imagem'): ?>
                                    <img src="<?php echo $arquivo_path; ?>" alt="<?php echo htmlspecialchars($midia['titulo']); ?>">
                                <?php else: ?>
                                    <div style="color: #999; font-size: 3rem;">📄</div>
                                <?php endif; ?>
                            </div>
                            <div class="media-info">
                                <h3 class="media-title"><?php echo htmlspecialchars($midia['titulo']); ?></h3>
                                <span class="media-type"><?php echo htmlspecialchars($midia['tipo']); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Footer -->
        <div class="mural-footer">
            <span id="update-info">Atualizado em: <strong id="last-update"></strong></span> | 
            <span id="total-midias"><?php echo count($midias); ?> mídia(s) exibida(s)</span>
        </div>
    </div>

<!-- Aqui abaixo está as funcionalidades do carrossel e relógio, sendo elas como é obtido o horário a se apresentar na
 tela do relógio e o carrossel, como é feita a troca
 Ambos são feitos com funções (updateClock e proximaMidia) -->

    <script>
    // Variáveis do carrossel
    const INTERVALO_TROCA = 8000; // 8 segundos (ajustável)
    let indiceAtual = 0;
    const totalMidias = document.querySelectorAll('.media-item').length;
    
    // Atualizar relógio
    function updateClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        document.getElementById('clock').textContent = `${hours}:${minutes}:${seconds}`;
        
        const lastUpdate = document.getElementById('last-update');
        lastUpdate.textContent = `${hours}:${minutes}:${seconds}`;
    }
    
    // Função do carrossel
    function proximaMidia() {
        if (totalMidias === 0) return;
        
        // Remove classe active da mídia atual
        document.querySelectorAll('.media-item').forEach(item => {
            item.classList.remove('active');
        });
        
        // Move para próxima
        indiceAtual = (indiceAtual + 1) % totalMidias;
        
        // Adiciona classe active na nova mídia
        const proximaItem = document.querySelector(`.media-item[data-index="${indiceAtual}"]`);
        if (proximaItem) {
            proximaItem.classList.add('active');
            
            // Parar e reiniciar vídeo se houver
            const video = proximaItem.querySelector('video');
            if (video) {
                video.pause();
                video.currentTime = 0;
                video.play().catch(() => {}); // Ignorar erro se autoplay bloqueado
            }
        }
    }
    
    updateClock();
    setInterval(updateClock, 1000);
    
    // Carrossel automático
    if (totalMidias > 1) {
        setInterval(proximaMidia, INTERVALO_TROCA);
    }
    
    // Auto-refresh a cada 5 minutos
    setTimeout(() => {
        location.reload();
    }, 5 * 60 * 1000);
    </script>
</body>
</html>
