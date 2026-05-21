<?php
// ============================================================
// NATURAFRIK - Fonctions utilitaires et sécurité
// ============================================================

if (!defined('SECRET_KEY')) {
    require_once dirname(__DIR__) . '/config/config.php';
}
require_once __DIR__ . '/db.php';

// ============================================================
// SÉCURITÉ - CSRF
// ============================================================
function csrf_token(): string {
    if (empty($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}

function csrf_field(): string {
    return '<input type="hidden" name="' . CSRF_TOKEN_NAME . '" value="' . csrf_token() . '">';
}

function csrf_verify(): bool {
    $token = $_POST[CSRF_TOKEN_NAME] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    return !empty($_SESSION[CSRF_TOKEN_NAME]) && hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
}

// ============================================================
// SÉCURITÉ - XSS
// ============================================================
function e(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

function sanitize(string $str): string {
    return trim(strip_tags($str));
}

function sanitize_int($val): int {
    return (int) filter_var($val, FILTER_SANITIZE_NUMBER_INT);
}

function sanitize_email(string $email): string|false {
    return filter_var(trim($email), FILTER_VALIDATE_EMAIL);
}

// ============================================================
// RATE LIMITING
// ============================================================
function check_rate_limit(string $ip, string $action, int $max = 5, int $window = 3600): bool {
    try {
        $pdo = db();
        if ($pdo === null) return true;
        $stmt = $pdo->prepare('SELECT attempts, blocked_until FROM rate_limits WHERE ip_address = ? AND action = ?');
        $stmt->execute([$ip, $action]);
        $row = $stmt->fetch();

        if ($row) {
            if ($row['blocked_until'] && new DateTime() < new DateTime($row['blocked_until'])) {
                return false; // Bloqué
            }
            if ($row['attempts'] >= $max) {
                $pdo->prepare('UPDATE rate_limits SET blocked_until = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE ip_address = ? AND action = ?')
                    ->execute([$ip, $action]);
                return false;
            }
            $pdo->prepare('UPDATE rate_limits SET attempts = attempts + 1 WHERE ip_address = ? AND action = ?')
                ->execute([$ip, $action]);
        } else {
            $pdo->prepare('INSERT INTO rate_limits (ip_address, action) VALUES (?, ?)')
                ->execute([$ip, $action]);
        }
        return true;
    } catch (Exception $e) {
        return true; // En cas d'erreur, on laisse passer
    }
}

function get_ip(): string {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    }
    return filter_var(trim($ip), FILTER_VALIDATE_IP) ?: '0.0.0.0';
}

// ============================================================
// UPLOAD SÉCURISÉ
// ============================================================
function upload_image(array $file, string $folder = 'products'): string|false {
    if ($file['error'] !== UPLOAD_ERR_OK) return false;
    if ($file['size'] > MAX_UPLOAD_SIZE) return false;

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime  = $finfo->file($file['tmp_name']);
    if (!in_array($mime, ALLOWED_IMAGES, true)) return false;

    $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ALLOWED_EXTENSIONS, true)) return false;

    $filename = bin2hex(random_bytes(16)) . '.' . $ext;
    $dir      = UPLOADS_PATH . $folder . '/';
    if (!is_dir($dir)) mkdir($dir, 0755, true);

    $destination = $dir . $filename;
    if (!move_uploaded_file($file['tmp_name'], $destination)) return false;

    return 'uploads/' . $folder . '/' . $filename;
}

// ============================================================
// SLUGIFICATION
// ============================================================
function slugify(string $text): string {
    $text = mb_strtolower($text, 'UTF-8');
    $chars = [
        'à'=>'a','á'=>'a','â'=>'a','ä'=>'a','ã'=>'a',
        'è'=>'e','é'=>'e','ê'=>'e','ë'=>'e',
        'ì'=>'i','í'=>'i','î'=>'i','ï'=>'i',
        'ò'=>'o','ó'=>'o','ô'=>'o','ö'=>'o','õ'=>'o',
        'ù'=>'u','ú'=>'u','û'=>'u','ü'=>'u',
        'ç'=>'c','ñ'=>'n','ý'=>'y','ÿ'=>'y',
    ];
    $text = strtr($text, $chars);
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/[\s-]+/', '-', $text);
    return trim($text, '-');
}

function unique_slug(string $table, string $base): string {
    $pdo  = db();
    if ($pdo === null) return slugify($base) . '-' . uniqid();
    $slug = slugify($base);
    $orig = $slug;
    $i    = 1;
    while (true) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM `$table` WHERE slug = ?");
        $stmt->execute([$slug]);
        if ($stmt->fetchColumn() == 0) return $slug;
        $slug = $orig . '-' . $i++;
    }
}

// ============================================================
// PRODUITS
// ============================================================
function get_featured_products(int $limit = 8): array {
    try {
        $stmt = db()->prepare('
            SELECT p.*, c.name AS category_name, c.section
            FROM products p
            JOIN categories c ON p.category_id = c.id
            WHERE p.is_featured = 1 AND p.is_active = 1
            ORDER BY p.created_at DESC
            LIMIT ?
        ');
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    } catch (Exception $e) { return []; }
}

function get_products_by_section(string $section, int $limit = 12, int $offset = 0): array {
    try {
        $stmt = db()->prepare('
            SELECT p.*, c.name AS category_name
            FROM products p
            JOIN categories c ON p.category_id = c.id
            WHERE c.section = ? AND p.is_active = 1
            ORDER BY p.is_featured DESC, p.created_at DESC
            LIMIT ? OFFSET ?
        ');
        $stmt->execute([$section, $limit, $offset]);
        return $stmt->fetchAll();
    } catch (Exception $e) { return []; }
}

function get_categories_by_section(string $section): array {
    try {
        $stmt = db()->prepare('SELECT * FROM categories WHERE section = ? AND is_active = 1 ORDER BY display_order');
        $stmt->execute([$section]);
        return $stmt->fetchAll();
    } catch (Exception $e) { return []; }
}

function get_product_by_slug(string $slug): array|false {
    try {
        $stmt = db()->prepare('
            SELECT p.*, c.name AS category_name, c.section, c.slug AS category_slug
            FROM products p
            JOIN categories c ON p.category_id = c.id
            WHERE p.slug = ? AND p.is_active = 1
        ');
        $stmt->execute([$slug]);
        $p = $stmt->fetch();
        if ($p) {
            db()->prepare('UPDATE products SET views = views + 1 WHERE id = ?')->execute([$p['id']]);
        }
        return $p;
    } catch (Exception $e) { return false; }
}

// ============================================================
// IMMOBILIER
// ============================================================
function get_featured_realestate(int $limit = 6): array {
    try {
        $stmt = db()->prepare('
            SELECT * FROM real_estate
            WHERE is_featured = 1 AND is_available = 1
            ORDER BY created_at DESC
            LIMIT ?
        ');
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    } catch (Exception $e) { return []; }
}

// ============================================================
// FORMATAGE
// ============================================================
function format_price(float $price, string $currency = 'XAF'): string {
    if ($currency === 'XAF') {
        return number_format($price, 0, ',', ' ') . ' FCFA';
    }
    return number_format($price, 2, ',', ' ') . ' ' . $currency;
}

function format_date(string $date): string {
    return date('d/m/Y', strtotime($date));
}

function truncate(string $text, int $length = 150): string {
    if (mb_strlen($text) <= $length) return $text;
    return mb_substr($text, 0, $length) . '…';
}

function product_image_url(string $path): string {
    if (empty($path)) return SITE_URL . '/images/placeholder.svg';
    return SITE_URL . '/' . $path;
}

// ============================================================
// STATISTIQUES (pour admin dashboard)
// ============================================================
function get_stats(): array {
    try {
        $pdo = db();
        return [
            'products'   => $pdo->query('SELECT COUNT(*) FROM products WHERE is_active=1')->fetchColumn(),
            'realestate' => $pdo->query('SELECT COUNT(*) FROM real_estate WHERE is_available=1')->fetchColumn(),
            'messages'   => $pdo->query('SELECT COUNT(*) FROM contact_messages WHERE is_read=0')->fetchColumn(),
            'newsletter' => $pdo->query('SELECT COUNT(*) FROM newsletter WHERE is_active=1')->fetchColumn(),
        ];
    } catch (Exception $e) { return ['products'=>0,'realestate'=>0,'messages'=>0,'newsletter'=>0]; }
}

// ============================================================
// ADMIN - AUTHENTIFICATION
// ============================================================
function is_admin_logged(): bool {
    return !empty($_SESSION['admin_id']) && !empty($_SESSION['admin_role']);
}

function require_admin_login(): void {
    if (!is_admin_logged()) {
        header('Location: ' . SITE_URL . '/admin/login.php');
        exit;
    }
}

function admin_log(string $action, string $details = ''): void {
    try {
        $pdo = db();
        if ($pdo === null) return;
        $stmt = $pdo->prepare('INSERT INTO admin_logs (user_id, action, details, ip_address) VALUES (?,?,?,?)');
        $stmt->execute([$_SESSION['admin_id'] ?? null, $action, $details, get_ip()]);
    } catch (\Throwable $e) {}
}

// ============================================================
// FLASH MESSAGES
// ============================================================
function set_flash(string $type, string $message): void {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function get_flash(): ?array {
    if (!empty($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}
