<?php
// ============================================================
// NATURAFRIK — Configuration principale
// NATURCAM Sarl – Groupe Nature Cameroun
// ============================================================

// ── Charger .env si présent (local dev) ─────────────────────
$envFile = dirname(__DIR__) . '/.env';
if (file_exists($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') === false)   continue;
        [$k, $v] = array_map('trim', explode('=', $line, 2));
        if (!empty($k) && !getenv($k)) putenv("$k=$v");
    }
}

// ── Affichage erreurs ────────────────────────────────────────
$isProduction = !empty(getenv('RAILWAY_ENVIRONMENT')) || !empty(getenv('RENDER'));
ini_set('display_errors', $isProduction ? 0 : 1);
ini_set('log_errors', 1);
error_reporting(E_ALL);

// ============================================================
// BASE DE DONNÉES — env vars en priorité, fallback XAMPP
// ============================================================
define('DB_HOST',    getenv('DB_HOST')    ?: getenv('MYSQLHOST')     ?: '127.0.0.1');
define('DB_PORT',    getenv('DB_PORT')    ?: getenv('MYSQLPORT')     ?: '3307');
define('DB_NAME',    getenv('DB_NAME')    ?: getenv('MYSQLDATABASE') ?: 'naturafrica');
define('DB_USER',    getenv('DB_USER')    ?: getenv('MYSQLUSER')     ?: 'root');
define('DB_PASS',    getenv('DB_PASS')    ?: getenv('MYSQLPASSWORD') ?: '');
define('DB_CHARSET', 'utf8mb4');

// ============================================================
// CHEMIN DE BASE
// SITE_BASE=/naturafrik  → XAMPP local
// SITE_BASE=             → Railway / InfinityFree (racine)
// ============================================================
define('BASE', rtrim(getenv('SITE_BASE') !== false ? getenv('SITE_BASE') : '/naturafrik', '/'));

// ============================================================
// MAINTENANCE — mettre true pour suspendre le site
// ============================================================
define('MAINTENANCE_MODE', true);

// ============================================================
// SITE
// ============================================================
define('SITE_NAME',     'NATURAFRIK');
define('SITE_TAGLINE',  "L'Excellence Africaine au Monde");
define('SITE_URL',      getenv('SITE_URL') ?: 'http://localhost/naturafrik');
define('SITE_EMAIL',    'contact@naturafrik.com');
define('SITE_PHONE',    '+237 698 141 070');
define('SITE_PHONE2',   '+237 688 620 132');
define('SITE_WHATSAPP', '+237 691 268 428');
define('SITE_ADDRESS',  'BP 3005, Yaoundé-Cameroun (Montée Anne Rouge)');

// ============================================================
// INFORMATIONS SOCIÉTÉ
// ============================================================
define('COMPANY_NAME',     'NATURCAM Sarl');
define('COMPANY_GROUP',    'Groupe Nature Cameroun Sarl');
define('COMPANY_RCCM',     'RC/YAO/2023/B/519');
define('COMPANY_NIU',      'M032318076551C');
define('COMPANY_BGFI',     'CM21 10035012004001776701179');
define('COMPANY_AFRILAND', 'CM21 10005 00038 09295421001 80');
define('COMPANY_INTL',     '+1 (613) 282-6599');

// ============================================================
// CHEMINS
// ============================================================
define('ROOT_PATH',    dirname(__DIR__) . '/');
define('UPLOADS_PATH', ROOT_PATH . 'uploads/');
define('UPLOADS_URL',  SITE_URL . '/uploads/');
define('IMAGES_URL',   SITE_URL . '/images/');

// ============================================================
// SÉCURITÉ
// ============================================================
define('SECRET_KEY',       getenv('SECRET_KEY') ?: 'NaturAfrica_S3cur3_K3y_2024_LOCAL');
define('CSRF_TOKEN_NAME',  'naturafrica_csrf');
define('SESSION_NAME',     'NATURAFRIK_SESSION');
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOCKOUT_DURATION',   1800);
define('SESSION_LIFETIME',   7200);

// ============================================================
// UPLOAD
// ============================================================
define('MAX_UPLOAD_SIZE',    5 * 1024 * 1024);
define('ALLOWED_IMAGES',     ['image/jpeg', 'image/png', 'image/webp', 'image/gif']);
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'webp', 'gif']);

// ============================================================
// PAGINATION
// ============================================================
define('ITEMS_PER_PAGE', 12);

// ============================================================
// SESSION SÉCURISÉE
// ============================================================
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_strict_mode', 1);
    ini_set('session.cookie_samesite', 'Strict');
    if ($isProduction) ini_set('session.cookie_secure', 1);
    session_name(SESSION_NAME);
    session_set_cookie_params([
        'lifetime' => SESSION_LIFETIME,
        'path'     => '/',
        'httponly' => true,
        'samesite' => 'Strict',
    ]);
    session_start();
}
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id(true);
    $_SESSION['initiated'] = true;
}
