-- ============================================================
-- SCRIPT SQL 03 : REQUETES DE MODIFICATION
-- ============================================================
--
-- Ce script demontre comment modifier les donnees et la
-- structure des tables dans MySQL :
-- 1. INSERT : inserer de nouvelles lignes
-- 2. UPDATE : modifier des lignes existantes
-- 3. DELETE : supprimer des lignes
-- 4. ALTER TABLE : modifier la structure d'une table
--
-- ATTENTION : les requetes UPDATE et DELETE modifient ou
-- suppriment des donnees de maniere IRREVERSIBLE.
-- Toujours tester avec un SELECT avant d'executer un UPDATE/DELETE.
--
-- Prerequis : avoir execute 01_creation.sql avant ce fichier.
--
-- ============================================================

USE boutique;


-- ############################################################
-- 1. INSERT : INSERER DES DONNEES
-- ############################################################

-- ========================================
-- Syntaxe classique : une seule ligne
-- ========================================

-- Inserer un nouveau produit
INSERT INTO produits (nom, description, prix, stock, categorie_id)
VALUES ('Batterie externe 20000mAh',
        'Batterie portable USB-C, charge rapide 65W.',
        45.99, 30, 3);

-- Verifier l'insertion
-- lastInsertId en SQL : LAST_INSERT_ID() retourne l'id genere
SELECT LAST_INSERT_ID() AS dernier_id_insere;
SELECT * FROM produits WHERE id = LAST_INSERT_ID();


-- ========================================
-- Syntaxe multi-lignes : plusieurs lignes d'un coup
-- ========================================
-- Plus efficace que plusieurs INSERT individuels

INSERT INTO commandes (produit_id, quantite, statut) VALUES
    (3, 1, 'en_attente'),
    (5, 2, 'en_attente'),
    (10, 1, 'confirmee');

-- Verifier : les 3 dernieres commandes
SELECT * FROM commandes ORDER BY id DESC LIMIT 3;


-- ========================================
-- INSERT avec sous-requete
-- ========================================
-- Inserer des donnees basees sur une requete SELECT

-- Creer une nouvelle categorie
INSERT INTO categories (nom, description)
VALUES ('Promotion', 'Produits en promotion temporaire');

-- Verifier
SELECT * FROM categories ORDER BY id DESC LIMIT 1;


-- ############################################################
-- 2. UPDATE : MODIFIER DES DONNEES
-- ############################################################
--
-- REGLE D'OR : TOUJOURS ajouter une clause WHERE
-- Un UPDATE sans WHERE modifie TOUTES les lignes de la table !
--
-- Bonne pratique : tester d'abord avec un SELECT
-- pour verifier quelles lignes seront affectees.

-- ========================================
-- Modifier un seul champ d'un seul produit
-- ========================================

-- D'abord, verifier l'etat actuel du produit #1
SELECT id, nom, prix FROM produits WHERE id = 1;

-- Augmenter le prix du produit #1
UPDATE produits
SET prix = 94.99
WHERE id = 1;

-- Verifier la modification
SELECT id, nom, prix FROM produits WHERE id = 1;


-- ========================================
-- Modifier plusieurs champs en meme temps
-- ========================================

-- Mettre a jour le stock et la description du produit #3
UPDATE produits
SET stock = 25,
    description = 'Moniteur IPS 27 pouces 4K UHD, HDR400, USB-C. Nouveau modele 2026.'
WHERE id = 3;

-- Verifier
SELECT id, nom, stock, description FROM produits WHERE id = 3;


-- ========================================
-- UPDATE avec condition complexe
-- ========================================

-- Reduire de 10% le prix de tous les produits de la categorie Audio (id=2)
-- D'abord, voir les prix actuels
SELECT nom, prix FROM produits WHERE categorie_id = 2;

-- Appliquer la reduction
UPDATE produits
SET prix = ROUND(prix * 0.90, 2)
WHERE categorie_id = 2;

-- Verifier les nouveaux prix
SELECT nom, prix FROM produits WHERE categorie_id = 2;


-- ========================================
-- UPDATE avec calcul sur le stock
-- ========================================

-- Ajouter 50 unites au stock de tous les cables (nom contient "Cable")
-- D'abord verifier
SELECT nom, stock FROM produits WHERE nom LIKE '%Cable%';

-- Ajouter au stock
UPDATE produits
SET stock = stock + 50
WHERE nom LIKE '%Cable%';

-- Verifier
SELECT nom, stock FROM produits WHERE nom LIKE '%Cable%';


-- ========================================
-- Passer des commandes en "confirmee"
-- ========================================

-- Toutes les commandes "en_attente" passent en "confirmee"
-- D'abord verifier
SELECT id, statut FROM commandes WHERE statut = 'en_attente';

-- Modifier le statut
UPDATE commandes
SET statut = 'confirmee'
WHERE statut = 'en_attente';

-- Verifier
SELECT id, statut FROM commandes ORDER BY id;


-- ############################################################
-- 3. DELETE : SUPPRIMER DES DONNEES
-- ############################################################
--
-- ATTENTION : la suppression est IRREVERSIBLE.
-- Comme pour UPDATE, toujours ajouter une clause WHERE.
-- Un DELETE sans WHERE VIDE TOUTE LA TABLE !
--
-- Bonne pratique : tester avec SELECT d'abord.

-- ========================================
-- Supprimer une ligne specifique
-- ========================================

-- Verifier que le produit #16 existe (la batterie ajoutee plus haut)
SELECT id, nom FROM produits WHERE id = 16;

-- Supprimer le produit #16
DELETE FROM produits
WHERE id = 16;

-- Verifier : le produit n'existe plus
SELECT id, nom FROM produits WHERE id = 16;
-- Resultat : 0 lignes (vide)


-- ========================================
-- Supprimer avec une condition
-- ========================================

-- Supprimer les commandes dont la quantite est 1
-- et le statut est "confirmee"
-- D'abord compter combien seront supprimees
SELECT COUNT(*) AS commandes_a_supprimer
FROM commandes
WHERE quantite = 1 AND statut = 'confirmee';

-- Supprimer
DELETE FROM commandes
WHERE quantite = 1 AND statut = 'confirmee';


-- ========================================
-- Attention aux cles etrangeres
-- ========================================
-- Si on essaie de supprimer une categorie qui a des produits,
-- le comportement depend de ON DELETE :
--
-- ON DELETE CASCADE  : supprime aussi les lignes enfants
-- ON DELETE SET NULL : met la cle etrangere a NULL
-- ON DELETE RESTRICT : empeche la suppression (erreur)
--
-- Dans notre schema :
-- - Supprimer une categorie => produits.categorie_id = NULL (SET NULL)
-- - Supprimer un produit => ses commandes sont supprimees (CASCADE)

-- Exemple : supprimer la categorie "Promotion" (ajoutee plus haut)
DELETE FROM categories WHERE nom = 'Promotion';


-- ############################################################
-- 4. ALTER TABLE : MODIFIER LA STRUCTURE
-- ############################################################
-- ALTER TABLE modifie la structure d'une table EXISTANTE.
-- On peut ajouter, modifier ou supprimer des colonnes.

-- ========================================
-- Ajouter une colonne
-- ========================================

-- Ajouter une colonne "poids_grammes" a la table produits
ALTER TABLE produits
ADD COLUMN poids_grammes INT UNSIGNED DEFAULT NULL
AFTER stock;

-- AFTER stock : la nouvelle colonne sera placee apres "stock"
-- Verifier la structure
DESCRIBE produits;


-- ========================================
-- Modifier le type d'une colonne
-- ========================================

-- Changer le type de poids_grammes en DECIMAL (pour les fractions)
ALTER TABLE produits
MODIFY COLUMN poids_grammes DECIMAL(8, 2) DEFAULT NULL;

-- Verifier
DESCRIBE produits;


-- ========================================
-- Renommer une colonne
-- ========================================

-- Renommer poids_grammes en poids_kg
ALTER TABLE produits
CHANGE COLUMN poids_grammes poids_kg DECIMAL(8, 2) DEFAULT NULL;

-- CHANGE permet de changer le NOM et le TYPE en meme temps
-- Verifier
DESCRIBE produits;


-- ========================================
-- Supprimer une colonne
-- ========================================

-- Supprimer la colonne poids_kg
ALTER TABLE produits
DROP COLUMN poids_kg;

-- Verifier que la colonne n'existe plus
DESCRIBE produits;


-- ========================================
-- Ajouter une contrainte UNIQUE
-- ========================================

-- Empecher deux produits d'avoir le meme nom
ALTER TABLE produits
ADD CONSTRAINT uq_produit_nom UNIQUE (nom);

-- Test : essayer d'inserer un doublon (provoquera une erreur)
-- INSERT INTO produits (nom, prix) VALUES ('Clavier mecanique RGB', 50.00);
-- Erreur : Duplicate entry 'Clavier mecanique RGB' for key 'uq_produit_nom'


-- ========================================
-- Ajouter une colonne avec valeur par defaut
-- ========================================

-- Ajouter un champ "actif" pour activer/desactiver un produit
ALTER TABLE produits
ADD COLUMN actif TINYINT(1) NOT NULL DEFAULT 1
AFTER categorie_id;

-- TINYINT(1) est souvent utilise comme BOOLEAN en MySQL
-- 1 = actif (true), 0 = inactif (false)
-- DEFAULT 1 : tous les produits existants seront actifs

-- Verifier
SELECT id, nom, actif FROM produits;


-- ============================================================
-- RESUME DES COMMANDES DE MODIFICATION
-- ============================================================
--
-- INSERT INTO table (col1, col2) VALUES (val1, val2);
-- INSERT INTO table (col1, col2) VALUES (v1, v2), (v3, v4);
--
-- UPDATE table SET col1 = val1, col2 = val2 WHERE condition;
-- !! TOUJOURS mettre un WHERE !!
--
-- DELETE FROM table WHERE condition;
-- !! TOUJOURS mettre un WHERE !!
--
-- ALTER TABLE table ADD COLUMN nom TYPE;
-- ALTER TABLE table MODIFY COLUMN nom NOUVEAU_TYPE;
-- ALTER TABLE table CHANGE COLUMN ancien_nom nouveau_nom TYPE;
-- ALTER TABLE table DROP COLUMN nom;
-- ALTER TABLE table ADD CONSTRAINT nom_contrainte UNIQUE (colonne);
--
-- ============================================================
