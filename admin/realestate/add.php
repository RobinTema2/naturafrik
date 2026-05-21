<?php
require_once dirname(dirname(__DIR__)) . '/config/config.php';
require_once dirname(dirname(__DIR__)) . '/includes/functions.php';
require_admin_login();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        $error = 'Token de sécurité invalide.';
    } else {
        $title       = sanitize($_POST['title'] ?? '');
        $type        = sanitize($_POST['type'] ?? 'house');
        $transaction = sanitize($_POST['transaction'] ?? 'sale');
        $price       = (float)($_POST['price'] ?? 0);
        $currency    = sanitize($_POST['currency'] ?? 'XAF');
        $location    = sanitize($_POST['location'] ?? '');
        $city        = sanitize($_POST['city'] ?? 'Yaoundé');
        $country     = sanitize($_POST['country'] ?? 'Cameroun');
        $surface     = (float)($_POST['surface_area'] ?? 0);
        $bedrooms    = sanitize_int($_POST['bedrooms'] ?? 0);
        $bathrooms   = sanitize_int($_POST['bathrooms'] ?? 0);
        $desc        = $_POST['description'] ?? '';
        $amenities   = sanitize($_POST['amenities'] ?? '');
        $is_featured = isset($_POST['is_featured']) ? 1 : 0;
        $is_available = isset($_POST['is_available']) ? 1 : 0;

        if (strlen($title) < 2 || empty($location)) {
            $error = 'Titre et localisation sont obligatoires.';
        } else {
            $main_image = null;
            if (!empty($_FILES['main_image']['name'])) {
                $uploaded = upload_image($_FILES['main_image'], 'realestate');
                if ($uploaded) {
                    $main_image = $uploaded;
                } else {
                    $error = "Erreur upload image. Vérifiez le format (JPG, PNG, WebP) et la taille (max 5MB).";
                }
            }

            if (!$error) {
                try {
                    $allowed_tags = '<p><br><b><strong><i><em><ul><ol><li><h2><h3><h4>';
                    $desc = strip_tags($desc, $allowed_tags);
                    $slug = unique_slug('real_estate', $title);

                    db()->prepare('
                        INSERT INTO real_estate (title,slug,type,transaction,price,currency,location,city,country,surface_area,bedrooms,bathrooms,description,main_image,amenities,is_featured,is_available)
                        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
                    ')->execute([$title,$slug,$type,$transaction,$price ?: null,$currency,$location,$city,$country,
                        $surface ?: null,$bedrooms ?: null,$bathrooms ?: null,$desc,$main_image,$amenities,$is_featured,$is_available]);

                    admin_log('realestate_added', "Bien ajouté: $title");
                    set_flash('success', "Bien \"$title\" ajouté avec succès !");
                    header('Location: ' . SITE_URL . '/admin/realestate/index.php');
                    exit;
                } catch (Exception $e) {
                    $error = 'Erreur enregistrement: ' . $e->getMessage();
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ajouter Bien – NATURAFRIK Admin</title>
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
      <a href="../products/index.php" class="sidebar-link"><i class="fas fa-box-open"></i> Produits</a>
      <a href="../products/add.php" class="sidebar-link"><i class="fas fa-plus-circle"></i> Ajouter un produit</a>
      <a href="index.php" class="sidebar-link"><i class="fas fa-building"></i> Immobilier</a>
      <a href="add.php" class="sidebar-link active"><i class="fas fa-plus-circle"></i> Ajouter un bien</a>
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
      <h1>Ajouter un Bien Immobilier</h1>
      <span><a href="index.php" style="color:var(--gold)">Immobilier</a> / Nouveau</span>
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
              <label class="form-label">Titre du bien <span class="req">*</span></label>
              <input type="text" name="title" class="form-control" placeholder="Ex: Villa 5 pièces à Bastos, Yaoundé" required value="<?= e($_POST['title'] ?? '') ?>">
            </div>
            <div class="form-row">
              <div class="form-group">
                <label class="form-label">Type de bien</label>
                <select name="type" class="form-select">
                  <option value="house" <?= ($_POST['type'] ?? '') === 'house' ? 'selected' : '' ?>>Maison</option>
                  <option value="apartment" <?= ($_POST['type'] ?? '') === 'apartment' ? 'selected' : '' ?>>Appartement</option>
                  <option value="villa" <?= ($_POST['type'] ?? '') === 'villa' ? 'selected' : '' ?>>Villa</option>
                  <option value="land" <?= ($_POST['type'] ?? '') === 'land' ? 'selected' : '' ?>>Terrain</option>
                  <option value="shop" <?= ($_POST['type'] ?? '') === 'shop' ? 'selected' : '' ?>>Boutique / Local</option>
                  <option value="rental" <?= ($_POST['type'] ?? '') === 'rental' ? 'selected' : '' ?>>Location</option>
                  <option value="warehouse" <?= ($_POST['type'] ?? '') === 'warehouse' ? 'selected' : '' ?>>Entrepôt</option>
                </select>
              </div>
              <div class="form-group">
                <label class="form-label">Transaction</label>
                <select name="transaction" class="form-select">
                  <option value="sale" <?= ($_POST['transaction'] ?? 'sale') === 'sale' ? 'selected' : '' ?>>Vente</option>
                  <option value="rent" <?= ($_POST['transaction'] ?? '') === 'rent' ? 'selected' : '' ?>>Location</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="form-label">Description</label>
              <textarea name="description" class="form-control" rows="6" placeholder="Description détaillée du bien..."><?= e($_POST['description'] ?? '') ?></textarea>
            </div>
          </div>

          <div class="admin-form-card" style="margin-bottom:20px;">
            <div class="form-section-title">Localisation</div>
            <div class="form-group">
              <label class="form-label">Adresse / Quartier <span class="req">*</span></label>
              <input type="text" name="location" class="form-control" placeholder="Bastos, Quartier résidentiel..." required value="<?= e($_POST['location'] ?? '') ?>">
            </div>
            <div class="form-row">
              <div class="form-group">
                <label class="form-label">Ville</label>
                <input type="text" name="city" class="form-control" value="<?= e($_POST['city'] ?? 'Yaoundé') ?>">
              </div>
              <div class="form-group">
                <label class="form-label">Pays</label>
                <input type="text" name="country" class="form-control" value="<?= e($_POST['country'] ?? 'Cameroun') ?>">
              </div>
            </div>
          </div>

          <div class="admin-form-card">
            <div class="form-section-title">Caractéristiques</div>
            <div class="form-row">
              <div class="form-group">
                <label class="form-label">Surface (m²)</label>
                <input type="number" name="surface_area" class="form-control" min="0" step="0.01" placeholder="150" value="<?= e($_POST['surface_area'] ?? '') ?>">
              </div>
              <div class="form-group">
                <label class="form-label">Chambres</label>
                <input type="number" name="bedrooms" class="form-control" min="0" value="<?= e($_POST['bedrooms'] ?? '') ?>">
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label class="form-label">Salles de bain</label>
                <input type="number" name="bathrooms" class="form-control" min="0" value="<?= e($_POST['bathrooms'] ?? '') ?>">
              </div>
              <div class="form-group">
                <label class="form-label">Équipements / Commodités</label>
                <input type="text" name="amenities" class="form-control" placeholder="Piscine, Parking, Gardien..." value="<?= e($_POST['amenities'] ?? '') ?>">
              </div>
            </div>
          </div>
        </div>

        <!-- Colonne latérale -->
        <div>
          <div class="admin-form-card" style="margin-bottom:20px;">
            <div class="form-section-title">Prix</div>
            <div class="form-group">
              <label class="form-label">Prix (vide = sur demande)</label>
              <input type="number" name="price" class="form-control" min="0" step="0.01" placeholder="25000000" value="<?= e($_POST['price'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label class="form-label">Devise</label>
              <select name="currency" class="form-select">
                <option value="XAF" <?= ($_POST['currency'] ?? 'XAF') === 'XAF' ? 'selected' : '' ?>>FCFA (XAF)</option>
                <option value="EUR" <?= ($_POST['currency'] ?? '') === 'EUR' ? 'selected' : '' ?>>Euro (EUR)</option>
                <option value="USD" <?= ($_POST['currency'] ?? '') === 'USD' ? 'selected' : '' ?>>Dollar USD</option>
              </select>
            </div>
          </div>

          <div class="admin-form-card" style="margin-bottom:20px;">
            <div class="form-section-title">Image Principale</div>
            <label class="upload-zone" for="imgInput">
              <i class="fas fa-cloud-upload-alt"></i>
              <p>Cliquez pour uploader<br><span style="font-size:.75rem;color:rgba(255,255,255,.25);">JPG, PNG, WebP · Max 5MB</span></p>
              <input type="file" name="main_image" id="imgInput" accept="image/jpeg,image/png,image/webp,image/gif" onchange="previewImg(this)">
            </label>
            <div class="img-preview-grid" id="imgPreview"></div>
          </div>

          <div class="admin-form-card" style="margin-bottom:20px;">
            <div class="form-section-title">Options</div>
            <div class="form-check">
              <input type="checkbox" name="is_available" id="isAvailable" class="form-check-input" <?= !isset($_POST['is_available']) || $_POST['is_available'] ? 'checked' : '' ?>>
              <label for="isAvailable" class="form-check-label">Bien disponible</label>
            </div>
            <div class="form-check">
              <input type="checkbox" name="is_featured" id="isFeatured" class="form-check-input" <?= isset($_POST['is_featured']) ? 'checked' : '' ?>>
              <label for="isFeatured" class="form-check-label">Bien vedette (affiché en premier)</label>
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
