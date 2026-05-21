<?php
require_once dirname(dirname(__DIR__)) . '/config/config.php';
require_once dirname(dirname(__DIR__)) . '/includes/functions.php';
require_admin_login();

// Marquer comme lu
if (isset($_GET['read'])) {
    $id = sanitize_int($_GET['read']);
    db()->prepare('UPDATE contact_messages SET is_read=1 WHERE id=?')->execute([$id]);
}

$page    = max(1, sanitize_int($_GET['page'] ?? 1));
$limit   = 20;
$offset  = ($page - 1) * $limit;
$unread  = db()->query('SELECT COUNT(*) FROM contact_messages WHERE is_read=0')->fetchColumn();
$total   = db()->query('SELECT COUNT(*) FROM contact_messages')->fetchColumn();
$messages = db()->prepare('SELECT * FROM contact_messages ORDER BY is_read ASC, created_at DESC LIMIT '.$limit.' OFFSET '.$offset);
$messages->execute();
$messages = $messages->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Messages – NATURAFRIK Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="<?= SITE_URL ?>/admin/css/admin.css">
</head>
<body class="admin-body">

<aside class="admin-sidebar" id="adminSidebar">
  <div class="sidebar-logo"><div class="sidebar-logo-icon">🌿</div><div><div class="sidebar-brand">NATURAFRIK</div></div></div>
  <nav class="sidebar-nav">
    <div class="nav-group">
      <a href="../dashboard.php" class="sidebar-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
      <a href="../products/index.php" class="sidebar-link"><i class="fas fa-box-open"></i> Produits</a>
      <a href="../products/add.php" class="sidebar-link"><i class="fas fa-plus-circle"></i> Ajouter</a>
      <a href="index.php" class="sidebar-link active"><i class="fas fa-envelope"></i> Messages <span class="badge badge-alert"><?= $unread ?></span></a>
    </div>
  </nav>
  <div class="sidebar-footer">
    <div class="admin-user-info"><div class="admin-avatar"><?= strtoupper(substr($_SESSION['admin_name'] ?? 'A', 0, 2)) ?></div><div><div class="admin-name"><?= e($_SESSION['admin_name'] ?? '') ?></div></div></div>
    <a href="../logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i></a>
  </div>
</aside>

<main class="admin-main">
  <header class="admin-topbar">
    <button class="sidebar-toggle" id="sidebarToggle"><i class="fas fa-bars"></i></button>
    <div class="topbar-title"><h1>Messages</h1><span><?= $unread ?> non lu(s) · <?= $total ?> total</span></div>
  </header>

  <div class="admin-content">
    <div class="admin-section">
      <?php if (!empty($messages)): ?>
      <div class="admin-table-wrap">
        <table class="admin-table">
          <thead><tr><th></th><th>Nom</th><th>Email</th><th>Téléphone</th><th>Sujet</th><th>Intérêt</th><th>Date</th><th>Action</th></tr></thead>
          <tbody>
            <?php foreach ($messages as $msg): ?>
            <tr style="<?= !$msg['is_read'] ? 'font-weight:700;' : 'opacity:.75;' ?>">
              <td><?= !$msg['is_read'] ? '<span style="color:#f87171;font-size:.7rem;">●</span>' : '<span style="color:rgba(255,255,255,.2);font-size:.7rem;">○</span>' ?></td>
              <td><?= e($msg['name']) ?></td>
              <td><a href="mailto:<?= e($msg['email']) ?>" style="color:var(--gold);"><?= e($msg['email']) ?></a></td>
              <td><?= e($msg['phone'] ?? '-') ?></td>
              <td><?= e(truncate($msg['subject'], 35)) ?></td>
              <td style="font-size:.8rem;color:rgba(255,255,255,.4);"><?= e($msg['product_interest'] ?? '-') ?></td>
              <td style="white-space:nowrap;font-size:.78rem;"><?= format_date($msg['created_at']) ?></td>
              <td>
                <div class="table-actions">
                  <?php if (!$msg['is_read']): ?>
                  <a href="?read=<?= $msg['id'] ?>" class="action-btn action-view" title="Marquer lu"><i class="fas fa-check"></i></a>
                  <?php endif; ?>
                  <a href="view.php?id=<?= $msg['id'] ?>" class="action-btn action-edit" title="Lire"><i class="fas fa-eye"></i></a>
                  <a href="mailto:<?= e($msg['email']) ?>?subject=Re: <?= urlencode($msg['subject']) ?>" class="action-btn action-view" title="Répondre par email"><i class="fas fa-reply"></i></a>
                  <a href="https://wa.me/<?= preg_replace('/[^0-9]/','',$msg['phone'] ?? '') ?>" class="action-btn" style="background:rgba(37,211,102,.1);color:#25D366;" title="WhatsApp" <?= empty($msg['phone']) ? 'style="opacity:.3;pointer-events:none;"' : '' ?>><i class="fab fa-whatsapp"></i></a>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <?php else: ?>
      <div class="empty-state"><i class="fas fa-inbox"></i><p>Aucun message reçu.</p></div>
      <?php endif; ?>
    </div>
  </div>
</main>

<script>document.getElementById('sidebarToggle')?.addEventListener('click', () => { document.getElementById('adminSidebar')?.classList.toggle('open'); });</script>
</body>
</html>
