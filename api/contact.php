<?php
// API contact form — réponse JSON
require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/includes/functions.php';

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
    exit;
}

if (!csrf_verify()) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Token invalide.']);
    exit;
}

$ip = get_ip();
if (!check_rate_limit($ip, 'contact_api', 5, 3600)) {
    http_response_code(429);
    echo json_encode(['success' => false, 'message' => 'Trop de requêtes. Réessayez dans une heure.']);
    exit;
}

$name    = sanitize($_POST['name'] ?? '');
$email   = sanitize_email($_POST['email'] ?? '');
$phone   = sanitize($_POST['phone'] ?? '');
$subject = sanitize($_POST['subject'] ?? '');
$message = sanitize($_POST['message'] ?? '');

if (strlen($name) < 2 || !$email || strlen($subject) < 3 || strlen($message) < 10) {
    echo json_encode(['success' => false, 'message' => 'Données incomplètes ou invalides.']);
    exit;
}

try {
    $stmt = db()->prepare('INSERT INTO contact_messages (name,email,phone,subject,message,ip_address) VALUES (?,?,?,?,?,?)');
    $stmt->execute([$name, $email, $phone, $subject, $message, $ip]);
    echo json_encode(['success' => true, 'message' => 'Message envoyé ! Nous vous répondrons sous 24h.']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erreur serveur. Contactez-nous par téléphone.']);
}
