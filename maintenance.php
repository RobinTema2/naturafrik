<?php
http_response_code(503);
header('Retry-After: 3600');
?><!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Site en maintenance — NATURAFRIK</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;1,300;1,400&family=DM+Sans:opsz,wght@9..40,300;9..40,400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --gold:       #C8920A;
      --gold-light: #E8A820;
      --gold-pale:  #F5D78A;
      --ink:        #080604;
      --cream:      #F4EDE0;
      --serif:      'Cormorant Garamond', Georgia, serif;
      --sans:       'DM Sans', system-ui, sans-serif;
    }

    body {
      font-family: var(--sans);
      background: var(--ink);
      color: var(--cream);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 2rem;
      position: relative;
      overflow: hidden;
    }

    /* Fond radial doré */
    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background:
        radial-gradient(ellipse 80% 60% at 50% 0%, rgba(200,146,10,0.12) 0%, transparent 70%),
        radial-gradient(ellipse 60% 40% at 50% 100%, rgba(200,146,10,0.06) 0%, transparent 60%);
      pointer-events: none;
    }

    /* Grand texte NATURE en fond */
    body::after {
      content: 'NATURE';
      position: fixed;
      top: 50%; left: 50%;
      transform: translate(-50%, -50%);
      font-family: var(--serif);
      font-size: clamp(8rem, 25vw, 22rem);
      font-weight: 300;
      letter-spacing: 0.08em;
      color: rgba(200,146,10,0.04);
      white-space: nowrap;
      pointer-events: none;
      user-select: none;
    }

    .card {
      position: relative;
      z-index: 2;
      text-align: center;
      max-width: 620px;
      width: 100%;
    }

    .logo {
      font-family: var(--serif);
      font-size: clamp(1.8rem, 5vw, 2.8rem);
      font-weight: 300;
      letter-spacing: 0.15em;
      color: var(--cream);
      margin-bottom: 0.25rem;
    }
    .logo span { color: var(--gold-light); }

    .logo-sub {
      font-size: 0.6rem;
      letter-spacing: 0.4em;
      color: rgba(200,146,10,0.6);
      text-transform: uppercase;
      margin-bottom: 3rem;
    }

    .divider {
      width: 80px;
      height: 1px;
      background: linear-gradient(90deg, transparent, var(--gold-light), transparent);
      margin: 0 auto 3rem;
    }

    .icon-wrap {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      border: 1px solid rgba(200,146,10,0.3);
      background: rgba(200,146,10,0.06);
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 2rem;
      animation: pulse 3s ease-in-out infinite;
    }
    .icon-wrap i {
      font-size: 2rem;
      color: var(--gold-light);
    }
    @keyframes pulse {
      0%, 100% { box-shadow: 0 0 0 0 rgba(200,146,10,0.15); }
      50%       { box-shadow: 0 0 0 16px rgba(200,146,10,0); }
    }

    h1 {
      font-family: var(--serif);
      font-size: clamp(2.2rem, 6vw, 4rem);
      font-weight: 300;
      line-height: 1.15;
      color: var(--cream);
      margin-bottom: 0.5rem;
    }
    h1 em {
      font-style: italic;
      color: var(--gold-pale);
      display: block;
    }

    .subtitle {
      font-size: 0.82rem;
      letter-spacing: 0.25em;
      color: rgba(200,146,10,0.65);
      text-transform: uppercase;
      margin-bottom: 2rem;
    }

    p {
      font-size: 1rem;
      line-height: 1.8;
      color: rgba(244,237,224,0.6);
      margin-bottom: 2.5rem;
      max-width: 480px;
      margin-left: auto;
      margin-right: auto;
    }

    .contact-block {
      display: flex;
      flex-direction: column;
      gap: 0.75rem;
      align-items: center;
      margin-bottom: 2.5rem;
    }

    .contact-link {
      display: inline-flex;
      align-items: center;
      gap: 0.6rem;
      padding: 0.7rem 1.5rem;
      border: 1px solid rgba(200,146,10,0.25);
      border-radius: 100px;
      color: var(--cream);
      text-decoration: none;
      font-size: 0.85rem;
      transition: border-color 0.2s, background 0.2s, transform 0.2s;
    }
    .contact-link:hover {
      border-color: var(--gold-light);
      background: rgba(200,146,10,0.08);
      transform: translateY(-2px);
    }
    .contact-link i { color: var(--gold-light); }

    .contact-link.wa {
      background: rgba(37,211,102,0.08);
      border-color: rgba(37,211,102,0.3);
    }
    .contact-link.wa i { color: #25D366; }
    .contact-link.wa:hover {
      background: rgba(37,211,102,0.15);
      border-color: rgba(37,211,102,0.6);
    }

    .bottom-line {
      font-size: 0.65rem;
      letter-spacing: 0.2em;
      color: rgba(200,146,10,0.3);
      text-transform: uppercase;
    }
  </style>
</head>
<body>

<div class="card">

  <div class="logo">NATURA<span>FRIK</span></div>
  <div class="logo-sub">NATURCAM SARL · Groupe Nature Cameroun</div>

  <div class="divider"></div>

  <div class="icon-wrap">
    <i class="fas fa-tools"></i>
  </div>

  <h1>
    Site en maintenance
    <em>Nous revenons bientôt</em>
  </h1>

  <div class="subtitle">Amélioration en cours</div>

  <p>
    Notre site est temporairement indisponible pour des améliorations.
    Vous pouvez toujours nous contacter directement par WhatsApp ou par téléphone.
  </p>

  <div class="contact-block">
    <a href="https://wa.me/237691268428?text=Bonjour%20NATURAFRIK%2C%20je%20souhaite%20des%20informations." target="_blank" class="contact-link wa">
      <i class="fab fa-whatsapp"></i> +237 691 268 428
    </a>
    <a href="https://wa.me/237688620132" target="_blank" class="contact-link wa">
      <i class="fab fa-whatsapp"></i> +237 688 620 132
    </a>
    <a href="tel:+237698141070" class="contact-link">
      <i class="fas fa-phone"></i> +237 698 141 070
    </a>
  </div>

  <div class="bottom-line">NATURAFRIK · Cacao · Café · Immobilier · Cameroun</div>

</div>

</body>
</html>
