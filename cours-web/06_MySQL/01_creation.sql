-- ============================================================
-- SCRIPT SQL 01 : CREATION DE LA BASE DE DONNEES "BOUTIQUE"
-- ============================================================
--
-- Ce script cree la base de donnees et les tables necessaires
-- pour le cours MySQL. Il introduit les concepts de :
-- - Creation de base de donnees et de tables
-- - Types de donnees (INT, VARCHAR, TEXT, DECIMAL, DATETIME...)
-- - Contraintes (PRIMARY KEY, NOT NULL, UNIQUE, DEFAULT, FOREIGN KEY)
-- - Relations entre tables (cles etrangeres)
-- - Insertion de donnees d'exemple
--
-- Environnements de test :
--   1. phpMyAdmin : http://localhost:8888/phpMyAdmin > onglet SQL
--   2. Terminal :   /Applications/MAMP/Library/bin/mysql -u root -proot < 01_creation.sql
--
-- ============================================================


-- ============================================================
-- 1. CREATION DE LA BASE DE DONNEES
-- ============================================================

-- Supprimer la base si elle existe (pour pouvoir relancer le script)
-- ATTENTION : cela efface toutes les donnees !
DROP DATABASE IF EXISTS boutique;

-- Creer la base de donnees
-- CHARACTER SET utf8mb4 : supporte les accents francais et les emojis
-- COLLATE utf8mb4_unicode_ci : tri alphabetique correct (a = A, e = e)
CREATE DATABASE boutique
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

-- Se positionner sur la base de donnees
-- Toutes les commandes suivantes s'executeront dans "boutique"
USE boutique;


-- ============================================================
-- 2. TABLE "categories"
-- ============================================================
-- Une categorie regroupe des produits similaires.
-- C'est la table "parent" : les produits font reference a elle.
--
-- Contraintes utilisees :
-- - PRIMARY KEY : identifiant unique de chaque categorie
-- - AUTO_INCREMENT : MySQL genere automatiquement l'id (1, 2, 3...)
-- - NOT NULL : le champ ne peut pas etre vide
-- - UNIQUE : deux categories ne peuvent pas avoir le meme nom
-- ============================================================

CREATE TABLE categories (
    -- Identifiant unique, genere automatiquement
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    -- Nom de la categorie (obligatoire, unique)
    nom VARCHAR(50) NOT NULL UNIQUE,

    -- Description de la categorie (optionnelle)
    description TEXT DEFAULT NULL
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- 3. TABLE "produits"
-- ============================================================
-- Un produit appartient a UNE categorie (relation Many-to-One).
-- La colonne categorie_id est une CLE ETRANGERE (FOREIGN KEY)
-- qui fait reference a la colonne id de la table categories.
--
-- Nouveaux types de donnees :
-- - DECIMAL(10,2) : nombre a virgule avec 2 decimales (pour les prix)
-- - INT UNSIGNED : entier positif uniquement (pas de stock negatif)
-- - DATETIME : date et heure (format : 2026-03-16 14:30:00)
-- - DEFAULT CURRENT_TIMESTAMP : la date se remplit automatiquement
-- ============================================================

CREATE TABLE produits (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    -- Nom du produit (obligatoire, max 100 caracteres)
    nom VARCHAR(100) NOT NULL,

    -- Description detaillee (optionnelle, texte long)
    description TEXT DEFAULT NULL,

    -- Prix en euros : 10 chiffres max dont 2 decimales
    -- Exemples valides : 9.99, 1234.50, 99999999.99
    prix DECIMAL(10, 2) NOT NULL,

    -- Quantite en stock (UNSIGNED = pas de valeurs negatives)
    stock INT UNSIGNED NOT NULL DEFAULT 0,

    -- Cle etrangere vers la table categories
    -- Peut etre NULL si le produit n'a pas encore de categorie
    categorie_id INT UNSIGNED DEFAULT NULL,

    -- Date de creation automatique
    date_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    -- ============================================================
    -- DEFINITION DE LA CLE ETRANGERE
    -- ============================================================
    -- FOREIGN KEY (categorie_id) : la colonne de CETTE table
    -- REFERENCES categories(id)  : pointe vers la colonne id de categories
    --
    -- ON DELETE SET NULL : si la categorie est supprimee,
    --   categorie_id devient NULL (le produit n'est pas supprime)
    --
    -- ON UPDATE CASCADE : si l'id de la categorie change,
    --   categorie_id se met a jour automatiquement
    -- ============================================================
    CONSTRAINT fk_produit_categorie
        FOREIGN KEY (categorie_id) REFERENCES categories(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- 4. TABLE "commandes"
-- ============================================================
-- Une commande concerne UN produit (relation Many-to-One).
-- Elle enregistre la quantite commandee et le statut de la commande.
--
-- Nouveau concept :
-- - ENUM : liste de valeurs autorisees (le champ ne peut contenir
--   QUE l'une des valeurs listees)
-- - ON DELETE CASCADE : si le produit est supprime,
--   ses commandes sont aussi supprimees
-- ============================================================

CREATE TABLE commandes (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    -- Cle etrangere vers la table produits
    produit_id INT UNSIGNED NOT NULL,

    -- Quantite commandee (au moins 1)
    quantite INT UNSIGNED NOT NULL DEFAULT 1,

    -- Date de la commande
    date_commande DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    -- Statut de la commande : seulement ces 4 valeurs sont autorisees
    statut ENUM('en_attente', 'confirmee', 'expediee', 'livree')
        NOT NULL DEFAULT 'en_attente',

    -- Cle etrangere avec suppression en cascade
    CONSTRAINT fk_commande_produit
        FOREIGN KEY (produit_id) REFERENCES produits(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- 5. INDEX SUPPLEMENTAIRES
-- ============================================================
-- Un index accelere les recherches sur une colonne.
-- On cree des index sur les colonnes souvent utilisees dans
-- les clauses WHERE, ORDER BY et JOIN.
-- ============================================================

-- Index sur le nom du produit (recherche par nom frequente)
CREATE INDEX idx_produit_nom ON produits(nom);

-- Index sur la categorie des produits (filtrage par categorie)
CREATE INDEX idx_produit_categorie ON produits(categorie_id);

-- Index sur la date de commande (tri chronologique)
CREATE INDEX idx_commande_date ON commandes(date_commande);

-- Index sur le statut des commandes (filtrage par statut)
CREATE INDEX idx_commande_statut ON commandes(statut);


-- ============================================================
-- 6. INSERTION DES CATEGORIES
-- ============================================================

INSERT INTO categories (nom, description) VALUES
    ('Informatique', 'Ordinateurs, peripheriques et composants'),
    ('Audio', 'Casques, enceintes et equipement sonore'),
    ('Accessoires', 'Cables, tapis de souris et petits equipements'),
    ('Stockage', 'Cles USB, disques durs et cartes memoire'),
    ('Reseau', 'Routeurs, switches et equipement reseau');


-- ============================================================
-- 7. INSERTION DES PRODUITS
-- ============================================================
-- On utilise les id des categories (1=Informatique, 2=Audio, etc.)
-- Grace a AUTO_INCREMENT, les categories ont recu les id 1 a 5
-- dans l'ordre d'insertion.
-- ============================================================

INSERT INTO produits (nom, description, prix, stock, categorie_id) VALUES
    -- Informatique (categorie_id = 1)
    ('Clavier mecanique RGB',
     'Clavier mecanique retroeclaire avec switches Cherry MX Blue.',
     89.99, 45, 1),

    ('Souris sans fil ergonomique',
     'Souris ergonomique Bluetooth et USB, capteur 4000 DPI.',
     39.99, 120, 1),

    ('Ecran 27 pouces 4K',
     'Moniteur IPS 27 pouces, resolution 4K UHD, temps de reponse 5ms.',
     349.99, 18, 1),

    ('Webcam Full HD 1080p',
     'Camera web avec microphone integre et correction de lumiere.',
     59.99, 32, 1),

    -- Audio (categorie_id = 2)
    ('Casque audio Bluetooth',
     'Casque sans fil avec reduction de bruit active, autonomie 30h.',
     79.50, 65, 2),

    ('Enceinte portable waterproof',
     'Enceinte Bluetooth etanche IPX7, 20W, autonomie 12h.',
     49.99, 88, 2),

    ('Microphone USB studio',
     'Microphone a condensateur USB pour podcast et streaming.',
     119.99, 22, 2),

    -- Accessoires (categorie_id = 3)
    ('Cable USB-C vers USB-C 2m',
     'Cable charge rapide 100W et transfert de donnees.',
     12.99, 200, 3),

    ('Tapis de souris XXL',
     'Tapis grand format 80x30cm, surface lisse, base antiderapante.',
     19.99, 150, 3),

    ('Hub USB-C 7 ports',
     'Adaptateur USB-C avec HDMI, USB 3.0, lecteur SD et charge.',
     44.99, 55, 3),

    -- Stockage (categorie_id = 4)
    ('Cle USB 128 Go',
     'Cle USB 3.2 haute vitesse, 128 Go, boitier metallique.',
     24.99, 95, 4),

    ('Disque dur externe 2 To',
     'Disque dur portable USB 3.0, 2 To, compatible Windows et Mac.',
     69.99, 40, 4),

    ('Carte microSD 256 Go',
     'Carte memoire haute vitesse classe 10, ideale pour smartphone.',
     34.99, 110, 4),

    -- Reseau (categorie_id = 5)
    ('Routeur Wi-Fi 6',
     'Routeur double bande AX1800, couverture 150m2, 4 antennes.',
     89.99, 28, 5),

    ('Cable Ethernet Cat 6 - 5m',
     'Cable reseau RJ45 Cat 6, debit 10 Gbps, blinde.',
     8.99, 300, 5);


-- ============================================================
-- 8. INSERTION DES COMMANDES
-- ============================================================
-- On simule 10 commandes sur differents produits
-- avec differents statuts et dates.
-- ============================================================

INSERT INTO commandes (produit_id, quantite, date_commande, statut) VALUES
    (1,  2, '2026-03-01 10:30:00', 'livree'),
    (3,  1, '2026-03-03 14:15:00', 'livree'),
    (5,  3, '2026-03-05 09:00:00', 'expediee'),
    (2,  1, '2026-03-07 16:45:00', 'expediee'),
    (8,  5, '2026-03-09 11:20:00', 'confirmee'),
    (11, 2, '2026-03-10 13:00:00', 'confirmee'),
    (6,  1, '2026-03-12 08:30:00', 'en_attente'),
    (1,  1, '2026-03-13 15:10:00', 'en_attente'),
    (14, 1, '2026-03-14 17:00:00', 'en_attente'),
    (7,  2, '2026-03-15 10:00:00', 'en_attente');


-- ============================================================
-- 9. VERIFICATION : AFFICHER LE CONTENU DES TABLES
-- ============================================================

SELECT '=== CATEGORIES ===' AS '';
SELECT * FROM categories;

SELECT '=== PRODUITS ===' AS '';
SELECT id, nom, prix, stock, categorie_id FROM produits ORDER BY id;

SELECT '=== COMMANDES ===' AS '';
SELECT * FROM commandes ORDER BY date_commande;
