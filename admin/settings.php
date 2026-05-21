<?php
require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_admin_login();

// Seul super_admin peut accéder aux paramètres
$is_super = ($_SESSION['admin_role'] ?? '') === 'super_admin';

$flash = get_flash();

// Stats système
$stats = get_stats();
$php_version    = phpversion();
$db_version     = '';
$db_size        = '';
try {
    $pdo = db();
    if ($pdo) {
        $db_version = $pdo->query('SELECT VERSION()')->fetchColumn();
        $row = $pdo->query("SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size FROM information_schema.tables WHERE table_schema = DATABASE()")->fetch();
        $db_size    = ($row['size'] ?? 0) . ' MB';
    }
} catch (Exception $e) {}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Paramètres – NATURAFRIK Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="<?= SITE_URL ?>/admin/css/admin.css">
</head>
<body class="admin-body">

<aside class="admin-sidebar" id="adminSidebar">
  <div class="sidebar-logo">
    <div class="sidebar-logo-icon">
      <svg viewBox="0 0 24 24" fill="white"><path d="M17 8C8 10 5.9 16.17 3.82 21.34L5.71 22l1-2.3A4.49 4.49 0 008 20C19 20 22 3 22 3c-1 2-8 2-8 2C14 8 17 8 17 8z"/></svg>
    </div>
    <div><div class="sidebar-brand">NATURAFRIK</div><div class="sidebar-admin">Administration</div></div>
  </div>
  <nav class="sidebar-nav">
    <div class="nav-group">
      <div class="nav-group-title">Principal</div>
      <a href="dashboard.php" class="sidebar-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    </div>
    <div class="nav-group">
      <div class="nav-group-title">Catalogue</div>
      <a href="products/index.php" class="sidebar-link"><i class="fas fa-box-open"></i> Produits</a>
      <a href="products/add.php" class="sidebar-link"><i class="fas fa-plus-circle"></i> Ajouter un produit</a>
      <a href="realestate/index.php" class="sidebar-link"><i class="fas fa-building"></i> Immobilier</a>
      <a href="realestate/add.php" class="sidebar-link"><i class="fas fa-plus-circle"></i> Ajouter un bien</a>
    </div>
    <div class="nav-group">
      <div class="nav-group-title">Communication</div>
      <a href="messages/index.php" class="sidebar-link"><i class="fas fa-envelope"></i> Messages</a>
      <a href="newsletter/index.php" class="sidebar-link"><i class="fas fa-paper-plane"></i> Newsletter</a>
    </div>
    <div class="nav-group">
      <div class="nav-group-title">Système</div>
      <a href="settings.php" class="sidebar-link active"><i class="fas fa-cog"></i> Paramètres</a>
      <a href="profile.php" class="sidebar-link"><i class="fas fa-user-circle"></i> Mon Profil</a>
    </div>
  </nav>
  <div class="sidebar-footer">
    <div class="admin-user-info">
      <div class="admin-avatar"><?= strtoupper(substr($_SESSION['admin_name'] ?? 'A', 0, 2)) ?></div>
      <div>
        <div class="admin-name"><?= e($_SESSION['admin_name'] ?? '') ?></div>
        <div class="admin-role"><?= e($_SESSION['admin_role'] ?? '') ?></div>
      </div>
    </div>
    <a href="logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i></a>
  </div>
</aside>
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<main class="admin-main">
  <header class="admin-topbar">
    <button class="sidebar-toggle" id="sidebarToggle"><i class="fas fa-bars"></i></button>
    <div class="topbar-title">
      <h1>Paramètres</h1>
      <span>Informations système et configuration</span>
    </div>
  </header>

  <div class="admin-content">

    <!-- Infos site -->
    <div class="admin-section" style="margin-bottom:20px;">
      <h2 class="section-heading">Informations du Site</h2>
      <div class="grid-3">
        <div style="padding:16px;background:var(--bg-elevated);border-radius:var(--r);border:1px solid var(--border);">
          <div style="font-size:.7rem;text-transform:uppercase;letter-spacing:.12em;color:var(--gold);font-weight:700;margin-bottom:6px;">Nom du site</div>
          <div style="color:var(--text-1);font-weight:600;"><?= SITE_NAME ?></div>
        </div>
        <div style="padding:16px;background:var(--bg-elevated);border-radius:var(--r);border:1px solid var(--border);">
          <div style="font-size:.7rem;text-transform:uppercase;letter-spacing:.12em;color:var(--gold);font-weight:700;margin-bottom:6px;">URL du site</div>
          <a href="<?= SITE_URL ?>" target="_blank" style="color:var(--text-2);font-size:.85rem;word-break:break-all;"><?= SITE_URL ?></a>
        </div>
        <div style="padding:16px;background:var(--bg-elevated);border-radius:var(--r);border:1px solid var(--border);">
          <div style="font-size:.7rem;text-transform:uppercase;letter-spacing:.12em;color:var(--gold);font-weight:700;margin-bottom:6px;">Email de contact</div>
          <div style="color:var(--text-2);font-size:.88rem;"><?= SITE_EMAIL ?></div>
        </div>
      </div>
    </div>

    <!-- Infos société -->
    <div class="admin-section" style="margin-bottom:20px;">
      <h2 class="section-heading">Informations Société</h2>
      <div class="grid-3">
        <?php
        $company_info = [
            'Société'        => COMPANY_NAME,
            'Groupe'         => COMPANY_GROUP,
            'RCCM'           => COMPANY_RCCM,
            'NIU'            => COMPANY_NIU,
            'BGFI Bank'      => COMPANY_BGFI,
            'Afriland First' => COMPANY_AFRILAND,
        ];
        foreach ($company_info as $label => $value): ?>
        <div style="padding:14px;background:var(--bg-elevated);border-radius:var(--r);border:1px solid var(--border);">
          <div style="font-size:.68rem;text-transform:uppercase;letter-spacing:.12em;color:var(--text-3);font-weight:700;margin-bottom:5px;"><?= $label ?></div>
          <div style="color:var(--text-1);font-size:.86rem;font-weight:600;word-break:break-all;"><?= e($value) ?></div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Infos système -->
    <div class="admin-section" style="margin-bottom:20px;">
      <h2 class="section-heading">Informations Système</h2>
      <div class="grid-3">
        <div style="padding:16px;background:var(--bg-elevated);border-radius:var(--r);border:1px solid var(--border);">
          <div style="font-size:.7rem;text-transform:uppercase;letter-spacing:.12em;color:var(--gold);font-weight:700;margin-bottom:6px;">PHP</div>
          <div style="color:var(--text-1);font-weight:600;"><?= e($php_version) ?></div>
          <div style="font-size:.74rem;color:var(--text-3);margin-top:3px;">
            PDO MySQL: <?= extension_loaded('pdo_mysql') ? '<span style="color:var(--success);">OK</span>' : '<span style="color:var(--danger);">Manquant</span>' ?>
          </div>
        </div>
        <div style="padding:16px;background:var(--bg-elevated);border-radius:var(--r);border:1px solid var(--border);">
          <div style="font-size:.7rem;text-transform:uppercase;letter-spacing:.12em;color:var(--gold);font-weight:700;margin-bottom:6px;">MySQL</div>
          <div style="color:var(--text-1);font-weight:600;"><?= e($db_version ?: 'N/A') ?></div>
          <div style="font-size:.74rem;color:var(--text-3);margin-top:3px;">Taille BDD: <?= e($db_size ?: 'N/A') ?></div>
        </div>
        <div style="padding:16px;background:var(--bg-elevated);border-radius:var(--r);border:1px solid var(--border);">
          <div style="font-size:.7rem;text-transform:uppercase;letter-spacing:.12em;color:var(--gold);font-weight:700;margin-bottom:6px;">Environnement</div>
          <div style="color:var(--text-1);font-weight:600;"><?= getenv('RAILWAY_ENVIRONMENT') ? 'Production (Railway)' : 'Développement local' ?></div>
          <div style="font-size:.74rem;color:var(--text-3);margin-top:3px;">Serveur: <?= e($_SERVER['SERVER_SOFTWARE'] ?? 'PHP Built-in') ?></div>
        </div>
      </div>
    </div>

    <!-- Statistiques -->
    <div class="admin-section">
      <h2 class="section-heading">Statistiques du Catalogue</h2>
      <div class="stats-row" style="grid-template-columns:repeat(4,1fr);">
        <div class="stat-card">
          <div class="stat-card-icon" style="background:rgba(22,163,74,.15);color:var(--success);">
            <i class="fas fa-box-open"></i>
          </div>
          <div>
            <div class="stat-card-num"><?= $stats['products'] ?></div>
            <div class="stat-card-label">Produits actifs</div>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-card-icon" style="background:rgba(96,165,250,.15);color:var(--info);">
            <i class="fas fa-building"></i>
          </div>
          <div>
            <div class="stat-card-num"><?= $stats['realestate'] ?></div>
            <div class="stat-card-label">Biens immobiliers</div>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-card-icon" style="background:rgba(212,168,83,.15);color:var(--gold);">
            <i class="fas fa-envelope"></i>
          </div>
          <div>
            <div class="stat-card-num"><?= $stats['messages'] ?></div>
            <div class="stat-card-label">Messages non lus</div>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-card-icon" style="background:rgba(239,68,68,.15);color:var(--danger);">
            <i class="fas fa-users"></i>
          </div>
          <div>
            <div class="stat-card-num"><?= $stats['newsletter'] ?></div>
            <div class="stat-card-label">Abonnés newsletter</div>
          </div>
        </div>
      </div>
    </div>

  </div>
</main>

<script>
const toggle = document.getElementById('sidebarToggle');
const sidebar = document.getElementById('adminSidebar');
const overlay = document.getElementById('sidebarOverlay');
toggle?.addEventListener('click', () => { sidebar.classList.toggle('open'); overlay.classList.toggle('active'); });
overlay?.addEventListener('click', () => { sidebar.classList.remove('open'); overlay.classList.remove('active'); });
</script>
</body>
</html>
