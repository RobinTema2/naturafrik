<?php
require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/functions.php';

$page_title = 'NATCAFÉ — Café d\'Altitude du Cameroun';
$meta_description = 'NATCAFÉ, le café d\'altitude de NATURAFRIK : Arabica des Hauts-Plateaux, 1200–1600 m, notes de fruits rouges et caramel. Torréfaction artisanale, 250g.';

$extra_head = '<style>
/* ── NATCAFÉ Page Styles ─────────────────────────────────────── */

/* Hero */
.nc-hero {
  height: 100vh; min-height: 640px;
  position: relative; overflow: hidden;
  display: flex; align-items: flex-end;
}
.nc-hero-bg {
  position: absolute; inset: 0;
  width: 100%; height: 100%; object-fit: cover;
  transform: scale(1.04); transition: transform 10s ease;
  filter: brightness(0.55) saturate(0.9);
}
.nc-hero:hover .nc-hero-bg { transform: scale(1.0); }
.nc-hero-overlay {
  position: absolute; inset: 0;
  background: linear-gradient(
    to top,
    rgba(8,6,4,0.92) 0%,
    rgba(8,6,4,0.5) 40%,
    rgba(8,6,4,0.1) 70%,
    transparent 100%
  );
}
.nc-hero-content {
  position: relative; z-index: 2;
  width: 100%; padding: 0 0 5rem;
}
.nc-hero-inner {
  max-width: 1280px; margin: 0 auto; padding: 0 3rem;
  display: grid; grid-template-columns: 1fr auto; align-items: flex-end; gap: 4rem;
}
.nc-eyebrow {
  display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;
}
.nc-eyebrow-line { width: 50px; height: 1px; background: var(--gold-light); }
.nc-eyebrow-text {
  font-size: 0.62rem; letter-spacing: 0.4em;
  color: var(--gold-light); font-weight: 600; text-transform: uppercase;
}
.nc-hero-title {
  font-family: var(--serif);
  font-size: clamp(4rem, 9vw, 9rem);
  font-weight: 300; line-height: 0.92;
  color: #FEFCF8; letter-spacing: -0.02em;
  margin-bottom: 0.3rem;
}
.nc-hero-title em {
  font-style: italic; color: var(--gold-pale);
  font-size: 0.7em; display: block; margin-top: 0.2em;
}
.nc-hero-sub {
  font-size: 0.82rem; letter-spacing: 0.2em;
  color: rgba(255,255,255,0.5); margin-top: 1.2rem;
  text-transform: uppercase;
}
.nc-hero-price-block { text-align: right; flex-shrink: 0; }
.nc-hero-price {
  font-family: var(--serif);
  font-size: 3.5rem; font-weight: 300; color: var(--gold-pale);
  line-height: 1;
}
.nc-hero-price small {
  display: block; font-family: var(--sans);
  font-size: 0.68rem; letter-spacing: 0.25em;
  color: rgba(255,255,255,0.4); margin-top: 0.3rem;
}
.nc-hero-cta {
  display: inline-flex; align-items: center; gap: 0.7rem;
  margin-top: 1.5rem; padding: 0.85rem 2rem;
  background: var(--gold-light); color: #0C0906;
  font-size: 0.78rem; font-weight: 700; letter-spacing: 0.15em;
  border-radius: var(--r-full); text-transform: uppercase;
  transition: transform 0.2s, box-shadow 0.2s;
}
.nc-hero-cta:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 28px rgba(200,146,10,0.45);
}
.nc-scroll-hint {
  position: absolute; bottom: 2rem; left: 50%;
  transform: translateX(-50%); z-index: 3;
  display: flex; flex-direction: column; align-items: center; gap: 0.5rem;
}
.nc-scroll-hint span {
  font-size: 0.58rem; letter-spacing: 0.35em;
  color: rgba(255,255,255,0.3); text-transform: uppercase;
}
.nc-scroll-arrow {
  width: 1px; height: 40px;
  background: linear-gradient(to bottom, rgba(200,146,10,0.8), transparent);
  animation: scrollDrop 2s ease infinite;
}
@keyframes scrollDrop {
  0%,100% { transform: scaleY(1); opacity: 1; }
  50% { transform: scaleY(0.4); opacity: 0.4; }
}

/* Gold band */
.nc-gold-band {
  background: var(--gold-light);
  padding: 0.8rem 0; overflow: hidden;
}
.nc-band-track {
  display: flex; animation: marqueeScroll 30s linear infinite; white-space: nowrap;
}
.nc-band-item {
  display: inline-flex; align-items: center; gap: 1.5rem;
  padding: 0 2rem;
  font-family: var(--display); font-size: 0.95rem;
  letter-spacing: 0.2em; color: #0C0906;
}
.nc-band-sep { width: 4px; height: 4px; border-radius: 50%; background: rgba(0,0,0,0.3); }

/* Intro section */
.nc-intro {
  padding: 8rem 0;
  background: var(--ink);
}
.nc-intro-grid {
  display: grid; grid-template-columns: 1fr 1fr; gap: 7rem; align-items: center;
}
.nc-product-img-wrap { position: relative; }
.nc-product-img {
  width: 100%; border-radius: var(--r-lg);
  box-shadow: 0 30px 80px rgba(0,0,0,0.12), 0 0 60px rgba(150,110,8,0.08);
}
.nc-product-badge {
  position: absolute; top: -1.5rem; right: -1.5rem;
  width: 100px; height: 100px; border-radius: 50%;
  background: var(--gold-light); color: #0C0906;
  display: flex; flex-direction: column;
  align-items: center; justify-content: center;
  font-family: var(--display); font-size: 1.2rem;
  letter-spacing: 0.08em; text-align: center;
  box-shadow: 0 8px 30px rgba(200,146,10,0.4);
  animation: badgeSpin 20s linear infinite;
}
.nc-product-badge small {
  font-family: var(--sans); font-size: 0.5rem;
  letter-spacing: 0.15em; color: rgba(0,0,0,0.55);
  display: block; margin-top: 2px; font-weight: 600;
}
@keyframes badgeSpin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
.nc-intro-kv {
  font-family: var(--serif); font-size: clamp(2.5rem, 4vw, 4.2rem);
  font-weight: 300; line-height: 1.15; color: var(--cream);
  margin-bottom: 1.5rem;
}
.nc-intro-kv em { font-style: italic; color: var(--gold); display: block; }
.nc-intro-body {
  font-size: 1rem; line-height: 1.8;
  color: var(--text-muted); margin-bottom: 2.5rem;
}
.nc-divider {
  width: 60px; height: 2px;
  background: linear-gradient(90deg, var(--gold-light), transparent);
  margin: 2rem 0;
}
.nc-tag-row { display: flex; gap: 0.6rem; flex-wrap: wrap; margin-top: 1.5rem; }
.nc-tag {
  padding: 0.32rem 0.9rem;
  border: 1px solid rgba(150,110,8,0.3);
  border-radius: var(--r-full);
  font-size: 0.68rem; letter-spacing: 0.15em;
  color: var(--gold); font-weight: 600; text-transform: uppercase;
  background: rgba(150,110,8,0.06);
}

/* Specs */
.nc-specs {
  padding: 7rem 0;
  background: var(--ink-soft);
}
.nc-specs-title {
  font-family: var(--serif);
  font-size: clamp(1.8rem, 3vw, 3rem);
  font-weight: 300; color: var(--cream);
  margin-bottom: 3.5rem; text-align: center;
}
.nc-specs-title em { font-style: italic; color: var(--gold); }
.nc-specs-grid {
  display: grid; grid-template-columns: repeat(3, 1fr); gap: 1px;
  background: rgba(150,110,8,0.15);
  border: 1px solid rgba(150,110,8,0.15);
  border-radius: var(--r-lg); overflow: hidden;
}
.nc-spec {
  background: var(--card);
  padding: 2.2rem 2rem;
  transition: background 0.3s;
}
.nc-spec:hover { background: var(--surface); }
.nc-spec-key {
  font-size: 0.58rem; letter-spacing: 0.3em;
  text-transform: uppercase; color: var(--gold);
  margin-bottom: 0.5rem; font-weight: 700;
}
.nc-spec-val {
  font-family: var(--serif); font-size: 1.15rem;
  color: var(--cream); line-height: 1.3;
}
.nc-spec-val em { font-style: normal; color: var(--text-muted); font-size: 0.85rem; }

/* Aromes section */
.nc-aromes { padding: 8rem 0; background: var(--ink); }
.nc-aromes-grid {
  display: grid; grid-template-columns: 1fr 1fr; gap: 7rem; align-items: center;
}
.nc-aromes-img {
  position: relative; border-radius: var(--r-lg); overflow: hidden;
  aspect-ratio: 4/5;
}
.nc-aromes-img img {
  width: 100%; height: 100%; object-fit: cover;
  filter: brightness(0.75) saturate(0.9);
  transition: filter 0.6s;
}
.nc-aromes-img:hover img { filter: brightness(0.9) saturate(1); }
.nc-aromes-img-overlay {
  position: absolute; inset: 0;
  background: linear-gradient(to top, rgba(8,6,4,0.7) 0%, transparent 55%);
}
.nc-arome-label {
  position: absolute; bottom: 2rem; left: 2rem; right: 2rem;
  font-family: var(--serif); font-size: 1.4rem; font-style: italic;
  color: rgba(255,255,255,0.85); line-height: 1.4;
}
.nc-aromes-content {}
.nc-aromes-eyebrow {
  font-size: 0.62rem; letter-spacing: 0.4em; color: var(--gold);
  font-weight: 700; text-transform: uppercase; margin-bottom: 1rem;
}
.nc-aromes-heading {
  font-family: var(--serif);
  font-size: clamp(2rem, 3.5vw, 3.5rem);
  font-weight: 300; color: var(--cream);
  line-height: 1.15; margin-bottom: 2rem;
}
.nc-arome-pills { display: flex; flex-direction: column; gap: 1rem; }
.nc-arome-pill {
  display: flex; align-items: center; gap: 1.5rem;
  padding: 1.2rem 1.5rem;
  background: var(--card); border: 1px solid var(--border);
  border-radius: var(--r-md);
  transition: border-color 0.3s, transform 0.3s;
}
.nc-arome-pill:hover { border-color: rgba(150,110,8,0.35); transform: translateX(6px); }
.nc-arome-icon {
  width: 46px; height: 46px; border-radius: var(--r-sm);
  background: rgba(150,110,8,0.1); border: 1px solid rgba(150,110,8,0.2);
  display: flex; align-items: center; justify-content: center;
  font-size: 1.4rem; flex-shrink: 0;
}
.nc-arome-info-label {
  font-size: 0.62rem; letter-spacing: 0.2em;
  color: var(--text-dim); text-transform: uppercase;
}
.nc-arome-info-name {
  font-family: var(--serif); font-size: 1.1rem;
  color: var(--cream); margin-top: 0.1rem;
}
.nc-arome-bar-wrap {
  flex: 1; height: 3px;
  background: rgba(150,110,8,0.12); border-radius: 2px;
  overflow: hidden;
}
.nc-arome-bar {
  height: 100%; border-radius: 2px;
  background: linear-gradient(90deg, var(--gold-light), var(--gold-pale));
  transform-origin: left;
  animation: barFill 1.4s var(--ease) forwards;
  transform: scaleX(0);
}
@keyframes barFill { to { transform: scaleX(1); } }

/* Brewing */
.nc-brewing {
  padding: 7rem 0;
  background: linear-gradient(135deg, #F5EDD8 0%, #FFFAEF 100%);
  border-top: 1px solid rgba(150,110,8,0.2);
}
.nc-brewing-heading {
  text-align: center; margin-bottom: 4rem;
}
.nc-brewing-heading h2 {
  font-family: var(--serif);
  font-size: clamp(1.8rem, 3vw, 3rem);
  font-weight: 300; color: #0C0906;
  line-height: 1.15;
}
.nc-brewing-heading h2 em { font-style: italic; color: var(--gold); }
.nc-brewing-grid {
  display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem;
}
.nc-brew-card {
  background: rgba(255,255,255,0.7); border: 1px solid rgba(150,110,8,0.15);
  border-radius: var(--r-lg); padding: 2rem 1.5rem;
  text-align: center;
  transition: transform 0.3s, box-shadow 0.3s, border-color 0.3s;
  backdrop-filter: blur(10px);
}
.nc-brew-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 20px 50px rgba(150,110,8,0.12);
  border-color: rgba(150,110,8,0.3);
}
.nc-brew-icon { font-size: 2.8rem; margin-bottom: 1rem; }
.nc-brew-name {
  font-family: var(--serif); font-size: 1.15rem;
  color: #0C0906; margin-bottom: 0.5rem;
}
.nc-brew-ratio {
  font-size: 0.68rem; letter-spacing: 0.15em;
  color: var(--gold); font-weight: 700; margin-bottom: 0.5rem;
}
.nc-brew-desc { font-size: 0.8rem; color: #6A5840; line-height: 1.6; }

/* Origin */
.nc-origin { padding: 8rem 0; background: var(--ink); }
.nc-origin-grid {
  display: grid; grid-template-columns: 1fr 1.1fr; gap: 6rem; align-items: center;
}
.nc-origin-content {}
.nc-origin-map {
  position: relative; border-radius: var(--r-lg); overflow: hidden;
  background: var(--surface);
  border: 1px solid var(--border);
}
.nc-origin-img {
  width: 100%; height: 420px; object-fit: cover;
  filter: brightness(0.7) saturate(0.8);
}
.nc-origin-overlay {
  position: absolute; inset: 0;
  background: linear-gradient(135deg, rgba(26,92,56,0.3), transparent);
}
.nc-origin-pin {
  position: absolute; bottom: 2rem; left: 2rem;
  background: rgba(8,6,4,0.88); backdrop-filter: blur(12px);
  border: 1px solid rgba(200,146,10,0.25); border-radius: var(--r-md);
  padding: 1rem 1.5rem;
}
.nc-origin-pin-name { font-size: 0.62rem; letter-spacing: 0.25em; color: var(--gold); margin-bottom: 0.2rem; }
.nc-origin-pin-place { font-family: var(--serif); font-size: 1.1rem; color: #F4EDE0; }
.nc-origin-heading {
  font-family: var(--serif);
  font-size: clamp(2rem, 3.5vw, 3.5rem);
  font-weight: 300; color: var(--cream);
  line-height: 1.15; margin-bottom: 1.5rem;
}
.nc-origin-body {
  font-size: 0.98rem; line-height: 1.8;
  color: var(--text-muted); margin-bottom: 2rem;
}
.nc-origin-stats {
  display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;
}
.nc-origin-stat {
  padding: 1.25rem; background: var(--card);
  border: 1px solid var(--border); border-radius: var(--r-md);
}
.nc-origin-stat-num {
  font-family: var(--display); font-size: 2rem;
  color: var(--gold-light); line-height: 1;
}
.nc-origin-stat-label {
  font-size: 0.62rem; letter-spacing: 0.2em;
  color: var(--text-dim); margin-top: 0.25rem; text-transform: uppercase;
}

/* Order CTA */
.nc-order {
  padding: 7rem 0;
  background: linear-gradient(135deg, #0A0704 0%, #1C1200 50%, #0A0704 100%);
  position: relative; overflow: hidden;
  border-top: 1px solid rgba(200,146,10,0.2);
}
.nc-order::before {
  content: "";
  position: absolute; top: -40%; left: 50%;
  transform: translateX(-50%);
  width: 700px; height: 700px; border-radius: 50%;
  background: radial-gradient(circle, rgba(200,146,10,0.1), transparent 65%);
  pointer-events: none;
}
.nc-order-inner {
  position: relative; z-index: 1; text-align: center;
}
.nc-order-kv {
  font-family: var(--display);
  font-size: clamp(5rem, 12vw, 14rem);
  letter-spacing: 0.05em;
  color: rgba(200,146,10,0.12); line-height: 1;
  position: absolute; inset: 0; display: flex;
  align-items: center; justify-content: center;
  pointer-events: none; user-select: none;
}
.nc-order-content { position: relative; z-index: 2; }
.nc-order-label {
  font-size: 0.62rem; letter-spacing: 0.45em; color: var(--gold-light);
  font-weight: 700; text-transform: uppercase; margin-bottom: 1.5rem;
  display: flex; align-items: center; justify-content: center; gap: 1rem;
}
.nc-order-label::before,
.nc-order-label::after {
  content: ""; flex: 0 0 40px; height: 1px;
  background: var(--gold-light);
}
.nc-order-heading {
  font-family: var(--serif);
  font-size: clamp(2.5rem, 5vw, 5.5rem);
  font-weight: 300; color: #F4EDE0; line-height: 1.1;
  margin-bottom: 0.8rem;
}
.nc-order-heading em { font-style: italic; color: var(--gold-pale); }
.nc-order-desc {
  font-size: 1rem; color: rgba(244,237,224,0.55);
  margin: 0 auto 3rem; max-width: 460px; line-height: 1.75;
}
.nc-price-big {
  font-family: var(--serif); font-size: 4rem;
  color: var(--gold-pale); line-height: 1;
  margin-bottom: 2rem;
}
.nc-price-big small {
  font-family: var(--sans); font-size: 1rem;
  color: rgba(200,146,10,0.5); letter-spacing: 0.2em;
}
.nc-order-btns { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
.nc-btn-wa {
  display: inline-flex; align-items: center; gap: 0.7rem;
  padding: 1.1rem 2.5rem;
  background: var(--gold-light); color: #0C0906;
  font-size: 0.82rem; font-weight: 700; letter-spacing: 0.12em;
  border-radius: var(--r-full); text-transform: uppercase;
  transition: transform 0.2s, box-shadow 0.2s;
}
.nc-btn-wa:hover { transform: translateY(-3px); box-shadow: 0 12px 35px rgba(200,146,10,0.5); }
.nc-btn-outline {
  display: inline-flex; align-items: center; gap: 0.7rem;
  padding: 1.1rem 2.5rem;
  border: 1px solid rgba(200,146,10,0.35); color: #F4EDE0;
  font-size: 0.82rem; font-weight: 500; letter-spacing: 0.1em;
  border-radius: var(--r-full); text-transform: uppercase;
  transition: border-color 0.2s, background 0.2s;
}
.nc-btn-outline:hover { border-color: var(--gold-light); background: rgba(200,146,10,0.08); }
.nc-order-guarantees {
  display: flex; justify-content: center; gap: 2.5rem;
  margin-top: 3rem; flex-wrap: wrap;
}
.nc-guarantee {
  display: flex; align-items: center; gap: 0.6rem;
  font-size: 0.72rem; letter-spacing: 0.1em;
  color: rgba(200,146,10,0.6); text-transform: uppercase;
}
.nc-guarantee i { color: var(--gold-light); }

/* Responsive */
@media (max-width: 900px) {
  .nc-hero-inner { grid-template-columns: 1fr; }
  .nc-hero-price-block { display: none; }
  .nc-intro-grid, .nc-aromes-grid, .nc-origin-grid { grid-template-columns: 1fr; }
  .nc-brewing-grid { grid-template-columns: 1fr 1fr; }
  .nc-product-badge { top: 1rem; right: 1rem; }
  .nc-specs-grid { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 600px) {
  .nc-brewing-grid { grid-template-columns: 1fr; }
  .nc-specs-grid { grid-template-columns: 1fr; }
  .nc-origin-stats { grid-template-columns: 1fr; }
  .nc-hero-title { font-size: clamp(3rem, 14vw, 5rem); }
}
</style>';

require_once dirname(__DIR__) . '/includes/header.php';
?>

<!-- ── Hero ─────────────────────────────────────────────────────── -->
<section class="nc-hero">
  <img src="/naturafrik/images/natcafe%20(2).png" alt="NATCAFÉ Café d'altitude" class="nc-hero-bg">
  <div class="nc-hero-overlay"></div>

  <div class="nc-hero-content">
    <div class="nc-hero-inner">
      <div>
        <div class="nc-eyebrow">
          <div class="nc-eyebrow-line"></div>
          <span class="nc-eyebrow-text">Produit Signature · NATURAFRIK</span>
        </div>
        <h1 class="nc-hero-title">
          NATCAFÉ
          <em>Café d'Altitude</em>
        </h1>
        <p class="nc-hero-sub">Arabica · Hauts-Plateaux Camerounais · 1200–1600 m</p>
      </div>
      <div class="nc-hero-price-block">
        <div class="nc-hero-price">
          2 800
          <small>FCFA / 250g</small>
        </div>
        <a href="https://wa.me/237680209435?text=Bonjour%2C%20je%20souhaite%20commander%20NATCAF%C3%89%20250g" target="_blank" class="nc-hero-cta">
          <i class="fab fa-whatsapp"></i> Commander
        </a>
      </div>
    </div>
  </div>

  <div class="nc-scroll-hint">
    <span>Découvrir</span>
    <div class="nc-scroll-arrow"></div>
  </div>
</section>

<!-- ── Gold Band ─────────────────────────────────────────────────── -->
<div class="nc-gold-band">
  <div class="nc-band-track">
    <?php $items = ['ARABICA PUR', 'ALTITUDE 1200–1600 M', 'TORRÉFACTION ARTISANALE', 'CUEILLETTE À LA MAIN', 'HAUTS-PLATEAUX CAMEROUN', 'NOTES : FRUITS ROUGES · CARAMEL', 'CONDITIONNEMENT 250G', 'NATURAFRIK – NATURCAM SARL']; foreach(array_merge($items,$items,$items) as $it): ?>
    <span class="nc-band-item"><?= $it ?><span class="nc-band-sep"></span></span>
    <?php endforeach; ?>
  </div>
</div>

<!-- ── Intro ──────────────────────────────────────────────────────── -->
<section class="nc-intro">
  <div class="container">
    <div class="nc-intro-grid">
      <div class="nc-product-img-wrap" data-reveal="left">
        <img src="/naturafrik/images/natcafe%20(2).png" alt="NATCAFÉ 250g" class="nc-product-img">
        <div class="nc-product-badge">
          250G<br><small>PREMIUM</small>
        </div>
      </div>
      <div data-reveal="right">
        <p class="t-label" style="margin-bottom:0.75rem;">Produit Signature</p>
        <h2 class="nc-intro-kv">
          L'âme du café<br>
          <em>camerounais</em><br>
          en tasse
        </h2>
        <div class="nc-divider"></div>
        <p class="nc-intro-body">
          NATCAFÉ naît sur les hauts-plateaux de l'Ouest Cameroun, à plus de 1 200 mètres d'altitude,
          là où les nuits fraîches et les sols volcaniques donnent au café arabica une complexité
          aromatique rare. Chaque grain est cueilli à la main par des producteurs familiaux, trié,
          lavé et torréfié artisanalement pour révéler ses meilleures notes.
        </p>
        <p class="nc-intro-body">
          C'est un café honnête, traçable, à l'identité africaine affirmée — pensé pour ceux
          qui reconnaissent la qualité dans sa simplicité.
        </p>
        <div class="nc-tag-row">
          <span class="nc-tag">Arabica</span>
          <span class="nc-tag">Lavé</span>
          <span class="nc-tag">Single Origin</span>
          <span class="nc-tag">Agriculture raisonnée</span>
          <span class="nc-tag">Artisanal</span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ── Fiche Technique ────────────────────────────────────────────── -->
<section class="nc-specs">
  <div class="container">
    <h2 class="nc-specs-title" data-reveal>Fiche <em>Technique</em></h2>
    <div class="nc-specs-grid">
      <?php
      $specs = [
        ['ESPÈCE',        'Coffea arabica',       'Bourbon &amp; Typica'],
        ['ORIGINE',       'Région Ouest, Cameroun', 'Bafoussam · Dschang'],
        ['ALTITUDE',      '1 200 – 1 600 m',      'Hauts-Plateaux'],
        ['PROCESS',       'Lavé (Washed)',         'Fermentation contrôlée'],
        ['TORRÉFACTION',  'Légère à Moyenne',      'Roast : Medium-Light'],
        ['CONDITIONNEMENT','250 g',                'Grain ou mouture'],
        ['CONSERVATION',  '12 mois',               'Lieu frais &amp; sec, sachet scellé'],
        ['CAFÉINE',       'Modérée',               'Arabica naturellement moins corsé'],
        ['NOTES',         'Fruits rouges',         'Caramel · Agrumes · Corps velouté'],
      ];
      foreach($specs as $i => $s): ?>
      <div class="nc-spec" data-reveal data-delay="<?= ($i % 3)+1 ?>">
        <div class="nc-spec-key"><?= $s[0] ?></div>
        <div class="nc-spec-val"><?= $s[1] ?><br><em><?= $s[2] ?></em></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ── Profil Aromatique ──────────────────────────────────────────── -->
<section class="nc-aromes">
  <div class="container">
    <div class="nc-aromes-grid">
      <div class="nc-aromes-img" data-reveal="left">
        <img src="/naturafrik/images/natcafe%20(2).png" alt="Café NATCAFÉ tasse">
        <div class="nc-aromes-img-overlay"></div>
        <p class="nc-arome-label">« Une tasse qui raconte<br>l'Afrique dans chaque gorgée »</p>
      </div>
      <div data-reveal="right">
        <p class="nc-aromes-eyebrow">Profil Sensoriel</p>
        <h2 class="nc-aromes-heading">
          Les arômes<br>qui vous attendent
        </h2>
        <div class="nc-arome-pills">
          <?php
          $aromes = [
            ['🍒', 'Fruits Rouges', 'Note dominante · Cerise, mûre', 90],
            ['🍯', 'Caramel',       'Douceur naturelle en milieu de bouche', 75],
            ['🍋', 'Agrumes',       'Légère acidité vive, fraîche et nette', 60],
            ['🌾', 'Corps Velouté', 'Texture soyeuse, finale longue et douce', 85],
          ];
          foreach($aromes as $d => $a): ?>
          <div class="nc-arome-pill" data-reveal data-delay="<?= $d+1 ?>">
            <div class="nc-arome-icon"><?= $a[0] ?></div>
            <div style="flex:1;min-width:0">
              <div class="nc-arome-info-label"><?= $a[1] ?></div>
              <div class="nc-arome-info-name"><?= $a[2] ?></div>
              <div class="nc-arome-bar-wrap" style="margin-top:0.5rem">
                <div class="nc-arome-bar" style="width:<?= $a[3] ?>%"></div>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ── Guide de Préparation ──────────────────────────────────────── -->
<section class="nc-brewing">
  <div class="container">
    <div class="nc-brewing-heading" data-reveal>
      <p class="t-label" style="text-align:center;margin-bottom:0.75rem;color:var(--gold);">Comment le préparer</p>
      <h2>Les méthodes <em>recommandées</em></h2>
    </div>
    <div class="nc-brewing-grid">
      <?php
      $brews = [
        ['☕', 'Cafetière Filtre',   '60g / litre · 93°C · 4 min',  'La méthode idéale pour révéler toute la complexité florale et fruitée de NATCAFÉ.'],
        ['🫗', 'French Press',       '65g / litre · 95°C · 4 min',  'Corps plein et texture veloutée. Laissez infuser exactement 4 minutes avant de presser.'],
        ['🔥', 'Moka / Italienne',   'Mouture fine · Feu doux',     'Expresso court et intense. Idéal le matin pour un réveil africain corsé.'],
        ['❄️', 'Cold Brew',          '80g / litre · 12h · frigo',   'Immersion longue à froid. Résultat : sucré naturellement, zéro amertume.'],
      ];
      foreach($brews as $i => $b): ?>
      <div class="nc-brew-card" data-reveal data-delay="<?= $i+1 ?>">
        <div class="nc-brew-icon"><?= $b[0] ?></div>
        <div class="nc-brew-name"><?= $b[1] ?></div>
        <div class="nc-brew-ratio"><?= $b[2] ?></div>
        <p class="nc-brew-desc"><?= $b[3] ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ── L'Origine ─────────────────────────────────────────────────── -->
<section class="nc-origin">
  <div class="container">
    <div class="nc-origin-grid">
      <div data-reveal="left">
        <p class="nc-aromes-eyebrow" style="color:var(--gold);font-size:0.62rem;letter-spacing:0.4em;font-weight:700;text-transform:uppercase;margin-bottom:1rem;">L'Origine</p>
        <h2 class="nc-origin-heading">
          Né sur les<br>
          <span style="font-style:italic;color:var(--gold);">Hauts-Plateaux</span><br>
          de l'Ouest
        </h2>
        <p class="nc-origin-body">
          La Région Ouest du Cameroun abrite l'une des terres à café les plus
          précieuses d'Afrique centrale. Entre Bafoussam et Dschang, à des altitudes
          dépassant 1 200 mètres, les caféiers arabica bénéficient d'un microclimat
          exceptionnel : températures douces, pluies abondantes et sols volcaniques
          riches en minéraux.
        </p>
        <p class="nc-origin-body">
          NATURAFRIK travaille directement avec des familles de producteurs engagées
          dans une agriculture raisonnée, sans pesticides chimiques, pour un café
          à la fois pur et durable.
        </p>
        <div class="nc-origin-stats">
          <div class="nc-origin-stat">
            <div class="nc-origin-stat-num">1 400<span style="font-size:1.2rem;color:var(--gold)">m</span></div>
            <div class="nc-origin-stat-label">Altitude moyenne</div>
          </div>
          <div class="nc-origin-stat">
            <div class="nc-origin-stat-num">100<span style="font-size:1.2rem;color:var(--gold)">%</span></div>
            <div class="nc-origin-stat-label">Cueillette manuelle</div>
          </div>
          <div class="nc-origin-stat">
            <div class="nc-origin-stat-num">12<span style="font-size:1.2rem;color:var(--gold)">h</span></div>
            <div class="nc-origin-stat-label">Fermentation lavée</div>
          </div>
          <div class="nc-origin-stat">
            <div class="nc-origin-stat-num">0</span></div>
            <div class="nc-origin-stat-label">Additifs ou conservateurs</div>
          </div>
        </div>
      </div>
      <div class="nc-origin-map" data-reveal="right">
        <img src="/naturafrik/images/Agriculture.png" alt="Plantation café Cameroun" class="nc-origin-img">
        <div class="nc-origin-overlay"></div>
        <div class="nc-origin-pin">
          <div class="nc-origin-pin-name">📍 ORIGINE CERTIFIÉE</div>
          <div class="nc-origin-pin-place">Région Ouest — Cameroun</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ── Commander ─────────────────────────────────────────────────── -->
<section class="nc-order">
  <div class="nc-order-kv">CAFÉ</div>
  <div class="container">
    <div class="nc-order-inner">
      <div class="nc-order-content">
        <div class="nc-order-label">Commander NATCAFÉ</div>
        <h2 class="nc-order-heading">
          Votre café africain<br>
          <em>livré chez vous</em>
        </h2>
        <p class="nc-order-desc">
          Commande rapide via WhatsApp. Disponible en grain ou mouture selon votre méthode.
          Livraison à Yaoundé, Douala et sur commande en régions.
        </p>
        <div class="nc-price-big">
          2 800 <small>FCFA / 250g</small>
        </div>
        <div class="nc-order-btns">
          <a href="https://wa.me/237680209435?text=Bonjour%20NATURAFRIK%2C%20je%20souhaite%20commander%20NATCAF%C3%89%20250g.%20Pouvez-vous%20m%27indiquer%20les%20disponibilit%C3%A9s%20%3F" target="_blank" class="nc-btn-wa">
            <i class="fab fa-whatsapp"></i> Commander via WhatsApp
          </a>
          <a href="/naturafrik/pages/contact.php" class="nc-btn-outline">
            <i class="fas fa-envelope"></i> Nous contacter
          </a>
        </div>
        <div class="nc-order-guarantees">
          <span class="nc-guarantee"><i class="fas fa-check-circle"></i> Fraîcheur garantie</span>
          <span class="nc-guarantee"><i class="fas fa-check-circle"></i> Livraison Yaoundé & Douala</span>
          <span class="nc-guarantee"><i class="fas fa-check-circle"></i> Grain ou mouture au choix</span>
          <span class="nc-guarantee"><i class="fas fa-check-circle"></i> 100% naturel</span>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
