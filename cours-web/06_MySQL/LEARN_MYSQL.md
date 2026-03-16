# Cours MySQL - Bases de donnees relationnelles

## Introduction

MySQL est un **systeme de gestion de bases de donnees relationnelles** (SGBDR). Il permet de stocker, organiser et manipuler des donnees structurees en tables reliees entre elles.

### Pourquoi une base de donnees ?

Sans base de donnees, les donnees sont stockees dans des fichiers (JSON, CSV, texte). Cela pose plusieurs problemes :

- **Performance** : lire un fichier entier pour trouver une seule ligne
- **Concurrence** : deux utilisateurs modifient le fichier en meme temps = corruption
- **Integrite** : rien n'empeche d'inserer des donnees invalides
- **Relations** : difficile de lier des donnees entre fichiers

Une base de donnees resout tous ces problemes grace a un **moteur** optimise pour les lectures/ecritures, un systeme de **verrous** pour la concurrence, des **contraintes** pour l'integrite, et des **cles etrangeres** pour les relations.

### Le modele relationnel

Le modele relationnel organise les donnees en **tables** (aussi appelees relations) :

| Concept       | Description                                   | Exemple                        |
| ------------- | --------------------------------------------- | ------------------------------ |
| **Table**     | Collection de donnees du meme type             | `produits`, `categories`       |
| **Colonne**   | Un attribut de la table                        | `nom`, `prix`, `stock`         |
| **Ligne**     | Un enregistrement (une entite)                 | Un produit specifique          |
| **Cle primaire** | Identifiant unique de chaque ligne          | `id` (auto-increment)         |
| **Cle etrangere** | Lien vers une ligne d'une autre table      | `categorie_id` → `categories.id` |

### SQL : le langage des bases de donnees

**SQL** (Structured Query Language) est le langage standard pour interagir avec les bases relationnelles. Il se divise en deux categories principales :

- **DDL** (Data Definition Language) : definir la structure → `CREATE`, `ALTER`, `DROP`
- **DML** (Data Manipulation Language) : manipuler les donnees → `SELECT`, `INSERT`, `UPDATE`, `DELETE`

---

## 1. Environnements de test

Avant de commencer, il faut pouvoir executer des requetes SQL. Voici trois environnements disponibles :

### 1.1 phpMyAdmin (recommande pour debuter)

phpMyAdmin est une interface web incluse avec MAMP. C'est le moyen le plus simple pour ecrire et tester des requetes.

**Acces :**
```
http://localhost:8888/phpMyAdmin
```

**Utilisation :**
1. Ouvrir phpMyAdmin dans le navigateur
2. Cliquer sur l'onglet **SQL** en haut
3. Coller le contenu d'un fichier `.sql`
4. Cliquer sur **Executer**

**Avantages :**
- Interface visuelle, pas besoin du terminal
- Voir les tables, les colonnes et les donnees en un clic
- Exporter/importer facilement

### 1.2 Terminal MAMP (ligne de commande)

Le client MySQL en ligne de commande est plus rapide une fois qu'on le maitrise.

**Connexion :**
```bash
/Applications/MAMP/Library/bin/mysql -u root -proot
```

> **Note** : il n'y a pas d'espace entre `-p` et le mot de passe `root`.

**Executer un fichier SQL :**
```bash
/Applications/MAMP/Library/bin/mysql -u root -proot < 01_creation.sql
```

**Commandes utiles dans le client :**
```sql
SHOW DATABASES;          -- Lister toutes les bases
USE boutique;            -- Se positionner sur une base
SHOW TABLES;             -- Lister les tables de la base active
DESCRIBE produits;       -- Voir la structure d'une table
SELECT * FROM produits;  -- Voir le contenu d'une table
```

> **Astuce** : chaque commande SQL doit se terminer par un point-virgule `;`.

### 1.3 MySQL Workbench (outil professionnel)

MySQL Workbench est un outil graphique officiel de MySQL, plus complet que phpMyAdmin.

**Installation :**
Telecharger sur [dev.mysql.com/downloads/workbench](https://dev.mysql.com/downloads/workbench/)

**Configuration de la connexion :**
- Hostname : `127.0.0.1`
- Port : `8889` (port MySQL de MAMP)
- Username : `root`
- Password : `root`

**Avantages :**
- Editeur SQL avec coloration syntaxique et auto-completion
- Modelisation visuelle des tables (diagrammes ER)
- Gestion avancee des utilisateurs et permissions

---

## 2. Creer une base de donnees et des tables

> **Fichier de reference** : `01_creation.sql`

### 2.1 CREATE DATABASE

```sql
-- Supprimer la base si elle existe (pour pouvoir relancer le script)
DROP DATABASE IF EXISTS boutique;

-- Creer la base de donnees
CREATE DATABASE boutique
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

-- Se positionner sur la base
USE boutique;
```

**Explications :**
- `DROP DATABASE IF EXISTS` : supprime la base si elle existe deja (utile pour re-executer le script)
- `CHARACTER SET utf8mb4` : supporte les accents francais et les emojis
- `COLLATE utf8mb4_unicode_ci` : tri alphabetique correct (`a` = `A`, `e` = `e`)
- `USE` : toutes les commandes suivantes s'executeront dans cette base

### 2.2 Types de donnees

MySQL propose de nombreux types. Voici les plus courants :

| Type | Description | Exemple |
| --- | --- | --- |
| `INT` | Entier | `42`, `-7` |
| `INT UNSIGNED` | Entier positif uniquement | `0`, `1`, `255` |
| `VARCHAR(n)` | Texte court (max `n` caracteres) | `'Clavier mecanique'` |
| `TEXT` | Texte long (pas de limite pratique) | Description detaillee |
| `DECIMAL(p, d)` | Nombre a virgule fixe (`p` chiffres, `d` decimales) | `89.99`, `1234.50` |
| `DATETIME` | Date et heure | `'2026-03-16 14:30:00'` |
| `ENUM(...)` | Liste de valeurs autorisees | `'en_attente'`, `'confirmee'` |
| `TINYINT(1)` | Booleen (0 ou 1) | `1` (vrai), `0` (faux) |

> **Bonne pratique** : utiliser `DECIMAL` pour les prix (jamais `FLOAT` qui cause des erreurs d'arrondi).

### 2.3 CREATE TABLE avec contraintes

```sql
CREATE TABLE categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL UNIQUE,
    description TEXT DEFAULT NULL
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4;
```

**Contraintes :**

| Contrainte | Effet |
| --- | --- |
| `PRIMARY KEY` | Identifiant unique de chaque ligne, jamais NULL |
| `AUTO_INCREMENT` | MySQL genere automatiquement la valeur (1, 2, 3...) |
| `NOT NULL` | Le champ ne peut pas etre vide |
| `UNIQUE` | Deux lignes ne peuvent pas avoir la meme valeur |
| `DEFAULT valeur` | Valeur par defaut si non specifiee a l'insertion |

### 2.4 Cles etrangeres (FOREIGN KEY)

Les cles etrangeres creent des **liens entre tables**. Elles garantissent que les donnees referencees existent.

```sql
CREATE TABLE produits (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prix DECIMAL(10, 2) NOT NULL,
    stock INT UNSIGNED NOT NULL DEFAULT 0,
    categorie_id INT UNSIGNED DEFAULT NULL,

    CONSTRAINT fk_produit_categorie
        FOREIGN KEY (categorie_id) REFERENCES categories(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);
```

**Comportements ON DELETE / ON UPDATE :**

| Option | Effet |
| --- | --- |
| `CASCADE` | Supprime/modifie aussi les lignes enfants |
| `SET NULL` | Met la cle etrangere a NULL |
| `RESTRICT` | Empeche la suppression/modification (erreur) |

Dans notre schema :
- Supprimer une **categorie** → `produits.categorie_id` = `NULL` (SET NULL)
- Supprimer un **produit** → ses commandes sont aussi supprimees (CASCADE)

### 2.5 Index

Un **index** accelere les recherches sur une colonne, comme l'index d'un livre permet de trouver une page rapidement.

```sql
-- Index sur le nom du produit (recherche par nom frequente)
CREATE INDEX idx_produit_nom ON produits(nom);

-- Index sur la date de commande (tri chronologique)
CREATE INDEX idx_commande_date ON commandes(date_commande);
```

> **Quand creer un index ?** Sur les colonnes utilisees dans `WHERE`, `ORDER BY` et `JOIN`.
> **Attention** : trop d'index ralentissent les insertions et modifications.

---

## 3. Inserer des donnees (INSERT)

> **Fichier de reference** : `01_creation.sql` (section 6-8) et `03_requetes_modification.sql`

### 3.1 Syntaxe de base

```sql
-- Inserer une seule ligne
INSERT INTO produits (nom, description, prix, stock, categorie_id)
VALUES ('Batterie externe 20000mAh',
        'Batterie portable USB-C, charge rapide 65W.',
        45.99, 30, 3);
```

- Lister les colonnes entre parentheses apres le nom de la table
- Les valeurs doivent etre dans le **meme ordre** que les colonnes listees
- Les textes sont entre guillemets simples `'...'`
- Les nombres sont sans guillemets

### 3.2 Insertion multi-lignes

Plus efficace que plusieurs `INSERT` individuels :

```sql
INSERT INTO commandes (produit_id, quantite, statut) VALUES
    (3, 1, 'en_attente'),
    (5, 2, 'en_attente'),
    (10, 1, 'confirmee');
```

### 3.3 LAST_INSERT_ID()

Apres un `INSERT`, on peut recuperer l'`id` genere automatiquement :

```sql
INSERT INTO produits (nom, prix) VALUES ('Nouveau produit', 19.99);
SELECT LAST_INSERT_ID() AS dernier_id;
```

Cela correspond a `lastInsertId()` en PDO (PHP).

---

## 4. Lire des donnees (SELECT)

> **Fichier de reference** : `02_requetes_lecture.sql`

### 4.1 SELECT de base

```sql
-- Toutes les colonnes, toutes les lignes
SELECT * FROM produits;

-- Certaines colonnes seulement (recommande)
SELECT nom, prix, stock FROM produits;

-- Renommer les colonnes avec AS (alias)
SELECT
    nom AS nom_produit,
    prix AS prix_euros
FROM produits;
```

> **Bonne pratique** : toujours lister les colonnes dont on a besoin plutot que `*`. C'est plus rapide et on sait exactement ce qu'on recupere.

### 4.2 Filtrer avec WHERE

```sql
-- Produits dont le prix est superieur a 50 euros
SELECT nom, prix FROM produits WHERE prix > 50;

-- Combiner avec AND (les deux conditions doivent etre vraies)
SELECT nom, prix FROM produits
WHERE categorie_id = 1 AND prix < 100;

-- Combiner avec OR (au moins une condition vraie)
SELECT nom, prix FROM produits
WHERE prix < 20 OR prix > 100;

-- Exclure avec != (different de)
SELECT nom, categorie_id FROM produits
WHERE categorie_id != 3;
```

**Operateurs de comparaison :**

| Operateur | Signification |
| --- | --- |
| `=` | Egal |
| `!=` ou `<>` | Different |
| `<`, `>` | Inferieur, Superieur |
| `<=`, `>=` | Inferieur ou egal, Superieur ou egal |

### 4.3 Tri et pagination

```sql
-- Trier par prix croissant (ASC par defaut)
SELECT nom, prix FROM produits ORDER BY prix ASC;

-- Trier par prix decroissant
SELECT nom, prix FROM produits ORDER BY prix DESC;

-- Les 5 produits les plus chers
SELECT nom, prix FROM produits
ORDER BY prix DESC
LIMIT 5;

-- Pagination : produits 6 a 10
SELECT nom, prix FROM produits
ORDER BY prix DESC
LIMIT 5 OFFSET 5;
```

### 4.4 Filtres avances

```sql
-- LIKE : recherche par motif
-- % = n'importe quelle suite de caracteres
-- _ = un seul caractere
SELECT nom FROM produits WHERE nom LIKE '%usb%';     -- contient "usb"
SELECT nom FROM produits WHERE nom LIKE 'C%';        -- commence par "C"

-- BETWEEN : valeur entre deux bornes (incluses)
SELECT nom, prix FROM produits
WHERE prix BETWEEN 20 AND 80;

-- IN : valeur parmi une liste
SELECT nom, categorie_id FROM produits
WHERE categorie_id IN (1, 2);

-- IS NULL / IS NOT NULL
SELECT nom FROM produits WHERE description IS NULL;
SELECT nom FROM produits WHERE description IS NOT NULL;
```

---

## 5. Fonctions d'agregation

Les fonctions d'agregation calculent **une valeur** a partir de **plusieurs lignes**.

```sql
-- Compter les produits
SELECT COUNT(*) AS total_produits FROM produits;

-- Stock total
SELECT SUM(stock) AS stock_total FROM produits;

-- Prix moyen (arrondi a 2 decimales)
SELECT ROUND(AVG(prix), 2) AS prix_moyen FROM produits;

-- Prix minimum et maximum
SELECT MIN(prix) AS prix_min, MAX(prix) AS prix_max FROM produits;

-- Valeur totale du stock
SELECT ROUND(SUM(prix * stock), 2) AS valeur_stock FROM produits;
```

| Fonction | Calcul |
| --- | --- |
| `COUNT(*)` | Nombre de lignes |
| `SUM(col)` | Somme |
| `AVG(col)` | Moyenne |
| `MIN(col)` | Valeur minimum |
| `MAX(col)` | Valeur maximum |
| `ROUND(val, n)` | Arrondir a `n` decimales |

---

## 6. Regroupement (GROUP BY, HAVING)

### 6.1 GROUP BY

`GROUP BY` regroupe les lignes ayant la meme valeur dans une colonne, puis applique les fonctions d'agregation **par groupe**.

```sql
-- Nombre de produits par categorie
SELECT
    categorie_id,
    COUNT(*) AS nombre_produits
FROM produits
GROUP BY categorie_id;

-- Statistiques detaillees par categorie
SELECT
    categorie_id,
    COUNT(*) AS nombre_produits,
    ROUND(AVG(prix), 2) AS prix_moyen,
    SUM(stock) AS stock_total
FROM produits
GROUP BY categorie_id
ORDER BY nombre_produits DESC;
```

### 6.2 HAVING

`HAVING` filtre les **groupes** (apres le regroupement), contrairement a `WHERE` qui filtre les **lignes** (avant).

```sql
-- Categories avec plus de 3 produits
SELECT categorie_id, COUNT(*) AS nombre_produits
FROM produits
GROUP BY categorie_id
HAVING nombre_produits > 3;

-- Categories dont le prix moyen depasse 50 EUR
SELECT categorie_id, ROUND(AVG(prix), 2) AS prix_moyen
FROM produits
GROUP BY categorie_id
HAVING prix_moyen > 50;
```

**Ordre d'execution d'une requete SQL :**
```
FROM → WHERE → GROUP BY → HAVING → SELECT → ORDER BY → LIMIT
```

C'est pourquoi `WHERE` ne peut pas utiliser les alias definis dans `SELECT`, mais `HAVING` le peut.

---

## 7. Modifier des donnees (UPDATE)

> **Fichier de reference** : `03_requetes_modification.sql`

### Regle d'or : TOUJOURS ajouter une clause WHERE

Un `UPDATE` sans `WHERE` modifie **toutes les lignes** de la table.

**Bonne pratique** : tester d'abord avec un `SELECT` pour verifier quelles lignes seront affectees.

```sql
-- D'abord verifier
SELECT id, nom, prix FROM produits WHERE id = 1;

-- Puis modifier
UPDATE produits
SET prix = 94.99
WHERE id = 1;
```

### Modifier plusieurs champs

```sql
UPDATE produits
SET stock = 25,
    description = 'Nouveau modele 2026.'
WHERE id = 3;
```

### UPDATE avec calcul

```sql
-- Reduire de 10% le prix de tous les produits Audio
UPDATE produits
SET prix = ROUND(prix * 0.90, 2)
WHERE categorie_id = 2;

-- Ajouter 50 unites au stock des cables
UPDATE produits
SET stock = stock + 50
WHERE nom LIKE '%Cable%';
```

---

## 8. Supprimer des donnees (DELETE)

### Attention : la suppression est IRREVERSIBLE

Un `DELETE` sans `WHERE` **vide toute la table**.

```sql
-- D'abord compter les lignes qui seront supprimees
SELECT COUNT(*) FROM commandes
WHERE quantite = 1 AND statut = 'confirmee';

-- Puis supprimer
DELETE FROM commandes
WHERE quantite = 1 AND statut = 'confirmee';
```

### Comportement des cles etrangeres

Quand on supprime une ligne referencee par une cle etrangere, le comportement depend de `ON DELETE` :

```sql
-- Supprimer une categorie :
-- → produits.categorie_id = NULL (ON DELETE SET NULL)
DELETE FROM categories WHERE nom = 'Promotion';

-- Supprimer un produit :
-- → ses commandes sont aussi supprimees (ON DELETE CASCADE)
DELETE FROM produits WHERE id = 16;
```

---

## 9. Jointures (JOIN)

Les jointures sont **la force des bases relationnelles** : elles combinent des donnees de plusieurs tables en une seule requete.

### 9.1 INNER JOIN

Retourne uniquement les lignes qui ont une **correspondance dans les deux tables**.

```sql
-- Produits avec le NOM de leur categorie
SELECT
    p.nom AS produit,
    p.prix,
    c.nom AS categorie
FROM produits p
INNER JOIN categories c ON p.categorie_id = c.id
ORDER BY c.nom, p.nom;
```

**Explication :**
- `FROM produits p` : table de depart (alias `p`)
- `INNER JOIN categories c` : table jointe (alias `c`)
- `ON p.categorie_id = c.id` : condition de jointure

### Jointure sur 3 tables

```sql
-- Commandes avec nom du produit ET nom de la categorie
SELECT
    cmd.id AS commande_id,
    p.nom AS produit,
    c.nom AS categorie,
    cmd.quantite,
    (cmd.quantite * p.prix) AS prix_total,
    cmd.statut
FROM commandes cmd
INNER JOIN produits p ON cmd.produit_id = p.id
INNER JOIN categories c ON p.categorie_id = c.id
ORDER BY cmd.date_commande DESC;
```

### 9.2 LEFT JOIN

Retourne **toutes les lignes** de la table de gauche, meme sans correspondance a droite. Les colonnes de droite sont `NULL` s'il n'y a pas de correspondance.

```sql
-- Toutes les categories, meme celles sans produit
SELECT
    c.nom AS categorie,
    COUNT(p.id) AS nombre_produits
FROM categories c
LEFT JOIN produits p ON c.id = p.categorie_id
GROUP BY c.id, c.nom
ORDER BY nombre_produits DESC;
```

### Trouver les lignes sans correspondance

```sql
-- Produits qui n'ont jamais ete commandes
SELECT p.nom, p.prix, p.stock
FROM produits p
LEFT JOIN commandes cmd ON p.id = cmd.produit_id
WHERE cmd.id IS NULL
ORDER BY p.nom;
```

> **Astuce** : `LEFT JOIN` + `WHERE droite.id IS NULL` est le pattern classique pour trouver les "orphelins".

### COALESCE

`COALESCE(valeur, defaut)` retourne `defaut` si `valeur` est `NULL`.

```sql
SELECT
    p.nom,
    COALESCE(SUM(cmd.quantite), 0) AS total_commande
FROM produits p
LEFT JOIN commandes cmd ON p.id = cmd.produit_id
GROUP BY p.id, p.nom;
```

---

## 10. Modifier la structure (ALTER TABLE)

`ALTER TABLE` modifie la structure d'une table **existante** sans perdre les donnees.

```sql
-- Ajouter une colonne
ALTER TABLE produits
ADD COLUMN poids_grammes INT UNSIGNED DEFAULT NULL
AFTER stock;

-- Modifier le type d'une colonne
ALTER TABLE produits
MODIFY COLUMN poids_grammes DECIMAL(8, 2) DEFAULT NULL;

-- Renommer une colonne (et changer son type)
ALTER TABLE produits
CHANGE COLUMN poids_grammes poids_kg DECIMAL(8, 2) DEFAULT NULL;

-- Supprimer une colonne
ALTER TABLE produits
DROP COLUMN poids_kg;

-- Ajouter une contrainte UNIQUE
ALTER TABLE produits
ADD CONSTRAINT uq_produit_nom UNIQUE (nom);

-- Ajouter une colonne booleenne avec valeur par defaut
ALTER TABLE produits
ADD COLUMN actif TINYINT(1) NOT NULL DEFAULT 1
AFTER categorie_id;
```

---

## 11. Resume : ordre des clauses SQL

```sql
SELECT colonnes           -- 5. Quelles colonnes afficher
FROM table                -- 1. De quelle table
JOIN autre_table ON ...   -- 2. Joindre avec quelle table
WHERE condition           -- 3. Filtrer les lignes
GROUP BY colonne          -- 4. Regrouper
HAVING condition          -- 5. Filtrer les groupes
ORDER BY colonne          -- 6. Trier
LIMIT n OFFSET m          -- 7. Limiter les resultats
```

**Aide-memoire des commandes :**

| Action | Commande |
| --- | --- |
| Creer une base | `CREATE DATABASE nom` |
| Creer une table | `CREATE TABLE nom (colonnes)` |
| Inserer | `INSERT INTO table (cols) VALUES (vals)` |
| Lire | `SELECT cols FROM table WHERE condition` |
| Modifier | `UPDATE table SET col = val WHERE condition` |
| Supprimer | `DELETE FROM table WHERE condition` |
| Modifier structure | `ALTER TABLE table ADD/MODIFY/DROP COLUMN` |

---

## Exercices

### Exercice 1 : Requetes de base (Debutant)

En utilisant la base `boutique` creee par `01_creation.sql`, ecrivez les requetes suivantes :

1. Afficher le nom et le prix de tous les produits de la categorie "Stockage" (categorie_id = 4)
2. Afficher les produits dont le stock est superieur a 100, tries par stock decroissant
3. Afficher les 3 produits les moins chers
4. Afficher les produits dont le nom contient "USB"
5. Afficher les produits dont le prix est entre 40 et 90 euros

<details>
<summary>Solution</summary>

```sql
-- 1. Produits de la categorie Stockage
SELECT nom, prix
FROM produits
WHERE categorie_id = 4;

-- 2. Produits avec stock > 100, tries par stock decroissant
SELECT nom, stock
FROM produits
WHERE stock > 100
ORDER BY stock DESC;

-- 3. Les 3 produits les moins chers
SELECT nom, prix
FROM produits
ORDER BY prix ASC
LIMIT 3;

-- 4. Produits contenant "USB" dans le nom
SELECT nom, prix
FROM produits
WHERE nom LIKE '%USB%';

-- 5. Produits entre 40 et 90 euros
SELECT nom, prix
FROM produits
WHERE prix BETWEEN 40 AND 90;
```

</details>

### Exercice 2 : Agregation et regroupement (Debutant)

1. Compter le nombre total de commandes
2. Calculer le prix moyen des produits de chaque categorie
3. Trouver la categorie qui a le plus grand stock total
4. Afficher le nombre de commandes par statut
5. Afficher les categories dont le prix moyen est inferieur a 50 euros

<details>
<summary>Solution</summary>

```sql
-- 1. Nombre total de commandes
SELECT COUNT(*) AS total_commandes FROM commandes;

-- 2. Prix moyen par categorie
SELECT
    categorie_id,
    ROUND(AVG(prix), 2) AS prix_moyen
FROM produits
GROUP BY categorie_id;

-- 3. Categorie avec le plus grand stock total
SELECT
    categorie_id,
    SUM(stock) AS stock_total
FROM produits
GROUP BY categorie_id
ORDER BY stock_total DESC
LIMIT 1;

-- 4. Nombre de commandes par statut
SELECT
    statut,
    COUNT(*) AS nombre_commandes
FROM commandes
GROUP BY statut;

-- 5. Categories dont le prix moyen < 50 EUR
SELECT
    categorie_id,
    ROUND(AVG(prix), 2) AS prix_moyen
FROM produits
GROUP BY categorie_id
HAVING prix_moyen < 50;
```

</details>

### Exercice 3 : Jointures (Intermediaire)

1. Afficher chaque commande avec le nom du produit et le nom de la categorie
2. Calculer le chiffre d'affaires total (somme de quantite * prix) pour les commandes livrees
3. Afficher les categories avec le nombre de commandes associees (meme les categories sans commande)
4. Trouver le produit le plus commande (en quantite totale)
5. Afficher les produits de la categorie "Audio" qui ont au moins une commande

<details>
<summary>Solution</summary>

```sql
-- 1. Commandes avec nom du produit et categorie
SELECT
    cmd.id AS commande_id,
    p.nom AS produit,
    c.nom AS categorie,
    cmd.quantite,
    cmd.statut,
    cmd.date_commande
FROM commandes cmd
INNER JOIN produits p ON cmd.produit_id = p.id
INNER JOIN categories c ON p.categorie_id = c.id
ORDER BY cmd.date_commande DESC;

-- 2. Chiffre d'affaires des commandes livrees
SELECT
    ROUND(SUM(cmd.quantite * p.prix), 2) AS chiffre_affaires_livrees
FROM commandes cmd
INNER JOIN produits p ON cmd.produit_id = p.id
WHERE cmd.statut = 'livree';

-- 3. Categories avec nombre de commandes (meme sans commande)
SELECT
    c.nom AS categorie,
    COUNT(cmd.id) AS nombre_commandes
FROM categories c
LEFT JOIN produits p ON c.id = p.categorie_id
LEFT JOIN commandes cmd ON p.id = cmd.produit_id
GROUP BY c.id, c.nom
ORDER BY nombre_commandes DESC;

-- 4. Produit le plus commande
SELECT
    p.nom AS produit,
    SUM(cmd.quantite) AS quantite_totale
FROM produits p
INNER JOIN commandes cmd ON p.id = cmd.produit_id
GROUP BY p.id, p.nom
ORDER BY quantite_totale DESC
LIMIT 1;

-- 5. Produits Audio avec au moins une commande
SELECT DISTINCT
    p.nom,
    p.prix
FROM produits p
INNER JOIN categories c ON p.categorie_id = c.id
INNER JOIN commandes cmd ON p.id = cmd.produit_id
WHERE c.nom = 'Audio';
```

</details>

### Exercice 4 : Modifications (Intermediaire)

1. Ajouter un nouveau produit "Souris gaming RGB" dans la categorie Informatique, a 59.99 EUR avec un stock de 40
2. Augmenter de 15% le prix de tous les produits de la categorie Reseau
3. Mettre a jour le statut de toutes les commandes "en_attente" en "confirmee"
4. Supprimer toutes les commandes dont le statut est "livree"
5. Ajouter une colonne `promotion` (TINYINT, default 0) a la table produits, puis mettre en promotion tous les produits dont le stock depasse 100

<details>
<summary>Solution</summary>

```sql
-- 1. Ajouter un nouveau produit
INSERT INTO produits (nom, description, prix, stock, categorie_id)
VALUES ('Souris gaming RGB',
        'Souris gaming filaire, 16000 DPI, eclairage RGB.',
        59.99, 40, 1);

-- 2. Augmenter de 15% les prix Reseau (categorie_id = 5)
-- D'abord verifier
SELECT nom, prix FROM produits WHERE categorie_id = 5;
-- Puis modifier
UPDATE produits
SET prix = ROUND(prix * 1.15, 2)
WHERE categorie_id = 5;

-- 3. Passer les commandes en_attente en confirmee
UPDATE commandes
SET statut = 'confirmee'
WHERE statut = 'en_attente';

-- 4. Supprimer les commandes livrees
-- D'abord compter
SELECT COUNT(*) FROM commandes WHERE statut = 'livree';
-- Puis supprimer
DELETE FROM commandes WHERE statut = 'livree';

-- 5. Ajouter colonne promotion et mettre en promotion
ALTER TABLE produits
ADD COLUMN promotion TINYINT(1) NOT NULL DEFAULT 0;

UPDATE produits
SET promotion = 1
WHERE stock > 100;

-- Verifier
SELECT nom, stock, promotion FROM produits ORDER BY promotion DESC;
```

</details>

### Exercice 5 : Requetes avancees (Avance)

1. Afficher un "tableau de bord" avec : nombre total de produits, valeur totale du stock, nombre de commandes en attente, et chiffre d'affaires total
2. Pour chaque categorie, afficher : le nom, le nombre de produits, le produit le plus cher, et le nombre total de commandes
3. Trouver les produits qui representent plus de 10% de la valeur totale du stock
4. Afficher les commandes du mois de mars 2026 avec le detail produit, triees par montant total decroissant
5. Creer une vue `vue_catalogue` qui affiche les produits avec leur categorie et leur nombre de commandes, puis l'utiliser pour trouver les produits les plus populaires

<details>
<summary>Solution</summary>

```sql
-- 1. Tableau de bord
SELECT
    (SELECT COUNT(*) FROM produits) AS total_produits,
    (SELECT ROUND(SUM(prix * stock), 2) FROM produits) AS valeur_stock,
    (SELECT COUNT(*) FROM commandes WHERE statut = 'en_attente') AS commandes_en_attente,
    (SELECT ROUND(SUM(cmd.quantite * p.prix), 2)
     FROM commandes cmd
     INNER JOIN produits p ON cmd.produit_id = p.id) AS chiffre_affaires_total;

-- 2. Statistiques par categorie
SELECT
    c.nom AS categorie,
    COUNT(DISTINCT p.id) AS nombre_produits,
    MAX(p.prix) AS prix_max,
    COUNT(cmd.id) AS nombre_commandes
FROM categories c
LEFT JOIN produits p ON c.id = p.categorie_id
LEFT JOIN commandes cmd ON p.id = cmd.produit_id
GROUP BY c.id, c.nom
ORDER BY nombre_commandes DESC;

-- 3. Produits qui representent plus de 10% de la valeur du stock
SELECT
    nom,
    ROUND(prix * stock, 2) AS valeur,
    ROUND((prix * stock) / (SELECT SUM(prix * stock) FROM produits) * 100, 2)
        AS pourcentage_stock
FROM produits
HAVING pourcentage_stock > 10
ORDER BY pourcentage_stock DESC;

-- 4. Commandes de mars 2026 avec detail
SELECT
    cmd.id,
    p.nom AS produit,
    cmd.quantite,
    p.prix AS prix_unitaire,
    ROUND(cmd.quantite * p.prix, 2) AS montant_total,
    cmd.statut,
    cmd.date_commande
FROM commandes cmd
INNER JOIN produits p ON cmd.produit_id = p.id
WHERE cmd.date_commande BETWEEN '2026-03-01' AND '2026-03-31'
ORDER BY montant_total DESC;

-- 5. Vue catalogue
CREATE VIEW vue_catalogue AS
SELECT
    p.id,
    p.nom AS produit,
    c.nom AS categorie,
    p.prix,
    p.stock,
    COUNT(cmd.id) AS nombre_commandes,
    COALESCE(SUM(cmd.quantite), 0) AS quantite_totale_commandee
FROM produits p
LEFT JOIN categories c ON p.categorie_id = c.id
LEFT JOIN commandes cmd ON p.id = cmd.produit_id
GROUP BY p.id, p.nom, c.nom, p.prix, p.stock;

-- Utiliser la vue
SELECT * FROM vue_catalogue
ORDER BY nombre_commandes DESC
LIMIT 5;
```

</details>
