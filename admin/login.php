<?php
require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/functions.php';

// Déjà connecté → dashboard
if (is_admin_logged()) {
    header('Location: ' . SITE_URL . '/admin/dashboard.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        $error = 'Requête invalide.';
    } else {
        $ip       = get_ip();
        $username = sanitize($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!check_rate_limit($ip, 'admin_login', MAX_LOGIN_ATTEMPTS, LOCKOUT_DURATION)) {
            $error = 'Trop de tentatives. Compte bloqué pour 30 minutes.';
        } elseif (empty($username) || empty($password)) {
            $error = 'Veuillez remplir tous les champs.';
        } else {
            try {
                $stmt = db()->prepare('SELECT * FROM users WHERE (username = ? OR email = ?) AND is_active = 1 LIMIT 1');
                $stmt->execute([$username, $username]);
                $user = $stmt->fetch();

                $valid = false;
                if ($user) {
                    // Support du mot de passe par défaut en clair pour première connexion
                    if ($user['password'] === 'NaturAfrica@2024!') {
                        $valid = ($password === 'NaturAfrica@2024!');
                    } else {
                        $valid = password_verify($password, $user['password']);
                    }
                }

                if ($valid) {
                    session_regenerate_id(true);
                    $_SESSION['admin_id']       = $user['id'];
                    $_SESSION['admin_username'] = $user['username'];
                    $_SESSION['admin_name']     = $user['full_name'];
                    $_SESSION['admin_role']     = $user['role'];
                    $_SESSION['admin_ip']       = $ip;

                    // Update last login
                    db()->prepare('UPDATE users SET last_login = NOW(), login_attempts = 0 WHERE id = ?')
                        ->execute([$user['id']]);

                    // Si c'est le mot de passe par défaut, forcer le changement
                    if ($user['password'] === 'NaturAfrica@2024!') {
                        $_SESSION['force_password_change'] = true;
                    }

                    admin_log('login', 'Connexion réussie');
                    header('Location: ' . SITE_URL . '/admin/dashboard.php');
                    exit;
                } else {
                    $error = 'Identifiants incorrects. Vérifiez votre nom d\'utilisateur et mot de passe.';
                    admin_log('login_failed', "Tentative: $username");
                }
            } catch (Exception $e) {
                $error = 'Erreur technique. Veuillez réessayer.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Administration – NATURAFRIK</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    :root {
      --green: #0B4D2E; --gold: #C9A84C; --dark: #060F0A;
      --font-serif: 'Cormorant Garamond', serif; --font-sans: 'Nunito', sans-serif;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: var(--font-sans);
      background: radial-gradient(ellipse at top, #0B4D2E 0%, #060F0A 60%);
      min-height: 100vh; display: flex; align-items: center; justify-content: center;
      position: relative; overflow: hidden;
    }
    .bg-orb {
      position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.2; pointer-events: none;
      animation: float 12s ease-in-out infinite;
    }
    .bg-orb-1 { width: 400px; height: 400px; background: var(--green); top: -100px; right: -100px; }
    .bg-orb-2 { width: 300px; height: 300px; background: var(--gold); bottom: -100px; left: -50px; animation-delay: -5s; }
    @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-30px)} }

    .login-card {
      background: rgba(255,255,255,0.05);
      border: 1px solid rgba(201,168,76,0.2);
      border-radius: 24px;
      padding: 48px 44px;
      width: 420px;
      max-width: 95vw;
      backdrop-filter: blur(20px);
      position: relative;
      z-index: 1;
      box-shadow: 0 30px 80px rgba(0,0,0,0.5);
    }
    .login-logo { text-align: center; margin-bottom: 32px; }
    .login-logo-icon {
      width: 64px; height: 64px; border-radius: 16px;
      background: linear-gradient(135deg, var(--green), var(--gold));
      display: flex; align-items: center; justify-content: center;
      font-size: 1.8rem; margin: 0 auto 12px;
    }
    .login-logo h1 {
      font-family: var(--font-serif); font-size: 1.8rem; font-weight: 700;
      color: white; letter-spacing: .05em;
    }
    .login-logo h1 strong { color: var(--gold); }
    .login-logo p { font-size: .78rem; color: rgba(255,255,255,.4); margin-top: 4px; text-transform: uppercase; letter-spacing: .2em; }

    .login-title { font-family: var(--font-serif); font-size: 1.2rem; color: white; margin-bottom: 24px; }

    .form-group { margin-bottom: 20px; }
    .form-label { display: block; font-size: .82rem; font-weight: 600; color: rgba(255,255,255,.7); margin-bottom: 7px; }
    .input-wrap { position: relative; }
    .input-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--gold); font-size: .9rem; }
    .form-control {
      width: 100%; padding: 13px 14px 13px 40px;
      background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,.12);
      border-radius: 12px; font-family: var(--font-sans); font-size: .92rem; color: white;
      outline: none; transition: .2s;
    }
    .form-control::placeholder { color: rgba(255,255,255,.25); }
    .form-control:focus { border-color: var(--gold); background: rgba(201,168,76,.05); }
    .toggle-pw { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); cursor: pointer; color: rgba(255,255,255,.3); font-size: .9rem; transition: .2s; }
    .toggle-pw:hover { color: var(--gold); }

    .alert-error {
      padding: 13px 16px; border-radius: 12px; margin-bottom: 20px;
      background: rgba(220,38,38,.12); border: 1px solid rgba(220,38,38,.3);
      color: #fca5a5; font-size: .88rem; display: flex; align-items: center; gap: 8px;
    }
    .btn-login {
      width: 100%; padding: 14px; border: none; border-radius: 12px; cursor: pointer;
      background: linear-gradient(135deg, var(--gold), #A07A28); color: #060F0A;
      font-family: var(--font-sans); font-size: 1rem; font-weight: 700;
      transition: .3s; display: flex; align-items: center; justify-content: center; gap: 8px;
    }
    .btn-login:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(201,168,76,.4); }

    .login-footer { text-align: center; margin-top: 24px; font-size: .78rem; color: rgba(255,255,255,.3); }
    .login-footer a { color: var(--gold); text-decoration: none; }
    .login-footer a:hover { text-decoration: underline; }

    .security-badge {
      display: flex; align-items: center; gap: 6px; justify-content: center;
      margin-top: 20px; font-size: .72rem; color: rgba(255,255,255,.3);
    }
    .security-badge i { color: var(--gold); font-size: .75rem; }
  </style>
</head>
<body>
  <div class="bg-orb bg-orb-1"></div>
  <div class="bg-orb bg-orb-2"></div>

  <div class="login-card">
    <div class="login-logo">
      <div class="login-logo-icon">🌿</div>
      <h1>NATUR<strong>AFRIK</strong></h1>
      <p>Administration</p>
    </div>

    <h2 class="login-title">Connexion Admin</h2>

    <?php if ($error): ?>
    <div class="alert-error">
      <i class="fas fa-exclamation-circle"></i>
      <?= e($error) ?>
    </div>
    <?php endif; ?>

    <form method="POST" action="" autocomplete="off">
      <?= csrf_field() ?>
      <div class="form-group">
        <label class="form-label">Nom d'utilisateur ou Email</label>
        <div class="input-wrap">
          <i class="input-icon fas fa-user"></i>
          <input type="text" name="username" class="form-control" placeholder="admin" required autocomplete="username" value="<?= e($_POST['username'] ?? '') ?>">
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Mot de passe</label>
        <div class="input-wrap">
          <i class="input-icon fas fa-lock"></i>
          <input type="password" name="password" id="pwInput" class="form-control" placeholder="••••••••••" required autocomplete="current-password">
          <i class="toggle-pw fas fa-eye" id="togglePw"></i>
        </div>
      </div>
      <button type="submit" class="btn-login">
        <i class="fas fa-sign-in-alt"></i> Se connecter
      </button>
    </form>

    <div class="login-footer">
      <a href="<?= SITE_URL ?>/index.php">← Retour au site public</a>
    </div>
    <div class="security-badge">
      <i class="fas fa-shield-alt"></i> Connexion sécurisée SSL/TLS
    </div>
  </div>

  <script>
    document.getElementById('togglePw')?.addEventListener('click', function() {
      const inp = document.getElementById('pwInput');
      inp.type  = inp.type === 'password' ? 'text' : 'password';
      this.classList.toggle('fa-eye');
      this.classList.toggle('fa-eye-slash');
    });
  </script>
</body>
</html>
