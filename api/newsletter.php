<?php
require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/includes/functions.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo json_encode(['success'=>false,'message'=>'Méthode non autorisée.']); exit; }
if (!csrf_verify()) { http_response_code(403); echo json_encode(['success'=>false,'message'=>'Token invalide.']); exit; }

$ip = get_ip();
if (!check_rate_limit($ip, 'newsletter', 3, 3600)) { echo json_encode(['success'=>false,'message'=>'Trop de tentatives.']); exit; }

$email = sanitize_email($_POST['email'] ?? '');
$name  = sanitize($_POST['name'] ?? '');

if (!$email) { echo json_encode(['success'=>false,'message'=>'Email invalide.']); exit; }

try {
    $exists = db()->prepare('SELECT id FROM newsletter WHERE email=?');
    $exists->execute([$email]);
    if ($exists->fetchColumn()) {
        echo json_encode(['success'=>true,'message'=>'Vous êtes déjà abonné(e) à notre newsletter !']); exit;
    }
    $token = bin2hex(random_bytes(32));
    db()->prepare('INSERT INTO newsletter (email,name,token) VALUES (?,?,?)')->execute([$email,$name,$token]);
    echo json_encode(['success'=>true,'message'=>'Inscription réussie ! Merci de rejoindre NATURAFRIK.']);
} catch (Exception $e) {
    echo json_encode(['success'=>false,'message'=>'Erreur serveur.']);
}
