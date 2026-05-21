<?php
require_once dirname(dirname(__DIR__)) . '/config/config.php';
require_once dirname(dirname(__DIR__)) . '/includes/functions.php';
require_admin_login();

// Suppression
if (isset($_GET['delete']) && csrf_verify()) {
    $id = sanitize_int($_GET['delete']);
    try {
        $p = db()->prepare('SELECT name FROM products WHERE id=?');
        $p->execute([$id]);
        $name = $p->fetchColumn();
        db()->prepare('DELETE FROM products WHERE id=?')->execute([$id]);
        admin_log('product_deleted', "Produit supprimé: $name");
        set_flash('success', "Produit supprimé.");
    } catch (Exception $e) { set_flash('error', 'Erreur lors de la suppression.'); }
    header('Location: index.php'); exit;
}

$flash = get_flash();
$page  = max(1, sanitize_int($_GET['page'] ?? 1));
$limit = 20;
$offset = ($page - 1) * $limit;
$search = sanitize($_GET['q'] ?? '');

$products = [];
$total    = 0;
try {
    $where = $search ? 'WHERE p.name LIKE ?' : '';
    $params = $search ? ["%$search%"] : [];
    $stmt = db()->prepare("SELECT p.*, c.name AS cat FROM products p LEFT JOIN categories c ON p.category_id = c.id $where ORDER BY p.created_at DESC LIMIT $limit OFFSET $offset");
    $stmt->execute($params);
    $products = $stmt->fetchAll();
    $stmt2 = db()->prepare("SELECT COUNT(*) FROM products p $where");
    $stmt2->execute($params);
    $total = (int)$stmt2->fetchColumn();
} catch (Exception $e) {}

$pages = ceil($total / $limit);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Produits – NATURAFRIK Admin</title>
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
    </div>
    <div class="nav-group">
      <div class="nav-group-title">Catalogue</div>
      <a href="index.php" class="sidebar-link active"><i class="fas fa-box-open"></i> Produits</a>
      <a href="add.php" class="sidebar-link"><i class="fas fa-plus-circle"></i> Ajouter</a>
      <a href="../realestate/index.php" class="sidebar-link"><i class="fas fa-building"></i> Immobilier</a>
    </div>
    <div class="nav-group">
      <a href="../messages/index.php" class="sidebar-link"><i class="fas fa-envelope"></i> Messages</a>
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
    <div class="topbar-title"><h1>Produits</h1><span><?= $total ?> produit(s)</span></div>
    <div class="topbar-actions">
      <form method="GET" style="display:flex;gap:8px;">
        <input type="text" name="q" value="<?= e($search) ?>" placeholder="Rechercher..." style="padding:8px 14px;border-radius:10px;border:1px solid rgba(255,255,255,.1);background:rgba(255,255,255,.05);color:white;font-size:.85rem;outline:none;">
        <button type="submit" style="padding:8px 12px;border-radius:10px;background:rgba(201,168,76,.15);color:var(--gold);border:1px solid rgba(201,168,76,.3);cursor:pointer;"><i class="fas fa-search"></i></button>
      </form>
      <a href="add.php" class="topbar-cta"><i class="fas fa-plus"></i> Ajouter</a>
    </div>
  </header>

  <div class="admin-content">
    <?php if ($flash): ?>
    <div class="alert alert-<?= e($flash['type']) ?>"><i class="fas fa-check-circle"></i> <?= e($flash['message']) ?></div>
    <?php endif; ?>

    <div class="admin-section">
      <?php if (!empty($products)): ?>
      <div class="admin-table-wrap">
        <table class="admin-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Image</th>
              <th>Nom</th>
              <th>Catégorie</th>
              <th>Prix</th>
              <th>Statut</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($products as $p): ?>
            <tr>
              <td style="color:rgba(255,255,255,.3);font-size:.8rem;">#<?= $p['id'] ?></td>
              <td>
                <?php if (!empty($p['main_image'])): ?>
                <img src="<?= product_image_url($p['main_image']) ?>" style="width:40px;height:40px;object-fit:cover;border-radius:6px;">
                <?php else: ?>
                <div style="width:40px;height:40px;border-radius:6px;background:rgba(255,255,255,.05);display:flex;align-items:center;justify-content:center;font-size:1.1rem;">📦</div>
                <?php endif; ?>
              </td>
              <td>
                <div style="font-weight:600;"><?= e(truncate($p['name'], 40)) ?></div>
                <?php if ($p['is_featured']): ?><span class="status-badge status-featured">Vedette</span><?php endif; ?>
                <?php if ($p['is_new']): ?><span class="status-badge" style="background:rgba(30,144,80,.15);color:#4ade80;border:1px solid rgba(30,144,80,.25);">Nouveau</span><?php endif; ?>
              </td>
              <td style="color:rgba(255,255,255,.55);font-size:.85rem;"><?= e($p['cat'] ?? '-') ?></td>
              <td style="color:var(--gold);font-weight:700;"><?= $p['price'] ? format_price($p['price'], $p['currency']) : '<span style="color:rgba(255,255,255,.3);">Sur demande</span>' ?></td>
              <td><span class="status-badge <?= $p['is_active'] ? 'status-active' : 'status-inactive' ?>"><?= $p['is_active'] ? 'Actif' : 'Inactif' ?></span></td>
              <td>
                <div class="table-actions">
                  <a href="edit.php?id=<?= $p['id'] ?>" class="action-btn action-edit" title="Modifier"><i class="fas fa-edit"></i></a>
                  <a href="index.php?delete=<?= $p['id'] ?>&<?= CSRF_TOKEN_NAME ?>=<?= csrf_token() ?>" class="action-btn action-delete" title="Supprimer" onclick="return confirm('Supprimer ce produit définitivement ?')"><i class="fas fa-trash-alt"></i></a>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <?php if ($pages > 1): ?>
      <div style="display:flex;gap:8px;margin-top:20px;justify-content:center;">
        <?php for ($i = 1; $i <= $pages; $i++): ?>
        <a href="?page=<?= $i ?><?= $search ? '&q='.urlencode($search) : '' ?>" style="width:36px;height:36px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:.85rem;text-decoration:none;<?= $i === $page ? 'background:var(--gold);color:#060F0A;font-weight:700;' : 'background:rgba(255,255,255,.05);color:rgba(255,255,255,.5);border:1px solid rgba(255,255,255,.08);' ?>"><?= $i ?></a>
        <?php endfor; ?>
      </div>
      <?php endif; ?>

      <?php else: ?>
      <div class="empty-state">
        <i class="fas fa-box-open"></i>
        <p>Aucun produit trouvé.<br><a href="add.php">Ajouter le premier produit →</a></p>
      </div>
      <?php endif; ?>
    </div>
  </div>
</main>

<script>document.getElementById('sidebarToggle')?.addEventListener('click', () => { document.getElementById('adminSidebar')?.classList.toggle('open'); });</script>
</body>
</html>
