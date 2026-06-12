<?php
// Maintenance mode — affiche la page directement sans redirection
if (defined('MAINTENANCE_MODE') && MAINTENANCE_MODE === true) {
    $is_admin = strpos(str_replace('\\', '/', $_SERVER['SCRIPT_FILENAME'] ?? ''), '/admin/') !== false;
    if (!$is_admin) {
        require dirname(__DIR__) . '/maintenance.php';
        exit;
    }
}

// Auto-rewrite /naturafrik/ paths when deployed at root (Railway, InfinityFree root, etc.)
if (defined('BASE') && BASE !== '/naturafrik') {
    ob_start(function ($buf) {
        return str_replace('/naturafrik/', BASE . '/', $buf);
    });
}
?><!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="<?= e($meta_description ?? 'NATURAFRIK – Produits naturels du Cameroun : cacao, café, immobilier, agriculture, matières premières.') ?>">
  <title><?= e($page_title ?? 'NATURAFRIK') ?> — NATURCAM Sarl</title>
  <link rel="icon" type="image/svg+xml" href="/naturafrik/images/favicon.svg">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600&family=Bebas+Neue&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="/naturafrik/css/style.css">
  <?= $extra_head ?? '' ?>
</head>
<body>

<!-- Page Loader -->
<div id="page-loader">
  <div class="loader-logo">NATURA<span>FRIK</span></div>
  <div class="loader-tagline">NATURCAM SARL · GROUPE NATURE CAMEROUN</div>
  <div class="loader-track"><div class="loader-fill"></div></div>
</div>

<!-- Navigation -->
<nav id="nav">
  <div class="nav-inner">
    <a href="/naturafrik/" class="nav-logo">
      <div class="nav-logo-icon">
        <img src="/naturafrik/images/favicon.svg" alt="NATURAFRIK">
      </div>
      <div class="nav-logo-text">
        <strong>NATURAFRIK</strong>
        <small>NATURCAM SARL</small>
      </div>
    </a>

    <div class="nav-links">
      <a href="/naturafrik/">Accueil</a>
      <a href="/naturafrik/pages/produits.php">Produits</a>
      <a href="/naturafrik/pages/natcafe.php" style="color:var(--gold-light);font-weight:600;">NATCAFÉ</a>
      <a href="/naturafrik/pages/immobilier.php">Immobilier</a>
      <a href="/naturafrik/pages/agriculture.php">Agriculture</a>
      <a href="/naturafrik/pages/matieres-premieres.php">Matières 1ères</a>
      <a href="/naturafrik/pages/contact.php">Contact</a>
    </div>

    <div class="nav-cta">
      <a href="https://wa.me/237691268428" target="_blank" class="btn-nav">
        <i class="fab fa-whatsapp"></i> WhatsApp
      </a>
    </div>
    <button id="hamburger" aria-label="Menu">
      <span></span><span></span><span></span>
    </button>
  </div>
</nav>

<!-- Mobile Menu -->
<div id="mobile-menu">
  <a href="/naturafrik/">Accueil</a>
  <a href="/naturafrik/pages/produits.php">Produits</a>
  <a href="/naturafrik/pages/natcafe.php" style="color:var(--gold)">NATCAFÉ ✦</a>
  <a href="/naturafrik/pages/immobilier.php">Immobilier</a>
  <a href="/naturafrik/pages/agriculture.php">Agriculture</a>
  <a href="/naturafrik/pages/matieres-premieres.php">Matières Premières</a>
  <a href="/naturafrik/pages/contact.php">Contact</a>
  <a href="https://wa.me/237691268428" target="_blank" style="color:var(--gold)">
    <i class="fab fa-whatsapp"></i> +237 691 268 428
  </a>
</div>

<!-- Toast Container -->
<div id="toast-container"></div>
