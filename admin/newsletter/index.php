<?php
require_once dirname(dirname(__DIR__)) . '/config/config.php';
require_once dirname(dirname(__DIR__)) . '/includes/functions.php';
require_admin_login();

// Désabonner via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_id']) && csrf_verify()) {
    $sub_id = sanitize_int($_POST['toggle_id']);
    try {
        $pdo = db();
        if ($pdo) {
            $stmt = $pdo->prepare('SELECT is_active FROM newsletter WHERE id=?');
            $stmt->execute([$sub_id]);
            $current = $stmt->fetchColumn();
            $pdo->prepare('UPDATE newsletter SET is_active=? WHERE id=?')->execute([$current ? 0 : 1, $sub_id]);
            set_flash('success', 'Abonnement mis à jour.');
        }
    } catch (Exception $e) { set_flash('error', 'Erreur.'); }
    header('Location: index.php'); exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id']) && csrf_verify()) {
    $del_id = sanitize_int($_POST['delete_id']);
    try {
        $pdo = db();
        if ($pdo) {
            $pdo->prepare('DELETE FROM newsletter WHERE id=?')->execute([$del_id]);
            set_flash('success', 'Abonné supprimé.');
        }
    } catch (Exception $e) { set_flash('error', 'Erreur.'); }
    header('Location: index.php'); exit;
}

$flash = get_flash();
$page  = max(1, sanitize_int($_GET['page'] ?? 1));
$limit = 25;
$offset = ($page - 1) * $limit;

$subscribers = [];
$total       = 0;
$total_active = 0;

try {
    $pdo = db();
    if ($pdo) {
        $total        = (int)$pdo->query('SELECT COUNT(*) FROM newsletter')->fetchColumn();
        $total_active = (int)$pdo->query('SELECT COUNT(*) FROM newsletter WHERE is_active=1')->fetchColumn();
        $stmt = $pdo->prepare('SELECT * FROM newsletter ORDER BY subscribed_at DESC LIMIT '.$limit.' OFFSET '.$offset);
        $stmt->execute();
        $subscribers = $stmt->fetchAll();
    }
} catch (Exception $e) {}

$pages = $total > 0 ? ceil($total / $limit) : 1;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Newsletter – NATURAFRIK Admin</title>
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
      <a href="../dashboard.php" class="sidebar-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    </div>
    <div class="nav-group">
      <div class="nav-group-title">Catalogue</div>
      <a href="../products/index.php" class="sidebar-link"><i class="fas fa-box-open"></i> Produits</a>
      <a href="../products/add.php" class="sidebar-link"><i class="fas fa-plus-circle"></i> Ajouter un produit</a>
      <a href="../realestate/index.php" class="sidebar-link"><i class="fas fa-building"></i> Immobilier</a>
      <a href="../realestate/add.php" class="sidebar-link"><i class="fas fa-plus-circle"></i> Ajouter un bien</a>
    </div>
    <div class="nav-group">
      <div class="nav-group-title">Communication</div>
      <a href="../messages/index.php" class="sidebar-link"><i class="fas fa-envelope"></i> Messages</a>
      <a href="index.php" class="sidebar-link active"><i class="fas fa-paper-plane"></i> Newsletter</a>
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
    <a href="../logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i></a>
  </div>
</aside>
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<main class="admin-main">
  <header class="admin-topbar">
    <button class="sidebar-toggle" id="sidebarToggle"><i class="fas fa-bars"></i></button>
    <div class="topbar-title">
      <h1>Newsletter</h1>
      <span><?= $total_active ?> abonné(s) actif(s) · <?= $total ?> total</span>
    </div>
  </header>

  <div class="admin-content">
    <?php if ($flash): ?>
    <div class="alert alert-<?= e($flash['type']) ?>">
      <i class="fas fa-<?= $flash['type'] === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
      <?= e($flash['message']) ?>
    </div>
    <?php endif; ?>

    <!-- Stats -->
    <div class="stats-row" style="grid-template-columns:1fr 1fr;margin-bottom:20px;max-width:480px;">
      <div class="stat-card">
        <div class="stat-card-icon" style="background:rgba(22,163,74,.15);color:var(--success);">
          <i class="fas fa-user-check"></i>
        </div>
        <div>
          <div class="stat-card-num"><?= $total_active ?></div>
          <div class="stat-card-label">Abonnés actifs</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-card-icon" style="background:rgba(212,168,83,.15);color:var(--gold);">
          <i class="fas fa-users"></i>
        </div>
        <div>
          <div class="stat-card-num"><?= $total ?></div>
          <div class="stat-card-label">Total inscrits</div>
        </div>
      </div>
    </div>

    <div class="admin-section">
      <?php if (!empty($subscribers)): ?>
      <div class="admin-table-wrap">
        <table class="admin-table">
          <thead>
            <tr>
              <th></th>
              <th>Email</th>
              <th>Nom</th>
              <th>Date d'inscription</th>
              <th>Statut</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($subscribers as $sub): ?>
            <tr>
              <td><span class="subscriber-dot <?= $sub['is_active'] ? 'active' : 'inactive' ?>"></span></td>
              <td style="color:var(--text-1);font-weight:600;"><?= e($sub['email']) ?></td>
              <td style="color:var(--text-2);"><?= e($sub['name'] ?? '—') ?></td>
              <td style="font-size:.8rem;color:var(--text-3);white-space:nowrap;"><?= format_date($sub['subscribed_at']) ?></td>
              <td>
                <span class="status-badge <?= $sub['is_active'] ? 'status-active' : 'status-inactive' ?>">
                  <?= $sub['is_active'] ? 'Actif' : 'Désabonné' ?>
                </span>
              </td>
              <td>
                <div class="table-actions">
                  <form method="POST" style="display:inline;">
                    <?= csrf_field() ?>
                    <input type="hidden" name="toggle_id" value="<?= $sub['id'] ?>">
                    <button type="submit" class="action-btn action-view" title="<?= $sub['is_active'] ? 'Désabonner' : 'Réabonner' ?>">
                      <i class="fas fa-<?= $sub['is_active'] ? 'toggle-on' : 'toggle-off' ?>"></i>
                    </button>
                  </form>
                  <form method="POST" style="display:inline;" onsubmit="return confirm('Supprimer cet abonné ?')">
                    <?= csrf_field() ?>
                    <input type="hidden" name="delete_id" value="<?= $sub['id'] ?>">
                    <button type="submit" class="action-btn action-delete" title="Supprimer">
                      <i class="fas fa-trash-alt"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <?php if ($pages > 1): ?>
      <div class="pagination">
        <?php for ($i = 1; $i <= $pages; $i++): ?>
        <a href="?page=<?= $i ?>" class="<?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
      </div>
      <?php endif; ?>

      <?php else: ?>
      <div class="empty-state">
        <i class="fas fa-paper-plane"></i>
        <p>Aucun abonné à la newsletter pour le moment.</p>
      </div>
      <?php endif; ?>
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
