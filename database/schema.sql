-- ============================================================
-- NATURAFRICA - Base de données complète
-- NATURCAM Sarl - Groupe Nature Cameroun
-- ============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS `naturafrica_db`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
USE `naturafrica_db`;

-- --------------------------------------------------------
-- Table: utilisateurs admin
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id`           INT(11)      NOT NULL AUTO_INCREMENT,
  `username`     VARCHAR(50)  NOT NULL,
  `password`     VARCHAR(255) NOT NULL,
  `email`        VARCHAR(100) NOT NULL,
  `full_name`    VARCHAR(100) DEFAULT NULL,
  `role`         ENUM('super_admin','admin','editor') NOT NULL DEFAULT 'admin',
  `avatar`       VARCHAR(255) DEFAULT NULL,
  `last_login`   DATETIME     DEFAULT NULL,
  `login_attempts` INT(11)   DEFAULT 0,
  `locked_until` DATETIME     DEFAULT NULL,
  `is_active`    TINYINT(1)   NOT NULL DEFAULT 1,
  `created_at`   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Mot de passe par défaut: NaturAfrica@2024! (à changer après installation)
INSERT INTO `users` (`username`, `password`, `email`, `full_name`, `role`) VALUES
('admin', '$2y$12$NaturAfrica2024HashedXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX', 'admin@naturafrica.com', 'Super Administrateur', 'super_admin');

-- --------------------------------------------------------
-- Table: catégories
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `categories` (
  `id`            INT(11)      NOT NULL AUTO_INCREMENT,
  `name`          VARCHAR(150) NOT NULL,
  `slug`          VARCHAR(150) NOT NULL,
  `description`   TEXT         DEFAULT NULL,
  `icon`          VARCHAR(100) DEFAULT NULL,
  `image_path`    VARCHAR(255) DEFAULT NULL,
  `section`       ENUM('produits','immobilier','agriculture','matieres_premieres') NOT NULL,
  `parent_id`     INT(11)      DEFAULT NULL,
  `display_order` INT(11)      DEFAULT 0,
  `is_active`     TINYINT(1)   DEFAULT 1,
  `created_at`    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `section` (`section`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `categories` (`name`, `slug`, `description`, `icon`, `section`, `display_order`) VALUES
-- Produits en sachet
('Cacao', 'cacao', 'Poudre de cacao pur du Cameroun - Région de l\'Ouest', '🍫', 'produits', 1),
('Café', 'cafe', 'Café premium des highlands camerounais', '☕', 'produits', 2),
('Avocat', 'avocat', 'Avocats frais et transformés du Cameroun', '🥑', 'produits', 3),
('Autres Produits', 'autres-produits', 'Autres produits agricoles transformés', '🌿', 'produits', 4),
-- Immobilier
('Maisons à vendre', 'maisons-vente', 'Maisons résidentielles à vendre', '🏠', 'immobilier', 1),
('Terrains', 'terrains', 'Terrains constructibles et agricoles', '🏞️', 'immobilier', 2),
('Locations', 'locations', 'Biens immobiliers en location', '🔑', 'immobilier', 3),
('Boutiques & Locaux', 'boutiques-locaux', 'Espaces commerciaux et bureaux', '🏪', 'immobilier', 4),
('Appartements', 'appartements', 'Appartements et studios', '🏢', 'immobilier', 5),
-- Agriculture & Élevage
('Volaille', 'volaille', 'Poulets, pintades et autres volailles', '🐔', 'agriculture', 1),
('Élevage Porcin', 'elevage-porcin', 'Porcs et produits porcins', '🐷', 'agriculture', 2),
('Élevage Bovin', 'elevage-bovin', 'Bovins et produits laitiers', '🐄', 'agriculture', 3),
('Élevage Caprin', 'elevage-caprin', 'Chèvres et ovins', '🐐', 'agriculture', 4),
('Céréales', 'cereales', 'Maïs, sorgho et autres céréales', '🌽', 'agriculture', 5),
('Tubercules', 'tubercules', 'Manioc, igname, patate douce, taro', '🥔', 'agriculture', 6),
-- Matières premières
('Or', 'or', 'Or naturel et lingots', '🥇', 'matieres_premieres', 1),
('Cuivre', 'cuivre', 'Cuivre brut et transformé', '🟤', 'matieres_premieres', 2),
('Fer', 'fer', 'Fer et acier industriel', '⚙️', 'matieres_premieres', 3),
('Bronze', 'bronze', 'Bronze et alliages cuivreux', '🏅', 'matieres_premieres', 4),
('Caoutchouc', 'caoutchouc', 'Caoutchouc naturel du Cameroun', '🌿', 'matieres_premieres', 5),
('Cobalt', 'cobalt', 'Cobalt et minéraux rares', '💎', 'matieres_premieres', 6);

-- --------------------------------------------------------
-- Table: produits
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `products` (
  `id`                INT(11)        NOT NULL AUTO_INCREMENT,
  `name`              VARCHAR(200)   NOT NULL,
  `slug`              VARCHAR(200)   NOT NULL,
  `short_description` VARCHAR(500)   DEFAULT NULL,
  `description`       LONGTEXT       DEFAULT NULL,
  `price`             DECIMAL(15,2)  DEFAULT NULL,
  `price_max`         DECIMAL(15,2)  DEFAULT NULL,
  `price_unit`        VARCHAR(50)    DEFAULT 'kg',
  `currency`          VARCHAR(10)    DEFAULT 'XAF',
  `category_id`       INT(11)        NOT NULL,
  `main_image`        VARCHAR(255)   DEFAULT NULL,
  `gallery`           TEXT           DEFAULT NULL,
  `stock_quantity`    INT(11)        DEFAULT 0,
  `min_order`         INT(11)        DEFAULT 1,
  `min_order_unit`    VARCHAR(50)    DEFAULT 'kg',
  `weight`            VARCHAR(50)    DEFAULT NULL,
  `origin`            VARCHAR(100)   DEFAULT NULL,
  `certification`     VARCHAR(200)   DEFAULT NULL,
  `specifications`    TEXT           DEFAULT NULL,
  `tags`              VARCHAR(500)   DEFAULT NULL,
  `is_featured`       TINYINT(1)     DEFAULT 0,
  `is_new`            TINYINT(1)     DEFAULT 0,
  `is_active`         TINYINT(1)     DEFAULT 1,
  `views`             INT(11)        DEFAULT 0,
  `created_at`        TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`        TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `category_id` (`category_id`),
  KEY `is_featured` (`is_featured`),
  KEY `is_active` (`is_active`),
  FULLTEXT KEY `search_index` (`name`, `short_description`, `tags`),
  CONSTRAINT `fk_product_category` FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Produits par défaut (NATCACAO)
INSERT INTO `products` (`name`, `slug`, `short_description`, `description`, `price`, `price_unit`, `currency`, `category_id`, `stock_quantity`, `min_order`, `min_order_unit`, `weight`, `origin`, `certification`, `specifications`, `is_featured`, `is_new`) VALUES
('NATCACAO - Poudre de Cacao Pur 500g', 'natcacao-poudre-500g',
 'Cacao pur du Cameroun, Région de l\'Ouest. Notes: Cacao Riche, Miel Sauvage, Légère Épice.',
 '<p>Le Groupe Nature s\'engage à innover dans la filière cacao, en sélectionnant des fèves d\'exception grâce à des essais de terrain rigoureux menés dans la Région de l\'Ouest du Cameroun, en étroite collaboration avec les communautés locales.</p><h4>Notes de Dégustation</h4><ul><li><strong>Cacao Riche</strong> (Intense)</li><li><strong>Miel Sauvage</strong> (Doux)</li><li><strong>Légère Épice</strong> (Gingembre)</li></ul><h4>Fiche Technique</h4><ul><li>Producteur: Groupe Nature, Coopérative de l\'Ouest-Cameroun</li><li>Procédé: Fermentation en Caisses, Séchage Solaire</li><li>Origine: Hauts Plateaux, Ouest Cameroun</li><li>Torréfaction: Légère (pour préserver les arômes)</li><li>Variété: Trinitario & Forastero, Variétés Améliorées</li><li>Altitude: 1000m - 1500m</li></ul>',
 5000.00, 'sachet 500g', 'XAF', 1, 100, 1, 'sachet', '500g', 'Région Ouest, Cameroun', '100% Naturel, Sans additifs', '{"variete":"Trinitario & Forastero","altitude":"1000-1500m","sechage":"Solaire","torrefaction":"Legere"}', 1, 1);

-- --------------------------------------------------------
-- Table: immobilier
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `real_estate` (
  `id`           INT(11)       NOT NULL AUTO_INCREMENT,
  `title`        VARCHAR(200)  NOT NULL,
  `slug`         VARCHAR(200)  NOT NULL,
  `type`         ENUM('house','land','rental','shop','apartment','villa','warehouse') NOT NULL,
  `transaction`  ENUM('sale','rent') NOT NULL DEFAULT 'sale',
  `price`        DECIMAL(15,2) DEFAULT NULL,
  `price_period` VARCHAR(20)   DEFAULT NULL,
  `currency`     VARCHAR(10)   DEFAULT 'XAF',
  `location`     VARCHAR(200)  NOT NULL,
  `city`         VARCHAR(100)  DEFAULT 'Yaoundé',
  `country`      VARCHAR(100)  DEFAULT 'Cameroun',
  `surface_area` DECIMAL(10,2) DEFAULT NULL,
  `bedrooms`     INT(11)       DEFAULT NULL,
  `bathrooms`    INT(11)       DEFAULT NULL,
  `floors`       INT(11)       DEFAULT NULL,
  `year_built`   YEAR          DEFAULT NULL,
  `description`  LONGTEXT      DEFAULT NULL,
  `main_image`   VARCHAR(255)  DEFAULT NULL,
  `gallery`      TEXT          DEFAULT NULL,
  `amenities`    TEXT          DEFAULT NULL,
  `latitude`     DECIMAL(10,8) DEFAULT NULL,
  `longitude`    DECIMAL(11,8) DEFAULT NULL,
  `is_featured`  TINYINT(1)    DEFAULT 0,
  `is_available` TINYINT(1)    DEFAULT 1,
  `views`        INT(11)       DEFAULT 0,
  `created_at`   TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`   TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `type` (`type`),
  KEY `transaction` (`transaction`),
  KEY `city` (`city`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: messages de contact
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id`               INT(11)      NOT NULL AUTO_INCREMENT,
  `name`             VARCHAR(100) NOT NULL,
  `email`            VARCHAR(100) NOT NULL,
  `phone`            VARCHAR(30)  DEFAULT NULL,
  `subject`          VARCHAR(200) NOT NULL,
  `message`          TEXT         NOT NULL,
  `product_interest` VARCHAR(200) DEFAULT NULL,
  `section`          VARCHAR(50)  DEFAULT NULL,
  `ip_address`       VARCHAR(45)  DEFAULT NULL,
  `is_read`          TINYINT(1)   DEFAULT 0,
  `replied_at`       DATETIME     DEFAULT NULL,
  `created_at`       TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `is_read` (`is_read`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: newsletter
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `newsletter` (
  `id`            INT(11)      NOT NULL AUTO_INCREMENT,
  `email`         VARCHAR(100) NOT NULL,
  `name`          VARCHAR(100) DEFAULT NULL,
  `is_active`     TINYINT(1)   DEFAULT 1,
  `token`         VARCHAR(64)  DEFAULT NULL,
  `subscribed_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: rate limiting (anti-spam)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `rate_limits` (
  `id`            INT(11)     NOT NULL AUTO_INCREMENT,
  `ip_address`    VARCHAR(45) NOT NULL,
  `action`        VARCHAR(50) NOT NULL,
  `attempts`      INT(11)     DEFAULT 1,
  `last_attempt`  TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `blocked_until` DATETIME    DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ip_action` (`ip_address`, `action`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: sessions admin
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `admin_sessions` (
  `id`         VARCHAR(128) NOT NULL,
  `user_id`    INT(11)      NOT NULL,
  `ip_address` VARCHAR(45)  DEFAULT NULL,
  `user_agent` VARCHAR(500) DEFAULT NULL,
  `created_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expires_at` DATETIME     NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `expires_at` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: logs admin
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `admin_logs` (
  `id`         INT(11)      NOT NULL AUTO_INCREMENT,
  `user_id`    INT(11)      DEFAULT NULL,
  `action`     VARCHAR(100) NOT NULL,
  `details`    TEXT         DEFAULT NULL,
  `ip_address` VARCHAR(45)  DEFAULT NULL,
  `created_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `action` (`action`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
