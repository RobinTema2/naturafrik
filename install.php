<?php
/* =====================================================
   NATURAFRIK — Installateur
   À SUPPRIMER après utilisation !
   ===================================================== */

$db_host = '127.0.0.1';
$db_port = '3307';
$db_user = 'root';
$db_pass = '';
$db_name = 'naturafrik';
$admin_user = 'admin';
$admin_pass = 'Admin@Natura2024!';

$messages = [];
$success  = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db_pass = $_POST['db_pass'] ?? '';
    try {
        // 1. Connexion sans base
        $pdo = new PDO("mysql:host={$db_host};port={$db_port};charset=utf8mb4", $db_user, $db_pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
        $messages[] = ['ok', 'Connexion MySQL réussie (port ' . $db_port . ')'];

        // 2. Créer la base
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$db_name}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo->exec("USE `{$db_name}`");
        $messages[] = ['ok', "Base de données `{$db_name}` créée"];

        // 3. Exécuter le schéma SQL
        $schemaFile = __DIR__ . '/database/schema.sql';
        if (!file_exists($schemaFile)) throw new Exception("Fichier schema.sql introuvable : {$schemaFile}");
        $sql = file_get_contents($schemaFile);
        foreach (array_filter(array_map('trim', explode(';', $sql))) as $stmt) {
            try { if ($stmt) $pdo->exec($stmt); } catch (PDOException $e) {
                if ($e->getCode() !== '23000') throw $e; // ignorer doublons
            }
        }
        $messages[] = ['ok', 'Schéma SQL installé (tables créées)'];

        // 4. Créer admin (ou mettre à jour si existant)
        $hash = password_hash($admin_pass, PASSWORD_BCRYPT, ['cost' => 12]);
        $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?,?,?)
                       ON DUPLICATE KEY UPDATE password=VALUES(password), role=VALUES(role)")
            ->execute([$admin_user, $hash, 'admin']);
        $messages[] = ['ok', "Compte admin créé → login: <strong>{$admin_user}</strong> / mdp: <strong>{$admin_pass}</strong>"];

        $success = true;
        $messages[] = ['ok', '✅ Installation terminée ! Supprimez ce fichier maintenant.'];

    } catch (Exception $e) {
        $messages[] = ['err', 'Erreur : ' . $e->getMessage()];
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>NATURAFRIK — Installation</title>
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'Segoe UI', sans-serif; background: #08070A; color: #E8E0D0; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
  .box { background: #1A1820; border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; padding: 3rem; max-width: 520px; width: 100%; }
  h1 { font-size: 1.8rem; color: #F4EDE0; margin-bottom: 0.3rem; }
  h1 span { color: #C8920A; }
  .sub { font-size: 0.82rem; color: #6A5C50; margin-bottom: 2rem; letter-spacing: 0.2em; }
  label { display: block; font-size: 0.68rem; letter-spacing: 0.2em; color: #C8920A; margin-bottom: 0.4rem; }
  input { width: 100%; padding: 0.9rem 1rem; background: #201E28; border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #E8E0D0; font-size: 0.95rem; outline: none; margin-bottom: 1.5rem; }
  input:focus { border-color: #C8920A; }
  button { width: 100%; padding: 1rem; background: linear-gradient(135deg,#C8920A,#E06818); color: #08070A; font-weight: 700; font-size: 0.9rem; letter-spacing: 0.1em; border: none; border-radius: 8px; cursor: pointer; }
  button:hover { opacity: 0.9; }
  .msg { padding: 0.75rem 1rem; border-radius: 8px; margin-bottom: 0.6rem; font-size: 0.88rem; }
  .msg.ok { background: rgba(40,160,96,0.12); border: 1px solid rgba(40,160,96,0.3); color: #90D8A8; }
  .msg.err { background: rgba(200,48,32,0.12); border: 1px solid rgba(200,48,32,0.3); color: #F08080; }
  .warn { background: rgba(200,146,10,0.12); border: 1px solid rgba(200,146,10,0.3); border-radius: 8px; padding: 0.75rem 1rem; font-size: 0.8rem; color: #E8C080; margin-bottom: 1.5rem; }
  .links { display: flex; gap: 1rem; margin-top: 1.5rem; flex-wrap: wrap; }
  .links a { flex: 1; text-align: center; padding: 0.7rem; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #E8E0D0; font-size: 0.82rem; transition: border-color 0.2s; }
  .links a:hover { border-color: #C8920A; color: #C8920A; }
</style>
</head>
<body>
<div class="box">
  <h1>NATURA<span>FRIK</span></h1>
  <div class="sub">INSTALLATEUR — NATURCAM SARL</div>

  <?php if (!empty($messages)): ?>
    <?php foreach ($messages as $m): ?>
      <div class="msg <?= $m[0] ?>"><?= $m[1] ?></div>
    <?php endforeach; ?>
    <?php if ($success): ?>
      <div class="links">
        <a href="/naturafrik/">🌍 Voir le site</a>
        <a href="/naturafrik/admin/">⚙️ Admin</a>
      </div>
    <?php endif; ?>
  <?php else: ?>
    <div class="warn">⚠️ Ce fichier doit être <strong>supprimé</strong> après installation.</div>
    <form method="POST">
      <label>MOT DE PASSE MYSQL (vide par défaut XAMPP)</label>
      <input type="password" name="db_pass" placeholder="Laisser vide si pas de mot de passe">
      <button type="submit">🚀 Lancer l'installation</button>
    </form>
  <?php endif; ?>
</div>
</body>
</html>
