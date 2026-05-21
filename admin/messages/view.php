<?php
require_once dirname(dirname(__DIR__)) . '/config/config.php';
require_once dirname(dirname(__DIR__)) . '/includes/functions.php';
require_admin_login();

$id = sanitize_int($_GET['id'] ?? 0);
if (!$id) { header('Location: index.php'); exit; }

$msg = null;
try {
    $pdo = db();
    if ($pdo) {
        $stmt = $pdo->prepare('SELECT * FROM contact_messages WHERE id = ?');
        $stmt->execute([$id]);
        $msg = $stmt->fetch();
        if ($msg && !$msg['is_read']) {
            $pdo->prepare('UPDATE contact_messages SET is_read=1 WHERE id=?')->execute([$id]);
        }
    }
} catch (Exception $e) {}

if (!$msg) {
    set_flash('error', 'Message introuvable.');
    header('Location: index.php'); exit;
}

// Suppression via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete']) && csrf_verify()) {
    try {
        db()->prepare('DELETE FROM contact_messages WHERE id=?')->execute([$id]);
        admin_log('message_deleted', "Message supprimé: #$id");
        set_flash('success', 'Message supprimé.');
    } catch (Exception $e) {}
    header('Location: index.php'); exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Message – NATURAFRIK Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="<?= SITE_URL ?>/admin/css/admin.css">
</head>
<body class="admin-body">

<aside class="admin-sidebar" id="adminSidebar">
  <div class="sidebar-logo">
    <div class="sidebar-logo-icon">🌿</div>
    <div><div class="sidebar-brand">NATURAFRIK</div><div class="sidebar-admin">Administration</div></div>
  </div>
  <nav class="sidebar-nav">
    <div class="nav-group">
      <a href="../dashboard.php" class="sidebar-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
      <a href="../products/index.php" class="sidebar-link"><i class="fas fa-box-open"></i> Produits</a>
      <a href="../products/add.php" class="sidebar-link"><i class="fas fa-plus-circle"></i> Ajouter produit</a>
      <a href="../realestate/index.php" class="sidebar-link"><i class="fas fa-building"></i> Immobilier</a>
      <a href="index.php" class="sidebar-link active"><i class="fas fa-envelope"></i> Messages</a>
    </div>
  </nav>
  <div class="sidebar-footer">
    <div class="admin-user-info">
      <div class="admin-avatar"><?= strtoupper(substr($_SESSION['admin_name'] ?? 'A', 0, 2)) ?></div>
      <div><div class="admin-name"><?= e($_SESSION['admin_name'] ?? '') ?></div></div>
    </div>
    <a href="../logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i></a>
  </div>
</aside>

<main class="admin-main">
  <header class="admin-topbar">
    <button class="sidebar-toggle" id="sidebarToggle"><i class="fas fa-bars"></i></button>
    <div class="topbar-title">
      <h1>Message</h1>
      <span><a href="index.php" style="color:var(--gold)">Messages</a> / Détail</span>
    </div>
    <div class="topbar-actions">
      <a href="index.php" class="topbar-btn" title="Retour"><i class="fas fa-arrow-left"></i></a>
    </div>
  </header>

  <div class="admin-content">
    <div style="display:grid;grid-template-columns:1fr 320px;gap:24px;align-items:start;">

      <!-- Message principal -->
      <div class="admin-section">
        <div style="margin-bottom:24px;padding-bottom:20px;border-bottom:1px solid var(--border);">
          <h2 style="font-family:var(--font-serif);font-size:1.4rem;font-weight:700;color:white;margin-bottom:8px;"><?= e($msg['subject']) ?></h2>
          <div style="display:flex;align-items:center;gap:16px;font-size:.82rem;color:rgba(255,255,255,.4);">
            <span><i class="fas fa-user" style="color:var(--gold);margin-right:5px;"></i><?= e($msg['name']) ?></span>
            <span><i class="fas fa-calendar" style="color:var(--gold);margin-right:5px;"></i><?= format_date($msg['created_at']) ?></span>
            <?php if ($msg['section']): ?>
            <span><i class="fas fa-tag" style="color:var(--gold);margin-right:5px;"></i><?= e($msg['section']) ?></span>
            <?php endif; ?>
          </div>
        </div>

        <div style="font-size:.95rem;line-height:1.8;color:rgba(255,255,255,.8);white-space:pre-wrap;"><?= e($msg['message']) ?></div>

        <?php if (!empty($msg['product_interest'])): ?>
        <div style="margin-top:20px;padding:14px;background:rgba(201,168,76,.06);border:1px solid rgba(201,168,76,.15);border-radius:10px;font-size:.85rem;">
          <strong style="color:var(--gold);">Intérêt produit :</strong>
          <span style="color:rgba(255,255,255,.7);margin-left:8px;"><?= e($msg['product_interest']) ?></span>
        </div>
        <?php endif; ?>
      </div>

      <!-- Panneau latéral -->
      <div>
        <!-- Infos contact -->
        <div class="admin-section" style="margin-bottom:16px;">
          <div class="form-section-title">Contact</div>
          <div style="display:flex;flex-direction:column;gap:12px;font-size:.87rem;">
            <div>
              <div style="font-size:.72rem;color:rgba(255,255,255,.35);text-transform:uppercase;letter-spacing:.1em;margin-bottom:3px;">Nom</div>
              <div style="color:white;font-weight:600;"><?= e($msg['name']) ?></div>
            </div>
            <div>
              <div style="font-size:.72rem;color:rgba(255,255,255,.35);text-transform:uppercase;letter-spacing:.1em;margin-bottom:3px;">Email</div>
              <a href="mailto:<?= e($msg['email']) ?>" style="color:var(--gold);word-break:break-all;"><?= e($msg['email']) ?></a>
            </div>
            <?php if (!empty($msg['phone'])): ?>
            <div>
              <div style="font-size:.72rem;color:rgba(255,255,255,.35);text-transform:uppercase;letter-spacing:.1em;margin-bottom:3px;">Téléphone</div>
              <div style="color:white;"><?= e($msg['phone']) ?></div>
            </div>
            <?php endif; ?>
            <div>
              <div style="font-size:.72rem;color:rgba(255,255,255,.35);text-transform:uppercase;letter-spacing:.1em;margin-bottom:3px;">IP</div>
              <div style="color:rgba(255,255,255,.4);font-size:.8rem;"><?= e($msg['ip_address'] ?? '-') ?></div>
            </div>
          </div>
        </div>

        <!-- Actions -->
        <div class="admin-section">
          <div class="form-section-title">Actions</div>
          <div style="display:flex;flex-direction:column;gap:10px;">
            <a href="mailto:<?= e($msg['email']) ?>?subject=Re: <?= urlencode($msg['subject']) ?>"
               class="btn-admin btn-admin-primary" style="justify-content:center;">
              <i class="fas fa-reply"></i> Répondre par email
            </a>
            <?php if (!empty($msg['phone'])): ?>
            <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $msg['phone']) ?>?text=<?= urlencode('Bonjour ' . $msg['name'] . ', suite à votre message concernant: ' . $msg['subject']) ?>"
               class="btn-admin" style="background:rgba(37,211,102,.12);color:#25D366;border:1px solid rgba(37,211,102,.2);justify-content:center;" target="_blank">
              <i class="fab fa-whatsapp"></i> WhatsApp
            </a>
            <?php endif; ?>
            <form method="POST" onsubmit="return confirm('Supprimer ce message définitivement ?')">
              <?= csrf_field() ?>
              <input type="hidden" name="delete" value="1">
              <button type="submit" class="btn-admin btn-admin-danger" style="width:100%;justify-content:center;">
                <i class="fas fa-trash-alt"></i> Supprimer
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<script>document.getElementById('sidebarToggle')?.addEventListener('click', () => { document.getElementById('adminSidebar')?.classList.toggle('open'); });</script>
</body>
</html>
