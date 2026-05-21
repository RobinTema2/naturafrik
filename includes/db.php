<?php
// ============================================================
// NATURAFRIK - Connexion base de données (PDO sécurisé)
// ============================================================

if (!defined('DB_HOST')) {
    require_once dirname(__DIR__) . '/config/config.php';
}

class Database {
    private static ?PDO $instance = null;

    public static function getConnection(): ?PDO {
        if (self::$instance === null) {
            try {
                $port = defined('DB_PORT') ? DB_PORT : '3306';
                $dsn = sprintf(
                    'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                    DB_HOST, $port, DB_NAME, DB_CHARSET
                );
                $options = [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ];
                if (defined('PDO::MYSQL_ATTR_INIT_COMMAND')) {
                    $options[PDO::MYSQL_ATTR_INIT_COMMAND] = "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci";
                }
                self::$instance = new PDO($dsn, DB_USER, DB_PASS, $options);
            } catch (PDOException $e) {
                error_log('DB Connection Error: ' . $e->getMessage());
                self::$instance = null;
                return null;
            }
        }
        return self::$instance;
    }

    private function __construct() {}
    private function __clone() {}
}

function db(): ?PDO {
    return Database::getConnection();
}
