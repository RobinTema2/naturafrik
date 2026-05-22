<?php
require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_admin_login();

$message = '';
$type    = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify()) {
    try {
        $pdo = db();
        if (!$pdo) throw new Exception('Connexion BDD impossible.');

        // Fix NATCAFÉ — set correct description (was showing NATCACAO text)
        $natcafe_desc = "NATCAFÉ est notre café d'altitude Arabica pur, cultivé à 1200–1600 m dans les Hauts Plateaux du Cameroun (Région Ouest). Torréfaction légère artisanale pour révéler toutes les notes florales, caramel et fruits rouges. Sac de 250 g.";
        $natcafe_short = "Café Arabica d'altitude, notes de caramel et fruits rouges, torréfaction légère artisanale. Sac de 250 g.";

        $stmt = $pdo->prepare("UPDATE products SET description=?, short_description=? WHERE LOWER(name) LIKE '%natcaf%' OR LOWER(name) LIKE '%natcafe%' OR LOWER(name) LIKE '%natcafé%'");
        $stmt->execute([$natcafe_desc, $natcafe_short]);
        $affected = $stmt->rowCount();

        // Also fix NATCACAO just in case it was overwritten
        $cacao_desc  = "NATCACAO est notre produit phare : cacao pur Trinitario & Forastero de la Région Ouest du Cameroun, cultivé à 1000–1500 m d'altitude. Torréfaction artisanale légère, notes de chocolat, floral et fruits secs. Paquet de 500 g.";
        $cacao_short = "Cacao pur Trinitario & Forastero, Région Ouest, altitude 1000–1500 m, torréfaction artisanale. 500 g.";

        $stmt2 = $pdo->prepare("UPDATE products SET description=?, short_description=? WHERE LOWER(name) LIKE '%natcacao%'");
        $stmt2->execute([$cacao_desc, $cacao_short]);
        $affected2 = $stmt2->rowCount();

        admin_log('fix_descriptions', "Descriptions corrigées — NATCAFÉ: {$affected} ligne(s), NATCACAO: {$affected2} ligne(s)");

        $message = "Correction appliquée : NATCAFÉ ({$affected} produit), NATCACAO ({$affected2} produit). Vous pouvez supprimer ce fichier.";
        $type    = 'success';
    } catch (Exception $e) {
        $message = 'Erreur : ' . $e->getMessage();
        $type    = 'error';
    }
}

// Read current products for review
$products = [];
try {
    $pdo = db();
    if ($pdo) {
        $products = $pdo->query("SELECT id, name, LEFT(short_description,120) AS short_desc FROM products ORDER BY name")->fetchAll();
    }
} catch (Exception $e) {}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Correction descriptions – NATURAFRIK</title>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="<?= SITE_URL ?>/admin/css/admin.css">
</head>
<body class="admin-body" style="display:block;padding:2rem;max-width:860px;margin:0 auto;">

  <h1 style="font-family:'Cormorant Garamond',serif;margin-bottom:1rem;">Correction des descriptions produits</h1>
  <p style="color:var(--text-2);margin-bottom:1.5rem;">Ce script corrige les descriptions NATCAFÉ et NATCACAO dans la base de données.</p>

  <?php if ($message): ?>
  <div class="alert alert-<?= $type ?>" style="margin-bottom:1.5rem;">
    <i class="fas fa-<?= $type === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
    <?= e($message) ?>
  </div>
  <?php endif; ?>

  <div class="admin-section" style="margin-bottom:1.5rem;">
    <div class="section-heading">Produits actuels (descriptions)</div>
    <?php if ($products): ?>
    <div class="admin-table-wrap">
      <table class="admin-table">
        <thead><tr><th>ID</th><th>Nom</th><th>Description courte (120 cars)</th></tr></thead>
        <tbody>
          <?php foreach ($products as $p): ?>
          <tr><td><?= $p['id'] ?></td><td><?= e($p['name']) ?></td><td style="font-size:.8rem;"><?= e($p['short_desc'] ?? '—') ?></td></tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php else: ?>
    <p style="color:var(--text-3);">Aucun produit trouvé.</p>
    <?php endif; ?>
  </div>

  <form method="POST">
    <?= csrf_field() ?>
    <button type="submit" class="btn-admin btn-admin-primary">
      <i class="fas fa-magic"></i> Appliquer la correction
    </button>
    <a href="dashboard.php" class="btn-admin btn-admin-secondary" style="margin-left:.75rem;">
      <i class="fas fa-arrow-left"></i> Retour au dashboard
    </a>
  </form>

</body>
</html>
