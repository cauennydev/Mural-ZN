<?php
/**
 * Validação de Sessão e Autenticação
 * Segurança melhorada com verificações modernas
 * 
 * @author Sistema Mural Eletrônico
 * @version 2.0
 */

// Iniciar sessão de forma segura
if (session_status() === PHP_SESSION_NONE) {
    // Configurações de segurança para sessão
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', isset($_SERVER['HTTPS']) ? 1 : 0);
    ini_set('session.cookie_samesite', 'Strict');
    ini_set('session.gc_maxlifetime', 3600); // 1 hora
    
    session_start();
}

// Classe de autenticação
class AuthValidator {
    
    /**
     * Verifica se usuário está autenticado
     * @return bool
     */
    public static function isAuthenticated() {
        return isset($_SESSION['user_id']) && 
               isset($_SESSION['login']) && 
               !empty($_SESSION['user_id']) &&
               !empty($_SESSION['login']);
    }
    
    /**
     * Obtém dados do usuário autenticado
     * @return array|null
     */
    public static function getCurrentUser() {
        if (self::isAuthenticated()) {
            return [
                'id' => $_SESSION['user_id'],
                'login' => $_SESSION['login'],
                'nome' => $_SESSION['nome'] ?? 'Usuário',
            ];
        }
        return null;
    }
    
    /**
     * Faz logout do usuário
     */
    public static function logout() {
        $_SESSION = [];
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        
        session_destroy();
    }
    
    /**
     * Redireciona para login se não autenticado
     * @param string $redirectUrl URL para redirecionar após login
     */
    public static function requireLogin($redirectUrl = null) {
        if (!self::isAuthenticated()) {
            $returnTo = urlencode($redirectUrl ?? $_SERVER['REQUEST_URI']);
            header("Location: login.php?return=" . $returnTo);
            exit;
        }
    }
    
    /**
     * Gera token CSRF para proteção
     * @return string
     */
    public static function generateCSRFToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Valida token CSRF
     * @param string $token
     * @return bool
     */
    public static function validateCSRFToken($token) {
        return isset($_SESSION['csrf_token']) && 
               hash_equals($_SESSION['csrf_token'], $token);
    }
}

// Redirecionar para login se não autenticado (em páginas que necessitam)
// Use: AuthValidator::requireLogin(); no início de páginas protegidas
?>
