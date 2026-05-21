<?php
require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_admin_login();

$error   = '';
$success = '';

// Charger le profil
$user = null;
try {
    $pdo = db();
    if ($pdo) {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE id=?');
        $stmt->execute([$_SESSION['admin_id']]);
        $user = $stmt->fetch();
    }
} catch (Exception $e) {}

if (!$user) { header('Location: dashboard.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify()) {
    $action = $_POST['action'] ?? '';

    if ($action === 'update_profile') {
        $full_name = sanitize($_POST['full_name'] ?? '');
        $email     = sanitize_email($_POST['email'] ?? '');

        if (!$email) {
            $error = 'Adresse email invalide.';
        } elseif (strlen($full_name) < 2) {
            $error = 'Nom complet invalide.';
        } else {
            try {
                db()->prepare('UPDATE users SET full_name=?,email=?,updated_at=NOW() WHERE id=?')
                    ->execute([$full_name, $email, $_SESSION['admin_id']]);
                $_SESSION['admin_name'] = $full_name;
                admin_log('profile_updated', 'Profil mis à jour');
                $success = 'Profil mis à jour avec succès.';
                $user['full_name'] = $full_name;
                $user['email']     = $email;
            } catch (Exception $e) {
                $error = 'Erreur: ' . $e->getMessage();
            }
        }
    }

    if ($action === 'change_password') {
        $current  = $_POST['current_password'] ?? '';
        $new_pw   = $_POST['new_password'] ?? '';
        $confirm  = $_POST['confirm_password'] ?? '';

        if (empty($current) || empty($new_pw) || empty($confirm)) {
            $error = 'Tous les champs mot de passe sont obligatoires.';
        } elseif ($new_pw !== $confirm) {
            $error = 'Les nouveaux mots de passe ne correspondent pas.';
        } elseif (strlen($new_pw) < 8) {
            $error = 'Le mot de passe doit contenir au moins 8 caractères.';
        } else {
            $valid = ($user['password'] === 'NaturAfrica@2024!')
                ? ($current === 'NaturAfrica@2024!')
                : password_verify($current, $user['password']);

            if (!$valid) {
                $error = 'Mot de passe actuel incorrect.';
            } else {
                try {
                    $hash = password_hash($new_pw, PASSWORD_BCRYPT, ['cost' => 12]);
                    db()->prepare('UPDATE users SET password=?,updated_at=NOW() WHERE id=?')
                        ->execute([$hash, $_SESSION['admin_id']]);
                    unset($_SESSION['force_password_change']);
                    admin_log('password_changed', 'Mot de passe changé');
                    $success = 'Mot de passe modifié avec succès.';
                } catch (Exception $e) {
                    $error = 'Erreur: ' . $e->getMessage();
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mon Profil – NATURAFRIK Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="<?= SITE_URL ?>/admin/css/admin.css">
</head>
<body class="admin-body">

<aside class="admin-sidebar" id="adminSidebar">
  <div class="sidebar-logo">
    <div class="sidebar-logo-icon">
      <svg viewBox="0 0 24 24" fill="white"><path d="M17 8C8 10 5.9 16.17 3.82 21.34L5.71 22l1-2.3A4.49 4.49 0 008 20C19 20 22 3 22 3c-1 2-8 2-8 2C14 8 17 8 17 8z"/></svg>
    </div>
    <div><div class="sidebar-brand">NATURAFRIK</div><div class="sidebar-admin">Administration</div></div>
  </div>
  <nav class="sidebar-nav">
    <div class="nav-group">
      <div class="nav-group-title">Principal</div>
      <a href="dashboard.php" class="sidebar-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    </div>
    <div class="nav-group">
      <div class="nav-group-title">Catalogue</div>
      <a href="products/index.php" class="sidebar-link"><i class="fas fa-box-open"></i> Produits</a>
      <a href="products/add.php" class="sidebar-link"><i class="fas fa-plus-circle"></i> Ajouter un produit</a>
      <a href="realestate/index.php" class="sidebar-link"><i class="fas fa-building"></i> Immobilier</a>
      <a href="realestate/add.php" class="sidebar-link"><i class="fas fa-plus-circle"></i> Ajouter un bien</a>
    </div>
    <div class="nav-group">
      <div class="nav-group-title">Communication</div>
      <a href="messages/index.php" class="sidebar-link"><i class="fas fa-envelope"></i> Messages</a>
      <a href="newsletter/index.php" class="sidebar-link"><i class="fas fa-paper-plane"></i> Newsletter</a>
    </div>
    <div class="nav-group">
      <div class="nav-group-title">Système</div>
      <a href="settings.php" class="sidebar-link"><i class="fas fa-cog"></i> Paramètres</a>
      <a href="profile.php" class="sidebar-link active"><i class="fas fa-user-circle"></i> Mon Profil</a>
    </div>
  </nav>
  <div class="sidebar-footer">
    <div class="admin-user-info">
      <div class="admin-avatar"><?= strtoupper(substr($_SESSION['admin_name'] ?? 'A', 0, 2)) ?></div>
      <div>
        <div class="admin-name"><?= e($_SESSION['admin_name'] ?? '') ?></div>
        <div class="admin-role"><?= e($_SESSION['admin_role'] ?? '') ?></div>
      </div>
    </div>
    <a href="logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i></a>
  </div>
</aside>
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<main class="admin-main">
  <header class="admin-topbar">
    <button class="sidebar-toggle" id="sidebarToggle"><i class="fas fa-bars"></i></button>
    <div class="topbar-title">
      <h1>Mon Profil</h1>
      <span>Gérer vos informations personnelles</span>
    </div>
  </header>

  <div class="admin-content">
    <?php if ($error): ?>
    <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= e($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
    <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= e($success) ?></div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['force_password_change'])): ?>
    <div class="alert alert-warning"><i class="fas fa-exclamation-triangle"></i> Vous utilisez le mot de passe par défaut. Veuillez le changer maintenant pour sécuriser votre compte.</div>
    <?php endif; ?>

    <div class="grid-2">

      <!-- Infos du profil -->
      <div>
        <div class="admin-section" style="margin-bottom:20px;">
          <div style="display:flex;align-items:center;gap:16px;margin-bottom:20px;padding-bottom:16px;border-bottom:1px solid var(--border);">
            <div class="profile-avatar-lg"><?= strtoupper(substr($user['full_name'] ?? 'A', 0, 2)) ?></div>
            <div>
              <div style="font-family:var(--font-serif);font-size:1.3rem;font-weight:700;color:var(--text-1);"><?= e($user['full_name'] ?? '') ?></div>
              <div style="color:var(--text-3);font-size:.8rem;margin-top:3px;"><?= e($user['email'] ?? '') ?></div>
              <span class="status-badge status-active" style="margin-top:8px;"><?= e(ucfirst(str_replace('_',' ',$user['role']??''))) ?></span>
            </div>
          </div>

          <div class="info-row">
            <span class="info-label">Identifiant</span>
            <span class="info-value" style="font-family:monospace;background:var(--bg-elevated);padding:2px 8px;border-radius:4px;font-size:.85rem;"><?= e($user['username'] ?? '') ?></span>
          </div>
          <div class="info-row">
            <span class="info-label">Dernière connexion</span>
            <span class="info-value" style="color:var(--text-2);"><?= $user['last_login'] ? format_date($user['last_login']) : 'Jamais' ?></span>
          </div>
          <div class="info-row">
            <span class="info-label">Compte créé</span>
            <span class="info-value" style="color:var(--text-2);"><?= format_date($user['created_at']) ?></span>
          </div>
        </div>

        <!-- Modifier le profil -->
        <div class="admin-form-card">
          <div class="form-section-title">Modifier les informations</div>
          <form method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="update_profile">
            <div class="form-group">
              <label class="form-label">Nom complet</label>
              <input type="text" name="full_name" class="form-control" value="<?= e($user['full_name'] ?? '') ?>" required>
            </div>
            <div class="form-group">
              <label class="form-label">Adresse email</label>
              <input type="email" name="email" class="form-control" value="<?= e($user['email'] ?? '') ?>" required>
            </div>
            <button type="submit" class="btn-admin btn-admin-primary">
              <i class="fas fa-save"></i> Enregistrer
            </button>
          </form>
        </div>
      </div>

      <!-- Changer le mot de passe -->
      <div class="admin-form-card">
        <div class="form-section-title">Changer le mot de passe</div>
        <form method="POST" autocomplete="off">
          <?= csrf_field() ?>
          <input type="hidden" name="action" value="change_password">
          <div class="form-group">
            <label class="form-label">Mot de passe actuel</label>
            <div style="position:relative;">
              <input type="password" name="current_password" id="pwCurrent" class="form-control" style="padding-right:40px;" required autocomplete="current-password">
              <button type="button" class="toggle-pw-btn" data-target="pwCurrent" style="position:absolute;right:11px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--text-3);cursor:pointer;font-size:.85rem;">
                <i class="fas fa-eye"></i>
              </button>
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">Nouveau mot de passe</label>
            <div style="position:relative;">
              <input type="password" name="new_password" id="pwNew" class="form-control" style="padding-right:40px;" required autocomplete="new-password" minlength="8">
              <button type="button" class="toggle-pw-btn" data-target="pwNew" style="position:absolute;right:11px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--text-3);cursor:pointer;font-size:.85rem;">
                <i class="fas fa-eye"></i>
              </button>
            </div>
            <div style="font-size:.74rem;color:var(--text-3);margin-top:5px;">Minimum 8 caractères</div>
          </div>
          <div class="form-group">
            <label class="form-label">Confirmer le nouveau mot de passe</label>
            <input type="password" name="confirm_password" class="form-control" required autocomplete="new-password" minlength="8">
          </div>
          <button type="submit" class="btn-admin btn-admin-primary">
            <i class="fas fa-key"></i> Changer le mot de passe
          </button>
        </form>
      </div>
    </div>
  </div>
</main>

<script>
const toggle = document.getElementById('sidebarToggle');
const sidebar = document.getElementById('adminSidebar');
const overlay = document.getElementById('sidebarOverlay');
toggle?.addEventListener('click', () => { sidebar.classList.toggle('open'); overlay.classList.toggle('active'); });
overlay?.addEventListener('click', () => { sidebar.classList.remove('open'); overlay.classList.remove('active'); });

document.querySelectorAll('.toggle-pw-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    const inp = document.getElementById(btn.dataset.target);
    inp.type = inp.type === 'password' ? 'text' : 'password';
    btn.querySelector('i').classList.toggle('fa-eye');
    btn.querySelector('i').classList.toggle('fa-eye-slash');
  });
});
</script>
</body>
</html>
