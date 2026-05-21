<?php
require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/includes/functions.php';

if (is_admin_logged()) {
    admin_log('logout', 'Déconnexion');
}
session_destroy();
header('Location: ' . SITE_URL . '/admin/login.php');
exit;
