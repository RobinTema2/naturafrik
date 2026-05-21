<?php
require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/functions.php';

$page_title = 'Immobilier de Prestige';
$meta_description = 'NATURAFRIK Immobilier – maisons de luxe, terrains, appartements, locaux commerciaux et entrepôts à Yaoundé et au Cameroun. NATURCAM Sarl.';
$csrf = csrf_token();

$properties = [];
try {
    $stmt = db()->query("SELECT * FROM real_estate WHERE is_active=1 ORDER BY is_featured DESC, created_at DESC LIMIT 12");
    $properties = $stmt->fetchAll();
} catch (Exception $e) {}

require_once dirname(__DIR__) . '/includes/header.php';
?>

<!-- Page Hero -->
<section class="page-hero" style="height:55vh;min-height:480px;">
  <img src="/naturafrik/images/maison%20luxueuse%20plan%20de%20fond%20de%20la%20page.jpg" alt="Immobilier de prestige Cameroun" class="page-hero-bg" style="filter:brightness(0.28);">
  <div class="page-hero-content">
    <div class="container">
      <div class="section-eyebrow" style="margin-bottom:1rem;" data-reveal>
        <span class="section-num" style="color:rgba(200,146,10,0.7);">IMMOBILIER</span>
        <div class="section-line"></div>
        <span class="t-label">Maisons · Terrains · Locations</span>
      </div>
      <h1 class="t-title" data-reveal data-delay="1">
        Investissez dans<br>
        <em style="font-style:italic;color:var(--gold-pale);">l'immobilier camerounais</em>
      </h1>
      <p style="max-width:480px;font-size:0.95rem;color:rgba(255,255,255,0.6);line-height:1.7;margin-top:1rem;" data-reveal data-delay="2">
        Biens résidentiels et commerciaux à Yaoundé et partout au Cameroun. Accompagnement complet, titre foncier sécurisé.
      </p>
    </div>
  </div>
</section>

<!-- ── Type navigation ── -->
<section style="padding:3rem 0;background:var(--ink);border-bottom:1px solid var(--border);">
  <div class="container">
    <div class="immo-type-grid" data-reveal>
      <?php
      $types = [
        ['icon'=>'fa-house',       'label'=>'Maisons',    'sub'=>'Résidences clés en main',  'filter'=>'maison'],
        ['icon'=>'fa-map-location-dot','label'=>'Terrains','sub'=>'Parcelles viabilisées',    'filter'=>'terrain'],
        ['icon'=>'fa-building',    'label'=>'Locations',  'sub'=>'Appartements & villas',     'filter'=>'location'],
        ['icon'=>'fa-store',       'label'=>'Boutiques',  'sub'=>'Locaux commerciaux',        'filter'=>'boutique'],
        ['icon'=>'fa-warehouse',   'label'=>'Entrepôts',  'sub'=>'Espaces industriels',       'filter'=>'entrepot'],
      ];
      foreach ($types as $t): ?>
      <button class="immo-type-btn" data-filter="<?= $t['filter'] ?>">
        <div class="immo-type-icon"><i class="fas <?= $t['icon'] ?>"></i></div>
        <div class="immo-type-label"><?= $t['label'] ?></div>
        <div class="immo-type-sub"><?= $t['sub'] ?></div>
      </button>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ── Featured luxury property ── -->
<section style="padding:6rem 0;background:var(--ink);">
  <div class="container">
    <div class="section-eyebrow" style="margin-bottom:1rem;" data-reveal>
      <span class="section-num">01</span>
      <div class="section-line"></div>
      <span class="t-label">Bien à la une</span>
    </div>
    <div class="immo-featured-card" data-reveal data-delay="1">
      <div class="immo-featured-gallery">
        <div class="immo-featured-main">
          <img src="/naturafrik/images/maison%20luxueuse%203.jpg" alt="Maison de luxe">
          <span class="immo-badge-featured">
            <i class="fas fa-star"></i> Coup de cœur
          </span>
        </div>
        <div class="immo-featured-thumbs">
          <img src="/naturafrik/images/maison%20luxueuse%202.webp" alt="Vue 2">
          <img src="/naturafrik/images/duplex%20luxueuse%201.webp" alt="Vue 3">
          <img src="/naturafrik/images/maison%20et%20appartement%20luxueuse.jpg" alt="Vue 4">
        </div>
      </div>
      <div class="immo-featured-body">
        <span class="immo-badge" style="margin-bottom:1rem;">Vente · Prestige</span>
        <h2 style="font-family:var(--serif);font-size:2.2rem;color:var(--cream);line-height:1.2;margin-bottom:0.75rem;">
          Villa Luxueuse<br><span style="color:var(--gold-pale);font-style:italic;">Yaoundé, Cameroun</span>
        </h2>
        <p style="font-size:0.9rem;color:var(--text-muted);line-height:1.7;margin-bottom:2rem;">
          Exceptionnelle villa de standing, finitions haut de gamme, jardin paysagé, sécurité 24h. Titre foncier disponible. Parfaite pour résidence principale ou investissement.
        </p>
        <div class="immo-featured-specs">
          <div class="immo-spec"><i class="fas fa-bed"></i><span>5 chambres</span></div>
          <div class="immo-spec"><i class="fas fa-bath"></i><span>3 salles de bain</span></div>
          <div class="immo-spec"><i class="fas fa-ruler-combined"></i><span>400 m²</span></div>
          <div class="immo-spec"><i class="fas fa-car-garage"></i><span>Garage double</span></div>
          <div class="immo-spec"><i class="fas fa-tree"></i><span>Jardin 800 m²</span></div>
          <div class="immo-spec"><i class="fas fa-shield-halved"></i><span>Gardiennage</span></div>
        </div>
        <div style="display:flex;align-items:center;gap:2rem;flex-wrap:wrap;margin-top:2rem;">
          <div>
            <div style="font-size:0.65rem;letter-spacing:0.25em;color:var(--text-dim);margin-bottom:0.2rem;">PRIX DE VENTE</div>
            <div style="font-family:var(--serif);font-size:2.4rem;color:var(--gold-light);">120 000 000 <span style="font-size:1rem;color:var(--text-dim);">FCFA</span></div>
          </div>
          <a href="https://wa.me/237680209435?text=Bonjour%2C%20je%20suis%20int%C3%A9ress%C3%A9%20par%20la%20Villa%20Luxueuse%20NATURAFRIK." target="_blank" class="btn-primary">
            <i class="fab fa-whatsapp"></i> <span>Renseignements</span>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ── All properties ── -->
<section style="padding:2rem 0 8rem;background:var(--ink-soft);">
  <div class="container">
    <div class="section-eyebrow" style="margin-bottom:1rem;" data-reveal>
      <span class="section-num">02</span>
      <div class="section-line"></div>
      <span class="t-label">Toutes les annonces</span>
    </div>
    <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:1rem;flex-wrap:wrap;gap:1rem;">
      <h2 class="t-title" data-reveal data-delay="1">Nos biens disponibles</h2>
    </div>

    <!-- Filter bar -->
    <div class="filter-bar" data-reveal style="margin-bottom:2rem;">
      <button class="filter-btn active" data-filter="all">Toutes annonces</button>
      <button class="filter-btn" data-filter="maison">Maisons</button>
      <button class="filter-btn" data-filter="terrain">Terrains</button>
      <button class="filter-btn" data-filter="location">Locations</button>
      <button class="filter-btn" data-filter="boutique">Boutiques</button>
      <button class="filter-btn" data-filter="entrepot">Entrepôts</button>
    </div>

    <!-- Properties grid -->
    <div class="products-grid">
      <?php if (!empty($properties)): foreach ($properties as $i => $p): ?>
      <div class="product-card" data-category="<?= e($p['type'] ?? 'maison') ?>" data-reveal data-delay="<?= ($i % 4) + 1 ?>">
        <div class="product-card-img">
          <img src="<?= $p['image'] ? '/naturafrik/uploads/' . e($p['image']) : '/naturafrik/images/maison%20luxueuse.jpeg' ?>" alt="<?= e($p['title']) ?>">
        </div>
        <div class="product-card-body">
          <span class="product-badge"><?= ucfirst(e($p['type'])) ?></span>
          <h3 class="product-name"><?= e($p['title']) ?></h3>
          <p class="product-desc"><?= e(mb_strimwidth($p['description'] ?? '', 0, 90, '…')) ?></p>
          <div class="product-meta">
            <div class="product-price"><?= number_format($p['price']) ?> <small><?= e($p['currency'] ?? 'FCFA') ?></small></div>
            <a href="https://wa.me/237680209435?text=<?= urlencode('Immobilier: ' . $p['title']) ?>" target="_blank" class="btn-product">Infos <i class="fas fa-arrow-right"></i></a>
          </div>
        </div>
      </div>
      <?php endforeach; else: ?>

      <?php
      $props = [
        ['type'=>'maison', 'badge'=>'Prestige','name'=>'Maison Luxueuse F5','desc'=>'Villa avec jardin, 5 chambres, quartier résidentiel calme. Titre foncier.','price'=>'120 000 000','img'=>'maison%20luxueuse.jpeg'],
        ['type'=>'maison', 'badge'=>'Vente',   'name'=>'Villa Duplex – Bastos','desc'=>'Luxueuse villa duplex 5 chambres, quartier Bastos, vue dégagée.','price'=>'85 000 000','img'=>'duplex%20luxueuse.webp'],
        ['type'=>'maison', 'badge'=>'Vente',   'name'=>'Maison Duplex Moderne','desc'=>'Maison duplex avec terrasse, 4 chambres, cuisinière intégrée.','price'=>'65 000 000','img'=>'duplex%20luxueuse%201.webp'],
        ['type'=>'terrain','badge'=>'Terrain', 'name'=>'Terrain 500m² – Yaoundé','desc'=>'Terrain viabilisé avec titre foncier, accès route bitumée, zone résidentielle.','price'=>'12 000 000','img'=>'terrain%20500m2.png'],
        ['type'=>'terrain','badge'=>'Terrain', 'name'=>'Terrain Agricole 2ha','desc'=>'2 hectares de terre fertile avec source d\'eau, Région Centre.','price'=>'25 000 000','img'=>'terrain%20agricole%202ha.png'],
        ['type'=>'location','badge'=>'Location','name'=>'Appartement 3P meublé','desc'=>'Appartement meublé 3 pièces en centre-ville, toutes commodités.','price'=>'180 000 / mois','img'=>'Appartement%203p.png'],
        ['type'=>'location','badge'=>'Studio',  'name'=>'Studio meublé – Mvog-Ada','desc'=>'Studio moderne entièrement équipé, quartier calme.','price'=>'75 000 / mois','img'=>'studio%20meuble.png'],
        ['type'=>'maison', 'badge'=>'Vente',   'name'=>'Maison F3 – Ekounou','desc'=>'Maison 3 chambres, cuisine, salle de bain, cour clôturée.','price'=>'22 000 000','img'=>'maison%20f3.png'],
        ['type'=>'boutique','badge'=>'Commercial','name'=>'Local commercial – Marché','desc'=>'Boutique 45m² en bord de route à fort passage.','price'=>'150 000 / mois','img'=>'Local%20commercial.png'],
        ['type'=>'entrepot','badge'=>'Entrepôt','name'=>'Entrepôt 200m² – Zone Ind.','desc'=>'Grand entrepôt industriel, accès poids lourds, gardiennage.','price'=>'500 000 / mois','img'=>'entrepot%202000m2.png'],
        ['type'=>'maison', 'badge'=>'Vente',   'name'=>'Maison & Appartement de Luxe','desc'=>'Ensemble immobilier comprenant maison principale et appartement annexe.','price'=>'95 000 000','img'=>'maison%20et%20appartement%20luxueuse.jpg'],
        ['type'=>'maison', 'badge'=>'Vente',   'name'=>'Maison de Luxe – Vue Panoramique','desc'=>'Résidence d\'exception avec vue dégagée, sécurité intégrée, parking.','price'=>'75 000 000','img'=>'maison%20luxueuse%203.jpg'],
      ];
      foreach ($props as $i => $p): ?>
      <div class="product-card" data-category="<?= $p['type'] ?>" data-reveal data-delay="<?= ($i % 4) + 1 ?>">
        <div class="product-card-img">
          <img src="/naturafrik/images/<?= $p['img'] ?>" alt="<?= $p['name'] ?>">
        </div>
        <div class="product-card-body">
          <span class="product-badge"><?= $p['badge'] ?></span>
          <h3 class="product-name"><?= $p['name'] ?></h3>
          <p class="product-desc"><?= $p['desc'] ?></p>
          <div class="product-meta">
            <div class="product-price" style="font-size:1.05rem;"><?= $p['price'] ?> <small>FCFA</small></div>
            <a href="https://wa.me/237680209435?text=<?= urlencode('Immobilier: ' . $p['name']) ?>" target="_blank" class="btn-product">
              Infos <i class="fas fa-arrow-right"></i>
            </a>
          </div>
        </div>
      </div>
      <?php endforeach; endif; ?>
    </div>

    <!-- Vendor CTA -->
    <div style="text-align:center;margin-top:4rem;padding:3.5rem;background:var(--card);border:1px solid var(--border);border-radius:var(--r-lg);" data-reveal>
      <div style="width:56px;height:56px;background:rgba(150,112,8,0.12);border:1px solid rgba(150,112,8,0.25);border-radius:var(--r-md);display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;font-size:1.4rem;color:var(--gold);">
        <i class="fas fa-key"></i>
      </div>
      <h3 style="font-family:var(--serif);font-size:1.7rem;color:var(--cream);margin-bottom:0.6rem;">Vous avez un bien à vendre ou à louer ?</h3>
      <p class="t-body" style="margin-bottom:1.75rem;max-width:460px;margin-left:auto;margin-right:auto;">Confiez votre bien à NATURAFRIK. Estimation gratuite, mandat exclusif ou simple, diffusion large.</p>
      <a href="https://wa.me/237680209435?text=Bonjour%2C%20j%27ai%20un%20bien%20immobilier%20%C3%A0%20mettre%20en%20vente%2Flocation." target="_blank" class="btn-primary">
        <i class="fab fa-whatsapp"></i> <span>Confier mon bien</span>
      </a>
    </div>

  </div>
</section>

<style>
.immo-type-grid {
  display: grid; grid-template-columns: repeat(5, 1fr); gap: 1rem;
}
.immo-type-btn {
  background: var(--surface); border: 1px solid var(--border); border-radius: var(--r-md);
  padding: 1.5rem 1rem; text-align: center; cursor: pointer;
  transition: border-color 0.25s, background 0.25s, transform 0.25s;
}
.immo-type-btn:hover, .immo-type-btn.active {
  border-color: var(--gold); background: rgba(150,112,8,0.07); transform: translateY(-3px);
}
.immo-type-icon {
  font-size: 1.5rem; color: var(--gold); margin-bottom: 0.5rem;
}
.immo-type-label { font-size: 0.9rem; font-weight: 600; color: var(--cream); margin-bottom: 0.2rem; }
.immo-type-sub { font-size: 0.68rem; color: var(--text-dim); }
@media (max-width: 768px) {
  .immo-type-grid { grid-template-columns: repeat(3, 1fr); }
}
@media (max-width: 480px) {
  .immo-type-grid { grid-template-columns: repeat(2, 1fr); }
  .immo-type-btn:last-child { grid-column: span 2; }
}

.immo-featured-card {
  display: grid; grid-template-columns: 1.2fr 1fr; gap: 3rem;
  background: var(--card); border: 1px solid var(--border);
  border-radius: var(--r-lg); overflow: hidden;
}
.immo-featured-gallery { display: grid; grid-template-rows: 1fr auto; gap: 0; }
.immo-featured-main {
  position: relative; overflow: hidden; height: 360px;
}
.immo-featured-main img {
  width: 100%; height: 100%; object-fit: cover;
  transition: transform 0.6s;
}
.immo-featured-card:hover .immo-featured-main img { transform: scale(1.04); }
.immo-badge-featured {
  position: absolute; top: 1.25rem; left: 1.25rem;
  background: var(--gold-light); color: #FEFCF8;
  padding: 0.3rem 0.85rem; border-radius: var(--r-full);
  font-size: 0.65rem; font-weight: 700; letter-spacing: 0.15em;
  display: flex; align-items: center; gap: 0.4rem;
}
.immo-featured-thumbs {
  display: grid; grid-template-columns: repeat(3, 1fr); height: 110px;
}
.immo-featured-thumbs img {
  width: 100%; height: 100%; object-fit: cover;
  filter: brightness(0.7); transition: filter 0.3s;
}
.immo-featured-thumbs img:hover { filter: brightness(1); }
.immo-featured-body { padding: 2.5rem 2.5rem 2.5rem 0; display: flex; flex-direction: column; justify-content: center; }
.immo-featured-specs {
  display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0.75rem;
}
.immo-spec {
  display: flex; align-items: center; gap: 0.5rem;
  font-size: 0.8rem; color: var(--text-muted);
}
.immo-spec i { color: var(--gold); font-size: 0.75rem; width: 14px; }
@media (max-width: 900px) {
  .immo-featured-card { grid-template-columns: 1fr; }
  .immo-featured-body { padding: 2rem; }
  .immo-featured-main { height: 260px; }
}
@media (max-width: 600px) {
  .immo-featured-specs { grid-template-columns: 1fr 1fr; }
}
</style>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
