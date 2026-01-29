<?php
/**
 * Conexão com Banco de Dados - PDO (Modernizado)
 * Segurança, performance e compatibilidade melhorada
 * 
 * @author Sistema Mural Eletrônico
 * @version 2.0
 */

// Configurações do banco de dados
const DB_HOST = 'localhost';
const DB_USER = 'root';
const DB_PASS = '';
const DB_NAME = 'mural';
const DB_PORT = 3306;
const DB_CHARSET = 'utf8mb4';

// Classe para gerenciar conexão com banco de dados
class DatabaseConnection {
    private static $connection = null;
    
    /**
     * Obtém ou cria a conexão PDO
     * @return PDO|null
     */
    public static function getInstance() {
        if (self::$connection === null) {
            try {
                $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
                
                self::$connection = new PDO(
                    $dsn,
                    DB_USER,
                    DB_PASS,
                    [
                        // Atributos de segurança e performance
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                        PDO::ATTR_PERSISTENT => false,
                    ]
                );
                
                // Configurações de charset
                self::$connection->exec("SET NAMES " . DB_CHARSET);
                self::$connection->exec("SET CHARACTER SET " . DB_CHARSET);
                
            } catch (PDOException $e) {
                // Log de erro em ambiente de produção
                if (getenv('ENVIRONMENT') === 'production') {
                    error_log("Erro na conexão com BD: " . $e->getMessage());
                    die("Erro ao conectar ao banco de dados. Contate o administrador.");
                } else {
                    // Desenvolvimento: mostrar erro
                    die("Erro PDO: " . $e->getMessage());
                }
            }
        }
        
        return self::$connection;
    }
    
    /**
     * Fecha a conexão
     */
    public static function closeConnection() {
        self::$connection = null;
    }
}

// Função auxiliar para obter conexão
function getDB() {
    return DatabaseConnection::getInstance();
}

// Fechar conexão ao término do script
register_shutdown_function(function() {
    DatabaseConnection::closeConnection();
});
?>
