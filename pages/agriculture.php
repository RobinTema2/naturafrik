<?php
require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/functions.php';

$page_title = 'Agriculture & Élevage';
$meta_description = 'NATURAFRIK – Volaille, porcins, bovins, caprins, céréales et tubercules. Élevage naturel et agriculture durable au Cameroun.';
$csrf = csrf_token();

require_once dirname(__DIR__) . '/includes/header.php';
?>

<!-- Page Hero -->
<section class="page-hero">
  <img src="/naturafrik/images/Agriculture-image%20de%20page.jpg" alt="Agriculture camerounaise" class="page-hero-bg">
  <div class="page-hero-content">
    <div class="container">
      <div class="section-eyebrow" style="margin-bottom:1rem;" data-reveal>
        <span class="section-num" style="color:rgba(90,168,40,0.8);">AGRICULTURE</span>
        <div class="section-line" style="background:linear-gradient(90deg,rgba(90,168,40,0.5),transparent)"></div>
        <span class="t-label" style="color:#90C040;">Élevage & Cultures</span>
      </div>
      <h1 class="t-title" data-reveal data-delay="1">Élevage naturel &<br>cultures durables</h1>
    </div>
  </div>
</section>

<!-- ── Category showcase ── -->
<section style="padding:4rem 0 2rem;background:var(--ink);">
  <div class="container">
    <div class="section-eyebrow" style="margin-bottom:1.25rem;" data-reveal>
      <span class="section-num">01</span>
      <div class="section-line"></div>
      <span class="t-label">Nos filières</span>
    </div>
    <div class="agri-cats-grid" data-reveal data-delay="1">
      <?php
      $cats = [
        ['icon'=>'fa-dove',     'label'=>'Volaille',    'sub'=>'Poulets · Pintades · Canards', 'filter'=>'volaille', 'color'=>'#C8A030'],
        ['icon'=>'fa-bacon','label'=>'Porcins',    'sub'=>'Porcs · Truies · Porcelets',   'filter'=>'porc',    'color'=>'#E08060'],
        ['icon'=>'fa-cow',      'label'=>'Bovins',      'sub'=>'Vaches · Bœufs · Veaux',       'filter'=>'bovin',   'color'=>'#C09060'],
        ['icon'=>'fa-horse',    'label'=>'Caprins',     'sub'=>'Chèvres · Boucs · Chevreaux',  'filter'=>'caprin',  'color'=>'#A0C060'],
        ['icon'=>'fa-seedling','label'=>'Céréales',    'sub'=>'Maïs · Sorgho · Riz',          'filter'=>'cereale', 'color'=>'#D4B830'],
        ['icon'=>'fa-carrot',   'label'=>'Tubercules',  'sub'=>'Manioc · Igname · Patate',     'filter'=>'tubercule','color'=>'#B88030'],
      ];
      foreach ($cats as $i => $c): ?>
      <div class="agri-cat-card" data-filter-target="<?= $c['filter'] ?>">
        <div class="agri-cat-icon" style="color:<?= $c['color'] ?>;">
          <i class="fas <?= $c['icon'] ?>"></i>
        </div>
        <div class="agri-cat-label"><?= $c['label'] ?></div>
        <div class="agri-cat-sub"><?= $c['sub'] ?></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ── Photo gallery band ── -->
<div class="agri-gallery-band">
  <div class="agri-gallery-item"><img src="/naturafrik/images/Poule%20dans%20une%20ferme.jpg" alt="Volaille fermière"></div>
  <div class="agri-gallery-item"><img src="/naturafrik/images/Agriculture%202.jpg" alt="Agriculture"></div>
  <div class="agri-gallery-item"><img src="/naturafrik/images/Banane.jpg" alt="Bananes"></div>
  <div class="agri-gallery-item"><img src="/naturafrik/images/Macabo.jpg" alt="Macabo"></div>
  <div class="agri-gallery-item"><img src="/naturafrik/images/Mais%20dans%20une%20plantation.jpg" alt="Maïs"></div>
</div>

<!-- ── Products ── -->
<section style="padding:5rem 0 8rem;background:var(--ink-soft);">
  <div class="container">

    <!-- Filter bar -->
    <div class="filter-bar" data-reveal style="margin-bottom:2rem;">
      <button class="filter-btn active" data-filter="all">Tous les produits</button>
      <button class="filter-btn" data-filter="volaille">Volaille</button>
      <button class="filter-btn" data-filter="porc">Porcins</button>
      <button class="filter-btn" data-filter="bovin">Bovins</button>
      <button class="filter-btn" data-filter="caprin">Caprins</button>
      <button class="filter-btn" data-filter="cereale">Céréales</button>
      <button class="filter-btn" data-filter="tubercule">Tubercules</button>
    </div>

    <!-- Products grid -->
    <div class="products-grid">
      <?php
      $agriProducts = [
        ['cat'=>'volaille','icon'=>'fa-dove',      'name'=>'Poulet de chair',    'desc'=>'Poulets élevés en plein air, 2–2.5 kg. Livraison vivant ou abattu.','price'=>'5 000 FCFA/pièce','img'=>'Poule%20dans%20une%20ferme.jpg'],
        ['cat'=>'volaille','icon'=>'fa-feather',   'name'=>'Canard',             'desc'=>'Canards de Barbarie, élevage naturel. Chair ferme et savoureuse.','price'=>'8 000 FCFA/pièce','img'=>'canard.png'],
        ['cat'=>'volaille','icon'=>'fa-feather-alt','name'=>'Pintade',           'desc'=>'Pintades sauvages semi-domestiquées. Goût inimitable, chair maigre.','price'=>'7 000 FCFA/pièce','img'=>'pintade.png'],
        ['cat'=>'porc',   'icon'=>'fa-bacon', 'name'=>'Porc sur pied',      'desc'=>'Porcs de race locale et améliorée, 80–200 kg. Alimentation naturelle.','price'=>'180 000 FCFA','img'=>'Porc%20100-200kg.jpg'],
        ['cat'=>'porc',   'icon'=>'fa-bacon', 'name'=>'Porcelet sevré',     'desc'=>'Porcelets sevrés 8 semaines, prêts pour l\'engraissement.','price'=>'25 000 FCFA/pièce','img'=>'porcelet%20sevre.png'],
        ['cat'=>'bovin',  'icon'=>'fa-cow',        'name'=>'Bœuf local',         'desc'=>'Bovins de race locale, 200–300 kg. Viande tendre et goûteuse.','price'=>'450 000 FCFA','img'=>'Boeuf.jpg'],
        ['cat'=>'caprin', 'icon'=>'fa-horse',      'name'=>'Chèvre',             'desc'=>'Chèvres naines de Guinée et chèvres du Sahel. Lait et viande.','price'=>'45 000 FCFA/pièce','img'=>'Chevre.jpg'],
        ['cat'=>'cereale','icon'=>'fa-seedling',   'name'=>'Maïs frais',         'desc'=>'Épis de maïs frais de saison, plantation contrôlée. Min. 50 kg.','price'=>'200 FCFA/kg','img'=>'Mais%20dans%20une%20plantation.jpg'],
        ['cat'=>'cereale','icon'=>'fa-seedling',  'name'=>'Maïs séché',         'desc'=>'Maïs sec battu et propre, stockage 12 mois. Sacs de 50 kg.','price'=>'180 FCFA/kg','img'=>'mais%20sec.png'],
        ['cat'=>'tubercule','icon'=>'fa-leaf',     'name'=>'Manioc frais',       'desc'=>'Tubercules de manioc frais, épluchés ou avec peau. Livraison rapide.','price'=>'150 FCFA/kg','img'=>'Manioc%20frais.jpg'],
        ['cat'=>'tubercule','icon'=>'fa-carrot',   'name'=>'Patate douce',       'desc'=>'Patates douces oranges et blanches, sucrées et nutritives.','price'=>'250 FCFA/kg','img'=>'Patate%20douce.jpg'],
        ['cat'=>'tubercule','icon'=>'fa-circle',   'name'=>'Igname',             'desc'=>'Ignames de première qualité, variétés locales sélectionnées.','price'=>'300 FCFA/kg','img'=>'igname.png'],
        ['cat'=>'cereale','icon'=>'fa-apple-alt',  'name'=>'Banane plantain',    'desc'=>'Bananes plantain mûres, idéales pour la cuisson. Régimes entiers ou en détail.','price'=>'600 FCFA/régime','img'=>'Banane.jpg'],
        ['cat'=>'tubercule','icon'=>'fa-leaf',     'name'=>'Macabo',             'desc'=>'Tubercules de macabo frais, spécialité camerounaise très demandée.','price'=>'200 FCFA/kg','img'=>'Macabo.jpg'],
      ];
      foreach ($agriProducts as $i => $p): ?>
      <div class="product-card" data-category="<?= $p['cat'] ?>" data-reveal data-delay="<?= ($i % 4) + 1 ?>">
        <div class="product-card-img">
          <img src="/naturafrik/images/<?= $p['img'] ?>" alt="<?= $p['name'] ?>">
        </div>
        <div class="product-card-body">
          <span class="product-badge"><i class="fas <?= $p['icon'] ?>" style="font-size:0.6rem;"></i> Naturel</span>
          <h3 class="product-name"><?= $p['name'] ?></h3>
          <p class="product-desc"><?= $p['desc'] ?></p>
          <div class="product-meta">
            <div class="product-price" style="font-size:1.1rem;"><?= $p['price'] ?></div>
            <a href="https://wa.me/237691268428?text=<?= urlencode('Commande Agriculture: ' . $p['name']) ?>" target="_blank" class="btn-product">
              Commander <i class="fas fa-arrow-right"></i>
            </a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Bulk order CTA -->
    <div class="agri-bulk-banner" data-reveal>
      <div class="agri-bulk-icon"><i class="fas fa-boxes-stacked"></i></div>
      <div>
        <div class="t-label" style="color:rgba(255,255,255,0.5);margin-bottom:0.4rem;">Commande en gros</div>
        <h3 style="font-family:var(--serif);font-size:1.8rem;color:white;margin-bottom:0.4rem;">Prix de gros disponibles</h3>
        <p style="font-size:0.88rem;color:rgba(255,255,255,0.6);">Pour les commandes de plus de 100 kg ou 10 pièces, contactez-nous pour des tarifs préférentiels.</p>
      </div>
      <a href="https://wa.me/237691268428?text=Bonjour%2C%20je%20souhaite%20un%20devis%20pour%20une%20commande%20en%20gros." target="_blank" class="btn-primary" style="white-space:nowrap;flex-shrink:0;">
        <i class="fab fa-whatsapp"></i> <span>Devis gros</span>
      </a>
    </div>

  </div>
</section>

<style>
.agri-cats-grid {
  display: grid; grid-template-columns: repeat(6, 1fr); gap: 1rem;
}
.agri-cat-card {
  background: var(--card); border: 1px solid var(--border); border-radius: var(--r-md);
  padding: 1.5rem 1rem; text-align: center;
  transition: border-color 0.25s, transform 0.25s;
  cursor: default;
}
.agri-cat-card:hover { border-color: rgba(150,112,8,0.3); transform: translateY(-3px); }
.agri-cat-icon { font-size: 1.8rem; margin-bottom: 0.5rem; }
.agri-cat-label { font-size: 0.9rem; font-weight: 600; color: var(--cream); margin-bottom: 0.2rem; }
.agri-cat-sub { font-size: 0.68rem; color: var(--text-dim); line-height: 1.4; }
@media (max-width: 900px) { .agri-cats-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 500px) { .agri-cats-grid { grid-template-columns: repeat(2, 1fr); } }

.agri-gallery-band {
  display: flex; height: 220px; overflow: hidden;
}
.agri-gallery-item {
  flex: 1; overflow: hidden; position: relative;
  transition: flex 0.5s var(--ease);
}
.agri-gallery-item:hover { flex: 2.2; }
.agri-gallery-item img {
  width: 100%; height: 100%; object-fit: cover;
  filter: brightness(0.65) saturate(0.9);
  transition: filter 0.4s;
}
.agri-gallery-item:hover img { filter: brightness(0.85) saturate(1.1); }
@media (max-width: 600px) {
  .agri-gallery-band { height: 160px; }
  .agri-gallery-item:nth-child(4),
  .agri-gallery-item:nth-child(5) { display: none; }
}

.agri-bulk-banner {
  margin-top: 4rem;
  background: linear-gradient(135deg, var(--jade), #0D3A20);
  border-radius: var(--r-lg); padding: 2.5rem 3rem;
  display: grid; grid-template-columns: auto 1fr auto;
  gap: 2rem; align-items: center;
}
.agri-bulk-icon {
  width: 56px; height: 56px; flex-shrink: 0;
  background: rgba(255,255,255,0.12); border-radius: var(--r-md);
  display: flex; align-items: center; justify-content: center;
  font-size: 1.5rem; color: var(--gold-light);
}
@media (max-width: 768px) {
  .agri-bulk-banner { grid-template-columns: 1fr; gap: 1.25rem; }
  .agri-bulk-icon { display: none; }
}
</style>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
