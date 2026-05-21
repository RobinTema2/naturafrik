<?php
require_once dirname(dirname(__DIR__)) . '/config/config.php';
require_once dirname(dirname(__DIR__)) . '/includes/functions.php';
require_admin_login();

// Suppression via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id']) && csrf_verify()) {
    $del_id = sanitize_int($_POST['delete_id']);
    try {
        $p = db()->prepare('SELECT title FROM real_estate WHERE id=?');
        $p->execute([$del_id]);
        $title = $p->fetchColumn();
        db()->prepare('DELETE FROM real_estate WHERE id=?')->execute([$del_id]);
        admin_log('realestate_deleted', "Bien supprimé: $title");
        set_flash('success', "Bien immobilier supprimé.");
    } catch (Exception $e) { set_flash('error', 'Erreur lors de la suppression.'); }
    header('Location: index.php'); exit;
}

$flash  = get_flash();
$page   = max(1, sanitize_int($_GET['page'] ?? 1));
$limit  = 20;
$offset = ($page - 1) * $limit;
$search = sanitize($_GET['q'] ?? '');

$items = [];
$total = 0;
try {
    $pdo = db();
    if ($pdo) {
        $where  = $search ? 'WHERE title LIKE ?' : '';
        $params = $search ? ["%$search%"] : [];
        $stmt   = $pdo->prepare("SELECT * FROM real_estate $where ORDER BY created_at DESC LIMIT $limit OFFSET $offset");
        $stmt->execute($params);
        $items  = $stmt->fetchAll();
        $stmt2  = $pdo->prepare("SELECT COUNT(*) FROM real_estate $where");
        $stmt2->execute($params);
        $total  = (int)$stmt2->fetchColumn();
    }
} catch (Exception $e) {}

$pages = $total > 0 ? ceil($total / $limit) : 1;

$type_labels = [
    'house'     => 'Maison',
    'land'      => 'Terrain',
    'rental'    => 'Location',
    'shop'      => 'Boutique',
    'apartment' => 'Appartement',
    'villa'     => 'Villa',
    'warehouse' => 'Entrepôt',
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Immobilier – NATURAFRIK Admin</title>
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
      <div class="nav-group-title">Principal</div>
      <a href="../dashboard.php" class="sidebar-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    </div>
    <div class="nav-group">
      <div class="nav-group-title">Catalogue</div>
      <a href="../products/index.php" class="sidebar-link"><i class="fas fa-box-open"></i> Produits</a>
      <a href="../products/add.php" class="sidebar-link"><i class="fas fa-plus-circle"></i> Ajouter un produit</a>
      <a href="index.php" class="sidebar-link active"><i class="fas fa-building"></i> Immobilier</a>
      <a href="add.php" class="sidebar-link"><i class="fas fa-plus-circle"></i> Ajouter un bien</a>
    </div>
    <div class="nav-group">
      <div class="nav-group-title">Communication</div>
      <a href="../messages/index.php" class="sidebar-link"><i class="fas fa-envelope"></i> Messages</a>
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

<main class="admin-main">
  <header class="admin-topbar">
    <button class="sidebar-toggle" id="sidebarToggle"><i class="fas fa-bars"></i></button>
    <div class="topbar-title"><h1>Immobilier</h1><span><?= $total ?> bien(s)</span></div>
    <div class="topbar-actions">
      <form method="GET" style="display:flex;gap:8px;">
        <input type="text" name="q" value="<?= e($search) ?>" placeholder="Rechercher..." style="padding:8px 14px;border-radius:10px;border:1px solid rgba(255,255,255,.1);background:rgba(255,255,255,.05);color:white;font-size:.85rem;outline:none;width:200px;">
        <button type="submit" style="padding:8px 12px;border-radius:10px;background:rgba(201,168,76,.15);color:var(--gold);border:1px solid rgba(201,168,76,.3);cursor:pointer;"><i class="fas fa-search"></i></button>
      </form>
      <a href="add.php" class="topbar-cta"><i class="fas fa-plus"></i> Ajouter</a>
    </div>
  </header>

  <div class="admin-content">
    <?php if ($flash): ?>
    <div class="alert alert-<?= e($flash['type']) ?>"><i class="fas fa-<?= $flash['type'] === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i> <?= e($flash['message']) ?></div>
    <?php endif; ?>

    <div class="admin-section">
      <?php if (!empty($items)): ?>
      <div class="admin-table-wrap">
        <table class="admin-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Image</th>
              <th>Titre</th>
              <th>Type</th>
              <th>Ville</th>
              <th>Prix</th>
              <th>Statut</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
              <td style="color:rgba(255,255,255,.3);font-size:.8rem;">#<?= $item['id'] ?></td>
              <td>
                <?php if (!empty($item['main_image'])): ?>
                <img src="<?= product_image_url($item['main_image']) ?>" style="width:40px;height:40px;object-fit:cover;border-radius:6px;" alt="">
                <?php else: ?>
                <div style="width:40px;height:40px;border-radius:6px;background:rgba(255,255,255,.05);display:flex;align-items:center;justify-content:center;font-size:1rem;color:rgba(255,255,255,.2);"><i class="fas fa-building"></i></div>
                <?php endif; ?>
              </td>
              <td>
                <div style="font-weight:600;color:white;"><?= e(truncate($item['title'], 40)) ?></div>
                <?php if ($item['is_featured']): ?><span class="status-badge status-featured">Vedette</span><?php endif; ?>
              </td>
              <td style="color:rgba(255,255,255,.55);font-size:.85rem;"><?= e($type_labels[$item['type']] ?? $item['type']) ?></td>
              <td style="color:rgba(255,255,255,.55);font-size:.85rem;"><?= e($item['city'] ?? '-') ?></td>
              <td style="color:var(--gold);font-weight:700;"><?= $item['price'] ? format_price($item['price'], $item['currency']) : '<span style="color:rgba(255,255,255,.3);">Sur demande</span>' ?></td>
              <td><span class="status-badge <?= $item['is_available'] ? 'status-active' : 'status-inactive' ?>"><?= $item['is_available'] ? 'Disponible' : 'Indisponible' ?></span></td>
              <td>
                <div class="table-actions">
                  <a href="edit.php?id=<?= $item['id'] ?>" class="action-btn action-edit" title="Modifier"><i class="fas fa-edit"></i></a>
                  <form method="POST" style="display:inline;" onsubmit="return confirm('Supprimer ce bien définitivement ?')">
                    <?= csrf_field() ?>
                    <input type="hidden" name="delete_id" value="<?= $item['id'] ?>">
                    <button type="submit" class="action-btn action-delete" title="Supprimer"><i class="fas fa-trash-alt"></i></button>
                  </form>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <?php if ($pages > 1): ?>
      <div style="display:flex;gap:8px;margin-top:20px;justify-content:center;">
        <?php for ($i = 1; $i <= $pages; $i++): ?>
        <a href="?page=<?= $i ?><?= $search ? '&q='.urlencode($search) : '' ?>"
           style="width:36px;height:36px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:.85rem;text-decoration:none;<?= $i === $page ? 'background:var(--gold);color:#060F0A;font-weight:700;' : 'background:rgba(255,255,255,.05);color:rgba(255,255,255,.5);border:1px solid rgba(255,255,255,.08);' ?>"><?= $i ?></a>
        <?php endfor; ?>
      </div>
      <?php endif; ?>

      <?php else: ?>
      <div class="empty-state">
        <i class="fas fa-building"></i>
        <p>Aucun bien immobilier.<br><a href="add.php">Ajouter le premier bien →</a></p>
      </div>
      <?php endif; ?>
    </div>
  </div>
</main>

<script>document.getElementById('sidebarToggle')?.addEventListener('click', () => { document.getElementById('adminSidebar')?.classList.toggle('open'); });</script>
</body>
</html>
