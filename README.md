# NATURAFRIK — Site web officiel
**NATURCAM Sarl · Groupe Nature Cameroun**  
Produits naturels · Agriculture · Immobilier · Matières premières

---

## Déploiement en ligne — Guide complet

### Option A — InfinityFree (le plus simple, 100% gratuit)

> Idéal pour démarrer rapidement sans configuration complexe.

**1. Créer un compte**
- Va sur [infinityfree.net](https://infinityfree.net)
- Crée un compte gratuit
- Crée un hébergement → choisis un sous-domaine (ex: `naturafrik.infinityfree.net`)

**2. Base de données**
- Dans le cPanel → **MySQL Databases** → crée une DB
- Retiens : `host`, `db_name`, `user`, `password`
- Dans **phpMyAdmin** → importe le fichier `database/schema.sql`

**3. Configurer le fichier .env**
```
# Créer un fichier .env à la racine du projet
DB_HOST=sql123.infinityfree.com   # fourni par InfinityFree
DB_PORT=3306
DB_NAME=epiz_xxx_naturafrik
DB_USER=epiz_xxx
DB_PASS=ton_mot_de_passe
SITE_BASE=/naturafrik
SITE_URL=https://naturafrik.infinityfree.net/naturafrik
```

**4. Upload via FTP**
- Télécharge [FileZilla](https://filezilla-project.org/) (gratuit)
- Connecte-toi avec les infos FTP du cPanel
- Upload TOUS les fichiers dans `htdocs/naturafrik/`
- Vérifie que `.env` est bien uploadé (fichiers cachés !)

**URL finale :** `https://naturafrik.infinityfree.net/naturafrik/`

---

### Option B — Railway (GitHub → mise en ligne automatique)

> Plus professionnel. Chaque `git push` met à jour le site automatiquement.

**1. Mettre le code sur GitHub**
```bash
# Dans c:\xampp\htdocs\naturafrik\
git init
git add .
git commit -m "NATURAFRIK v1 — site complet"
```
- Va sur [github.com](https://github.com) → **New repository**
- Nom : `naturafrik` → Create
```bash
git remote add origin https://github.com/TON_USERNAME/naturafrik.git
git branch -M main
git push -u origin main
```

**2. Déployer sur Railway**
- Va sur [railway.app](https://railway.app) → **Login with GitHub**
- **New Project** → **Deploy from GitHub repo** → sélectionne `naturafrik`
- Railway détecte PHP et démarre automatiquement

**3. Ajouter MySQL**
- Dans ton projet Railway → **+ New** → **MySQL**
- Railway crée la DB et fournit les variables automatiquement

**4. Variables d'environnement Railway**
Dans Railway → ton service PHP → **Variables**, ajoute :
```
SITE_BASE=
SITE_URL=https://TON-APP.up.railway.app
SECRET_KEY=une_cle_secrete_longue_et_aleatoire
```
> `SITE_BASE` doit être **vide** (Railway sert depuis la racine)

**5. Importer la base de données**
- Dans Railway → MySQL service → **Connect** → copie la connection string
- Utilise [TablePlus](https://tableplus.com/) ou [DBeaver](https://dbeaver.io/) pour te connecter
- Importe `database/schema.sql`

**URL finale :** `https://TON-APP.up.railway.app`

---

### Option C — Hébergement payant (recommandé pour production)

| Hébergeur | Prix | Avantage |
|-----------|------|---------|
| [Hostinger](https://hostinger.fr) | ~3$/mois | Simple, rapide |
| [o2switch](https://o2switch.fr) | ~6€/mois | France, support FR |
| [Namecheap](https://namecheap.com) | ~2$/mois | Pas cher |

Avec un hébergement cPanel classique :
- Upload via FTP dans `public_html/naturafrik/`
- Importe la DB via phpMyAdmin
- Configure `.env` avec les infos DB

---

## Développement local (XAMPP)

```bash
# Démarrer XAMPP (Apache + MySQL)
# Accéder au site :
http://localhost/naturafrik/
```

Le fichier `.env` local doit contenir :
```
DB_HOST=127.0.0.1
DB_PORT=3307
DB_NAME=naturafrica
DB_USER=root
DB_PASS=
SITE_BASE=/naturafrik
```

---

## Structure des fichiers

```
naturafrik/
├── config/
│   └── config.php          # Configuration (lit .env)
├── includes/
│   ├── header.php           # Navigation + <head>
│   ├── footer.php           # Footer + scripts
│   ├── db.php               # Connexion PDO
│   └── functions.php        # Fonctions utilitaires
├── pages/
│   ├── natcafe.php          # Page NATCAFÉ (showcase)
│   ├── produits.php         # Catalogue produits
│   ├── agriculture.php      # Agriculture & élevage
│   ├── immobilier.php       # Immobilier
│   ├── matieres-premieres.php
│   └── contact.php
├── css/style.css            # Design system complet
├── js/main.js               # Interactions & modal
├── images/                  # Photos et assets
├── uploads/                 # Uploads utilisateurs
├── .env.example             # Template variables
├── .gitignore               # Fichiers à exclure de Git
└── nixpacks.toml            # Config Railway
```

---

## Contact

**NATURCAM Sarl** — Yaoundé, Cameroun  
WhatsApp : +237 680 209 435  
RCCM : RC/YAO/2023/B/519
