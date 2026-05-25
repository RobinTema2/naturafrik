<!-- ── Footer ── -->
<footer>
  <div class="container">
    <div class="footer-grid">

      <!-- Brand -->
      <div class="footer-brand">
        <div class="footer-logo-text">NATURA<span>FRIK</span></div>
        <p>Groupe Nature Cameroun Sarl — Produits naturels d'exception : cacao, café, avocats, immobilier, agriculture et matières premières.</p>
        <div class="footer-socials">
          <a href="https://wa.me/237691268428" target="_blank" class="footer-social" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
          <a href="#" class="footer-social" title="Facebook"><i class="fab fa-facebook-f"></i></a>
          <a href="#" class="footer-social" title="Instagram"><i class="fab fa-instagram"></i></a>
          <a href="#" class="footer-social" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
        </div>
      </div>

      <!-- Navigation -->
      <div class="footer-col">
        <h4>Navigation</h4>
        <ul>
          <li><a href="/naturafrik/">Accueil</a></li>
          <li><a href="/naturafrik/pages/produits.php">Produits</a></li>
          <li><a href="/naturafrik/pages/immobilier.php">Immobilier</a></li>
          <li><a href="/naturafrik/pages/agriculture.php">Agriculture</a></li>
          <li><a href="/naturafrik/pages/matieres-premieres.php">Matières Premières</a></li>
          <li><a href="/naturafrik/pages/contact.php">Contact</a></li>
        </ul>
      </div>

      <!-- Products -->
      <div class="footer-col">
        <h4>Produits phares</h4>
        <ul>
          <li><a href="/naturafrik/pages/produits.php">NATCACAO 500g</a></li>
          <li><a href="/naturafrik/pages/natcafe.php">NATCAFÉ 250g</a></li>
          <li><a href="/naturafrik/pages/produits.php">Avocat frais</a></li>
          <li><a href="/naturafrik/pages/agriculture.php">Volaille fermière</a></li>
          <li><a href="/naturafrik/pages/matieres-premieres.php">Or fin 999.9</a></li>
          <li><a href="/naturafrik/pages/immobilier.php">Maisons & terrains</a></li>
        </ul>
      </div>

      <!-- Contact -->
      <div class="footer-col">
        <h4>Contact</h4>
        <div class="footer-contact-item">
          <i class="fas fa-map-marker-alt"></i>
          <span>BP 3005, Yaoundé-Cameroun<br>Montée Anne Rouge</span>
        </div>
        <div class="footer-contact-item">
          <i class="fas fa-phone"></i>
          <span>
            <a href="tel:+237698141070">+237 698 141 070</a><br>
            <a href="tel:+237691268428">+237 691 268 428</a><br>
            <a href="tel:+237688620132">+237 688 620 132</a>
          </span>
        </div>
        <div class="footer-contact-item">
          <i class="fab fa-whatsapp"></i>
          <span>
            <a href="https://wa.me/237691268428" target="_blank">+237 691 268 428</a><br>
            <a href="https://wa.me/237688620132" target="_blank">+237 688 620 132</a>
          </span>
        </div>
        <div class="footer-contact-item">
          <i class="fas fa-globe"></i>
          <span><a href="tel:+16132826599">+1 (613) 282-6599</a></span>
        </div>
        <div class="footer-banks">
          <strong>Comptes Bancaires</strong>
          B.G.F.I. Bank: CM21 10035012004001776701179<br>
          Afriland First: CM21 10005 00038 09295421001 80
        </div>
      </div>

    </div><!-- /footer-grid -->

    <!-- Newsletter band -->
    <div style="background:var(--surface);border:1px solid var(--border);border-radius:var(--r-lg);padding:2rem 2.5rem;display:flex;align-items:center;justify-content:space-between;gap:2rem;flex-wrap:wrap;margin-bottom:2.5rem;">
      <div>
        <div class="t-label" style="margin-bottom:0.4rem;">Newsletter</div>
        <p style="font-size:0.9rem;color:var(--text-muted);margin:0;">Recevez nos offres exclusives et actualités.</p>
      </div>
      <form id="newsletter-form" style="display:flex;gap:0.75rem;flex:1;max-width:420px;">
        <input type="hidden" name="csrf_token" value="<?= isset($csrf) ? e($csrf) : '' ?>">
        <input type="email" name="email" placeholder="votre@email.com" required
               style="flex:1;padding:0.75rem 1rem;background:var(--card);border:1px solid var(--border);border-radius:var(--r-full);color:var(--text);font-size:0.88rem;outline:none;">
        <button type="submit" class="btn-primary" style="padding:0.75rem 1.5rem;white-space:nowrap;font-size:0.78rem;">
          S'abonner
        </button>
      </form>
    </div>

    <!-- Bottom bar -->
    <div class="footer-bottom">
      <div class="footer-legal">
        &copy; <?= date('Y') ?> NATURCAM Sarl · Groupe Nature Cameroun ·
        RCCM: RC/YAO/2023/B/519 · NIU: M032318076551C ·
        <a href="/naturafrik/pages/contact.php">Mentions légales</a>
      </div>
      <div style="font-size:0.72rem;color:var(--text-dim);">
        Imagination · Performance · Qualité
      </div>
    </div>

  </div>
</footer>

<!-- WhatsApp FAB -->
<a id="whatsapp-fab" href="https://wa.me/237691268428?text=Bonjour%20NATURAFRIK%2C%20je%20souhaite%20des%20informations." target="_blank" aria-label="WhatsApp">
  <i class="fab fa-whatsapp"></i>
</a>

<!-- Back to top -->
<button id="back-to-top" aria-label="Haut de page"><i class="fas fa-chevron-up"></i></button>

<!-- ══════════════════ PRODUCT MODAL (Facebook Marketplace style) ══════════════════ -->
<div id="product-modal" role="dialog" aria-modal="true" aria-labelledby="modal-title">
  <div id="modal-backdrop"></div>
  <div id="modal-panel">

    <!-- Close -->
    <button id="modal-close" aria-label="Fermer">
      <i class="fas fa-times"></i>
    </button>

    <!-- Image side -->
    <div id="modal-img-wrap">
      <img id="modal-img" src="" alt="">
      <div id="modal-img-nav">
        <button id="modal-prev" aria-label="Précédent"><i class="fas fa-chevron-left"></i></button>
        <button id="modal-next" aria-label="Suivant"><i class="fas fa-chevron-right"></i></button>
      </div>
      <div id="modal-badge-wrap"></div>
    </div>

    <!-- Info side -->
    <div id="modal-info">
      <div id="modal-category"></div>
      <h2 id="modal-title"></h2>
      <div id="modal-price"></div>
      <p id="modal-desc"></p>
      <div id="modal-specs"></div>
      <div id="modal-actions">
        <a id="modal-wa" href="#" target="_blank" class="btn-primary">
          <i class="fab fa-whatsapp"></i>
          <span>Commander via WhatsApp</span>
        </a>
        <button id="modal-share" class="btn-outline">
          <i class="fas fa-share-nodes"></i>
          <span>Partager</span>
        </button>
      </div>
      <div id="modal-meta-row">
        <span><i class="fas fa-map-marker-alt"></i> Yaoundé, Cameroun</span>
        <span><i class="fas fa-shield-halved"></i> Vérifié NATURAFRIK</span>
        <span><i class="fas fa-truck"></i> Livraison disponible</span>
      </div>
    </div>

  </div>
</div>

<script src="/naturafrik/js/main.js"></script>
<?php if (defined('BASE') && BASE !== '/naturafrik') ob_end_flush(); ?>
</body>
</html>
