<?php
require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/functions.php';

$page_title = 'Contact & Commande';
$meta_description = 'Contactez NATURAFRIK – NATURCAM Sarl à Yaoundé, Cameroun. Téléphone, WhatsApp, formulaire de contact.';
$csrf = csrf_token();

require_once dirname(__DIR__) . '/includes/header.php';
?>

<!-- Page Hero -->
<section class="page-hero" style="height:35vh;min-height:320px;">
  <div class="page-hero-content">
    <div class="container">
      <div class="section-eyebrow" style="margin-bottom:1rem;">
        <span class="section-num" style="color:rgba(200,146,10,0.7);">CONTACT</span>
        <div class="section-line"></div>
        <span class="t-label">Parlons de votre projet</span>
      </div>
      <h1 class="t-title" data-reveal>Contactez-nous</h1>
    </div>
  </div>
  <div style="position:absolute;inset:0;background:linear-gradient(135deg,#1A0A00,#0A0500);z-index:0;"></div>
</section>

<section style="padding:5rem 0 8rem;">
  <div class="container">
    <div class="contact-grid">

      <!-- Contact Form -->
      <div data-reveal>
        <div class="section-eyebrow" style="margin-bottom:1rem;">
          <span class="t-label">Formulaire</span>
        </div>
        <h2 class="t-title" style="font-size:2rem;margin-bottom:2rem;">Envoyez-nous un message</h2>

        <form id="contact-form">
          <input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">
          <div class="contact-form-group">
            <label>NOM COMPLET *</label>
            <input type="text" name="name" class="contact-input" placeholder="Votre nom et prénom" required>
          </div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
            <div class="contact-form-group">
              <label>EMAIL *</label>
              <input type="email" name="email" class="contact-input" placeholder="votre@email.com" required>
            </div>
            <div class="contact-form-group">
              <label>TÉLÉPHONE</label>
              <input type="tel" name="phone" class="contact-input" placeholder="+237 xxx xxx xxx">
            </div>
          </div>
          <div class="contact-form-group">
            <label>SUJET *</label>
            <select name="subject" class="contact-input" required>
              <option value="">Choisir un sujet…</option>
              <option>Commande de produits (cacao/café/avocat)</option>
              <option>Agriculture & Élevage</option>
              <option>Immobilier</option>
              <option>Matières premières</option>
              <option>Partenariat commercial</option>
              <option>Autre demande</option>
            </select>
          </div>
          <div class="contact-form-group">
            <label>MESSAGE *</label>
            <textarea name="message" class="contact-input" rows="5" placeholder="Décrivez votre besoin en détail…" required></textarea>
          </div>
          <button type="submit" class="btn-primary" style="width:100%;justify-content:center;font-size:0.9rem;">
            <i class="fas fa-paper-plane"></i> <span>Envoyer le message</span>
          </button>
        </form>
      </div><!-- /form -->

      <!-- Contact Info -->
      <div data-reveal data-delay="2">
        <div class="section-eyebrow" style="margin-bottom:1rem;">
          <span class="t-label">Informations</span>
        </div>
        <h2 class="t-title" style="font-size:2rem;margin-bottom:2rem;">Nous trouver</h2>

        <div class="contact-info-card">
          <div class="contact-info-icon"><i class="fas fa-map-marker-alt"></i></div>
          <div>
            <div class="contact-info-title">ADRESSE</div>
            <div class="contact-info-val">BP 3005, Yaoundé-Cameroun<br>Montée Anne Rouge</div>
          </div>
        </div>

        <div class="contact-info-card">
          <div class="contact-info-icon"><i class="fas fa-phone"></i></div>
          <div>
            <div class="contact-info-title">TÉLÉPHONE</div>
            <div class="contact-info-val">
              <a href="tel:+237698141070" style="color:var(--cream);display:block;">+237 698 141 070</a>
              <a href="tel:+237691268428" style="color:var(--cream);display:block;">+237 691 268 428</a>
              <a href="tel:+237688620132" style="color:var(--cream);display:block;">+237 688 620 132</a>
              <a href="tel:+237242058078" style="color:var(--cream);display:block;">+237 242 058 078</a>
            </div>
          </div>
        </div>

        <div class="contact-info-card">
          <div class="contact-info-icon" style="background:rgba(37,211,102,0.15);border-color:rgba(37,211,102,0.3);color:#25D366;"><i class="fab fa-whatsapp"></i></div>
          <div>
            <div class="contact-info-title">WHATSAPP</div>
            <div class="contact-info-val">
              <a href="https://wa.me/237691268428" target="_blank" style="color:#25D366;display:block;">+237 691 268 428</a>
              <a href="https://wa.me/237688620132" target="_blank" style="color:#25D366;display:block;">+237 688 620 132</a>
            </div>
          </div>
        </div>


        <div class="contact-info-card">
          <div class="contact-info-icon"><i class="fas fa-university"></i></div>
          <div>
            <div class="contact-info-title">COMPTES BANCAIRES</div>
            <div class="contact-info-val" style="font-size:0.82rem;">
              <strong style="color:var(--gold);display:block;margin-bottom:0.25rem;">B.G.F.I. Bank</strong>
              CM21 10035012004001776701179<br>
              <strong style="color:var(--gold);display:block;margin:0.4rem 0 0.25rem;">Afriland First Bank</strong>
              CM21 10005 00038 09295421001 80
            </div>
          </div>
        </div>

        <!-- Quick WhatsApp button -->
        <a href="https://wa.me/237691268428?text=Bonjour%20NATURAFRIK%2C%20j%27ai%20une%20demande%20sp%C3%A9ciale." target="_blank"
           style="display:flex;align-items:center;justify-content:center;gap:0.75rem;width:100%;padding:1rem;background:#25D366;color:white;font-weight:700;font-size:0.9rem;letter-spacing:0.1em;border-radius:var(--r-full);margin-top:1rem;transition:transform 0.2s,box-shadow 0.2s;"
           onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 10px 30px rgba(37,211,102,0.4)'"
           onmouseout="this.style.transform='';this.style.boxShadow=''">
          <i class="fab fa-whatsapp" style="font-size:1.2rem;"></i>
          Discuter sur WhatsApp
        </a>

        <!-- Legal info -->
        <div style="margin-top:2rem;padding:1.25rem;background:var(--surface);border:1px solid var(--border);border-radius:var(--r-md);">
          <div class="t-label" style="margin-bottom:0.75rem;">NATURCAM SARL — Infos légales</div>
          <div style="font-size:0.78rem;color:var(--text-dim);line-height:1.8;">
            <div>RCCM: <strong style="color:var(--cream);">RC/YAO/2023/B/519</strong></div>
            <div>NIU: <strong style="color:var(--cream);">M032318076551C</strong></div>
            <div>Groupe Nature Cameroun Sarl</div>
            <div>Imaginaton · Performance · Qualité</div>
          </div>
        </div>

      </div><!-- /info -->
    </div><!-- /contact-grid -->
  </div>
</section>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
