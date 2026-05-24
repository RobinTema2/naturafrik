<?php
require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/functions.php';

$page_title = 'Produits Naturels';
$meta_description = 'Découvrez les produits NATURAFRIK : NATCACAO cacao pur 500g, café d\'altitude Arabica, avocat frais — directement du Cameroun.';
$csrf = csrf_token();

$products = [];
try {
    $stmt = db()->query("SELECT p.*,c.name as cat_name FROM products p LEFT JOIN categories c ON p.category_id=c.id WHERE p.is_active=1 AND c.section='produits' ORDER BY p.is_featured DESC, p.created_at DESC");
    $products = $stmt->fetchAll();
} catch (Exception $e) {}

require_once dirname(__DIR__) . '/includes/header.php';
?>

<!-- Page Hero -->
<section class="page-hero">
  <img src="/naturafrik/images/Cacao%20seche.jpg" alt="Produits naturels" class="page-hero-bg">
  <div class="page-hero-content">
    <div class="container">
      <div class="section-eyebrow" style="margin-bottom:1rem;" data-reveal>
        <span class="section-num" style="color:rgba(200,146,10,0.7);">PRODUITS</span>
        <div class="section-line"></div>
        <span class="t-label">Naturels & Authentiques</span>
      </div>
      <h1 class="t-title" data-reveal data-delay="1">Produits naturels<br>du Cameroun</h1>
    </div>
  </div>
</section>

<section style="padding:5rem 0 8rem;">
  <div class="container">

    <!-- NATCACAO Spotlight -->
    <div class="natcacao-spotlight" data-reveal>
      <div>
        <img src="/naturafrik/images/Natcacao%20(2).png" alt="NATCACAO 500g" class="natcacao-img">
      </div>
      <div>
        <div class="t-label" style="margin-bottom:0.75rem;">Produit phare</div>
        <div class="natcacao-title">NATCACAO</div>
        <div class="natcacao-subtitle">Cacao Pur du Cameroun — 500g</div>
        <div class="natcacao-specs">
          <div class="natcacao-spec">
            <div class="natcacao-spec-key">RÉGION</div>
            <div class="natcacao-spec-val">Région Ouest</div>
          </div>
          <div class="natcacao-spec">
            <div class="natcacao-spec-key">ALTITUDE</div>
            <div class="natcacao-spec-val">1000–1500 m</div>
          </div>
          <div class="natcacao-spec">
            <div class="natcacao-spec-key">VARIÉTÉ</div>
            <div class="natcacao-spec-val">Trinitario & Forastero</div>
          </div>
          <div class="natcacao-spec">
            <div class="natcacao-spec-key">POIDS</div>
            <div class="natcacao-spec-val">500 grammes</div>
          </div>
          <div class="natcacao-spec">
            <div class="natcacao-spec-key">TORRÉFACTION</div>
            <div class="natcacao-spec-val">Légère artisanale</div>
          </div>
          <div class="natcacao-spec">
            <div class="natcacao-spec-key">NOTES</div>
            <div class="natcacao-spec-val">Chocolat · Floral · Fruits secs</div>
          </div>
        </div>
        <div style="display:flex;align-items:center;gap:1.5rem;flex-wrap:wrap;">
          <div style="font-family:var(--serif);font-size:2.5rem;color:var(--gold-light);">3 500 <span style="font-size:1rem;color:var(--text-dim);">FCFA</span></div>
          <a href="https://wa.me/237680209435?text=Bonjour%2C%20je%20souhaite%20commander%20NATCACAO%20500g" target="_blank" class="btn-primary">
            <i class="fab fa-whatsapp"></i> <span>Commander</span>
          </a>
        </div>
      </div>
    </div>

    <!-- Filter bar -->
    <div class="filter-bar" data-reveal>
      <button class="filter-btn active" data-filter="all">Tous les produits</button>
      <button class="filter-btn" data-filter="cacao">Cacao</button>
      <button class="filter-btn" data-filter="cafe">Café</button>
      <button class="filter-btn" data-filter="avocat">Avocat</button>
    </div>

    <!-- Products Grid -->
    <div class="products-grid">
      <?php if (!empty($products)): foreach ($products as $i => $p): ?>
      <div class="product-card" data-category="<?= e($p['slug'] ?? 'cacao') ?>" data-reveal data-delay="<?= ($i % 4) + 1 ?>">
        <div class="product-card-img">
          <img src="<?= $p['image'] ? '/naturafrik/uploads/' . e($p['image']) : '/naturafrik/images/Natcacao%20(2).png' ?>" alt="<?= e($p['name']) ?>" loading="lazy">
        </div>
        <div class="product-card-body">
          <?php if ($p['is_new']): ?><span class="product-badge">Nouveau</span><?php elseif ($p['is_featured']): ?><span class="product-badge">⭐ Vedette</span><?php endif; ?>
          <h3 class="product-name"><?= e($p['name']) ?></h3>
          <p class="product-desc"><?= e(mb_strimwidth($p['short_description'] ?? $p['description'] ?? '', 0, 100, '…')) ?></p>
          <div class="product-meta">
            <div class="product-price"><?= format_price($p['price'], $p['currency']) ?></div>
            <a href="https://wa.me/237680209435?text=<?= urlencode('Commande: ' . $p['name']) ?>" target="_blank" class="btn-product">
              Commander <i class="fas fa-arrow-right"></i>
            </a>
          </div>
        </div>
      </div>
      <?php endforeach; else: ?>

      <!-- Static fallback products -->
      <?php
      $staticProducts = [
        ['img'=>'Natcacao%20(2).png','badge'=>'⭐ Phare','name'=>'NATCACAO 500g','desc'=>'Cacao pur Région Ouest, altitude 1000–1500m. Trinitario & Forastero, torréfaction artisanale.','price'=>'3 500 FCFA','cat'=>'cacao'],
        ['img'=>'natcafe%20(2).png','badge'=>'Arabica','name'=>'Café Highland','desc'=>'Café d\'altitude, notes de fruits et caramel, torréfaction légère. Sac de 250g.','price'=>'2 800 FCFA','cat'=>'cafe'],
        ['img'=>'avocat.png','badge'=>'Bio','name'=>'Avocat Premium','desc'=>'Avocats frais cultivés sans pesticides. Commande minimum 5 kg.','price'=>'1 500 FCFA/kg','cat'=>'avocat'],
        ['img'=>'Cacao%20seche.jpg','badge'=>'En vrac','name'=>'Fèves de cacao brutes','desc'=>'Fèves de cacao non torréfiées, idéales pour la transformation artisanale.','price'=>'2 000 FCFA/kg','cat'=>'cacao'],
        ['img'=>'natcafe%20(2).png','badge'=>'Robusta','name'=>'Café Robusta','desc'=>'Café robusta camerounais, corsé et intense. Idéal pour l\'expresso.','price'=>'2 200 FCFA/250g','cat'=>'cafe'],
        ['img'=>'Natcacao%20(2).png','badge'=>'Lot','name'=>'Pack Découverte','desc'=>'Assortiment NATCACAO + café + avocat. Parfait comme cadeau ou pour découvrir nos saveurs.','price'=>'8 500 FCFA','cat'=>'cacao'],
      ];
      foreach ($staticProducts as $i => $p): ?>
      <div class="product-card" data-category="<?= $p['cat'] ?>" data-reveal data-delay="<?= ($i % 4) + 1 ?>">
        <div class="product-card-img">
          <img src="/naturafrik/images/<?= $p['img'] ?>" alt="<?= $p['name'] ?>">
        </div>
        <div class="product-card-body">
          <span class="product-badge"><?= $p['badge'] ?></span>
          <h3 class="product-name"><?= $p['name'] ?></h3>
          <p class="product-desc"><?= $p['desc'] ?></p>
          <div class="product-meta">
            <div class="product-price"><?= $p['price'] ?></div>
            <a href="https://wa.me/237680209435?text=<?= urlencode('Commande: ' . $p['name']) ?>" target="_blank" class="btn-product">
              Commander <i class="fas fa-arrow-right"></i>
            </a>
          </div>
        </div>
      </div>
      <?php endforeach; endif; ?>
    </div><!-- /products-grid -->

    <!-- CTA WhatsApp -->
    <div style="text-align:center;padding:3rem 0 1rem;" data-reveal>
      <p class="t-body" style="margin-bottom:1.5rem;">Vous ne trouvez pas ce que vous cherchez ? Contactez-nous directement.</p>
      <a href="https://wa.me/237680209435?text=Bonjour%20NATURAFRIK%2C%20j%27ai%20une%20demande%20sp%C3%A9cifique." target="_blank" class="btn-primary">
        <i class="fab fa-whatsapp"></i> <span>Demande personnalisée</span>
      </a>
    </div>

  </div>
</section>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
