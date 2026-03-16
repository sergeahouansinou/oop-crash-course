-- ============================================================
-- SCRIPT SQL 02 : REQUETES DE LECTURE (SELECT)
-- ============================================================
--
-- Ce script demontre les differentes facons de lire des donnees
-- dans MySQL, du plus simple au plus avance :
-- 1. SELECT de base
-- 2. Filtres avec WHERE
-- 3. Tri et limitation (ORDER BY, LIMIT)
-- 4. Filtres avances (LIKE, BETWEEN, IN, IS NULL)
-- 5. Fonctions d'agregation (COUNT, SUM, AVG, MIN, MAX)
-- 6. Regroupement (GROUP BY, HAVING)
-- 7. Jointures (INNER JOIN, LEFT JOIN)
--
-- Chaque requete est precedee d'un commentaire expliquant
-- ce qu'elle fait et le resultat attendu.
--
-- Prerequis : avoir execute 01_creation.sql avant ce fichier.
--
-- ============================================================

USE boutique;


-- ############################################################
-- 1. SELECT DE BASE
-- ############################################################

-- Selectionner TOUTES les colonnes de TOUS les produits
-- L'etoile (*) signifie "toutes les colonnes"
SELECT * FROM produits;

-- Selectionner seulement certaines colonnes
-- Bonne pratique : toujours lister les colonnes dont on a besoin
-- (plus rapide que *, et on sait exactement ce qu'on recupere)
SELECT nom, prix, stock FROM produits;

-- Renommer une colonne dans le resultat avec AS (alias)
-- Utile pour rendre les resultats plus lisibles
SELECT
    nom AS nom_produit,
    prix AS prix_euros,
    stock AS quantite_disponible
FROM produits;


-- ############################################################
-- 2. FILTRER AVEC WHERE
-- ############################################################

-- Produits dont le prix est superieur a 50 euros
SELECT nom, prix
FROM produits
WHERE prix > 50;

-- Produits de la categorie "Informatique" (categorie_id = 1)
SELECT nom, prix, stock
FROM produits
WHERE categorie_id = 1;

-- Produits en rupture de stock (stock = 0)
SELECT nom, prix
FROM produits
WHERE stock = 0;

-- Produits avec un stock faible (inferieur ou egal a 30)
SELECT nom, stock
FROM produits
WHERE stock <= 30;

-- Combiner plusieurs conditions avec AND (les deux doivent etre vraies)
-- Produits de la categorie Informatique ET dont le prix est < 100
SELECT nom, prix
FROM produits
WHERE categorie_id = 1 AND prix < 100;

-- Combiner avec OR (au moins une condition doit etre vraie)
-- Produits qui coutent moins de 20 EUR OU plus de 100 EUR
SELECT nom, prix
FROM produits
WHERE prix < 20 OR prix > 100;

-- Exclure avec != (different de)
-- Tous les produits sauf ceux de la categorie Accessoires (id = 3)
SELECT nom, prix, categorie_id
FROM produits
WHERE categorie_id != 3;


-- ############################################################
-- 3. TRI ET LIMITATION
-- ############################################################

-- Trier par prix croissant (ASC = ascending = du plus petit au plus grand)
-- ASC est la valeur par defaut, on peut l'omettre
SELECT nom, prix
FROM produits
ORDER BY prix ASC;

-- Trier par prix decroissant (DESC = descending = du plus grand au plus petit)
SELECT nom, prix
FROM produits
ORDER BY prix DESC;

-- Trier par plusieurs colonnes
-- D'abord par categorie, puis par prix decroissant dans chaque categorie
SELECT nom, prix, categorie_id
FROM produits
ORDER BY categorie_id ASC, prix DESC;

-- LIMIT : recuperer seulement les N premiers resultats
-- Les 5 produits les plus chers
SELECT nom, prix
FROM produits
ORDER BY prix DESC
LIMIT 5;

-- LIMIT avec OFFSET : sauter les N premiers resultats (pagination)
-- Produits 6 a 10 (on saute les 5 premiers, on en prend 5)
-- Syntaxe : LIMIT nombre OFFSET decalage
SELECT nom, prix
FROM produits
ORDER BY prix DESC
LIMIT 5 OFFSET 5;


-- ############################################################
-- 4. FILTRES AVANCES
-- ############################################################

-- LIKE : recherche par motif (pattern matching)
-- % remplace n'importe quelle suite de caracteres
-- _ remplace un seul caractere

-- Produits dont le nom CONTIENT "usb" (n'importe ou dans le nom)
SELECT nom, prix
FROM produits
WHERE nom LIKE '%usb%';

-- Produits dont le nom COMMENCE par "C"
SELECT nom
FROM produits
WHERE nom LIKE 'C%';

-- Produits dont le nom TERMINE par "m"
SELECT nom
FROM produits
WHERE nom LIKE '%m';

-- BETWEEN : valeur comprise entre deux bornes (incluses)
-- Produits dont le prix est entre 20 et 80 euros
SELECT nom, prix
FROM produits
WHERE prix BETWEEN 20 AND 80;

-- IN : valeur parmi une liste de valeurs possibles
-- Produits des categories Informatique (1) ou Audio (2)
-- Equivalent a : WHERE categorie_id = 1 OR categorie_id = 2
SELECT nom, prix, categorie_id
FROM produits
WHERE categorie_id IN (1, 2);

-- NOT IN : exclure certaines valeurs
SELECT nom, categorie_id
FROM produits
WHERE categorie_id NOT IN (3, 5);

-- IS NULL : verifier si une valeur est vide (NULL)
-- Produits sans description
SELECT nom
FROM produits
WHERE description IS NULL;

-- IS NOT NULL : verifier si une valeur existe
-- Produits qui ont une description
SELECT nom
FROM produits
WHERE description IS NOT NULL;

-- Combiner plusieurs filtres avances
-- Produits de categorie Informatique, entre 30 et 100 EUR, tries par prix
SELECT nom, prix, stock
FROM produits
WHERE categorie_id = 1
  AND prix BETWEEN 30 AND 100
ORDER BY prix ASC;


-- ############################################################
-- 5. FONCTIONS D'AGREGATION
-- ############################################################
-- Les fonctions d'agregation calculent UNE valeur a partir
-- de PLUSIEURS lignes. Elles sont souvent utilisees avec GROUP BY.

-- COUNT(*) : compter le nombre de lignes
-- Combien de produits dans la base ?
SELECT COUNT(*) AS total_produits
FROM produits;

-- COUNT avec WHERE : combien de produits en categorie Informatique ?
SELECT COUNT(*) AS produits_informatique
FROM produits
WHERE categorie_id = 1;

-- SUM() : calculer la somme
-- Stock total de tous les produits
SELECT SUM(stock) AS stock_total
FROM produits;

-- Valeur totale du stock (somme de prix * stock pour chaque produit)
SELECT SUM(prix * stock) AS valeur_stock_total
FROM produits;

-- AVG() : calculer la moyenne
-- Prix moyen des produits
-- ROUND(valeur, decimales) arrondit le resultat
SELECT ROUND(AVG(prix), 2) AS prix_moyen
FROM produits;

-- MIN() et MAX() : trouver le minimum et le maximum
-- Produit le moins cher et le plus cher
SELECT
    MIN(prix) AS prix_minimum,
    MAX(prix) AS prix_maximum
FROM produits;

-- Combiner plusieurs fonctions d'agregation en une seule requete
SELECT
    COUNT(*) AS total_produits,
    SUM(stock) AS stock_total,
    ROUND(AVG(prix), 2) AS prix_moyen,
    MIN(prix) AS prix_min,
    MAX(prix) AS prix_max,
    ROUND(SUM(prix * stock), 2) AS valeur_stock
FROM produits;


-- ############################################################
-- 6. GROUP BY ET HAVING
-- ############################################################

-- GROUP BY : regrouper les lignes par valeur commune
-- Nombre de produits PAR categorie
SELECT
    categorie_id,
    COUNT(*) AS nombre_produits
FROM produits
GROUP BY categorie_id;

-- Statistiques par categorie (plus detaille)
SELECT
    categorie_id,
    COUNT(*) AS nombre_produits,
    SUM(stock) AS stock_total,
    ROUND(AVG(prix), 2) AS prix_moyen,
    MIN(prix) AS prix_min,
    MAX(prix) AS prix_max
FROM produits
GROUP BY categorie_id
ORDER BY nombre_produits DESC;

-- Nombre de commandes par statut
SELECT
    statut,
    COUNT(*) AS nombre_commandes
FROM commandes
GROUP BY statut;

-- HAVING : filtrer les GROUPES (comme WHERE, mais apres GROUP BY)
-- WHERE filtre les lignes AVANT le regroupement
-- HAVING filtre les groupes APRES le regroupement

-- Categories qui ont plus de 3 produits
SELECT
    categorie_id,
    COUNT(*) AS nombre_produits
FROM produits
GROUP BY categorie_id
HAVING nombre_produits > 3;

-- Categories dont le prix moyen depasse 50 EUR
SELECT
    categorie_id,
    ROUND(AVG(prix), 2) AS prix_moyen
FROM produits
GROUP BY categorie_id
HAVING prix_moyen > 50;


-- ############################################################
-- 7. JOINTURES (JOIN)
-- ############################################################
-- Les jointures permettent de combiner des donnees de
-- PLUSIEURS tables en une seule requete.
-- C'est la force des bases de donnees relationnelles.

-- ========================================
-- INNER JOIN : seulement les correspondances
-- ========================================
-- Retourne uniquement les lignes qui ont une correspondance
-- dans LES DEUX tables.

-- Afficher chaque produit avec le NOM de sa categorie
-- (au lieu de juste categorie_id)
SELECT
    p.nom AS produit,
    p.prix,
    p.stock,
    c.nom AS categorie
FROM produits p
INNER JOIN categories c ON p.categorie_id = c.id
ORDER BY c.nom, p.nom;

-- Explication :
-- FROM produits p       : on part de la table produits (alias "p")
-- INNER JOIN categories c : on joint avec categories (alias "c")
-- ON p.categorie_id = c.id : la condition de jointure
--    (le categorie_id du produit = l'id de la categorie)

-- Afficher les commandes avec le nom du produit
SELECT
    cmd.id AS commande_id,
    p.nom AS produit,
    cmd.quantite,
    cmd.date_commande,
    cmd.statut
FROM commandes cmd
INNER JOIN produits p ON cmd.produit_id = p.id
ORDER BY cmd.date_commande DESC;

-- Jointure sur 3 tables : commandes + produits + categories
SELECT
    cmd.id AS commande_id,
    p.nom AS produit,
    c.nom AS categorie,
    cmd.quantite,
    p.prix AS prix_unitaire,
    (cmd.quantite * p.prix) AS prix_total,
    cmd.statut,
    cmd.date_commande
FROM commandes cmd
INNER JOIN produits p ON cmd.produit_id = p.id
INNER JOIN categories c ON p.categorie_id = c.id
ORDER BY cmd.date_commande DESC;


-- ========================================
-- LEFT JOIN : tout de la table de gauche
-- ========================================
-- Retourne TOUTES les lignes de la table de gauche,
-- meme si elles n'ont pas de correspondance a droite.
-- Les colonnes de droite seront NULL s'il n'y a pas de correspondance.

-- Toutes les categories, meme celles sans produit
SELECT
    c.nom AS categorie,
    COUNT(p.id) AS nombre_produits
FROM categories c
LEFT JOIN produits p ON c.id = p.categorie_id
GROUP BY c.id, c.nom
ORDER BY nombre_produits DESC;

-- Tous les produits, meme ceux sans commande
SELECT
    p.nom AS produit,
    COUNT(cmd.id) AS nombre_commandes,
    COALESCE(SUM(cmd.quantite), 0) AS total_commande
FROM produits p
LEFT JOIN commandes cmd ON p.id = cmd.produit_id
GROUP BY p.id, p.nom
ORDER BY nombre_commandes DESC;

-- COALESCE(valeur, defaut) : retourne "defaut" si "valeur" est NULL
-- Ici, si un produit n'a aucune commande, SUM serait NULL,
-- COALESCE le remplace par 0.


-- ========================================
-- EXEMPLES PRATIQUES COURANTS
-- ========================================

-- Les 5 produits les plus commandes
SELECT
    p.nom AS produit,
    SUM(cmd.quantite) AS total_commande
FROM produits p
INNER JOIN commandes cmd ON p.id = cmd.produit_id
GROUP BY p.id, p.nom
ORDER BY total_commande DESC
LIMIT 5;

-- Chiffre d'affaires par categorie (commandes confirmees et livrees)
SELECT
    c.nom AS categorie,
    COUNT(cmd.id) AS nombre_commandes,
    ROUND(SUM(cmd.quantite * p.prix), 2) AS chiffre_affaires
FROM categories c
INNER JOIN produits p ON c.id = p.categorie_id
INNER JOIN commandes cmd ON p.id = cmd.produit_id
WHERE cmd.statut IN ('confirmee', 'expediee', 'livree')
GROUP BY c.id, c.nom
ORDER BY chiffre_affaires DESC;

-- Produits qui n'ont jamais ete commandes
SELECT p.nom, p.prix, p.stock
FROM produits p
LEFT JOIN commandes cmd ON p.id = cmd.produit_id
WHERE cmd.id IS NULL
ORDER BY p.nom;
