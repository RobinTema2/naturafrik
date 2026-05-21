/* ═══════════════════════════════════════════════════════
   NATURAFRIK — Main JS v2
   Premium animations & interactions
   ═══════════════════════════════════════════════════════ */

/* ── Page Loader ── */
window.addEventListener('load', () => {
  setTimeout(() => {
    const loader = document.getElementById('page-loader');
    if (loader) loader.classList.add('hidden');
  }, 2200);
});

/* ── Navbar scroll ── */
const nav = document.getElementById('nav');
if (nav) {
  const onScroll = () => {
    nav.classList.toggle('scrolled', window.scrollY > 60);
  };
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();
}

/* ── Mobile menu ── */
const hamburger = document.getElementById('hamburger');
const mobileMenu = document.getElementById('mobile-menu');
if (hamburger && mobileMenu) {
  hamburger.addEventListener('click', () => {
    hamburger.classList.toggle('open');
    mobileMenu.classList.toggle('open');
    document.body.style.overflow = mobileMenu.classList.contains('open') ? 'hidden' : '';
  });
  mobileMenu.querySelectorAll('a').forEach(a => {
    a.addEventListener('click', () => {
      hamburger.classList.remove('open');
      mobileMenu.classList.remove('open');
      document.body.style.overflow = '';
    });
  });
}

/* ── Scroll Reveal ── */
const revealObserver = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('revealed');
      revealObserver.unobserve(entry.target);
    }
  });
}, { threshold: 0.12, rootMargin: '0px 0px -60px 0px' });

document.querySelectorAll('[data-reveal]').forEach(el => revealObserver.observe(el));

/* ── Counter Animation ── */
function easeOutQuart(t) { return 1 - Math.pow(1 - t, 4); }

function animateCounter(el) {
  const target = parseFloat(el.dataset.count);
  const suffix = el.dataset.suffix || '';
  const duration = 2000;
  const start = performance.now();

  function update(now) {
    const elapsed = Math.min((now - start) / duration, 1);
    const val = target * easeOutQuart(elapsed);
    el.textContent = (Number.isInteger(target) ? Math.round(val) : val.toFixed(1)) + suffix;
    if (elapsed < 1) requestAnimationFrame(update);
  }
  requestAnimationFrame(update);
}

const counterObserver = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      animateCounter(entry.target);
      counterObserver.unobserve(entry.target);
    }
  });
}, { threshold: 0.5 });

document.querySelectorAll('[data-count]').forEach(el => counterObserver.observe(el));

/* ── Back to Top ── */
const backTop = document.getElementById('back-to-top');
if (backTop) {
  window.addEventListener('scroll', () => {
    backTop.classList.toggle('visible', window.scrollY > 500);
  }, { passive: true });
  backTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
}

/* ── Toast ── */
function showToast(message, type = 'success') {
  const container = document.getElementById('toast-container');
  if (!container) return;
  const toast = document.createElement('div');
  toast.className = `toast ${type}`;
  const icon = type === 'success' ? 'fa-check-circle' : 'fa-times-circle';
  toast.innerHTML = `<i class="fas ${icon} toast-icon"></i><span>${message}</span>`;
  container.appendChild(toast);
  requestAnimationFrame(() => {
    requestAnimationFrame(() => toast.classList.add('show'));
  });
  setTimeout(() => {
    toast.classList.remove('show');
    setTimeout(() => toast.remove(), 450);
  }, 4000);
}
window.showToast = showToast;

/* ── Filter Buttons ── */
function applyFilter(filter) {
  const grids = document.querySelectorAll('.products-grid, .bento-grid');
  grids.forEach(grid => {
    grid.querySelectorAll('[data-category]').forEach(card => {
      const show = filter === 'all' || card.dataset.category === filter;
      card.style.transition = 'opacity 0.3s, transform 0.3s';
      if (show) {
        card.style.opacity = '1';
        card.style.transform = '';
        card.style.display = '';
      } else {
        card.style.opacity = '0';
        card.style.transform = 'scale(0.96)';
        setTimeout(() => { if (card.style.opacity === '0') card.style.display = 'none'; }, 300);
      }
    });
  });
}

document.querySelectorAll('.filter-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    const bar = btn.closest('.filter-bar');
    if (bar) bar.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    applyFilter(btn.dataset.filter);
  });
});

document.querySelectorAll('.immo-type-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.immo-type-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    const filterBar = document.querySelector('.filter-bar');
    if (filterBar) {
      const match = filterBar.querySelector(`.filter-btn[data-filter="${btn.dataset.filter}"]`);
      if (match) {
        filterBar.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        match.classList.add('active');
      }
    }
    applyFilter(btn.dataset.filter);
  });
});

/* ── Newsletter AJAX ── */
const newsletterForm = document.getElementById('newsletter-form');
if (newsletterForm) {
  newsletterForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = newsletterForm.querySelector('button[type="submit"]');
    const original = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    btn.disabled = true;
    try {
      const res = await fetch('/naturafrik/api/newsletter.php', {
        method: 'POST',
        body: new FormData(newsletterForm)
      });
      const data = await res.json();
      showToast(data.message, data.success ? 'success' : 'error');
      if (data.success) newsletterForm.reset();
    } catch {
      showToast('Erreur de connexion.', 'error');
    } finally {
      btn.innerHTML = original;
      btn.disabled = false;
    }
  });
}

/* ── Contact AJAX ── */
const contactForm = document.getElementById('contact-form');
if (contactForm) {
  contactForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = contactForm.querySelector('button[type="submit"]');
    const original = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Envoi…';
    btn.disabled = true;
    try {
      const res = await fetch('/naturafrik/api/contact.php', {
        method: 'POST',
        body: new FormData(contactForm)
      });
      const data = await res.json();
      showToast(data.message, data.success ? 'success' : 'error');
      if (data.success) contactForm.reset();
    } catch {
      showToast('Erreur de connexion.', 'error');
    } finally {
      btn.innerHTML = original;
      btn.disabled = false;
    }
  });
}

/* ── Smooth Scroll on anchor links ── */
document.querySelectorAll('a[href^="#"]').forEach(a => {
  a.addEventListener('click', e => {
    const target = document.querySelector(a.getAttribute('href'));
    if (target) {
      e.preventDefault();
      target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  });
});

/* ── Parallax Hero Image ── */
const heroImg = document.querySelector('.hero-img');
if (heroImg) {
  window.addEventListener('scroll', () => {
    const y = window.scrollY;
    if (y < window.innerHeight) {
      heroImg.style.transform = `scale(1.06) translateY(${y * 0.15}px)`;
    }
  }, { passive: true });
}

/* ── Card Tilt (subtle 3D) ── */
document.querySelectorAll('.product-card, .testi-card, .material-detail-card').forEach(card => {
  card.addEventListener('mousemove', e => {
    const rect = card.getBoundingClientRect();
    const x = (e.clientX - rect.left) / rect.width - 0.5;
    const y = (e.clientY - rect.top) / rect.height - 0.5;
    card.style.transform = `perspective(800px) rotateY(${x * 6}deg) rotateX(${-y * 6}deg) translateY(-6px)`;
  });
  card.addEventListener('mouseleave', () => {
    card.style.transform = '';
    card.style.transition = 'transform 0.5s ease';
    setTimeout(() => { card.style.transition = ''; }, 500);
  });
});

/* ── Image Lazy Load ── */
const imgObserver = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      const img = entry.target;
      if (img.dataset.src) { img.src = img.dataset.src; img.removeAttribute('data-src'); }
      imgObserver.unobserve(img);
    }
  });
}, { rootMargin: '200px' });
document.querySelectorAll('img[data-src]').forEach(img => imgObserver.observe(img));

/* ── Active nav link ── */
const currentPath = window.location.pathname;
document.querySelectorAll('.nav-links a').forEach(a => {
  const href = a.getAttribute('href');
  if (href && (currentPath.endsWith(href) || currentPath.includes(href.replace(/\.php$/, '')))) {
    a.classList.add('active');
  }
});

/* ══════════════════════════════════════════════════
   PRODUCT MODAL — Facebook Marketplace style
   ══════════════════════════════════════════════════ */
(function () {
  const modal      = document.getElementById('product-modal');
  const backdrop   = document.getElementById('modal-backdrop');
  const panel      = document.getElementById('modal-panel');
  const closeBtn   = document.getElementById('modal-close');
  const modalImg   = document.getElementById('modal-img');
  const modalBadge = document.getElementById('modal-badge-wrap');
  const modalCat   = document.getElementById('modal-category');
  const modalTitle = document.getElementById('modal-title');
  const modalPrice = document.getElementById('modal-price');
  const modalDesc  = document.getElementById('modal-desc');
  const modalSpecs = document.getElementById('modal-specs');
  const modalWA    = document.getElementById('modal-wa');
  const modalShare = document.getElementById('modal-share');
  const prevBtn    = document.getElementById('modal-prev');
  const nextBtn    = document.getElementById('modal-next');

  if (!modal) return;

  /* ---------- image gallery inside modal ---------- */
  let galleryImgs = [];
  let galleryIdx  = 0;

  function showGalleryImg(idx) {
    if (!galleryImgs.length) return;
    galleryIdx = Math.max(0, Math.min(idx, galleryImgs.length - 1));
    modalImg.classList.add('loading');
    const newSrc = galleryImgs[galleryIdx];
    const tmp = new Image();
    tmp.onload = () => { modalImg.src = newSrc; modalImg.classList.remove('loading'); };
    tmp.onerror = () => { modalImg.src = newSrc; modalImg.classList.remove('loading'); };
    tmp.src = newSrc;
    prevBtn.classList.toggle('hidden', galleryImgs.length <= 1 || galleryIdx === 0);
    nextBtn.classList.toggle('hidden', galleryImgs.length <= 1 || galleryIdx === galleryImgs.length - 1);
  }

  prevBtn.addEventListener('click', () => showGalleryImg(galleryIdx - 1));
  nextBtn.addEventListener('click', () => showGalleryImg(galleryIdx + 1));

  /* ---------- extra specs per category ---------- */
  const SPECS = {
    cacao:    [['Région','Région Ouest'],['Altitude','1000–1500 m'],['Variété','Trinitario'],['Torréfaction','Légère artisanale'],['Poids','500 g'],['Notes','Chocolat · Floral']],
    cafe:     [['Altitude','1200–1600 m'],['Variété','Arabica pur'],['Process','Lavé'],['Torréfaction','Médium–légère'],['Poids','250 g'],['Notes','Caramel · Fruits']],
    volaille: [['Élevage','Plein air'],['Alimentation','Naturelle'],['Traitement','Sans hormones'],['Livraison','Vivant / Abattu']],
    porc:     [['Race','Locale améliorée'],['Alimentation','Naturelle'],['Poids','80–200 kg'],['Traitement','Sans antibiotiques']],
    bovin:    [['Race','Locale'],['Poids','200–300 kg'],['Élevage','Extensif']],
    caprin:   [['Race','Naine de Guinée / Sahel'],['Usage','Lait & Viande'],['Élevage','Semi-extensif']],
    cereale:  [['Origine','Cameroun'],['Conservation','12 mois'],['Conditionnement','Sacs 50 kg']],
    tubercule:[['Origine','Cameroun'],['Récolte','Fraîche'],['Livraison','Sous 48h']],
    maison:   [['Localisation','Yaoundé, Cameroun'],['Titre','Foncier disponible'],['État','Très bon'],['Finitions','Haut de gamme']],
    terrain:  [['Localisation','Yaoundé, Cameroun'],['Viabilisation','Oui'],['Titre','Foncier']],
    location: [['Type','Meublé'],['Charges','Incluses'],['Disponibilité','Immédiate']],
    boutique: [['Surface','45–80 m²'],['Passage','Fort trafic'],['Bail','Professionnel']],
    entrepot: [['Accès','Poids lourds'],['Surface','200 m²'],['Gardiennage','Inclus']],
    avocat:   [['Culture','Sans pesticides'],['Récolte','Fraîche'],['Min. commande','5 kg']],
  };

  function buildSpecs(category, extraHTML) {
    const rows = SPECS[category] || [];
    if (!rows.length && !extraHTML) { modalSpecs.style.display = 'none'; return; }
    modalSpecs.style.display = '';
    modalSpecs.innerHTML = rows.map(([k, v]) =>
      `<div class="modal-spec"><div class="modal-spec-key">${k}</div><div class="modal-spec-val">${v}</div></div>`
    ).join('') + (extraHTML || '');
  }

  /* ---------- open modal ---------- */
  function openModal(card) {
    /* --- collect data from the card --- */
    const imgEl   = card.querySelector('.product-card-img img');
    const nameEl  = card.querySelector('.product-name');
    const descEl  = card.querySelector('.product-desc');
    const priceEl = card.querySelector('.product-price');
    const badgeEl = card.querySelector('.product-badge');
    const waEl    = card.querySelector('a[href*="wa.me"]');
    const category = card.dataset.category || '';

    /* image: try to find siblings in same grid for gallery */
    const allCards = Array.from(document.querySelectorAll('.product-card'));
    const sameGroup = allCards.filter(c => c.dataset.category === category || category === '');
    galleryImgs = sameGroup
      .map(c => c.querySelector('.product-card-img img')?.src)
      .filter(Boolean);
    galleryIdx = sameGroup.indexOf(card);
    if (galleryImgs.length < 2) galleryImgs = imgEl ? [imgEl.src] : [];

    /* populate */
    modalImg.src = imgEl ? imgEl.src : '';
    modalImg.alt = nameEl ? nameEl.textContent : '';
    prevBtn.classList.toggle('hidden', galleryImgs.length <= 1 || galleryIdx === 0);
    nextBtn.classList.toggle('hidden', galleryImgs.length <= 1 || galleryIdx >= galleryImgs.length - 1);

    modalBadge.innerHTML = badgeEl
      ? `<span class="modal-badge">${badgeEl.innerHTML}</span>`
      : '';

    const catLabels = {
      cacao:'Produit naturel · Cacao', cafe:'Produit naturel · Café',
      avocat:'Produit naturel · Avocat', volaille:'Agriculture · Élevage',
      porc:'Agriculture · Élevage', bovin:'Agriculture · Élevage',
      caprin:'Agriculture · Élevage', cereale:'Agriculture · Cultures',
      tubercule:'Agriculture · Cultures', maison:'Immobilier · Vente',
      terrain:'Immobilier · Terrain', location:'Immobilier · Location',
      boutique:'Immobilier · Commercial', entrepot:'Immobilier · Entrepôt',
    };
    modalCat.textContent = catLabels[category] || 'NATURAFRIK';
    modalTitle.textContent = nameEl ? nameEl.textContent.trim() : 'Produit';
    modalPrice.innerHTML  = priceEl ? priceEl.innerHTML : '';
    modalDesc.textContent = descEl  ? descEl.textContent.trim() : '';
    buildSpecs(category, null);

    /* WhatsApp link */
    if (waEl) {
      modalWA.href = waEl.href;
    } else {
      const txt = encodeURIComponent('Bonjour NATURAFRIK, je suis intéressé par : ' + (nameEl ? nameEl.textContent.trim() : 'ce produit'));
      modalWA.href = `https://wa.me/237680209435?text=${txt}`;
    }

    /* open */
    document.body.style.overflow = 'hidden';
    modal.classList.add('open');
    setTimeout(() => closeBtn.focus(), 50);
  }

  function closeModal() {
    modal.classList.remove('open');
    document.body.style.overflow = '';
  }

  /* ---------- share ---------- */
  modalShare.addEventListener('click', () => {
    const name = modalTitle.textContent;
    if (navigator.share) {
      navigator.share({ title: name, text: `Découvrez "${name}" sur NATURAFRIK`, url: window.location.href });
    } else {
      navigator.clipboard.writeText(window.location.href).then(() => {
        showToast('Lien copié !', 'success');
      });
    }
  });

  /* ---------- close triggers ---------- */
  closeBtn.addEventListener('click', closeModal);
  backdrop.addEventListener('click', closeModal);
  document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
  document.addEventListener('keydown', e => {
    if (!modal.classList.contains('open')) return;
    if (e.key === 'ArrowLeft')  showGalleryImg(galleryIdx - 1);
    if (e.key === 'ArrowRight') showGalleryImg(galleryIdx + 1);
  });

  /* ---------- attach click to all product cards ---------- */
  function attachModalToCards() {
    document.querySelectorAll('.product-card').forEach(card => {
      if (card.dataset.modalBound) return;
      card.dataset.modalBound = '1';
      card.addEventListener('click', e => {
        /* don't open if user clicked a link/button inside the card */
        if (e.target.closest('a, button')) return;
        openModal(card);
      });
    });
  }

  attachModalToCards();

  /* re-attach when filter changes (cards shown/hidden) */
  const filterBtns = document.querySelectorAll('.filter-btn, .immo-type-btn');
  filterBtns.forEach(btn => btn.addEventListener('click', () => {
    setTimeout(attachModalToCards, 350);
  }));

})();

/* ── Text Reveal on hero (char by char) ── */
function revealChars(el) {
  if (!el) return;
  const text = el.textContent;
  el.innerHTML = text.split('').map(c =>
    `<span style="display:inline-block;opacity:0;transform:translateY(20px);transition:opacity 0.4s,transform 0.4s">${c === ' ' ? '&nbsp;' : c}</span>`
  ).join('');
  el.querySelectorAll('span').forEach((span, i) => {
    setTimeout(() => {
      span.style.opacity = '1';
      span.style.transform = 'translateY(0)';
    }, 100 + i * 35);
  });
}
setTimeout(() => revealChars(document.querySelector('.hero-heading strong')), 2300);
