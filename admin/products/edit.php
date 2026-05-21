<?php
require_once dirname(dirname(__DIR__)) . '/config/config.php';
require_once dirname(dirname(__DIR__)) . '/includes/functions.php';
require_admin_login();

$id = sanitize_int($_GET['id'] ?? 0);
if (!$id) { header('Location: index.php'); exit; }

$product = null;
try {
    $pdo = db();
    if ($pdo) {
        $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
        $stmt->execute([$id]);
        $product = $stmt->fetch();
    }
} catch (Exception $e) {}

if (!$product) {
    set_flash('error', 'Produit introuvable.');
    header('Location: index.php'); exit;
}

$categories = [];
try {
    $pdo = db();
    if ($pdo) $categories = $pdo->query('SELECT * FROM categories WHERE is_active=1 ORDER BY section, display_order')->fetchAll();
} catch (Exception $e) {}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        $error = 'Token de sécurité invalide.';
    } else {
        $name        = sanitize($_POST['name'] ?? '');
        $desc        = $_POST['description'] ?? '';
        $short_desc  = sanitize($_POST['short_description'] ?? '');
        $price       = (float)($_POST['price'] ?? 0);
        $price_unit  = sanitize($_POST['price_unit'] ?? 'kg');
        $currency    = sanitize($_POST['currency'] ?? 'XAF');
        $cat_id      = sanitize_int($_POST['category_id'] ?? 0);
        $origin      = sanitize($_POST['origin'] ?? '');
        $weight      = sanitize($_POST['weight'] ?? '');
        $stock       = sanitize_int($_POST['stock_quantity'] ?? 0);
        $min_order   = max(1, sanitize_int($_POST['min_order'] ?? 1));
        $is_featured = isset($_POST['is_featured']) ? 1 : 0;
        $is_new      = isset($_POST['is_new']) ? 1 : 0;
        $is_active   = isset($_POST['is_active']) ? 1 : 0;
        $cert        = sanitize($_POST['certification'] ?? '');
        $tags        = sanitize($_POST['tags'] ?? '');

        if (strlen($name) < 2 || $cat_id === 0) {
            $error = 'Nom et catégorie sont obligatoires.';
        } else {
            $main_image = $product['main_image'];
            if (!empty($_FILES['main_image']['name'])) {
                $uploaded = upload_image($_FILES['main_image'], 'products');
                if ($uploaded) {
                    $main_image = $uploaded;
                } else {
                    $error = "Erreur upload image. Vérifiez le format (JPG, PNG, WebP) et la taille (max 5MB).";
                }
            }

            if (!$error) {
                try {
                    $allowed_tags = '<p><br><b><strong><i><em><ul><ol><li><h2><h3><h4><span>';
                    $desc = strip_tags($desc, $allowed_tags);
                    db()->prepare('
                        UPDATE products
                        SET name=?,short_description=?,description=?,price=?,price_unit=?,currency=?,
                            category_id=?,main_image=?,stock_quantity=?,min_order=?,weight=?,
                            origin=?,certification=?,tags=?,is_featured=?,is_new=?,is_active=?,updated_at=NOW()
                        WHERE id=?
                    ')->execute([$name,$short_desc,$desc,$price ?: null,$price_unit,$currency,
                        $cat_id,$main_image,$stock,$min_order,$weight,$origin,$cert,$tags,
                        $is_featured,$is_new,$is_active,$id]);

                    admin_log('product_updated', "Produit modifié: $name (ID: $id)");
                    set_flash('success', "Produit \"$name\" mis à jour avec succès !");
                    header('Location: ' . SITE_URL . '/admin/products/index.php');
                    exit;
                } catch (Exception $e) {
                    $error = 'Erreur enregistrement: ' . $e->getMessage();
                }
            }
        }
    }
}

$v = $_SERVER['REQUEST_METHOD'] === 'POST' ? array_merge($product, $_POST) : $product;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modifier Produit – NATURAFRIK Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
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
      <a href="index.php" class="sidebar-link active"><i class="fas fa-box-open"></i> Produits</a>
      <a href="add.php" class="sidebar-link"><i class="fas fa-plus-circle"></i> Ajouter un produit</a>
      <a href="../realestate/index.php" class="sidebar-link"><i class="fas fa-building"></i> Immobilier</a>
      <a href="../realestate/add.php" class="sidebar-link"><i class="fas fa-plus-circle"></i> Ajouter un bien</a>
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
    <div class="topbar-title">
      <h1>Modifier le Produit</h1>
      <span><a href="index.php" style="color:var(--gold)">Produits</a> / <?= e(truncate($product['name'], 30)) ?></span>
    </div>
    <div class="topbar-actions">
      <a href="index.php" class="topbar-btn" title="Retour"><i class="fas fa-arrow-left"></i></a>
    </div>
  </header>

  <div class="admin-content">
    <?php if ($error): ?>
    <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= e($error) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <div style="display:grid;grid-template-columns:1.5fr 1fr;gap:24px;align-items:start;">

        <!-- Colonne principale -->
        <div>
          <div class="admin-form-card" style="margin-bottom:20px;">
            <div class="form-section-title">Informations Générales</div>
            <div class="form-group">
              <label class="form-label">Nom du produit <span class="req">*</span></label>
              <input type="text" name="name" class="form-control" required value="<?= e($v['name'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label class="form-label">Description courte</label>
              <textarea name="short_description" class="form-control" rows="2"><?= e($v['short_description'] ?? '') ?></textarea>
            </div>
            <div class="form-group">
              <label class="form-label">Description complète (HTML accepté)</label>
              <textarea name="description" class="form-control" rows="8"><?= e($v['description'] ?? '') ?></textarea>
            </div>
          </div>

          <div class="admin-form-card" style="margin-bottom:20px;">
            <div class="form-section-title">Tarification & Stock</div>
            <div class="form-row">
              <div class="form-group">
                <label class="form-label">Prix (vide = sur demande)</label>
                <input type="number" name="price" class="form-control" min="0" step="0.01" value="<?= e($v['price'] ?? '') ?>">
              </div>
              <div class="form-group">
                <label class="form-label">Devise</label>
                <select name="currency" class="form-select">
                  <option value="XAF" <?= ($v['currency'] ?? 'XAF') === 'XAF' ? 'selected' : '' ?>>FCFA (XAF)</option>
                  <option value="EUR" <?= ($v['currency'] ?? '') === 'EUR' ? 'selected' : '' ?>>Euro (EUR)</option>
                  <option value="USD" <?= ($v['currency'] ?? '') === 'USD' ? 'selected' : '' ?>>Dollar USD</option>
                </select>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label class="form-label">Unité de prix</label>
                <input type="text" name="price_unit" class="form-control" placeholder="kg, sachet 500g..." value="<?= e($v['price_unit'] ?? 'kg') ?>">
              </div>
              <div class="form-group">
                <label class="form-label">Stock disponible</label>
                <input type="number" name="stock_quantity" class="form-control" min="0" value="<?= e($v['stock_quantity'] ?? '0') ?>">
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label class="form-label">Commande minimum</label>
                <input type="number" name="min_order" class="form-control" min="1" value="<?= e($v['min_order'] ?? '1') ?>">
              </div>
              <div class="form-group">
                <label class="form-label">Poids / Volume</label>
                <input type="text" name="weight" class="form-control" value="<?= e($v['weight'] ?? '') ?>">
              </div>
            </div>
          </div>

          <div class="admin-form-card">
            <div class="form-section-title">Détails Supplémentaires</div>
            <div class="form-row">
              <div class="form-group">
                <label class="form-label">Origine / Provenance</label>
                <input type="text" name="origin" class="form-control" value="<?= e($v['origin'] ?? '') ?>">
              </div>
              <div class="form-group">
                <label class="form-label">Certifications</label>
                <input type="text" name="certification" class="form-control" value="<?= e($v['certification'] ?? '') ?>">
              </div>
            </div>
            <div class="form-group">
              <label class="form-label">Tags (séparés par virgules)</label>
              <input type="text" name="tags" class="form-control" value="<?= e($v['tags'] ?? '') ?>">
            </div>
          </div>
        </div>

        <!-- Colonne latérale -->
        <div>
          <div class="admin-form-card" style="margin-bottom:20px;">
            <div class="form-section-title">Catégorie <span class="req">*</span></div>
            <select name="category_id" class="form-select" required>
              <option value="">-- Sélectionner --</option>
              <?php
              $current_section = '';
              foreach ($categories as $cat):
                if ($cat['section'] !== $current_section) {
                    if ($current_section) echo '</optgroup>';
                    $sections = ['produits'=>'Produits','immobilier'=>'Immobilier','agriculture'=>'Agriculture','matieres_premieres'=>'Matières Premières'];
                    echo '<optgroup label="' . htmlspecialchars($sections[$cat['section']] ?? $cat['section']) . '">';
                    $current_section = $cat['section'];
                }
              ?>
              <option value="<?= $cat['id'] ?>" <?= ($v['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                <?= e($cat['name']) ?>
              </option>
              <?php endforeach; if ($current_section) echo '</optgroup>'; ?>
            </select>
          </div>

          <div class="admin-form-card" style="margin-bottom:20px;">
            <div class="form-section-title">Image Principale</div>
            <?php if (!empty($product['main_image'])): ?>
            <div style="margin-bottom:12px;border-radius:10px;overflow:hidden;">
              <img src="<?= product_image_url($product['main_image']) ?>" alt="Image actuelle" style="width:100%;max-height:160px;object-fit:cover;">
            </div>
            <p style="font-size:.75rem;color:rgba(255,255,255,.3);margin-bottom:10px;">Laisser vide pour garder l'image actuelle</p>
            <?php endif; ?>
            <label class="upload-zone" for="imgInput">
              <i class="fas fa-cloud-upload-alt"></i>
              <p>Cliquer pour changer<br><span style="font-size:.75rem;color:rgba(255,255,255,.25);">JPG, PNG, WebP · Max 5MB</span></p>
              <input type="file" name="main_image" id="imgInput" accept="image/jpeg,image/png,image/webp,image/gif" onchange="previewImg(this)">
            </label>
            <div class="img-preview-grid" id="imgPreview"></div>
          </div>

          <div class="admin-form-card" style="margin-bottom:20px;">
            <div class="form-section-title">Options</div>
            <div class="form-check">
              <input type="checkbox" name="is_active" id="isActive" class="form-check-input" <?= ($v['is_active'] ?? 1) ? 'checked' : '' ?>>
              <label for="isActive" class="form-check-label">Produit actif (visible)</label>
            </div>
            <div class="form-check">
              <input type="checkbox" name="is_featured" id="isFeatured" class="form-check-input" <?= ($v['is_featured'] ?? 0) ? 'checked' : '' ?>>
              <label for="isFeatured" class="form-check-label">Produit vedette</label>
            </div>
            <div class="form-check">
              <input type="checkbox" name="is_new" id="isNew" class="form-check-input" <?= ($v['is_new'] ?? 0) ? 'checked' : '' ?>>
              <label for="isNew" class="form-check-label">Marquer comme nouveau</label>
            </div>
          </div>

          <div style="display:flex;gap:12px;">
            <button type="submit" class="btn-admin btn-admin-primary" style="flex:1;justify-content:center;">
              <i class="fas fa-save"></i> Enregistrer
            </button>
            <a href="index.php" class="btn-admin btn-admin-secondary"><i class="fas fa-times"></i></a>
          </div>
        </div>
      </div>
    </form>
  </div>
</main>

<script>
document.getElementById('sidebarToggle')?.addEventListener('click', () => {
  document.getElementById('adminSidebar')?.classList.toggle('open');
});
function previewImg(input) {
  const preview = document.getElementById('imgPreview');
  preview.innerHTML = '';
  if (input.files?.[0]) {
    const reader = new FileReader();
    reader.onload = e => { preview.innerHTML = `<div class="img-preview-item"><img src="${e.target.result}" alt="Preview"></div>`; };
    reader.readAsDataURL(input.files[0]);
  }
}
</script>
</body>
</html>
