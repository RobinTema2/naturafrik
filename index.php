<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$page_title = 'Accueil';
$meta_description = 'NATURAFRIK – Cacao pur, café, avocat, immobilier, agriculture et matières premières du Cameroun. NATURCAM Sarl, Groupe Nature Cameroun.';
$csrf = csrf_token();

$featured = [];
try {
    $db = db(); if (!$db) { $featured = []; } else { $stmt = $db->query("SELECT p.*, c.name as cat_name FROM products p LEFT JOIN categories c ON p.category_id=c.id WHERE p.is_active=1 ORDER BY p.is_featured DESC, p.created_at DESC LIMIT 5");
    $featured = $stmt->fetchAll(); }
} catch (Exception $e) {}

require_once __DIR__ . '/includes/header.php';
?>

<!-- ══════════════════════ HERO ══════════════════════ -->
<section id="hero">
  <div class="hero-left">
    <div class="hero-bg-gfx">NATURE</div>

    <div class="hero-eyebrow">
      <div class="hero-eyebrow-line"></div>
      <span class="hero-label">Groupe Nature Cameroun</span>
    </div>

    <h1 class="hero-heading" data-reveal>
      <em>La richesse</em>
      <strong>du Cameroun</strong>
      à votre portée
    </h1>

    <p class="hero-desc" data-reveal data-delay="2">
      Cacao fin, café d'altitude, immobilier de prestige, agriculture durable et matières premières — directement depuis les terres fertiles du Cameroun.
    </p>

    <div class="hero-actions" data-reveal data-delay="3">
      <a href="/naturafrik/pages/produits.php" class="btn-primary">
        <span>Découvrir les produits</span>
        <i class="fas fa-arrow-right"></i>
      </a>
      <a href="/naturafrik/pages/contact.php" class="btn-outline">
        Nous contacter
      </a>
    </div>

    <div class="hero-scroll">
      <div class="hero-scroll-line"></div>
      DÉFILER
    </div>
  </div>

  <div class="hero-right">
    <img src="/naturafrik/images/image%20daccueil.png" alt="NATCACAO – Cacao pur du Cameroun" class="hero-img">
  </div>

  <!-- Stats bar -->
  <div class="hero-stats">
    <div class="hero-stat">
      <div class="hero-stat-num" data-count="500" data-suffix="+">0</div>
      <div class="hero-stat-label">Clients satisfaits</div>
    </div>
    <div class="hero-stat">
      <div class="hero-stat-num" data-count="12">0</div>
      <div class="hero-stat-label">Régions couvertes</div>
    </div>
    <div class="hero-stat">
      <div class="hero-stat-num" data-count="2023">0</div>
      <div class="hero-stat-label">Fondée en</div>
    </div>
  </div>
</section>

<!-- ══════════════════════ MARQUEE ══════════════════════ -->
<div class="marquee-band">
  <div class="marquee-track">
    <?php
    $items = ['NATCACAO', 'NATCAFÉ', 'AVOCAT FRAIS', 'IMMOBILIER', 'VOLAILLE', 'OR FIN', 'CUIVRE', 'AGRICULTURE', 'CAOUTCHOUC', 'CAMEROUN'];
    $all = array_merge($items, $items);
    foreach ($all as $item): ?>
    <span class="marquee-item">
      <?= htmlspecialchars($item) ?>
      <span class="marquee-dot"></span>
    </span>
    <?php endforeach; ?>
  </div>
</div>

<!-- ══════════════════════ CATEGORIES ══════════════════════ -->
<section class="categories-section">
  <div class="container">
    <div class="categories-header">
      <div>
        <div class="section-eyebrow" data-reveal>
          <span class="section-num">01</span>
          <div class="section-line"></div>
          <span class="t-label">Nos activités</span>
        </div>
        <h2 class="t-title" data-reveal data-delay="1">
          Quatre univers,<br>une seule excellence
        </h2>
      </div>
      <a href="/naturafrik/pages/produits.php" class="btn-outline" data-reveal data-delay="2">
        Tout voir <i class="fas fa-arrow-right"></i>
      </a>
    </div>

    <div class="categories-grid">

      <!-- 1. Produits naturels (featured – tall) -->
      <div class="cat-card" data-reveal>
        <img src="/naturafrik/images/Natcacao%20(2).png" alt="Produits naturels" class="cat-card-img" loading="lazy">
        <div class="cat-card-body">
          <span class="cat-tag">Exclusif</span>
          <h3 class="cat-title">Produits naturels</h3>
          <p class="cat-desc">NATCACAO, café d'altitude, avocat frais — la pureté des saveurs camerounaises en sachet.</p>
          <a href="/naturafrik/pages/produits.php" class="cat-link">
            Explorer
            <span class="cat-link-arrow"><i class="fas fa-arrow-right"></i></span>
          </a>
        </div>
      </div>

      <!-- 2. Immobilier -->
      <div class="cat-card" data-reveal data-delay="1">
        <img src="/naturafrik/images/maison%20luxueuse.jpeg" alt="Immobilier de prestige" class="cat-card-img" loading="lazy">
        <div class="cat-card-body">
          <span class="cat-tag">Prestige</span>
          <h3 class="cat-title">Immobilier</h3>
          <p class="cat-desc">Maisons, terrains, locations et boutiques à Yaoundé et dans tout le Cameroun.</p>
          <a href="/naturafrik/pages/immobilier.php" class="cat-link">
            Explorer
            <span class="cat-link-arrow"><i class="fas fa-arrow-right"></i></span>
          </a>
        </div>
      </div>

      <!-- 3. Agriculture -->
      <div class="cat-card" data-reveal data-delay="2">
        <img src="/naturafrik/images/Agriculture-image%20de%20page.jpg" alt="Agriculture & Élevage" class="cat-card-img" loading="lazy">
        <div class="cat-card-body">
          <span class="cat-tag">Frais</span>
          <h3 class="cat-title">Agriculture</h3>
          <p class="cat-desc">Élevage naturel, volaille, porcins, céréales et tubercules bio du Cameroun.</p>
          <a href="/naturafrik/pages/agriculture.php" class="cat-link">
            Explorer
            <span class="cat-link-arrow"><i class="fas fa-arrow-right"></i></span>
          </a>
        </div>
      </div>

      <!-- 4. Matières premières -->
      <div class="cat-card" data-reveal data-delay="3">
        <img src="/naturafrik/images/Or.jpg" alt="Matières premières" class="cat-card-img" loading="lazy">
        <div class="cat-card-body">
          <span class="cat-tag">Premium</span>
          <h3 class="cat-title">Matières 1ères</h3>
          <p class="cat-desc">Or fin, cuivre, fer, caoutchouc — ressources naturelles du sous-sol camerounais.</p>
          <a href="/naturafrik/pages/matieres-premieres.php" class="cat-link">
            Explorer
            <span class="cat-link-arrow"><i class="fas fa-arrow-right"></i></span>
          </a>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ══════════════════════ FEATURED PRODUCTS ══════════════════════ -->
<section class="featured-section">
  <div class="container">
    <div class="featured-header">
      <div class="section-eyebrow" data-reveal>
        <span class="section-num">02</span>
        <div class="section-line"></div>
        <span class="t-label">Sélection du moment</span>
      </div>
      <h2 class="t-title" data-reveal data-delay="1">Produits phares</h2>
      <p class="t-body" data-reveal data-delay="2" style="max-width:520px;margin-top:0.75rem;">
        Notre sélection de produits d'exception, cultivés et traités selon les plus hauts standards de qualité.
      </p>
    </div>

    <div class="featured-grid">

      <!-- Featured: NATCACAO -->
      <div class="product-card featured" data-reveal>
        <div class="product-card-img">
          <img src="/naturafrik/images/Produit%20phare.png" alt="NATCACAO">
        </div>
        <div class="product-card-body">
          <span class="product-badge"><i class="fas fa-star" style="font-size:0.6rem;"></i> Produit phare</span>
          <h3 class="product-name">NATCACAO</h3>
          <p class="product-desc">Cacao pur du Cameroun, Région Ouest, altitude 1000–1500m. Variétés Trinitario & Forastero. Torréfaction artisanale, notes chocolatées intenses et florales.</p>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.5rem;margin-bottom:1.25rem;">
            <div style="background:rgba(150,112,8,0.08);border:1px solid rgba(150,112,8,0.15);border-radius:6px;padding:0.5rem 0.75rem;">
              <div style="font-size:0.58rem;letter-spacing:0.2em;color:var(--gold);">POIDS</div>
              <div style="font-size:0.88rem;color:var(--cream);">500 g</div>
            </div>
            <div style="background:rgba(150,112,8,0.08);border:1px solid rgba(150,112,8,0.15);border-radius:6px;padding:0.5rem 0.75rem;">
              <div style="font-size:0.58rem;letter-spacing:0.2em;color:var(--gold);">VARIÉTÉ</div>
              <div style="font-size:0.88rem;color:var(--cream);">Trinitario</div>
            </div>
            <div style="background:rgba(150,112,8,0.08);border:1px solid rgba(150,112,8,0.15);border-radius:6px;padding:0.5rem 0.75rem;">
              <div style="font-size:0.58rem;letter-spacing:0.2em;color:var(--gold);">ALTITUDE</div>
              <div style="font-size:0.88rem;color:var(--cream);">1000–1500m</div>
            </div>
            <div style="background:rgba(150,112,8,0.08);border:1px solid rgba(150,112,8,0.15);border-radius:6px;padding:0.5rem 0.75rem;">
              <div style="font-size:0.58rem;letter-spacing:0.2em;color:var(--gold);">RÉGION</div>
              <div style="font-size:0.88rem;color:var(--cream);">Ouest Cameroun</div>
            </div>
          </div>
          <div class="product-meta">
            <div class="product-price">
              <?php
              $p = !empty($featured) ? $featured[0] : null;
              echo $p ? format_price($p['price'], $p['currency']) : '3 500 <small>FCFA / 500g</small>';
              ?>
            </div>
            <a href="/naturafrik/pages/produits.php" class="btn-primary" style="font-size:0.78rem;padding:0.7rem 1.5rem;">
              <span>Commander</span> <i class="fas fa-arrow-right"></i>
            </a>
          </div>
        </div>
      </div>

      <!-- Café -->
      <div class="product-card" data-reveal data-delay="1">
        <div class="product-card-img">
          <img src="/naturafrik/images/Cafe%20seche.jpg" alt="Café de montagne">
        </div>
        <div class="product-card-body">
          <span class="product-badge">Highland Arabica</span>
          <h3 class="product-name">NATCAFÉ</h3>
          <p class="product-desc">Arabica d'altitude 1200–1600m, notes de fruits et caramel, torréfaction légère.</p>
          <div class="product-meta">
            <div class="product-price">2 800 <small>FCFA/250g</small></div>
            <a href="/naturafrik/pages/natcafe.php" class="btn-product">Découvrir <i class="fas fa-arrow-right"></i></a>
          </div>
        </div>
      </div>

      <!-- Avocat -->
      <div class="product-card" data-reveal data-delay="2">
        <div class="product-card-img">
          <img src="/naturafrik/images/avocat.png" alt="Avocat frais">
        </div>
        <div class="product-card-body">
          <span class="product-badge">Récolte fraîche</span>
          <h3 class="product-name">Avocat frais</h3>
          <p class="product-desc">Avocats locaux de qualité premium, cultivés sans pesticides.</p>
          <div class="product-meta">
            <div class="product-price">1 500 <small>FCFA/kg</small></div>
            <a href="/naturafrik/pages/produits.php" class="btn-product">Voir <i class="fas fa-arrow-right"></i></a>
          </div>
        </div>
      </div>

      <!-- Volaille -->
      <div class="product-card" data-reveal data-delay="3">
        <div class="product-card-img">
          <img src="/naturafrik/images/Poule%20dans%20une%20ferme.jpg" alt="Volaille fermière">
        </div>
        <div class="product-card-body">
          <span class="product-badge">Élevage naturel</span>
          <h3 class="product-name">Volaille fermière</h3>
          <p class="product-desc">Poulets, pintades et canards élevés en plein air, alimentation naturelle.</p>
          <div class="product-meta">
            <div class="product-price">5 000 <small>FCFA/pièce</small></div>
            <a href="/naturafrik/pages/agriculture.php" class="btn-product">Voir <i class="fas fa-arrow-right"></i></a>
          </div>
        </div>
      </div>

    </div><!-- /featured-grid -->
  </div>
</section>

<!-- ══════════════════════ NATCAFÉ TEASER ══════════════════════ -->
<section class="natcafe-teaser">
  <div class="natcafe-teaser-img-wrap">
    <img src="/naturafrik/images/Cafe%20seche.jpg" alt="NATCAFÉ – Café d'altitude camerounais" class="natcafe-teaser-bg">
  </div>
  <div class="container natcafe-teaser-inner">
    <div class="natcafe-teaser-content" data-reveal>
      <div class="section-eyebrow" style="margin-bottom:1.25rem;">
        <div class="hero-eyebrow-line"></div>
        <span class="t-label" style="color:var(--gold-pale);">Produit signature</span>
      </div>
      <div style="font-family:var(--display);font-size:clamp(3.5rem,8vw,7rem);letter-spacing:0.08em;color:var(--gold-pale);line-height:0.9;margin-bottom:1rem;">NATCAFÉ</div>
      <p style="font-family:var(--serif);font-size:1.25rem;font-style:italic;color:rgba(255,255,255,0.75);max-width:420px;line-height:1.6;margin-bottom:2rem;">
        Arabica d'altitude des terres fertiles de la Région Ouest — une tasse, un voyage.
      </p>
      <div style="display:flex;gap:0.75rem;flex-wrap:wrap;margin-bottom:2.5rem;">
        <span class="natcafe-pill"><i class="fas fa-mountain"></i> 1200–1600 m</span>
        <span class="natcafe-pill"><i class="fas fa-leaf"></i> Arabica pur</span>
        <span class="natcafe-pill"><i class="fas fa-fire"></i> Torréfaction légère</span>
      </div>
      <a href="/naturafrik/pages/natcafe.php" class="btn-primary">
        <span>Découvrir NATCAFÉ</span> <i class="fas fa-arrow-right"></i>
      </a>
    </div>
    <div class="natcafe-teaser-cup" data-reveal data-delay="2">
      <img src="/naturafrik/images/natcafe%20(2).png" alt="NATCAFÉ sachet">
    </div>
  </div>
</section>

<!-- ══════════════════════ STATS ══════════════════════ -->
<section class="stats-section">
  <div class="container">
    <div class="section-eyebrow" style="justify-content:center;margin-bottom:3rem;" data-reveal>
      <span class="section-num" style="color:rgba(255,255,255,0.5);">03</span>
      <div class="section-line" style="background:linear-gradient(90deg,rgba(255,255,255,0.2),transparent)"></div>
      <span class="t-label" style="color:rgba(255,255,255,0.6);">NATURAFRIK en chiffres</span>
    </div>
    <div class="stats-grid">
      <div class="stat-item" data-reveal>
        <div class="stat-num" data-count="500" data-suffix="+">0+</div>
        <div class="stat-label">Clients satisfaits</div>
      </div>
      <div class="stat-item" data-reveal data-delay="1">
        <div class="stat-num" data-count="4">0</div>
        <div class="stat-label">Secteurs d'activité</div>
      </div>
      <div class="stat-item" data-reveal data-delay="2">
        <div class="stat-num" data-count="1500" data-suffix="m">0m</div>
        <div class="stat-label">Altitude max. cultures</div>
      </div>
      <div class="stat-item" data-reveal data-delay="3">
        <div class="stat-num" data-count="100" data-suffix="%">0%</div>
        <div class="stat-label">Naturel & authentique</div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════════════════ ABOUT ══════════════════════ -->
<section class="about-section">
  <div class="container">
    <div class="about-grid">
      <div class="about-img-wrap" data-reveal="left">
        <img src="/naturafrik/images/Cacao%20seche.jpg" alt="Fèves de cacao NATURCAM" class="about-img-main" loading="lazy">
        <div class="about-img-badge">
          <div class="about-img-badge-num">2023</div>
          <div class="about-img-badge-text">Fondée à Yaoundé</div>
        </div>
      </div>
      <div class="about-content" data-reveal="right">
        <div class="section-eyebrow">
          <span class="section-num">04</span>
          <div class="section-line"></div>
          <span class="t-label">À propos</span>
        </div>
        <h2 class="t-title" style="margin-bottom:1rem;">
          NATURCAM Sarl —<br>
          <span style="font-style:italic;color:var(--gold-pale);">Nature & Performance</span>
        </h2>
        <p class="t-body">
          Fondée en 2023 à Yaoundé, NATURCAM Sarl (Groupe Nature Cameroun) s'est imposée comme un acteur incontournable dans la valorisation des ressources naturelles camerounaises. Notre philosophie : <em>Imagination · Performance · Qualité</em>.
        </p>
        <div class="about-values">
          <div class="about-value">
            <div class="about-value-icon"><i class="fas fa-seedling"></i></div>
            <div>
              <div class="about-value-title">Naturalité</div>
              <div class="about-value-text">Tous nos produits sont 100% naturels, sans additifs chimiques, respectant les écosystèmes locaux.</div>
            </div>
          </div>
          <div class="about-value">
            <div class="about-value-icon"><i class="fas fa-trophy"></i></div>
            <div>
              <div class="about-value-title">Excellence</div>
              <div class="about-value-text">Des standards de qualité stricts à chaque étape — de la récolte à la livraison.</div>
            </div>
          </div>
          <div class="about-value">
            <div class="about-value-icon"><i class="fas fa-handshake"></i></div>
            <div>
              <div class="about-value-title">Confiance</div>
              <div class="about-value-text">RCCM: RC/YAO/2023/B/519 · NIU: M032318076551C — Entreprise légalement enregistrée.</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════════════════ AGRICULTURE BENTO ══════════════════════ -->
<section class="bento-section" style="background:var(--ink-soft);">
  <div class="container">
    <div class="section-eyebrow" style="margin-bottom:1.25rem;" data-reveal>
      <span class="section-num">05</span>
      <div class="section-line"></div>
      <span class="t-label">Agriculture & Élevage</span>
    </div>
    <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:2.5rem;gap:2rem;flex-wrap:wrap;">
      <h2 class="t-title" data-reveal data-delay="1">Terroir camerounais</h2>
      <a href="/naturafrik/pages/agriculture.php" class="btn-outline" data-reveal data-delay="2">Tout voir <i class="fas fa-arrow-right"></i></a>
    </div>
    <div class="bento-grid">
      <div class="bento-card bento-lg" data-reveal>
        <img src="/naturafrik/images/Poule%20dans%20une%20ferme.jpg" alt="Volaille" class="bento-card-img" loading="lazy">
        <div class="bento-card-overlay">
          <div class="bento-icon-fa"><i class="fas fa-dove"></i></div>
          <div class="bento-label">Volaille fermière</div>
          <div class="bento-sub">Poulets · Pintades · Canards</div>
        </div>
      </div>
      <div class="bento-card bento-md" data-reveal data-delay="1">
        <img src="/naturafrik/images/Mais%20dans%20une%20plantation.jpg" alt="Céréales" class="bento-card-img" loading="lazy">
        <div class="bento-card-overlay">
          <div class="bento-icon-fa"><i class="fas fa-seedling"></i></div>
          <div class="bento-label">Céréales & Tubercules</div>
          <div class="bento-sub">Maïs · Manioc · Igname</div>
        </div>
      </div>
      <div class="bento-card bento-sm info-card" data-reveal data-delay="2">
        <div class="bento-info-body">
          <div style="font-family:var(--display);font-size:2.5rem;color:var(--gold-light);line-height:1;">100%</div>
          <div style="font-size:0.7rem;letter-spacing:0.2em;color:rgba(255,255,255,0.5);margin-top:0.5rem;">NATUREL</div>
          <div style="font-size:0.82rem;color:rgba(255,255,255,0.6);margin-top:0.75rem;line-height:1.5;">Élevage sans hormones ni antibiotiques</div>
        </div>
      </div>
      <div class="bento-card bento-tall" data-reveal data-delay="1">
        <img src="/naturafrik/images/Agriculture%202.jpg" alt="Agriculture" class="bento-card-img" loading="lazy">
        <div class="bento-card-overlay">
          <div class="bento-icon-fa"><i class="fas fa-leaf"></i></div>
          <div class="bento-label">Agriculture durable</div>
          <div class="bento-sub">Cultures bio certifiées</div>
        </div>
      </div>
      <div class="bento-card bento-wide gold-card" data-reveal data-delay="3">
        <div class="bento-info-body">
          <div class="t-label" style="margin-bottom:0.75rem;">Livraison directe</div>
          <h3 style="font-family:var(--serif);font-size:1.6rem;color:var(--cream);margin-bottom:0.75rem;">Du producteur à votre table</h3>
          <p style="font-size:0.85rem;color:var(--text-muted);margin-bottom:1.25rem;">Commandez directement nos produits frais avec livraison à Yaoundé et ses environs.</p>
          <a href="/naturafrik/pages/contact.php" class="btn-primary" style="font-size:0.78rem;padding:0.65rem 1.4rem;">Passer commande</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════════════════ IMMOBILIER PREVIEW ══════════════════════ -->
<section style="padding:8rem 0;background:var(--ink);">
  <div class="container">
    <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:3.5rem;gap:2rem;flex-wrap:wrap;">
      <div>
        <div class="section-eyebrow" data-reveal style="margin-bottom:1rem;">
          <span class="section-num">06</span>
          <div class="section-line"></div>
          <span class="t-label">Immobilier de prestige</span>
        </div>
        <h2 class="t-title" data-reveal data-delay="1">Biens d'exception<br><em style="font-style:italic;color:var(--gold-pale);">au Cameroun</em></h2>
      </div>
      <a href="/naturafrik/pages/immobilier.php" class="btn-outline" data-reveal data-delay="2">Toutes les annonces <i class="fas fa-arrow-right"></i></a>
    </div>
    <div class="immo-preview-grid">
      <div class="immo-preview-card immo-preview-featured" data-reveal>
        <img src="/naturafrik/images/maison%20luxueuse%20plan%20de%20fond%20de%20la%20page.jpg" alt="Villa de luxe" class="immo-preview-img" loading="lazy">
        <div class="immo-preview-overlay">
          <span class="immo-badge">Prestige</span>
          <h3 class="immo-preview-title">Maison de luxe<br>Yaoundé</h3>
          <div class="immo-preview-price">120 000 000 <small>FCFA</small></div>
          <a href="/naturafrik/pages/immobilier.php" class="btn-product" style="color:white;border-color:rgba(255,255,255,0.3);">
            Voir le bien <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>
      <div class="immo-preview-card" data-reveal data-delay="1">
        <img src="/naturafrik/images/duplex%20luxueuse.webp" alt="Duplex" class="immo-preview-img" loading="lazy">
        <div class="immo-preview-overlay">
          <span class="immo-badge">Vente</span>
          <h3 class="immo-preview-title">Villa Duplex</h3>
          <div class="immo-preview-price">85 000 000 <small>FCFA</small></div>
          <a href="/naturafrik/pages/immobilier.php" class="btn-product" style="color:white;border-color:rgba(255,255,255,0.3);">Voir <i class="fas fa-arrow-right"></i></a>
        </div>
      </div>
      <div class="immo-preview-card" data-reveal data-delay="2">
        <img src="/naturafrik/images/Appartement%203p.png" alt="Appartement" class="immo-preview-img" loading="lazy">
        <div class="immo-preview-overlay">
          <span class="immo-badge">Location</span>
          <h3 class="immo-preview-title">Appartement 3P</h3>
          <div class="immo-preview-price">180 000 <small>FCFA/mois</small></div>
          <a href="/naturafrik/pages/immobilier.php" class="btn-product" style="color:white;border-color:rgba(255,255,255,0.3);">Voir <i class="fas fa-arrow-right"></i></a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════════════════ MATIÈRES PREMIÈRES ══════════════════════ -->
<section class="materials-section">
  <div class="container">
    <div class="section-eyebrow" style="margin-bottom:1.25rem;" data-reveal>
      <span class="section-num">07</span>
      <div class="section-line"></div>
      <span class="t-label">Matières premières</span>
    </div>
    <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:1rem;gap:2rem;flex-wrap:wrap;">
      <h2 class="t-title" data-reveal data-delay="1">Les richesses du sous-sol</h2>
      <a href="/naturafrik/pages/matieres-premieres.php" class="btn-outline" data-reveal data-delay="2">Voir tout <i class="fas fa-arrow-right"></i></a>
    </div>
    <div class="materials-ticker" data-reveal data-delay="1">
      <div class="material-tick">
        <div class="material-icon-wrap" style="background:linear-gradient(135deg,#3A2A00,#1A1400);">
          <img src="/naturafrik/images/gold-bar.svg" alt="Or" style="width:28px;height:28px;filter:invert(1);">
        </div>
        <div class="material-tick-name">Or</div>
        <div class="material-tick-formula">Au · 999.9</div>
        <span class="badge-premium">PREMIUM</span>
      </div>
      <div class="material-tick">
        <div class="material-icon-wrap" style="background:linear-gradient(135deg,#3A1A08,#1E0E04);">
          <img src="/naturafrik/images/copper.svg" alt="Cuivre" style="width:28px;height:28px;filter:invert(1);">
        </div>
        <div class="material-tick-name">Cuivre</div>
        <div class="material-tick-formula">Cu · 99.9%</div>
        <span class="badge-standard">STANDARD</span>
      </div>
      <div class="material-tick">
        <div class="material-icon-wrap" style="background:linear-gradient(135deg,#1A1A1A,#0A0A0A);">
          <i class="fas fa-industry" style="color:rgba(255,255,255,0.6);font-size:1.3rem;"></i>
        </div>
        <div class="material-tick-name">Fer</div>
        <div class="material-tick-formula">Fe · Minerai</div>
        <span class="badge-standard">STANDARD</span>
      </div>
      <div class="material-tick">
        <div class="material-icon-wrap" style="background:linear-gradient(135deg,#0A1A0A,#050D05);">
          <i class="fas fa-tree" style="color:rgba(255,255,255,0.6);font-size:1.3rem;"></i>
        </div>
        <div class="material-tick-name">Caoutchouc</div>
        <div class="material-tick-formula">Hevea brasiliensis</div>
        <span class="badge-premium">PREMIUM</span>
      </div>
    </div>
    <div class="materials-detail-grid" data-reveal>
      <div class="material-detail-card">
        <img src="/naturafrik/images/Or.jpg" alt="Or fin" class="material-detail-img" loading="lazy">
        <div>
          <div class="t-label" style="margin-bottom:0.4rem;">Métal précieux</div>
          <div class="material-detail-name">Or fin</div>
          <p style="font-size:0.82rem;color:var(--text-muted);margin-bottom:0.75rem;">Or natif extrait au Cameroun, purifié à 999.9‰.</p>
          <div class="material-detail-specs">
            <div class="spec-row"><span class="spec-key">Pureté</span><span class="spec-val">999.9‰</span></div>
            <div class="spec-row"><span class="spec-key">Commande min.</span><span class="spec-val">100g</span></div>
            <div class="spec-row"><span class="spec-key">Certification</span><span class="spec-val">LBMA standard</span></div>
          </div>
        </div>
      </div>
      <div class="material-detail-card">
        <img src="/naturafrik/images/Cuivre.jpg" alt="Cuivre" class="material-detail-img" loading="lazy">
        <div>
          <div class="t-label" style="margin-bottom:0.4rem;">Métal industriel</div>
          <div class="material-detail-name">Cuivre</div>
          <p style="font-size:0.82rem;color:var(--text-muted);margin-bottom:0.75rem;">Cuivre industriel pur, en lingots ou bobines.</p>
          <div class="material-detail-specs">
            <div class="spec-row"><span class="spec-key">Pureté</span><span class="spec-val">99.9%</span></div>
            <div class="spec-row"><span class="spec-key">Commande min.</span><span class="spec-val">500 kg</span></div>
            <div class="spec-row"><span class="spec-key">Forme</span><span class="spec-val">Lingots / bobines</span></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════════════════ TESTIMONIALS ══════════════════════ -->
<section class="testimonials-section">
  <div class="container">
    <div class="section-eyebrow" style="justify-content:center;margin-bottom:1rem;" data-reveal>
      <span class="section-num">08</span>
      <div class="section-line"></div>
      <span class="t-label">Témoignages</span>
    </div>
    <h2 class="t-title text-center" style="margin-bottom:3rem;" data-reveal data-delay="1">Ce que disent nos clients</h2>
    <div class="testimonials-grid">
      <div class="testi-card" data-reveal>
        <div class="testi-stars">★★★★★</div>
        <p class="testi-text">"Le NATCACAO est d'une qualité exceptionnelle. L'arôme intense et la saveur profonde — on sent vraiment la richesse du terroir camerounais."</p>
        <div class="testi-author">
          <div class="testi-avatar">MK</div>
          <div>
            <div class="testi-name">Marie K.</div>
            <div class="testi-role">Cheffe pâtissière · Douala</div>
          </div>
        </div>
      </div>
      <div class="testi-card" data-reveal data-delay="1">
        <div class="testi-stars">★★★★★</div>
        <p class="testi-text">"Service impeccable pour notre commande de volailles. Livraison ponctuelle, produits frais et conformes. Je recommande vivement NATURAFRIK."</p>
        <div class="testi-author">
          <div class="testi-avatar">JN</div>
          <div>
            <div class="testi-name">Jean N.</div>
            <div class="testi-role">Restaurateur · Yaoundé</div>
          </div>
        </div>
      </div>
      <div class="testi-card" data-reveal data-delay="2">
        <div class="testi-stars">★★★★★</div>
        <p class="testi-text">"Excellent partenaire pour nos approvisionnements en matières premières. Sérieux, professionnel, prix compétitifs. Collaboration fructueuse depuis 2024."</p>
        <div class="testi-author">
          <div class="testi-avatar">PF</div>
          <div>
            <div class="testi-name">Paul F.</div>
            <div class="testi-role">Directeur achats · Cameroun</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════════════════ CTA ══════════════════════ -->
<section class="cta-band">
  <div class="container cta-band-inner">
    <div class="section-eyebrow" style="justify-content:center;margin-bottom:1rem;">
      <span class="section-num" style="color:rgba(150,112,8,0.6);">09</span>
      <div class="section-line" style="background:linear-gradient(90deg,rgba(150,112,8,0.3),transparent)"></div>
      <span class="t-label">Passez à l'action</span>
    </div>
    <h2 class="t-title" data-reveal>Prêt à commander ?</h2>
    <p class="t-body" data-reveal data-delay="1">Contactez-nous directement via WhatsApp ou notre formulaire. Livraison disponible à Yaoundé et partout au Cameroun.</p>
    <div class="cta-actions" data-reveal data-delay="2">
      <a href="https://wa.me/237691268428?text=Bonjour%20NATURAFRIK%2C%20je%20souhaite%20passer%20commande." target="_blank" class="btn-primary">
        <i class="fab fa-whatsapp"></i> <span>Commander via WhatsApp</span>
      </a>
      <a href="/naturafrik/pages/contact.php" class="btn-outline">Formulaire de contact</a>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
