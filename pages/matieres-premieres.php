<?php
require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/functions.php';

$page_title = 'Matières Premières';
$meta_description = 'NATURAFRIK – Or fin, cuivre, fer, bronze, caoutchouc naturel du Cameroun. Matières premières certifiées, export international.';
$csrf = csrf_token();

require_once dirname(__DIR__) . '/includes/header.php';
?>

<!-- Page Hero -->
<section class="page-hero" style="height:50vh;min-height:420px;">
  <img src="/naturafrik/images/Or.jpg" alt="Matières premières Cameroun" class="page-hero-bg" style="filter:brightness(0.22)saturate(0.7);">
  <div class="page-hero-content">
    <div class="container">
      <div class="section-eyebrow" style="margin-bottom:1rem;" data-reveal>
        <span class="section-num" style="color:rgba(200,146,10,0.7);">MATIÈRES 1ÈRES</span>
        <div class="section-line"></div>
        <span class="t-label">Ressources du sous-sol camerounais</span>
      </div>
      <h1 class="t-title" data-reveal data-delay="1">
        Richesses minérales<br>
        <em style="font-style:italic;color:var(--gold-pale);">&amp; naturelles</em>
      </h1>
      <p style="max-width:460px;font-size:0.95rem;color:rgba(255,255,255,0.6);line-height:1.7;margin-top:1rem;" data-reveal data-delay="2">
        Or, cuivre, fer, bronze, caoutchouc — les sous-sols camerounais abritent des trésors. NATURAFRIK vous connecte aux meilleures sources, certifiées et documentées.
      </p>
    </div>
  </div>
</section>

<!-- ── Material ticker ── -->
<section style="padding:4rem 0;background:var(--ink);">
  <div class="container">
    <div class="section-eyebrow" style="margin-bottom:2rem;" data-reveal>
      <span class="section-num">01</span>
      <div class="section-line"></div>
      <span class="t-label">Notre catalogue</span>
    </div>
    <div class="materials-ticker" data-reveal data-delay="1">
      <div class="material-tick">
        <div class="material-icon-wrap" style="background:linear-gradient(135deg,#3A2A00,#1A1400);">
          <img src="/naturafrik/images/gold-bar.svg" alt="Or" style="width:30px;height:30px;filter:invert(1)opacity(0.85);">
        </div>
        <div class="material-tick-name">Or</div>
        <div class="material-tick-formula">Au · 999.9‰</div>
        <span class="badge-premium">PREMIUM</span>
      </div>
      <div class="material-tick">
        <div class="material-icon-wrap" style="background:linear-gradient(135deg,#3A1A08,#1E0E04);">
          <img src="/naturafrik/images/copper.svg" alt="Cuivre" style="width:30px;height:30px;filter:invert(1)opacity(0.85);">
        </div>
        <div class="material-tick-name">Cuivre</div>
        <div class="material-tick-formula">Cu · 99.9%</div>
        <span class="badge-standard">INDUSTRIEL</span>
      </div>
      <div class="material-tick">
        <div class="material-icon-wrap" style="background:linear-gradient(135deg,#2A1A0A,#140D04);">
          <img src="/naturafrik/images/Bronze.jpg" alt="Bronze" style="width:30px;height:30px;border-radius:4px;object-fit:cover;">
        </div>
        <div class="material-tick-name">Bronze</div>
        <div class="material-tick-formula">Cu–Sn · Alliage</div>
        <span class="badge-standard">ALLIAGE</span>
      </div>
      <div class="material-tick">
        <div class="material-icon-wrap" style="background:linear-gradient(135deg,#1A1A1A,#0A0A0A);">
          <i class="fas fa-industry" style="color:rgba(255,255,255,0.65);font-size:1.4rem;"></i>
        </div>
        <div class="material-tick-name">Fer</div>
        <div class="material-tick-formula">Fe · Minerai brut</div>
        <span class="badge-standard">BRUT</span>
      </div>
      <div class="material-tick" style="grid-column:span 1;">
        <div class="material-icon-wrap" style="background:linear-gradient(135deg,#0A1A0A,#050D05);">
          <i class="fas fa-tree" style="color:rgba(180,255,130,0.65);font-size:1.4rem;"></i>
        </div>
        <div class="material-tick-name">Caoutchouc</div>
        <div class="material-tick-formula">Hevea brasiliensis</div>
        <span class="badge-premium">NATUREL</span>
      </div>
    </div>
  </div>
</section>

<!-- ── Detailed material cards ── -->
<section style="padding:4rem 0 8rem;background:var(--ink-soft);">
  <div class="container">
    <div class="section-eyebrow" style="margin-bottom:1.5rem;" data-reveal>
      <span class="section-num">02</span>
      <div class="section-line"></div>
      <span class="t-label">Fiches produits</span>
    </div>
    <h2 class="t-title" style="margin-bottom:3rem;" data-reveal data-delay="1">Nos matières en détail</h2>

    <div class="matieres-grid" data-reveal>

      <!-- Or -->
      <div class="matiere-card matiere-gold">
        <div class="matiere-card-img-wrap">
          <img src="/naturafrik/images/Or.jpg" alt="Or fin" class="matiere-card-img">
          <div class="matiere-card-overlay">
            <img src="/naturafrik/images/gold-bar.svg" alt="" class="matiere-svg-icon">
          </div>
        </div>
        <div class="matiere-card-body">
          <div class="t-label" style="margin-bottom:0.4rem;">Métal précieux</div>
          <h3 class="material-detail-name">Or fin</h3>
          <p style="font-size:0.84rem;color:var(--text-muted);line-height:1.65;margin-bottom:1rem;">
            Or alluvionnaire et minier extrait au Cameroun, soumis à purification rigoureuse. Conforme LBMA pour l'export international.
          </p>
          <div class="material-detail-specs">
            <div class="spec-row"><span class="spec-key">Pureté</span><span class="spec-val">999.9‰ (4 nines)</span></div>
            <div class="spec-row"><span class="spec-key">Conditionnement</span><span class="spec-val">Lingots · Paillettes</span></div>
            <div class="spec-row"><span class="spec-key">Commande min.</span><span class="spec-val">100 g</span></div>
            <div class="spec-row"><span class="spec-key">Délai livraison</span><span class="spec-val">3–7 jours</span></div>
            <div class="spec-row"><span class="spec-key">Certification</span><span class="spec-val">LBMA / ITIE</span></div>
          </div>
          <a href="https://wa.me/237691268428?text=Demande%20de%20renseignement%20sur%20l%27or%20fin%20NATURAFRIK" target="_blank" class="btn-primary" style="margin-top:1.5rem;font-size:0.78rem;padding:0.65rem 1.4rem;">
            <i class="fab fa-whatsapp"></i> <span>Renseignements</span>
          </a>
        </div>
      </div>

      <!-- Cuivre -->
      <div class="matiere-card">
        <div class="matiere-card-img-wrap">
          <img src="/naturafrik/images/Cuivre.jpg" alt="Cuivre" class="matiere-card-img">
          <div class="matiere-card-overlay">
            <img src="/naturafrik/images/copper.svg" alt="" class="matiere-svg-icon">
          </div>
        </div>
        <div class="matiere-card-body">
          <div class="t-label" style="margin-bottom:0.4rem;">Métal industriel</div>
          <h3 class="material-detail-name">Cuivre</h3>
          <p style="font-size:0.84rem;color:var(--text-muted);line-height:1.65;margin-bottom:1rem;">
            Cuivre électrolytique de haute pureté, disponible en lingots ou bobines. Idéal pour l'industrie électrique, la plomberie et la construction.
          </p>
          <div class="material-detail-specs">
            <div class="spec-row"><span class="spec-key">Pureté</span><span class="spec-val">99.9% Cu</span></div>
            <div class="spec-row"><span class="spec-key">Conditionnement</span><span class="spec-val">Lingots · Bobines</span></div>
            <div class="spec-row"><span class="spec-key">Commande min.</span><span class="spec-val">500 kg</span></div>
            <div class="spec-row"><span class="spec-key">Délai livraison</span><span class="spec-val">7–14 jours</span></div>
          </div>
          <a href="https://wa.me/237691268428?text=Demande%20cuivre%20NATURAFRIK" target="_blank" class="btn-product" style="margin-top:1.5rem;">
            Demande de devis <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>

      <!-- Bronze -->
      <div class="matiere-card">
        <div class="matiere-card-img-wrap">
          <img src="/naturafrik/images/Bronze.jpg" alt="Bronze" class="matiere-card-img">
          <div class="matiere-card-overlay">
            <i class="fas fa-medal matiere-fa-icon"></i>
          </div>
        </div>
        <div class="matiere-card-body">
          <div class="t-label" style="margin-bottom:0.4rem;">Alliage</div>
          <h3 class="material-detail-name">Bronze</h3>
          <p style="font-size:0.84rem;color:var(--text-muted);line-height:1.65;margin-bottom:1rem;">
            Alliage cuivre-étain de haute qualité, apprécié pour sa résistance à la corrosion et ses applications décoratives et mécaniques.
          </p>
          <div class="material-detail-specs">
            <div class="spec-row"><span class="spec-key">Composition</span><span class="spec-val">Cu 88% – Sn 12%</span></div>
            <div class="spec-row"><span class="spec-key">Conditionnement</span><span class="spec-val">Lingots · Billettes</span></div>
            <div class="spec-row"><span class="spec-key">Commande min.</span><span class="spec-val">200 kg</span></div>
            <div class="spec-row"><span class="spec-key">Application</span><span class="spec-val">Mécanique · Art · Bâtiment</span></div>
          </div>
          <a href="https://wa.me/237691268428?text=Demande%20bronze%20NATURAFRIK" target="_blank" class="btn-product" style="margin-top:1.5rem;">
            Demande de devis <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>

      <!-- Fer -->
      <div class="matiere-card">
        <div class="matiere-card-img-wrap">
          <img src="/naturafrik/images/Fer.jpg" alt="Minerai de fer" class="matiere-card-img">
          <div class="matiere-card-overlay">
            <i class="fas fa-industry matiere-fa-icon"></i>
          </div>
        </div>
        <div class="matiere-card-body">
          <div class="t-label" style="margin-bottom:0.4rem;">Minerai</div>
          <h3 class="material-detail-name">Fer</h3>
          <p style="font-size:0.84rem;color:var(--text-muted);line-height:1.65;margin-bottom:1rem;">
            Minerai de fer camerounais à haute teneur, destiné à la sidérurgie et la métallurgie. Extraction responsable et certifiée.
          </p>
          <div class="material-detail-specs">
            <div class="spec-row"><span class="spec-key">Teneur Fe</span><span class="spec-val">65–68%</span></div>
            <div class="spec-row"><span class="spec-key">Conditionnement</span><span class="spec-val">Vrac · Sacs bigbag</span></div>
            <div class="spec-row"><span class="spec-key">Commande min.</span><span class="spec-val">1 tonne</span></div>
            <div class="spec-row"><span class="spec-key">Transport</span><span class="spec-val">Route / Conteneur</span></div>
          </div>
          <a href="https://wa.me/237691268428?text=Demande%20minerai%20de%20fer%20NATURAFRIK" target="_blank" class="btn-product" style="margin-top:1.5rem;">
            Demande de devis <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>

      <!-- Caoutchouc -->
      <div class="matiere-card">
        <div class="matiere-card-img-wrap">
          <img src="/naturafrik/images/caoutchouc.png" alt="Caoutchouc naturel" class="matiere-card-img">
          <div class="matiere-card-overlay">
            <i class="fas fa-tree matiere-fa-icon" style="color:rgba(140,255,100,0.75);"></i>
          </div>
        </div>
        <div class="matiere-card-body">
          <div class="t-label" style="margin-bottom:0.4rem;">Matière naturelle</div>
          <h3 class="material-detail-name">Caoutchouc</h3>
          <p style="font-size:0.84rem;color:var(--text-muted);line-height:1.65;margin-bottom:1rem;">
            Latex naturel d'Hevea brasiliensis des plantations camerounaises. Coagulé ou en feuilles fumées RSS, certifié qualité export.
          </p>
          <div class="material-detail-specs">
            <div class="spec-row"><span class="spec-key">Grade</span><span class="spec-val">RSS1 / TSR20</span></div>
            <div class="spec-row"><span class="spec-key">Conditionnement</span><span class="spec-val">Balles 33 kg</span></div>
            <div class="spec-row"><span class="spec-key">Commande min.</span><span class="spec-val">1 tonne</span></div>
            <div class="spec-row"><span class="spec-key">Certification</span><span class="spec-val">ISO 2000/2002</span></div>
          </div>
          <a href="https://wa.me/237691268428?text=Demande%20caoutchouc%20naturel%20NATURAFRIK" target="_blank" class="btn-product" style="margin-top:1.5rem;">
            Demande de devis <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>

    </div><!-- /matieres-grid -->

    <!-- Summary table -->
    <div style="margin-top:5rem;" data-reveal>
      <div class="section-eyebrow" style="margin-bottom:1.5rem;">
        <span class="section-num">03</span>
        <div class="section-line"></div>
        <span class="t-label">Récapitulatif commandes</span>
      </div>
      <div style="overflow-x:auto;border:1px solid var(--border);border-radius:var(--r-md);">
        <table style="width:100%;border-collapse:collapse;font-size:0.88rem;min-width:600px;">
          <thead>
            <tr style="background:var(--surface);border-bottom:2px solid var(--gold);">
              <th style="padding:1rem 1.25rem;text-align:left;font-size:0.6rem;letter-spacing:0.2em;color:var(--gold);font-weight:700;">MATIÈRE</th>
              <th style="padding:1rem 1.25rem;text-align:left;font-size:0.6rem;letter-spacing:0.2em;color:var(--gold);font-weight:700;">SYMBOLE</th>
              <th style="padding:1rem 1.25rem;text-align:left;font-size:0.6rem;letter-spacing:0.2em;color:var(--gold);font-weight:700;">PURETÉ / GRADE</th>
              <th style="padding:1rem 1.25rem;text-align:left;font-size:0.6rem;letter-spacing:0.2em;color:var(--gold);font-weight:700;">COMMANDE MIN.</th>
              <th style="padding:1rem 1.25rem;text-align:left;font-size:0.6rem;letter-spacing:0.2em;color:var(--gold);font-weight:700;">DÉLAI</th>
              <th style="padding:1rem 1.25rem;font-size:0.6rem;letter-spacing:0.2em;color:var(--gold);font-weight:700;text-align:center;">ACTION</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $tableData = [
              ['Or','Au','999.9‰','100 g','3–7 jours'],
              ['Cuivre','Cu','99.9%','500 kg','7–14 jours'],
              ['Bronze','Cu–Sn','Grade A','200 kg','10–14 jours'],
              ['Fer','Fe','65–68%','1 tonne','14–21 jours'],
              ['Caoutchouc','C₅H₈','RSS1/TSR20','1 tonne','14–21 jours'],
            ];
            foreach ($tableData as $i => $row): ?>
            <tr style="border-bottom:1px solid var(--border);<?= $i % 2 === 0 ? 'background:var(--surface)' : 'background:var(--card)' ?>">
              <td style="padding:1rem 1.25rem;color:var(--cream);font-weight:600;"><?= $row[0] ?></td>
              <td style="padding:1rem 1.25rem;color:var(--gold-pale);font-family:monospace;letter-spacing:0.1em;"><?= $row[1] ?></td>
              <td style="padding:1rem 1.25rem;color:var(--text);"><?= $row[2] ?></td>
              <td style="padding:1rem 1.25rem;color:var(--text);"><?= $row[3] ?></td>
              <td style="padding:1rem 1.25rem;color:var(--text);"><?= $row[4] ?></td>
              <td style="padding:1rem 1.25rem;text-align:center;">
                <a href="https://wa.me/237691268428?text=<?= urlencode('Demande: ' . $row[0]) ?>" target="_blank" class="btn-product" style="font-size:0.72rem;padding:0.4rem 0.9rem;">
                  <i class="fab fa-whatsapp"></i> Contacter
                </a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Export capabilities -->
    <div class="matieres-export-grid" style="margin-top:3.5rem;" data-reveal>
      <div class="matieres-export-item">
        <div class="matieres-export-icon"><i class="fas fa-globe-africa"></i></div>
        <div class="material-detail-name" style="font-size:1.1rem;margin-bottom:0.3rem;">Export international</div>
        <div style="font-size:0.8rem;color:var(--text-dim);">Expédition vers l'Europe, l'Asie et les Amériques</div>
      </div>
      <div class="matieres-export-item">
        <div class="matieres-export-icon"><i class="fas fa-certificate"></i></div>
        <div class="material-detail-name" style="font-size:1.1rem;margin-bottom:0.3rem;">Documentés</div>
        <div style="font-size:0.8rem;color:var(--text-dim);">Certificats d'origine, factures pro-forma, SGS</div>
      </div>
      <div class="matieres-export-item">
        <div class="matieres-export-icon"><i class="fas fa-handshake"></i></div>
        <div class="material-detail-name" style="font-size:1.1rem;margin-bottom:0.3rem;">Partenariat durable</div>
        <div style="font-size:0.8rem;color:var(--text-dim);">Contrats long terme, tarifs préférentiels</div>
      </div>
    </div>

  </div>
</section>

<style>
.materials-ticker { grid-template-columns: repeat(5, 1fr); }
@media (max-width: 900px) { .materials-ticker { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 600px) { .materials-ticker { grid-template-columns: repeat(2, 1fr); } }

.matieres-grid {
  display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem;
}
.matieres-grid .matiere-card:nth-child(1) { grid-column: span 1; }
@media (max-width: 1100px) { .matieres-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 680px) { .matieres-grid { grid-template-columns: 1fr; } }

.matiere-card {
  background: var(--card); border: 1px solid var(--border); border-radius: var(--r-lg);
  overflow: hidden; transition: border-color 0.35s, transform 0.35s, box-shadow 0.35s;
}
.matiere-card:hover { border-color: rgba(150,112,8,0.4); transform: translateY(-5px); box-shadow: var(--shadow-hover); }
.matiere-gold { border-color: rgba(200,146,10,0.28); }
.matiere-card-img-wrap { position: relative; height: 200px; overflow: hidden; }
.matiere-card-img { width: 100%; height: 100%; object-fit: cover; filter: brightness(0.55) saturate(0.8); transition: filter 0.4s, transform 0.5s; }
.matiere-card:hover .matiere-card-img { filter: brightness(0.75) saturate(1); transform: scale(1.06); }
.matiere-card-overlay {
  position: absolute; inset: 0; display: flex; align-items: flex-end; padding: 1.25rem;
  background: linear-gradient(to top, rgba(0,0,0,0.55) 0%, transparent 60%);
}
.matiere-svg-icon { width: 40px; height: 40px; filter: invert(1) opacity(0.75); }
.matiere-fa-icon { font-size: 2rem; color: rgba(255,255,255,0.65); }
.matiere-card-body { padding: 1.75rem; }

.matieres-export-grid {
  display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem;
  background: var(--card); border: 1px solid var(--border);
  border-radius: var(--r-lg); overflow: hidden;
}
.matieres-export-item {
  padding: 2.5rem 2rem; text-align: center;
  border-right: 1px solid var(--border);
}
.matieres-export-item:last-child { border-right: none; }
.matieres-export-icon {
  font-size: 1.8rem; color: var(--gold); margin-bottom: 0.75rem;
}
@media (max-width: 768px) {
  .matieres-export-grid { grid-template-columns: 1fr; }
  .matieres-export-item { border-right: none; border-bottom: 1px solid var(--border); }
  .matieres-export-item:last-child { border-bottom: none; }
}
</style>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
