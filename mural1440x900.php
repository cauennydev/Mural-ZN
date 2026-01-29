<?php
/**
 * Exibição do Mural Eletrônico (Resolução: 1440x900)
 * 
 * @file mural1440x900.php
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
        ORDER BY RAND()
        LIMIT 6
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
    <title>Mural Eletrônico 1440x900</title>
    <link href="css/bootstrap-5.3.min.css" rel="stylesheet">
    <link href="css/estilo.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: linear-gradient(135deg, #37b637 0%, #37b637 100%);
            height: 100vh;
            overflow: hidden;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        .mural-wrapper {
            display: flex;
            flex-direction: column;
            height: 100vh;
            padding: 20px;
        }
        
        .mural-header {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            padding: 15px 20px;
            border-radius: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .header-left h1 {
            margin: 0;
            font-size: 2.5rem;
            font-weight: 700;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }
        
        .header-left p {
            margin: 5px 0 0 0;
            opacity: 0.9;
            font-size: 0.95rem;
        }
        
        .header-right {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 10px;
        }
        
        .mural-clock {
            font-size: 2.5rem;
            font-weight: bold;
            font-family: 'Courier New', monospace;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }
        
        .mural-stats {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .mural-grid {
            flex: 1;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-template-rows: repeat(2, 1fr);
            gap: 15px;
            overflow: hidden;
        }
        
        .media-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            animation: fadeInUp 0.6s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .media-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.25);
        }
        
        .media-display {
            flex: 1;
            background: linear-gradient(135deg, #37b637 0%, #37b637 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }
        
        .media-display img,
        .media-display video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .media-footer {
            padding: 12px;
            background: white;
            border-top: 1px solid #eee;
        }
        
        .media-title {
            font-weight: 600;
            color: #333;
            margin: 0;
            font-size: 0.95rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .media-badge {
            display: inline-block;
            padding: 3px 8px;
            background: linear-gradient(135deg, #37b637 0%, #37b637 100%);
            color: white;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 500;
            margin-top: 5px;
        }
        
        .mural-footer {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            padding: 12px 20px;
            border-radius: 12px;
            text-align: center;
            font-size: 0.85rem;
            margin-top: 15px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .error-container {
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 1;
        }
        
        .error-message {
            background: white;
            padding: 40px;
            border-radius: 12px;
            text-align: center;
            max-width: 500px;
        }
        
        .error-message h2 {
            color: #dc3545;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="mural-wrapper">
        <!-- Header -->
        <div class="mural-header">
            <div class="header-left">
                <h1>📺 Mural Eletrônico</h1>
                <p>IFRO | Resolução: 1440x900</p>
            </div>
            <div class="header-right">
                <div class="mural-clock" id="clock">00:00:00</div>
                <div class="mural-stats">
                    <span id="total-midias"><?php echo count($midias); ?></span> mídia(s)
                </div>
            </div>
        </div>

        <!-- Grid de Mídias -->
        <?php if ($erro): ?>
            <div class="error-container">
                <div class="error-message">
                    <h2>⚠️ Erro</h2>
                    <p><?php echo htmlspecialchars($erro); ?></p>
                </div>
            </div>
        <?php elseif (empty($midias)): ?>
            <div class="error-container">
                <div class="error-message">
                    <h2>📭 Sem Conteúdo</h2>
                    <p>Não há mídias agendadas para exibição no momento.</p>
                </div>
            </div>
        <?php else: ?>
            <div class="mural-grid">
                <?php foreach ($midias as $midia): 
                    $tipo = htmlspecialchars($midia['tipo']);
                    $arquivo = htmlspecialchars($midia['arquivo']);
                    $titulo = htmlspecialchars($midia['titulo']);
                ?>
                    <div class="media-card">
                        <div class="media-display">
                            <?php if (strtolower($tipo) === 'video'): ?>
                                <video autoplay muted loop>
                                    <source src="<?php echo $arquivo; ?>" type="video/mp4">
                                </video>
                            <?php elseif (strtolower($tipo) === 'imagem'): ?>
                                <img src="<?php echo $arquivo; ?>" alt="<?php echo $titulo; ?>">
                            <?php else: ?>
                                <div style="color: #bbb; font-size: 4rem;">📄</div>
                            <?php endif; ?>
                        </div>
                        <div class="media-footer">
                            <h3 class="media-title"><?php echo $titulo; ?></h3>
                            <span class="media-badge"><?php echo $tipo; ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Footer -->
        <div class="mural-footer">
            🕐 Atualizado em: <strong id="last-update"></strong> | 
            🔄 Auto-atualização a cada 60 segundos
        </div>
    </div>

    <script>
    function updateDisplay() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        
        document.getElementById('clock').textContent = `${hours}:${minutes}:${seconds}`;
        document.getElementById('last-update').textContent = `${hours}:${minutes}:${seconds}`;
    }
    
    updateDisplay();
    setInterval(updateDisplay, 1000);
    
    // Auto-refresh a cada 60 segundos
    setTimeout(() => {
        location.reload();
    }, 60 * 1000);
    </script>
</body>
</html>
