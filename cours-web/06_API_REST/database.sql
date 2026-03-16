-- ============================================================
-- SCRIPT SQL - CREATION DE LA BASE DE DONNEES "MAGASIN"
-- ============================================================
--
-- Ce script cree la base de donnees et la table necessaires
-- pour l'API REST de gestion de stock.
--
-- Execution :
--   1. Ouvrir phpMyAdmin (http://localhost:8888/phpMyAdmin)
--   2. Aller dans l'onglet "SQL"
--   3. Coller ce script et cliquer sur "Executer"
--
--   Ou en ligne de commande :
--   /Applications/MAMP/Library/bin/mysql -u root -p < database.sql
--
-- ============================================================


-- ============================================================
-- CREATION DE LA BASE DE DONNEES
-- ============================================================

-- Creer la base de donnees si elle n'existe pas encore
-- CHARACTER SET utf8mb4 : supporte les accents et les emojis
-- COLLATE utf8mb4_unicode_ci : tri alphabetique correct en francais
CREATE DATABASE IF NOT EXISTS magasin
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

-- Se positionner sur la base de donnees
USE magasin;


-- ============================================================
-- CREATION DE LA TABLE "produits"
-- ============================================================

-- Supprimer la table si elle existe deja (utile pour reinitialiser)
DROP TABLE IF EXISTS produits;

-- Creer la table des produits
CREATE TABLE produits (
    -- Identifiant unique, auto-incremente
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    -- Nom du produit (obligatoire, max 100 caracteres)
    nom VARCHAR(100) NOT NULL,

    -- Description du produit (optionnelle, texte long)
    description TEXT DEFAULT NULL,

    -- Prix en euros (10 chiffres max, 2 decimales)
    prix DECIMAL(10, 2) NOT NULL,

    -- Quantite en stock (entier positif ou zero)
    stock INT UNSIGNED NOT NULL DEFAULT 0,

    -- Categorie du produit (optionnelle, max 50 caracteres)
    categorie VARCHAR(50) DEFAULT NULL,

    -- Date de creation automatique
    date_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- INSERTION DE DONNEES D'EXEMPLE
-- ============================================================

INSERT INTO produits (nom, description, prix, stock, categorie) VALUES
    ('Clavier mecanique RGB',
     'Clavier mecanique avec retroeclairage RGB et switches Cherry MX Blue. Ideal pour la bureautique et le gaming.',
     89.99, 45, 'Informatique'),

    ('Souris sans fil ergonomique',
     'Souris ergonomique sans fil avec capteur optique 4000 DPI. Connexion Bluetooth et USB.',
     39.99, 120, 'Informatique'),

    ('Ecran 27 pouces 4K',
     'Moniteur IPS 27 pouces, resolution 4K UHD, temps de reponse 5ms. Ideal pour le graphisme.',
     349.99, 18, 'Informatique'),

    ('Casque audio Bluetooth',
     'Casque sans fil avec reduction de bruit active. Autonomie de 30 heures.',
     79.50, 65, 'Audio'),

    ('Enceinte portable waterproof',
     'Enceinte Bluetooth etanche IPX7, 20W, autonomie 12h. Parfaite pour l\'exterieur.',
     49.99, 88, 'Audio'),

    ('Cable USB-C vers USB-C',
     'Cable de charge et transfert de donnees USB-C, 2 metres, charge rapide 100W.',
     12.99, 200, 'Accessoires'),

    ('Tapis de souris XXL',
     'Tapis de souris grand format 80x30cm, surface lisse, base antiderapante.',
     19.99, 150, 'Accessoires'),

    ('Webcam Full HD 1080p',
     'Camera web Full HD avec microphone integre et correction automatique de la lumiere.',
     59.99, 32, 'Informatique'),

    ('Cle USB 128 Go',
     'Cle USB 3.2 haute vitesse, 128 Go, lecture 400 Mo/s. Boitier metallique compact.',
     24.99, 95, 'Stockage'),

    ('Disque dur externe 2 To',
     'Disque dur portable USB 3.0, 2 To de stockage. Compatible Windows et Mac.',
     69.99, 40, 'Stockage');


-- ============================================================
-- VERIFICATION
-- ============================================================

-- Afficher tous les produits inseres
SELECT id, nom, prix, stock, categorie FROM produits ORDER BY id;
