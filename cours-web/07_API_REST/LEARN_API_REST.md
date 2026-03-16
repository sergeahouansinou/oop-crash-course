# Cours complet : API REST en PHP avec MySQL

---

## Table des matieres

1. [Introduction : qu'est-ce qu'une API REST ?](#1-introduction--quest-ce-quune-api-rest-)
2. [Les principes REST](#2-les-principes-rest)
3. [Installer et configurer le projet](#3-installer-et-configurer-le-projet)
4. [Connexion a MySQL avec PDO](#4-connexion-a-mysql-avec-pdo)
5. [Le modele : la classe Produit (CRUD)](#5-le-modele--la-classe-produit-crud)
6. [Le routeur : point d'entree de l'API](#6-le-routeur--point-dentree-de-lapi)
7. [Les reponses JSON et les codes HTTP](#7-les-reponses-json-et-les-codes-http)
8. [Tester l'API avec Postman](#8-tester-lapi-avec-postman)
9. [Validation et gestion des erreurs](#9-validation-et-gestion-des-erreurs)
10. [Securite et bonnes pratiques](#10-securite-et-bonnes-pratiques)
11. [Exercices progressifs](#11-exercices-progressifs)

---

## 1. Introduction : qu'est-ce qu'une API REST ?

### Definition

**API** signifie **Application Programming Interface** (interface de programmation). C'est un ensemble de regles qui permet a deux logiciels de communiquer entre eux.

**REST** signifie **REpresentational State Transfer**. C'est un style d'architecture pour concevoir des API web.

Concretement, une API REST permet a un **client** (Postman, navigateur, application mobile, frontend JavaScript) d'envoyer des requetes HTTP a un **serveur** pour manipuler des donnees.

### Exemple concret

Imaginons un magasin d'informatique qui veut gerer son stock :

```
Client (Postman)                    Serveur (PHP + MySQL)
      |                                      |
      |--- GET /produits ------------------>|  (lire tous les produits)
      |<-- JSON [liste des produits] -------|
      |                                      |
      |--- POST /creer ------------------->|  (creer un nouveau produit)
      |    Body: {"nom": "Clavier", ...}    |
      |<-- JSON {"id": 11, "nom": ...} ----|
      |                                      |
      |--- DELETE /supprimer?id=11 -------->|  (supprimer le produit)
      |<-- JSON {"message": "Supprime"} ----|
```

### Pourquoi ce module ?

Ce module met en pratique **tout ce que vous avez appris** dans les cours precedents :

| Cours precedent | Utilise dans l'API |
|-----------------|-------------------|
| **PHP OOP** (module 04) | Classes `Database` et `Produit` avec proprietes typees |
| **Formulaire** (module 05) | Validation des donnees, nettoyage, codes d'erreur |
| **JavaScript** (module 03) | Le frontend utilisera `fetch()` pour appeler l'API |
| **HTML** (module 01) | Structure des reponses (JSON remplace HTML) |

---

## 2. Les principes REST

### Les 4 verbes HTTP (methodes)

REST utilise les methodes HTTP standard pour definir l'action a effectuer :

| Methode HTTP | Action CRUD | Description | Exemple |
|-------------|-------------|-------------|---------|
| **GET** | Read (Lire) | Recuperer des donnees | Lister les produits |
| **POST** | Create (Creer) | Ajouter une nouvelle ressource | Ajouter un produit |
| **PUT** | Update (Modifier) | Mettre a jour une ressource existante | Modifier le prix |
| **DELETE** | Delete (Supprimer) | Supprimer une ressource | Retirer un produit |

### Les codes de reponse HTTP

Le serveur repond avec un **code de statut** qui indique le resultat :

| Code | Nom | Signification |
|------|-----|---------------|
| **200** | OK | La requete a reussi |
| **201** | Created | La ressource a ete creee |
| **400** | Bad Request | La requete est mal formee |
| **404** | Not Found | La ressource n'existe pas |
| **405** | Method Not Allowed | La methode HTTP n'est pas autorisee |
| **422** | Unprocessable Entity | Les donnees sont invalides |
| **500** | Internal Server Error | Erreur cote serveur |

### Le format JSON

REST utilise le format **JSON** (JavaScript Object Notation) pour echanger des donnees :

```json
{
    "erreur": false,
    "produit": {
        "id": 1,
        "nom": "Clavier mecanique RGB",
        "prix": 89.99,
        "stock": 45,
        "categorie": "Informatique"
    }
}
```

JSON est lisible par les humains **et** par les machines. Tous les langages de programmation savent lire et ecrire du JSON.

---

## 3. Installer et configurer le projet

### Structure des fichiers

```
06_API_REST/
├── config/
│   └── database.php       # Classe Database : connexion PDO a MySQL
├── models/
│   └── Produit.php        # Classe Produit : operations CRUD
├── index.php              # Point d'entree / routeur de l'API
├── database.sql           # Script SQL pour creer la base et les donnees
└── LEARN_API_REST.md      # Ce fichier de cours
```

### Etape 1 : Creer la base de donnees

1. Demarrer MAMP
2. Ouvrir phpMyAdmin : `http://localhost:8888/phpMyAdmin`
3. Cliquer sur l'onglet **SQL**
4. Copier-coller le contenu du fichier `database.sql`
5. Cliquer sur **Executer**

Le script cree :
- La base de donnees `magasin`
- La table `produits` avec 10 produits d'exemple

### Etape 2 : Verifier la connexion

Tester dans le navigateur :

```
http://localhost:8888/oop-crash-course/cours-web/06_API_REST/index.php?action=produits
```

Vous devriez voir la liste des produits en JSON.

### Etape 3 : Installer Postman

Postman est un outil gratuit pour tester les API sans ecrire de code.

1. Telecharger Postman sur [postman.com/downloads](https://www.postman.com/downloads/)
2. Creer un compte gratuit (optionnel)
3. L'URL de base de l'API sera : `http://localhost:8888/oop-crash-course/cours-web/06_API_REST/index.php`

---

## 4. Connexion a MySQL avec PDO

### Qu'est-ce que PDO ?

**PDO** (PHP Data Objects) est l'interface standard de PHP pour communiquer avec les bases de donnees. Elle offre trois avantages majeurs :

1. **Portabilite** : le meme code fonctionne avec MySQL, PostgreSQL, SQLite...
2. **Securite** : les requetes preparees protegent contre l'injection SQL
3. **Gestion des erreurs** : les exceptions permettent de capter les erreurs proprement

### La classe Database

```php
class Database
{
    private string $hote = 'localhost';
    private string $nomBase = 'magasin';
    private string $utilisateur = 'root';
    private string $motDePasse = 'root';
    private int $port = 8889;
    private ?PDO $connexion = null;

    public function getConnexion(): PDO
    {
        if ($this->connexion === null) {
            $dsn = "mysql:host={$this->hote};port={$this->port};dbname={$this->nomBase};charset=utf8mb4";

            $this->connexion = new PDO($dsn, $this->utilisateur, $this->motDePasse, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        }

        return $this->connexion;
    }
}
```

### Le DSN (Data Source Name)

Le DSN est la chaine de connexion qui decrit **ou** se trouve la base de donnees :

```
mysql:host=localhost;port=8889;dbname=magasin;charset=utf8mb4
  |         |           |           |              |
  |     serveur       port       base         encodage
  |
  driver (type de BDD)
```

### Les options PDO importantes

| Option | Valeur | Pourquoi |
|--------|--------|----------|
| `ATTR_ERRMODE` | `ERRMODE_EXCEPTION` | Lance une exception en cas d'erreur (au lieu de retourner `false` silencieusement) |
| `ATTR_DEFAULT_FETCH_MODE` | `FETCH_ASSOC` | Les resultats sont des tableaux associatifs (`['nom' => 'Clavier']`) |
| `ATTR_EMULATE_PREPARES` | `false` | Les parametres sont envoyes separement au serveur MySQL (meilleure securite) |

### Patron Singleton simplifie

La methode `getConnexion()` reutilise la meme connexion si elle existe deja (`$this->connexion !== null`). Cela evite d'ouvrir une nouvelle connexion a chaque requete, ce qui serait lent et gaspillerait des ressources.

---

## 5. Le modele : la classe Produit (CRUD)

### Injection de dependances

La classe `Produit` recoit la connexion PDO via son constructeur :

```php
class Produit
{
    private PDO $connexion;

    public function __construct(PDO $connexion)
    {
        $this->connexion = $connexion;
    }
}
```

**Pourquoi ?** La classe `Produit` ne sait pas **comment** se connecter a la base. Elle recoit une connexion deja etablie. C'est le principe d'**injection de dependances** : la dependance (PDO) est "injectee" de l'exterieur.

### Les requetes preparees

**Regle absolue : ne JAMAIS inserer de variables directement dans une requete SQL.**

```php
// DANGEREUX : injection SQL possible
$requete = "SELECT * FROM produits WHERE id = $id";

// SECURISE : requete preparee avec parametre nomme
$requete = $this->connexion->prepare("SELECT * FROM produits WHERE id = :id");
$requete->bindValue(':id', $id, PDO::PARAM_INT);
$requete->execute();
```

Le fonctionnement en 3 etapes :

1. **prepare()** : envoie le squelette de la requete a MySQL (avec des `:placeholders`)
2. **bindValue()** : associe une valeur a chaque placeholder
3. **execute()** : execute la requete avec les valeurs liees

MySQL sait que `:id` est une **valeur**, pas du code SQL. Meme si un attaquant envoie `1 OR 1=1`, MySQL le traitera comme une chaine de caracteres, pas comme du code.

### Lire tous les produits (READ)

```php
public function lireTous(): array
{
    $requete = $this->connexion->prepare(
        "SELECT id, nom, description, prix, stock, categorie, date_creation
         FROM produits
         ORDER BY date_creation DESC"
    );
    $requete->execute();

    return $requete->fetchAll();
}
```

- `fetchAll()` retourne **toutes** les lignes sous forme de tableau de tableaux associatifs
- `ORDER BY date_creation DESC` affiche les produits les plus recents en premier

### Lire un produit par son ID (READ)

```php
public function lireParId(int $id): array|false
{
    $requete = $this->connexion->prepare(
        "SELECT * FROM produits WHERE id = :id LIMIT 1"
    );
    $requete->bindValue(':id', $id, PDO::PARAM_INT);
    $requete->execute();

    return $requete->fetch();
}
```

- `fetch()` retourne **une seule** ligne, ou `false` si rien n'est trouve
- `LIMIT 1` optimise la requete (arrete la recherche au premier resultat)
- Le type de retour `array|false` est un **union type** de PHP 8

### Creer un produit (CREATE)

```php
public function creer(): bool
{
    $requete = $this->connexion->prepare(
        "INSERT INTO produits (nom, description, prix, stock, categorie)
         VALUES (:nom, :description, :prix, :stock, :categorie)"
    );

    $requete->bindValue(':nom', $this->nom, PDO::PARAM_STR);
    $requete->bindValue(':description', $this->description, PDO::PARAM_STR);
    $requete->bindValue(':prix', $this->prix);
    $requete->bindValue(':stock', $this->stock, PDO::PARAM_INT);
    $requete->bindValue(':categorie', $this->categorie, PDO::PARAM_STR);

    if ($requete->execute()) {
        $this->id = (int) $this->connexion->lastInsertId();
        return true;
    }
    return false;
}
```

- `lastInsertId()` recupere l'`id` auto-genere par MySQL apres l'INSERT
- Les proprietes de l'objet (`$this->nom`, etc.) sont remplies par le routeur avant l'appel

### Modifier un produit (UPDATE)

```php
public function modifier(): bool
{
    $requete = $this->connexion->prepare(
        "UPDATE produits
         SET nom = :nom, description = :description, prix = :prix,
             stock = :stock, categorie = :categorie
         WHERE id = :id"
    );

    $requete->bindValue(':id', $this->id, PDO::PARAM_INT);
    // ... bindValue pour chaque champ ...

    return $requete->execute();
}
```

- La clause `WHERE id = :id` cible **uniquement** le produit a modifier
- Sans le `WHERE`, **tous** les produits seraient modifies (tres dangereux)

### Supprimer un produit (DELETE)

```php
public function supprimer(int $id): bool
{
    $requete = $this->connexion->prepare("DELETE FROM produits WHERE id = :id");
    $requete->bindValue(':id', $id, PDO::PARAM_INT);
    $requete->execute();

    return $requete->rowCount() > 0;
}
```

- `rowCount()` retourne le nombre de lignes affectees
- Si le produit n'existait pas, `rowCount()` retourne 0 et on renvoie `false`

---

## 6. Le routeur : point d'entree de l'API

### Le role du routeur

Le fichier `index.php` est le **point d'entree unique** de l'API. Son travail :

1. Configurer les en-tetes HTTP (JSON, CORS)
2. Lire la methode HTTP (`$_SERVER['REQUEST_METHOD']`)
3. Lire les parametres d'URL (`$_GET['action']`, `$_GET['id']`)
4. Appeler la bonne methode du modele `Produit`
5. Retourner la reponse en JSON

### Lecture de la methode HTTP et des parametres

```php
$methode = $_SERVER['REQUEST_METHOD'];  // "GET", "POST", "PUT" ou "DELETE"
$action = $_GET['action'] ?? '';         // "produits", "produit", "creer", etc.
$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
```

### Dispatch avec match (PHP 8)

L'expression `match` (introduite en PHP 8.0) remplace avantageusement `switch` :

```php
match ($action) {
    'produits'  => /* lister tous les produits */,
    'produit'   => /* lire un produit */,
    'creer'     => /* creer un produit */,
    'modifier'  => /* modifier un produit */,
    'supprimer' => /* supprimer un produit */,
    default     => /* action inconnue */,
};
```

Avantages de `match` par rapport a `switch` :
- Pas besoin de `break` (pas de "fall-through")
- Comparaison stricte (`===` au lieu de `==`)
- Retourne une valeur (c'est une expression)

### Lecture du body JSON (POST / PUT)

Quand le client envoie des donnees (creation ou modification), elles arrivent dans le **corps** (body) de la requete HTTP :

```php
function lireDonneesJSON(): ?array
{
    $json = file_get_contents('php://input');
    $donnees = json_decode($json, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        return null;
    }

    return $donnees;
}
```

- `php://input` est un flux speciale de PHP qui contient le body brut de la requete
- `json_decode($json, true)` convertit le JSON en tableau associatif PHP
- Le deuxieme parametre `true` est essentiel : sans lui, on obtiendrait un objet `stdClass`

---

## 7. Les reponses JSON et les codes HTTP

### Structure standard des reponses

Toutes nos reponses suivent le meme format :

**Reponse de succes :**
```json
{
    "erreur": false,
    "message": "Produit cree avec succes.",
    "produit": {
        "id": 11,
        "nom": "Nouveau produit",
        "prix": 29.99
    }
}
```

**Reponse d'erreur :**
```json
{
    "erreur": true,
    "message": "Donnees invalides.",
    "details": [
        "Le champ \"nom\" est obligatoire.",
        "Le champ \"prix\" doit etre un nombre positif."
    ]
}
```

Le champ `erreur` (boolean) permet au client de savoir immediatement si la requete a reussi ou echoue, sans analyser le code HTTP.

### Les en-tetes HTTP

```php
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
```

| En-tete | Role |
|---------|------|
| `Content-Type` | Indique que la reponse est du JSON |
| `Access-Control-Allow-Origin` | Autorise les requetes depuis d'autres origines (CORS) |
| `Access-Control-Allow-Methods` | Liste les methodes HTTP autorisees |
| `Access-Control-Allow-Headers` | Autorise l'envoi de `Content-Type` par le client |

### Qu'est-ce que le CORS ?

**CORS** (Cross-Origin Resource Sharing) est un mecanisme de securite des navigateurs. Par defaut, un site web (`http://monsite.com`) ne peut pas faire de requetes vers un autre domaine (`http://api.autresite.com`). Les en-tetes CORS autorisent explicitement ces requetes.

### La requete preflight OPTIONS

Avant d'envoyer une requete PUT ou DELETE, le navigateur envoie d'abord une requete **OPTIONS** pour demander "Est-ce que j'ai le droit ?". On y repond immediatement :

```php
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}
```

---

## 8. Tester l'API avec Postman

### GET : Lister tous les produits

1. Methode : **GET**
2. URL : `http://localhost:8888/oop-crash-course/cours-web/06_API_REST/index.php?action=produits`
3. Cliquer sur **Send**

Reponse attendue (code 200) :
```json
{
    "erreur": false,
    "nombre": 10,
    "produits": [
        { "id": "1", "nom": "Clavier mecanique RGB", ... },
        ...
    ]
}
```

### GET : Lire un produit

1. Methode : **GET**
2. URL : `http://localhost:8888/oop-crash-course/cours-web/06_API_REST/index.php?action=produit&id=1`
3. Cliquer sur **Send**

### POST : Creer un produit

1. Methode : **POST**
2. URL : `http://localhost:8888/oop-crash-course/cours-web/06_API_REST/index.php?action=creer`
3. Onglet **Body** > selectionner **raw** > choisir **JSON**
4. Saisir :

```json
{
    "nom": "Batterie externe 20000mAh",
    "description": "Batterie portable USB-C, charge rapide 65W",
    "prix": 45.99,
    "stock": 30,
    "categorie": "Accessoires"
}
```

5. Cliquer sur **Send**

Reponse attendue (code 201) :
```json
{
    "erreur": false,
    "message": "Produit cree avec succes.",
    "produit": {
        "id": 11,
        "nom": "Batterie externe 20000mAh",
        ...
    }
}
```

### PUT : Modifier un produit

1. Methode : **PUT**
2. URL : `http://localhost:8888/oop-crash-course/cours-web/06_API_REST/index.php?action=modifier&id=11`
3. Body JSON :

```json
{
    "nom": "Batterie externe 30000mAh",
    "description": "Batterie portable USB-C, charge rapide 100W, version amelioree",
    "prix": 59.99,
    "stock": 25,
    "categorie": "Accessoires"
}
```

### DELETE : Supprimer un produit

1. Methode : **DELETE**
2. URL : `http://localhost:8888/oop-crash-course/cours-web/06_API_REST/index.php?action=supprimer&id=11`
3. Cliquer sur **Send**

### Tester les erreurs

| Test | URL / Config | Code attendu |
|------|-------------|--------------|
| Action inconnue | `?action=inconnu` | 400 |
| ID inexistant | `?action=produit&id=999` | 404 |
| POST sans body | `?action=creer` avec body vide | 400 |
| POST avec donnees invalides | `?action=creer` avec `{"prix": -5}` | 422 |
| DELETE sans id | `?action=supprimer` | 400 |

---

## 9. Validation et gestion des erreurs

### La fonction de validation

Avant chaque creation ou modification, on valide les donnees :

```php
function validerDonneesProduit(array $donnees): array
{
    $erreurs = [];

    if (empty($donnees['nom']) || !is_string($donnees['nom'])) {
        $erreurs[] = 'Le champ "nom" est obligatoire.';
    } elseif (mb_strlen(trim($donnees['nom'])) > 100) {
        $erreurs[] = 'Le champ "nom" ne peut pas depasser 100 caracteres.';
    }

    if (!isset($donnees['prix'])) {
        $erreurs[] = 'Le champ "prix" est obligatoire.';
    } elseif (!is_numeric($donnees['prix']) || (float) $donnees['prix'] < 0) {
        $erreurs[] = 'Le champ "prix" doit etre un nombre positif.';
    }

    return $erreurs;
}
```

La validation retourne un **tableau d'erreurs**. Si le tableau est vide, les donnees sont valides. Ce patron est identique a celui de la classe `Validateur` du module 05.

### Verification d'existence avant modification

Avant de modifier un produit, on verifie qu'il existe :

```php
$existant = $produit->lireParId($id);
if ($existant === false) {
    repondreErreur(404, "Aucun produit trouve avec l'id $id.");
    return;
}
```

Sans cette verification, un UPDATE sur un id inexistant reussirait silencieusement (0 lignes affectees, pas d'erreur SQL).

---

## 10. Securite et bonnes pratiques

### Resume des protections

| Risque | Protection appliquee |
|--------|---------------------|
| **Injection SQL** | Requetes preparees PDO (`prepare` + `bindValue`) |
| **Donnees invalides** | Validation des champs avant insertion |
| **Methode HTTP incorrecte** | Verification de `$_SERVER['REQUEST_METHOD']` |
| **JSON malforme** | Verification de `json_last_error()` |
| **ID inexistant** | Verification avant modification/suppression |
| **Erreurs SQL** | `PDO::ERRMODE_EXCEPTION` pour capter les erreurs |

### Ce qu'il faudrait ajouter en production

Ce projet est pedagogique. Pour une API en production, il faudrait ajouter :

- **Authentification** : token JWT ou cle API pour identifier les utilisateurs
- **Limitation de debit** (rate limiting) : empecher les abus (trop de requetes)
- **Pagination** : ne pas retourner 10 000 produits d'un coup (`LIMIT` + `OFFSET`)
- **Logs** : enregistrer les requetes et erreurs dans des fichiers
- **HTTPS** : chiffrer toutes les communications
- **Variables d'environnement** : ne pas mettre les mots de passe dans le code

---

## 11. Exercices progressifs

### Exercice 1 : Recherche par categorie (Debutant)

Ajoutez un nouvel endpoint pour filtrer les produits par categorie :

- **URL** : `GET /index.php?action=categorie&nom=Informatique`
- **Comportement** : retourne uniquement les produits de la categorie donnee
- **Erreur** : si le parametre `nom` est absent, retourner une erreur 400

Vous devez :
1. Ajouter une methode `lireParCategorie(string $categorie): array` dans `Produit.php`
2. Ajouter un cas `'categorie'` dans le `match` du routeur

<details>
<summary>Voir la solution</summary>

**models/Produit.php** :
```php
public function lireParCategorie(string $categorie): array
{
    $requete = $this->connexion->prepare(
        "SELECT id, nom, description, prix, stock, categorie, date_creation
         FROM {$this->table}
         WHERE categorie = :categorie
         ORDER BY nom ASC"
    );

    $requete->bindValue(':categorie', $categorie, PDO::PARAM_STR);
    $requete->execute();

    return $requete->fetchAll();
}
```

**index.php** (dans le match) :
```php
'categorie' => (function () use ($methode, $produit): void {
    if ($methode !== 'GET') {
        repondreErreur(405, 'Methode non autorisee. Utilisez GET.');
        return;
    }

    $nomCategorie = $_GET['nom'] ?? '';
    if (empty($nomCategorie)) {
        repondreErreur(400, 'Le parametre "nom" est obligatoire (nom de la categorie).');
        return;
    }

    $resultats = $produit->lireParCategorie($nomCategorie);

    repondreSucces(200, [
        'categorie' => $nomCategorie,
        'nombre'    => count($resultats),
        'produits'  => $resultats,
    ]);
})(),
```

**Test Postman** :
- GET `http://localhost:8888/.../index.php?action=categorie&nom=Informatique`

</details>

---

### Exercice 2 : Recherche par mot-cle (Debutant-Intermediaire)

Ajoutez un endpoint de recherche par mot-cle dans le nom ou la description :

- **URL** : `GET /index.php?action=recherche&q=bluetooth`
- **SQL** : utilisez `LIKE` avec `%mot%` pour chercher dans `nom` et `description`
- **Erreur** : si `q` est absent ou fait moins de 2 caracteres, retourner une erreur 400

<details>
<summary>Voir la solution</summary>

**models/Produit.php** :
```php
public function rechercher(string $motCle): array
{
    $requete = $this->connexion->prepare(
        "SELECT id, nom, description, prix, stock, categorie, date_creation
         FROM {$this->table}
         WHERE nom LIKE :motCle OR description LIKE :motCle
         ORDER BY nom ASC"
    );

    // Le % est ajoute en PHP, pas dans la requete SQL
    // Cela reste securise car bindValue protege contre l'injection
    $requete->bindValue(':motCle', '%' . $motCle . '%', PDO::PARAM_STR);
    $requete->execute();

    return $requete->fetchAll();
}
```

**index.php** :
```php
'recherche' => (function () use ($methode, $produit): void {
    if ($methode !== 'GET') {
        repondreErreur(405, 'Methode non autorisee. Utilisez GET.');
        return;
    }

    $motCle = trim($_GET['q'] ?? '');
    if (mb_strlen($motCle) < 2) {
        repondreErreur(400, 'Le parametre "q" doit contenir au moins 2 caracteres.');
        return;
    }

    $resultats = $produit->rechercher($motCle);

    repondreSucces(200, [
        'recherche' => $motCle,
        'nombre'    => count($resultats),
        'produits'  => $resultats,
    ]);
})(),
```

</details>

---

### Exercice 3 : Gestion du stock (Intermediaire)

Ajoutez deux endpoints pour gerer le stock sans modifier tout le produit :

- **PUT** `/index.php?action=ajouter-stock&id=1` — Body : `{"quantite": 10}` — ajoute 10 au stock
- **PUT** `/index.php?action=retirer-stock&id=1` — Body : `{"quantite": 5}` — retire 5 du stock

Regles :
- La quantite doit etre un entier positif
- Le stock ne peut pas devenir negatif (retourner une erreur 422)
- Retourner le stock mis a jour dans la reponse

<details>
<summary>Voir la solution</summary>

**models/Produit.php** :
```php
public function ajouterStock(int $id, int $quantite): array|false
{
    $requete = $this->connexion->prepare(
        "UPDATE {$this->table}
         SET stock = stock + :quantite
         WHERE id = :id"
    );

    $requete->bindValue(':id', $id, PDO::PARAM_INT);
    $requete->bindValue(':quantite', $quantite, PDO::PARAM_INT);
    $requete->execute();

    if ($requete->rowCount() === 0) {
        return false;
    }

    return $this->lireParId($id);
}

public function retirerStock(int $id, int $quantite): array|false|null
{
    // Verifier le stock actuel avant de retirer
    $produit = $this->lireParId($id);
    if ($produit === false) {
        return false; // Produit inexistant
    }

    if ((int) $produit['stock'] < $quantite) {
        return null; // Stock insuffisant
    }

    $requete = $this->connexion->prepare(
        "UPDATE {$this->table}
         SET stock = stock - :quantite
         WHERE id = :id"
    );

    $requete->bindValue(':id', $id, PDO::PARAM_INT);
    $requete->bindValue(':quantite', $quantite, PDO::PARAM_INT);
    $requete->execute();

    return $this->lireParId($id);
}
```

**index.php** :
```php
'ajouter-stock' => (function () use ($methode, $produit, $id): void {
    if ($methode !== 'PUT') {
        repondreErreur(405, 'Utilisez PUT.');
        return;
    }
    if ($id === null || $id <= 0) {
        repondreErreur(400, 'Le parametre "id" est obligatoire.');
        return;
    }

    $donnees = lireDonneesJSON();
    if ($donnees === null || !isset($donnees['quantite']) || (int) $donnees['quantite'] <= 0) {
        repondreErreur(400, 'Le champ "quantite" est obligatoire (entier positif).');
        return;
    }

    $resultat = $produit->ajouterStock($id, (int) $donnees['quantite']);
    if ($resultat === false) {
        repondreErreur(404, "Produit #$id introuvable.");
        return;
    }

    repondreSucces(200, [
        'message' => "Stock augmente de {$donnees['quantite']} unites.",
        'produit' => $resultat,
    ]);
})(),

'retirer-stock' => (function () use ($methode, $produit, $id): void {
    if ($methode !== 'PUT') {
        repondreErreur(405, 'Utilisez PUT.');
        return;
    }
    if ($id === null || $id <= 0) {
        repondreErreur(400, 'Le parametre "id" est obligatoire.');
        return;
    }

    $donnees = lireDonneesJSON();
    if ($donnees === null || !isset($donnees['quantite']) || (int) $donnees['quantite'] <= 0) {
        repondreErreur(400, 'Le champ "quantite" est obligatoire (entier positif).');
        return;
    }

    $resultat = $produit->retirerStock($id, (int) $donnees['quantite']);
    if ($resultat === false) {
        repondreErreur(404, "Produit #$id introuvable.");
        return;
    }
    if ($resultat === null) {
        repondreErreur(422, 'Stock insuffisant pour cette operation.');
        return;
    }

    repondreSucces(200, [
        'message' => "Stock reduit de {$donnees['quantite']} unites.",
        'produit' => $resultat,
    ]);
})(),
```

</details>

---

### Exercice 4 : Pagination des resultats (Intermediaire)

Modifiez l'endpoint `produits` pour supporter la pagination :

- **URL** : `GET /index.php?action=produits&page=1&limite=5`
- Par defaut : page 1, 5 produits par page
- La reponse doit inclure : `page_courante`, `total_pages`, `total_produits`, `produits`
- SQL : utilisez `LIMIT` et `OFFSET`

Formule : `OFFSET = (page - 1) * limite`

<details>
<summary>Voir la solution</summary>

**models/Produit.php** :
```php
public function compter(): int
{
    $requete = $this->connexion->prepare("SELECT COUNT(*) as total FROM {$this->table}");
    $requete->execute();
    $resultat = $requete->fetch();
    return (int) $resultat['total'];
}

public function lireAvecPagination(int $limite, int $offset): array
{
    $requete = $this->connexion->prepare(
        "SELECT id, nom, description, prix, stock, categorie, date_creation
         FROM {$this->table}
         ORDER BY date_creation DESC
         LIMIT :limite OFFSET :offset"
    );

    $requete->bindValue(':limite', $limite, PDO::PARAM_INT);
    $requete->bindValue(':offset', $offset, PDO::PARAM_INT);
    $requete->execute();

    return $requete->fetchAll();
}
```

**index.php** (remplacer le cas 'produits') :
```php
'produits' => (function () use ($methode, $produit): void {
    if ($methode !== 'GET') {
        repondreErreur(405, 'Utilisez GET.');
        return;
    }

    $page   = max(1, (int) ($_GET['page'] ?? 1));
    $limite = max(1, min(100, (int) ($_GET['limite'] ?? 5)));
    $offset = ($page - 1) * $limite;

    $totalProduits = $produit->compter();
    $totalPages    = (int) ceil($totalProduits / $limite);
    $resultats     = $produit->lireAvecPagination($limite, $offset);

    repondreSucces(200, [
        'page_courante'  => $page,
        'total_pages'    => $totalPages,
        'total_produits' => $totalProduits,
        'produits'       => $resultats,
    ]);
})(),
```

**Test Postman** :
- Page 1 : `?action=produits&page=1&limite=3`
- Page 2 : `?action=produits&page=2&limite=3`

</details>

---

### Exercice 5 : Statistiques du magasin (Avance)

Creez un endpoint de statistiques :

- **URL** : `GET /index.php?action=statistiques`
- La reponse doit contenir :
  - `total_produits` : nombre total de produits
  - `valeur_stock_total` : somme de `prix * stock` pour tous les produits
  - `stock_total` : somme de tous les stocks
  - `prix_moyen` : prix moyen des produits
  - `produit_plus_cher` : le produit avec le prix le plus eleve
  - `produit_moins_cher` : le produit avec le prix le plus bas
  - `par_categorie` : un tableau avec le nombre de produits et le stock total par categorie

Utilisez les fonctions d'agregation SQL : `COUNT()`, `SUM()`, `AVG()`, `MAX()`, `MIN()`, `GROUP BY`.

<details>
<summary>Voir la solution</summary>

**models/Produit.php** :
```php
public function getStatistiques(): array
{
    // Statistiques globales
    $reqGlobales = $this->connexion->prepare(
        "SELECT
            COUNT(*) as total_produits,
            SUM(prix * stock) as valeur_stock_total,
            SUM(stock) as stock_total,
            ROUND(AVG(prix), 2) as prix_moyen
         FROM {$this->table}"
    );
    $reqGlobales->execute();
    $globales = $reqGlobales->fetch();

    // Produit le plus cher
    $reqPlusCher = $this->connexion->prepare(
        "SELECT id, nom, prix FROM {$this->table} ORDER BY prix DESC LIMIT 1"
    );
    $reqPlusCher->execute();
    $plusCher = $reqPlusCher->fetch();

    // Produit le moins cher
    $reqMoinsCher = $this->connexion->prepare(
        "SELECT id, nom, prix FROM {$this->table} ORDER BY prix ASC LIMIT 1"
    );
    $reqMoinsCher->execute();
    $moinsCher = $reqMoinsCher->fetch();

    // Statistiques par categorie
    $reqCategories = $this->connexion->prepare(
        "SELECT
            categorie,
            COUNT(*) as nombre_produits,
            SUM(stock) as stock_total,
            ROUND(AVG(prix), 2) as prix_moyen
         FROM {$this->table}
         WHERE categorie IS NOT NULL
         GROUP BY categorie
         ORDER BY nombre_produits DESC"
    );
    $reqCategories->execute();
    $parCategorie = $reqCategories->fetchAll();

    return [
        'total_produits'     => (int) $globales['total_produits'],
        'valeur_stock_total' => (float) $globales['valeur_stock_total'],
        'stock_total'        => (int) $globales['stock_total'],
        'prix_moyen'         => (float) $globales['prix_moyen'],
        'produit_plus_cher'  => $plusCher,
        'produit_moins_cher' => $moinsCher,
        'par_categorie'      => $parCategorie,
    ];
}
```

**index.php** :
```php
'statistiques' => (function () use ($methode, $produit): void {
    if ($methode !== 'GET') {
        repondreErreur(405, 'Utilisez GET.');
        return;
    }

    $stats = $produit->getStatistiques();
    repondreSucces(200, ['statistiques' => $stats]);
})(),
```

**Test Postman** :
- GET `?action=statistiques`

Reponse attendue :
```json
{
    "erreur": false,
    "statistiques": {
        "total_produits": 10,
        "valeur_stock_total": 23456.50,
        "stock_total": 853,
        "prix_moyen": 80.74,
        "produit_plus_cher": { "id": "3", "nom": "Ecran 27 pouces 4K", "prix": "349.99" },
        "produit_moins_cher": { "id": "6", "nom": "Cable USB-C vers USB-C", "prix": "12.99" },
        "par_categorie": [
            { "categorie": "Informatique", "nombre_produits": "4", "stock_total": "215", "prix_moyen": "134.99" },
            ...
        ]
    }
}
```

</details>

---

## Recapitulatif

| Concept | Fichier | A retenir |
|---------|---------|-----------|
| Connexion PDO | `config/database.php` | DSN + options (`ERRMODE_EXCEPTION`, `FETCH_ASSOC`) |
| Modele CRUD | `models/Produit.php` | Requetes preparees (`prepare` + `bindValue` + `execute`) |
| Routeur | `index.php` | `match` sur l'action, verification de la methode HTTP |
| Requete preparee | Partout | JAMAIS de variable directement dans le SQL |
| Reponse JSON | `index.php` | `http_response_code()` + `json_encode()` |
| Validation | `index.php` | Verifier les champs avant chaque INSERT/UPDATE |
| Codes HTTP | Partout | 200 OK, 201 Created, 400 Bad Request, 404 Not Found |

---

**Felicitations !** Vous avez construit une API REST complete. La prochaine etape serait d'ajouter un systeme d'authentification (tokens JWT) et de connecter un frontend JavaScript avec `fetch()` pour creer une application web complete.
