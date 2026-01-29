<?php
/**
 * Logout - Encerrar Sessão
 * 
 * @author Sistema Mural Eletrônico
 * @version 2.0
 */

require_once __DIR__ . '/config/conexao.php';
require_once __DIR__ . '/config/validar.php';

// Executar logout
AuthValidator::logout();

// Redirecionar para home
header("Location: index.php?logout=success");
exit;
?>
