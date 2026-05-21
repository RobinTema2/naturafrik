<?php
require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_admin_login();

$page_title = 'Dashboard Admin';
$stats  = get_stats();
$flash  = get_flash();

// Derniers messages
$recent_messages = [];
try {
    $recent_messages = db()->query('SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5')->fetchAll();
} catch (Exception $e) {}

// Derniers produits
$recent_products = [];
try {
    $recent_products = db()->query('SELECT p.*, c.name AS cat FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.created_at DESC LIMIT 5')->fetchAll();
} catch (Exception $e) {}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard – NATURAFRIK Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="<?= SITE_URL ?>/admin/css/admin.css">
</head>
<body class="admin-body">

<!-- Sidebar -->
<aside class="admin-sidebar" id="adminSidebar">
  <div class="sidebar-logo">
    <div class="sidebar-logo-icon">🌿</div>
    <div>
      <div class="sidebar-brand">NATURAFRIK</div>
      <div class="sidebar-admin">Administration</div>
    </div>
  </div>

  <nav class="sidebar-nav">
    <div class="nav-group">
      <div class="nav-group-title">Principal</div>
      <a href="dashboard.php" class="sidebar-link active">
        <i class="fas fa-tachometer-alt"></i> Dashboard
      </a>
    </div>
    <div class="nav-group">
      <div class="nav-group-title">Catalogue</div>
      <a href="products/index.php" class="sidebar-link">
        <i class="fas fa-box-open"></i> Produits
        <span class="badge"><?= $stats['products'] ?></span>
      </a>
      <a href="products/add.php" class="sidebar-link">
        <i class="fas fa-plus-circle"></i> Ajouter un produit
      </a>
      <a href="realestate/index.php" class="sidebar-link">
        <i class="fas fa-building"></i> Immobilier
        <span class="badge"><?= $stats['realestate'] ?></span>
      </a>
      <a href="realestate/add.php" class="sidebar-link">
        <i class="fas fa-plus-circle"></i> Ajouter un bien
      </a>
    </div>
    <div class="nav-group">
      <div class="nav-group-title">Communication</div>
      <a href="messages/index.php" class="sidebar-link">
        <i class="fas fa-envelope"></i> Messages
        <?php if ($stats['messages'] > 0): ?>
        <span class="badge badge-alert"><?= $stats['messages'] ?></span>
        <?php endif; ?>
      </a>
      <a href="newsletter/index.php" class="sidebar-link">
        <i class="fas fa-paper-plane"></i> Newsletter
        <span class="badge"><?= $stats['newsletter'] ?></span>
      </a>
    </div>
    <div class="nav-group">
      <div class="nav-group-title">Système</div>
      <a href="settings.php" class="sidebar-link">
        <i class="fas fa-cog"></i> Paramètres
      </a>
      <a href="profile.php" class="sidebar-link">
        <i class="fas fa-user-circle"></i> Mon Profil
      </a>
    </div>
  </nav>

  <div class="sidebar-footer">
    <div class="admin-user-info">
      <div class="admin-avatar"><?= strtoupper(substr($_SESSION['admin_name'] ?? 'A', 0, 2)) ?></div>
      <div>
        <div class="admin-name"><?= e($_SESSION['admin_name'] ?? 'Admin') ?></div>
        <div class="admin-role"><?= e(ucfirst(str_replace('_', ' ', $_SESSION['admin_role'] ?? ''))) ?></div>
      </div>
    </div>
    <a href="logout.php" class="btn-logout" title="Se déconnecter">
      <i class="fas fa-sign-out-alt"></i>
    </a>
  </div>
</aside>

<!-- Main content -->
<main class="admin-main">
  <!-- Top bar -->
  <header class="admin-topbar">
    <button class="sidebar-toggle" id="sidebarToggle">
      <i class="fas fa-bars"></i>
    </button>
    <div class="topbar-title">
      <h1>Dashboard</h1>
      <span>Bienvenue, <?= e($_SESSION['admin_name'] ?? 'Admin') ?> 👋</span>
    </div>
    <div class="topbar-actions">
      <a href="<?= SITE_URL ?>/index.php" target="_blank" class="topbar-btn" title="Voir le site">
        <i class="fas fa-external-link-alt"></i>
      </a>
      <a href="products/add.php" class="topbar-cta">
        <i class="fas fa-plus"></i> Ajouter
      </a>
    </div>
  </header>

  <div class="admin-content">

    <?php if ($flash): ?>
    <div class="alert alert-<?= e($flash['type']) ?>">
      <i class="fas fa-<?= $flash['type'] === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
      <?= e($flash['message']) ?>
    </div>
    <?php endif; ?>

    <!-- Stats cards -->
    <div class="stats-row">
      <div class="stat-card">
        <div class="stat-card-icon" style="background:rgba(11,77,46,.2);color:#1E9050;">
          <i class="fas fa-box-open"></i>
        </div>
        <div>
          <div class="stat-card-num"><?= $stats['products'] ?></div>
          <div class="stat-card-label">Produits actifs</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-card-icon" style="background:rgba(30,64,128,.2);color:#4A90D9;">
          <i class="fas fa-building"></i>
        </div>
        <div>
          <div class="stat-card-num"><?= $stats['realestate'] ?></div>
          <div class="stat-card-label">Biens immobiliers</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-card-icon" style="background:rgba(201,168,76,.15);color:var(--gold);">
          <i class="fas fa-envelope"></i>
        </div>
        <div>
          <div class="stat-card-num"><?= $stats['messages'] ?></div>
          <div class="stat-card-label">Messages non lus</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-card-icon" style="background:rgba(220,38,38,.15);color:#f87171;">
          <i class="fas fa-users"></i>
        </div>
        <div>
          <div class="stat-card-num"><?= $stats['newsletter'] ?></div>
          <div class="stat-card-label">Abonnés newsletter</div>
        </div>
      </div>
    </div>

    <!-- Actions rapides -->
    <div class="admin-section">
      <h2 class="section-heading">Actions Rapides</h2>
      <div class="quick-actions">
        <a href="products/add.php" class="quick-btn">
          <i class="fas fa-plus-circle"></i>
          <span>Nouveau produit</span>
        </a>
        <a href="realestate/add.php" class="quick-btn">
          <i class="fas fa-home"></i>
          <span>Nouveau bien</span>
        </a>
        <a href="messages/index.php" class="quick-btn">
          <i class="fas fa-envelope-open-text"></i>
          <span>Voir messages</span>
          <?php if ($stats['messages'] > 0): ?>
          <span class="quick-badge"><?= $stats['messages'] ?></span>
          <?php endif; ?>
        </a>
        <a href="<?= SITE_URL ?>/index.php" target="_blank" class="quick-btn">
          <i class="fas fa-eye"></i>
          <span>Voir le site</span>
        </a>
      </div>
    </div>

    <!-- Tables côte à côte -->
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">

      <!-- Derniers messages -->
      <div class="admin-section">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
          <h2 class="section-heading" style="margin:0;">Derniers Messages</h2>
          <a href="messages/index.php" style="font-size:.8rem;color:var(--gold);">Voir tout →</a>
        </div>
        <?php if (!empty($recent_messages)): ?>
        <div class="admin-table-wrap">
          <table class="admin-table">
            <thead><tr><th>Nom</th><th>Sujet</th><th>Date</th></tr></thead>
            <tbody>
              <?php foreach ($recent_messages as $msg): ?>
              <tr <?= !$msg['is_read'] ? 'style="font-weight:700;"' : '' ?>>
                <td><?= e($msg['name']) ?></td>
                <td><?= e(truncate($msg['subject'], 30)) ?></td>
                <td style="white-space:nowrap;font-size:.78rem;"><?= format_date($msg['created_at']) ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <?php else: ?>
        <div class="empty-state"><i class="fas fa-inbox"></i><p>Aucun message</p></div>
        <?php endif; ?>
      </div>

      <!-- Derniers produits -->
      <div class="admin-section">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
          <h2 class="section-heading" style="margin:0;">Derniers Produits</h2>
          <a href="products/index.php" style="font-size:.8rem;color:var(--gold);">Voir tout →</a>
        </div>
        <?php if (!empty($recent_products)): ?>
        <div class="admin-table-wrap">
          <table class="admin-table">
            <thead><tr><th>Nom</th><th>Catégorie</th><th>Prix</th></tr></thead>
            <tbody>
              <?php foreach ($recent_products as $p): ?>
              <tr>
                <td><?= e(truncate($p['name'], 28)) ?></td>
                <td><?= e($p['cat'] ?? '-') ?></td>
                <td><?= $p['price'] ? format_price($p['price'], $p['currency']) : 'Sur demande' ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <?php else: ?>
        <div class="empty-state"><i class="fas fa-box-open"></i><p>Aucun produit. <a href="products/add.php">Ajouter →</a></p></div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Info système -->
    <div class="admin-section" style="margin-top:24px;">
      <h2 class="section-heading">Informations Système</h2>
      <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;font-size:.85rem;">
        <div style="padding:16px;background:rgba(255,255,255,.03);border-radius:12px;border:1px solid rgba(255,255,255,.06);">
          <div style="color:var(--gold);font-weight:700;margin-bottom:4px;">RCCM</div>
          <div style="color:rgba(255,255,255,.7);"><?= COMPANY_RCCM ?></div>
        </div>
        <div style="padding:16px;background:rgba(255,255,255,.03);border-radius:12px;border:1px solid rgba(255,255,255,.06);">
          <div style="color:var(--gold);font-weight:700;margin-bottom:4px;">NIU</div>
          <div style="color:rgba(255,255,255,.7);"><?= COMPANY_NIU ?></div>
        </div>
        <div style="padding:16px;background:rgba(255,255,255,.03);border-radius:12px;border:1px solid rgba(255,255,255,.06);">
          <div style="color:var(--gold);font-weight:700;margin-bottom:4px;">Serveur PHP</div>
          <div style="color:rgba(255,255,255,.7);"><?= phpversion() ?></div>
        </div>
      </div>
    </div>

  </div>
</main>

<script>
document.getElementById('sidebarToggle')?.addEventListener('click', () => {
  document.getElementById('adminSidebar')?.classList.toggle('open');
});
</script>
</body>
</html>
