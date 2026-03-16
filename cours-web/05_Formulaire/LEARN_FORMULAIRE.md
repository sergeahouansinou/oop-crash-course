# Cours complet : Formulaires - Validation Client & Serveur

---

## Table des matieres

1. [Introduction : pourquoi valider un formulaire ?](#1-introduction--pourquoi-valider-un-formulaire-)
2. [Architecture client-serveur d'un formulaire](#2-architecture-client-serveur-dun-formulaire)
3. [Construire un formulaire HTML accessible](#3-construire-un-formulaire-html-accessible)
4. [Styliser un formulaire avec CSS](#4-styliser-un-formulaire-avec-css)
5. [Validation cote client avec JavaScript](#5-validation-cote-client-avec-javascript)
6. [Validation en temps reel et UX](#6-validation-en-temps-reel-et-ux)
7. [L'indicateur de force du mot de passe](#7-lindicateur-de-force-du-mot-de-passe)
8. [Nettoyage des donnees (Sanitization) en PHP](#8-nettoyage-des-donnees-sanitization-en-php)
9. [Validation cote serveur avec PHP OOP](#9-validation-cote-serveur-avec-php-oop)
10. [Affichage des resultats cote serveur](#10-affichage-des-resultats-cote-serveur)
11. [Securite des formulaires](#11-securite-des-formulaires)
12. [Exercices progressifs](#12-exercices-progressifs)

---

## 1. Introduction : pourquoi valider un formulaire ?

### Le probleme

Quand un utilisateur remplit un formulaire, il peut :
- Oublier un champ obligatoire
- Entrer un email invalide (sans `@`, sans domaine)
- Saisir un numero de telephone avec des lettres
- Injecter du code malveillant (attaque XSS, injection SQL)

Sans validation, ces donnees incorrectes ou dangereuses arrivent directement dans votre base de donnees ou votre application.

### Les deux niveaux de validation

| Niveau | Technologie | But | Contournable ? |
|--------|-------------|-----|----------------|
| **Client** | JavaScript | UX rapide, retour instantane | Oui (JS desactivable) |
| **Serveur** | PHP | Securite, verification definitive | Non |

**Regle d'or : Ne JAMAIS faire confiance aux donnees du client.** La validation JavaScript ameliore l'experience utilisateur, mais la validation serveur est **indispensable** pour la securite.

### Le flux complet

```
Utilisateur remplit le formulaire
        |
        v
[Validation JavaScript] --> Erreur ? --> Afficher messages d'erreur
        |                                 (pas d'envoi au serveur)
        | OK
        v
[Envoi POST vers le serveur]
        |
        v
[Nettoyage PHP (Sanitization)]
        |
        v
[Validation PHP (Validateur)] --> Erreur ? --> Afficher page d'erreurs
        |
        | OK
        v
[Traitement des donnees]
(enregistrement, email, etc.)
```

---

## 2. Architecture client-serveur d'un formulaire

### Les fichiers du projet

Notre formulaire est compose de 4 fichiers, chacun avec un role precis :

| Fichier | Role | Technologie |
|---------|------|-------------|
| `index.html` | Structure du formulaire | HTML5 semantique |
| `style.css` | Mise en forme et etats visuels | CSS3, variables CSS |
| `script.js` | Validation cote client en temps reel | JavaScript (DOM, events) |
| `traitement.php` | Nettoyage + validation cote serveur | PHP 8 OOP |

### Separation des responsabilites

Ce projet applique le principe de **separation des responsabilites** :

- **HTML** : definit la structure et les attributs de validation natifs (`required`, `minlength`, `pattern`)
- **CSS** : gere les etats visuels (focus, erreur, succes) via des classes ajoutees par JavaScript
- **JavaScript** : valide en temps reel, affiche les erreurs, empeche l'envoi si invalide
- **PHP** : re-valide tout cote serveur avec une architecture OOP (classes `Sanitizer` et `Validateur`)

---

## 3. Construire un formulaire HTML accessible

### L'attribut `novalidate`

```html
<form id="formulaire-contact" action="traitement.php" method="POST" novalidate>
```

L'attribut `novalidate` **desactive la validation native du navigateur**. Pourquoi ? Pour utiliser notre propre validation JavaScript avec des messages d'erreur personnalises et un meilleur controle visuel.

Sans `novalidate`, le navigateur afficherait ses propres bulles d'erreur (peu personnalisables et differentes selon les navigateurs).

### Associer labels et champs

Chaque champ doit avoir un `<label>` associe via l'attribut `for` :

```html
<div class="champ-groupe">
    <label for="nom">Nom <span class="requis">*</span></label>
    <input type="text" id="nom" name="nom" required minlength="2" maxlength="50">
    <div class="error-message" id="erreur-nom" aria-live="polite"></div>
</div>
```

Points importants :
- `for="nom"` correspond a `id="nom"` du champ
- `name="nom"` est le nom envoye au serveur dans `$_POST['nom']`
- `aria-live="polite"` permet aux lecteurs d'ecran d'annoncer les erreurs dynamiquement
- Les attributs `required`, `minlength`, `maxlength` servent de filet de securite meme si JavaScript est desactive

### Les types de champs HTML5

HTML5 offre des types specialises qui ajoutent une validation de base :

```html
<input type="email">    <!-- Verifie le format email -->
<input type="tel">      <!-- Clavier numerique sur mobile -->
<input type="number" min="13" max="120">  <!-- Bornes numeriques -->
<input type="password">  <!-- Masque la saisie -->
```

### Grouper les boutons radio avec `<fieldset>`

```html
<fieldset class="groupe-radio">
    <legend>Genre <span class="requis">*</span></legend>
    <div class="option-radio">
        <input type="radio" id="genre-homme" name="genre" value="homme" required>
        <label for="genre-homme">Homme</label>
    </div>
    <!-- ... autres options ... -->
</fieldset>
```

Le `<fieldset>` regroupe semantiquement les options, et le `<legend>` sert de titre au groupe.

### Les checkboxes avec valeurs multiples

```html
<input type="checkbox" name="interets[]" value="sport">
```

Le `[]` dans `name="interets[]"` indique a PHP que les valeurs seront recues sous forme de tableau (`$_POST['interets']` sera un `array`).

---

## 4. Styliser un formulaire avec CSS

### Variables CSS pour la coherence

Le fichier `style.css` definit des variables CSS sur `:root` pour centraliser les couleurs et espacements :

```css
:root {
    --couleur-primaire: #2563eb;
    --couleur-erreur: #dc2626;
    --couleur-succes: #16a34a;
    --couleur-bordure: #d1d5db;
    --rayon-bordure: 8px;
    --transition-rapide: 0.2s ease;
}
```

Avantage : pour changer la couleur d'erreur dans tout le formulaire, il suffit de modifier une seule ligne.

### Les trois etats visuels d'un champ

Un champ de formulaire peut avoir trois etats visuels. JavaScript ajoute/retire les classes CSS correspondantes :

```css
/* Etat normal : bordure grise */
.champ-groupe input {
    border: 2px solid var(--couleur-bordure);
}

/* Etat focus : bordure bleue + halo */
.champ-groupe input:focus {
    border-color: var(--couleur-bordure-focus);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
}

/* Etat erreur : bordure rouge + fond rose */
.champ-groupe input.erreur {
    border-color: var(--couleur-erreur);
    background-color: var(--couleur-erreur-fond);
}

/* Etat valide : bordure verte */
.champ-groupe input.valide {
    border-color: var(--couleur-succes);
}
```

### Messages d'erreur auto-caches

```css
.error-message:empty {
    display: none;
}
```

Grace au pseudo-selecteur `:empty`, le conteneur d'erreur disparait automatiquement quand il est vide. Pas besoin de JavaScript pour le cacher.

### Responsive : boutons en colonne sur mobile

```css
@media (max-width: 768px) {
    .groupe-boutons {
        flex-direction: column;
    }
}
```

---

## 5. Validation cote client avec JavaScript

### Structure generale du script

Le script est enveloppe dans `DOMContentLoaded` pour s'assurer que le HTML est charge :

```javascript
document.addEventListener('DOMContentLoaded', function () {
    // Recuperation des elements
    const formulaire = document.getElementById('formulaire-contact');
    const champNom = document.getElementById('nom');
    // ...

    // Fonctions de validation
    // Ecouteurs d'evenements
    // Gestion de la soumission
});
```

### Fonctions utilitaires

Trois fonctions gerent l'affichage des etats :

```javascript
// Affiche une erreur : bordure rouge + message
function afficherErreur(champ, idErreur, message) {
    document.getElementById(idErreur).textContent = message;
    champ.classList.add('erreur');
    champ.classList.remove('valide');
}

// Efface l'erreur : bordure verte
function effacerErreur(champ, idErreur) {
    document.getElementById(idErreur).textContent = '';
    champ.classList.remove('erreur');
    champ.classList.add('valide');
}

// Reinitialise : ni erreur, ni valide
function reinitialiserChamp(champ, idErreur) {
    document.getElementById(idErreur).textContent = '';
    champ.classList.remove('erreur', 'valide');
}
```

### Valider un champ avec une regex

Les expressions regulieres (regex) permettent de verifier le format des donnees :

```javascript
// Lettres, accents, tirets, espaces, 2 a 50 caracteres
const REGEX_NOM = /^[a-zA-ZÀ-ÿ\s\-']{2,50}$/;

// Email : partie-locale@domaine.extension
const REGEX_EMAIL = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,10}$/;

// Telephone francais : 0 + 9 chiffres
const REGEX_TELEPHONE = /^0[1-9][0-9]{8}$/;
```

Exemple de fonction de validation :

```javascript
function validerNom() {
    const valeur = champNom.value.trim();

    if (valeur === '') {
        afficherErreur(champNom, 'erreur-nom', 'Le nom est obligatoire.');
        return false;
    }

    if (!REGEX_NOM.test(valeur)) {
        afficherErreur(champNom, 'erreur-nom',
            'Le nom doit contenir au moins 2 caracteres (lettres uniquement).');
        return false;
    }

    effacerErreur(champNom, 'erreur-nom');
    return true;
}
```

Chaque fonction de validation :
1. Recupere la valeur du champ (avec `trim()` pour supprimer les espaces)
2. Verifie si le champ est vide
3. Teste le format avec une regex
4. Affiche l'erreur ou marque le champ comme valide
5. Retourne `true` ou `false`

### Validation globale a la soumission

```javascript
function validerTout() {
    const resultats = [
        validerNom(),
        validerPrenom(),
        validerEmail(),
        validerTelephone(),
        validerAge(),
        validerMotDePasse(),
        validerConfirmationMdp(),
        validerGenre(),
        validerMessage(),
        validerConditions()
    ];

    return resultats.every(function (resultat) {
        return resultat === true;
    });
}
```

**Important** : on stocke tous les resultats dans un tableau au lieu d'utiliser `&&`. Avec `&&`, JavaScript s'arreterait au premier `false` (court-circuit) et n'afficherait qu'une seule erreur. En utilisant un tableau, on execute **toutes** les validations pour afficher **toutes** les erreurs d'un coup.

### Interception de la soumission

```javascript
formulaire.addEventListener('submit', function (evenement) {
    const estValide = validerTout();

    if (!estValide) {
        // Empecher l'envoi au serveur
        evenement.preventDefault();

        // Scroller vers la premiere erreur
        const premiereErreur = document.querySelector('.error-message:not(:empty)');
        if (premiereErreur) {
            premiereErreur.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        return;
    }

    // Si valide, le formulaire s'envoie normalement vers traitement.php
});
```

`evenement.preventDefault()` empeche le comportement par defaut (l'envoi du formulaire) quand il y a des erreurs.

---

## 6. Validation en temps reel et UX

### Ecouteurs sur l'evenement `input`

Pour valider pendant que l'utilisateur tape, on ecoute l'evenement `input` :

```javascript
champNom.addEventListener('input', function () {
    if (this.value.trim() === '') {
        reinitialiserChamp(this, 'erreur-nom');
        return;
    }
    validerNom();
});
```

Pourquoi reinitialiser quand le champ est vide ? Pour ne pas afficher "Le nom est obligatoire" pendant que l'utilisateur n'a pas encore commence a taper. L'erreur n'apparaitra qu'a la soumission.

### Evenements selon le type de champ

| Type de champ | Evenement | Raison |
|---------------|-----------|--------|
| `input[type="text"]` | `input` | Chaque frappe de clavier |
| `input[type="email"]` | `input` | Chaque frappe de clavier |
| `input[type="radio"]` | `change` | Quand la selection change |
| `input[type="checkbox"]` | `change` | Quand on coche/decoche |
| `textarea` | `input` | Chaque frappe + compteur |

### Le compteur de caracteres

```javascript
champMessage.addEventListener('input', function () {
    const longueur = this.value.length;
    compteurMessage.textContent = longueur + ' / 1000 caracteres';

    if (this.value.trim() === '') {
        reinitialiserChamp(this, 'erreur-message');
        return;
    }
    validerMessage();
});
```

### Reinitialisation propre du formulaire

Quand l'utilisateur clique sur "Reinitialiser", il faut nettoyer les classes CSS et les messages d'erreur :

```javascript
formulaire.addEventListener('reset', function () {
    setTimeout(function () {
        // Effacer les messages d'erreur
        document.querySelectorAll('.error-message').forEach(function (div) {
            div.textContent = '';
        });

        // Retirer les classes visuelles
        formulaire.querySelectorAll('input, textarea').forEach(function (champ) {
            champ.classList.remove('erreur', 'valide');
        });
    }, 10);
});
```

Le `setTimeout` de 10ms est necessaire : le navigateur execute le reset natif (vide les champs) **apres** l'evenement. Sans delai, notre code s'executerait avant le reset et n'aurait aucun effet.

---

## 7. L'indicateur de force du mot de passe

### Le principe

Un indicateur visuel montre la force du mot de passe en temps reel. Il se compose d'une barre de progression coloree et d'un texte.

### Le calcul du score

```javascript
function mettreAJourForceMotDePasse(motDePasse) {
    let score = 0;

    // +1 point par critere rempli
    if (motDePasse.length >= 8) score++;    // Longueur minimale
    if (motDePasse.length >= 12) score++;   // Longueur confortable
    if (/[A-Z]/.test(motDePasse)) score++;  // Majuscule
    if (/[a-z]/.test(motDePasse)) score++;  // Minuscule
    if (/[0-9]/.test(motDePasse)) score++;  // Chiffre
    if (/[!@#$%^&*]/.test(motDePasse)) score++; // Special

    // Appliquer le niveau selon le score
    if (score <= 2) {
        // Faible (rouge, 25%)
    } else if (score <= 3) {
        // Moyen (orange, 50%)
    } else if (score <= 4) {
        // Fort (vert clair, 75%)
    } else {
        // Tres fort (vert, 100%)
    }
}
```

### Le CSS de la barre

```css
.barre-force {
    height: 6px;
    border-radius: 3px;
    transition: all 0.3s ease;
}

.barre-force.faible  { width: 25%;  background-color: #dc2626; }
.barre-force.moyen   { width: 50%;  background-color: #f59e0b; }
.barre-force.fort    { width: 75%;  background-color: #22c55e; }
.barre-force.tres-fort { width: 100%; background-color: #16a34a; }
```

### Revalidation croisee

Quand le mot de passe change, il faut aussi revalider la confirmation :

```javascript
champMotDePasse.addEventListener('input', function () {
    validerMotDePasse();

    // Si la confirmation est deja remplie, la revalider
    if (champConfirmationMdp.value !== '') {
        validerConfirmationMdp();
    }
});
```

---

## 8. Nettoyage des donnees (Sanitization) en PHP

### Pourquoi nettoyer avant de valider ?

Le nettoyage (**sanitization**) transforme les donnees brutes en donnees sures **avant** la validation. Cela protege contre les attaques XSS (Cross-Site Scripting).

Exemple d'attaque XSS sans nettoyage :

```
Nom saisi : <script>alert('pirate')</script>
```

Si on affiche ce nom tel quel dans la page HTML, le navigateur executera le JavaScript malveillant.

### La classe Sanitizer

Dans `traitement.php`, la classe `Sanitizer` utilise des **methodes statiques** car elle ne stocke aucun etat (pas de proprietes d'instance) :

```php
class Sanitizer
{
    public static function nettoyerChaine(string $donnee): string
    {
        $donnee = trim($donnee);              // Supprimer les espaces
        $donnee = stripslashes($donnee);      // Supprimer les antislashs
        $donnee = htmlspecialchars(           // Convertir les caracteres speciaux
            $donnee, ENT_QUOTES, 'UTF-8'
        );
        return $donnee;
    }

    public static function nettoyerEmail(string $email): string
    {
        $email = trim($email);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $email = strtolower($email);
        return $email;
    }

    public static function nettoyerTelephone(string $telephone): string
    {
        $telephone = trim($telephone);
        $telephone = preg_replace('/[^0-9]/', '', $telephone);
        return $telephone;
    }
}
```

### Les fonctions de nettoyage PHP

| Fonction | Role | Exemple |
|----------|------|---------|
| `trim()` | Supprime les espaces en debut et fin | `"  Alice  "` → `"Alice"` |
| `stripslashes()` | Supprime les antislashs | `"l\'email"` → `"l'email"` |
| `htmlspecialchars()` | Convertit `<`, `>`, `"`, `'` en entites HTML | `"<script>"` → `"&lt;script&gt;"` |
| `filter_var($v, FILTER_SANITIZE_EMAIL)` | Supprime les caracteres invalides dans un email | Filtre automatique |
| `preg_replace('/[^0-9]/', '', $v)` | Ne garde que les chiffres | `"06 12-34"` → `"061234"` |

### Pourquoi ne pas nettoyer le mot de passe ?

```php
'mot_de_passe' => $donneesPost['mot_de_passe'] ?? '',  // Pas de htmlspecialchars
```

Le mot de passe est **hache** (avec `password_hash()`) avant d'etre stocke. Appliquer `htmlspecialchars()` modifierait les caracteres speciaux du mot de passe (`&` deviendrait `&amp;`), ce qui changerait le mot de passe saisi par l'utilisateur.

---

## 9. Validation cote serveur avec PHP OOP

### La classe Validateur

La classe `Validateur` encapsule toute la logique de validation :

```php
class Validateur
{
    private array $erreurs = [];   // Tableau cle => message d'erreur
    private array $donnees = [];   // Donnees nettoyees

    public function __construct(array $donneesPost)
    {
        // Nettoyer chaque champ avec la classe Sanitizer
        $this->donnees = [
            'nom'    => Sanitizer::nettoyerChaine($donneesPost['nom'] ?? ''),
            'email'  => Sanitizer::nettoyerEmail($donneesPost['email'] ?? ''),
            // ...
        ];
    }
}
```

Points cles :
- L'operateur `??` (null coalescing) fournit une valeur par defaut si la cle n'existe pas dans `$_POST`
- Le constructeur nettoie immediatement les donnees
- Les erreurs et donnees sont en proprietes **privees** (encapsulation)

### Le patron de chaque methode de validation

Chaque methode suit le meme schema :

```php
public function validerNom(): void
{
    $nom = $this->donnees['nom'];

    // 1. Verifier si le champ est vide
    if (empty($nom)) {
        $this->erreurs['nom'] = 'Le nom est obligatoire.';
        return;
    }

    // 2. Decoder les entites HTML pour verifier la vraie longueur
    $nomDecode = html_entity_decode($nom, ENT_QUOTES, 'UTF-8');

    // 3. Verifier la longueur
    if (mb_strlen($nomDecode) < 2) {
        $this->erreurs['nom'] = 'Le nom doit contenir au moins 2 caracteres.';
        return;
    }

    // 4. Verifier le format avec une regex
    if (!preg_match('/^[a-zA-ZÀ-ÿ\s\-\']+$/u', $nomDecode)) {
        $this->erreurs['nom'] = 'Le nom ne contient que des lettres.';
    }
}
```

**Pourquoi `html_entity_decode()` ?** Les donnees ont ete nettoyees avec `htmlspecialchars()`, qui transforme `'` en `&#039;` (6 caracteres au lieu de 1). Pour verifier la vraie longueur, il faut decoder.

**Pourquoi `mb_strlen()` et pas `strlen()` ?** `mb_strlen()` compte correctement les caracteres multi-octets (accents, emojis). `strlen("é")` retourne 2 (octets), tandis que `mb_strlen("é")` retourne 1 (caractere).

### Validation globale

```php
public function validerTout(): bool
{
    $this->erreurs = [];

    $this->validerNom();
    $this->validerPrenom();
    $this->validerEmail();
    $this->validerTelephone();
    $this->validerAge();
    $this->validerMotDePasse();
    $this->validerGenre();
    $this->validerMessage();
    $this->validerConditions();

    return empty($this->erreurs);
}
```

### Validation de la whitelist (genre)

Pour les boutons radio et les listes deroulantes, on verifie que la valeur fait partie d'une liste autorisee :

```php
public function validerGenre(): void
{
    $genre = $this->donnees['genre'];
    $genresAutorises = ['homme', 'femme', 'autre'];

    if (!in_array($genre, $genresAutorises, true)) {
        $this->erreurs['genre'] = 'La valeur du genre n\'est pas valide.';
    }
}
```

Le troisieme parametre `true` de `in_array()` active la comparaison **stricte** (verifie aussi le type). C'est important pour eviter que `0 == "homme"` soit `true` en PHP.

---

## 10. Affichage des resultats cote serveur

### Le traitement principal

```php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $validateur = new Validateur($_POST);
    $validateur->validerTout();

    $donnees = $validateur->getDonnees();
    $erreurs = $validateur->getErreurs();
} else {
    // Acces direct en GET : rediriger vers le formulaire
    header('Location: index.html');
    exit;
}
```

On verifie `$_SERVER['REQUEST_METHOD']` pour s'assurer que la page n'est pas accedee directement via l'URL (methode GET).

### Affichage conditionnel avec PHP

La meme page `traitement.php` affiche soit le succes, soit les erreurs :

```php
<?php if ($validateur->estValide()): ?>
    <!-- Carte verte : recapitulatif des donnees -->
    <div class="carte carte-succes">
        <h2>&#10004; Formulaire envoye avec succes !</h2>
        <p>Merci <strong><?= htmlspecialchars($donnees['prenom']) ?></strong></p>
    </div>

<?php else: ?>
    <!-- Carte rouge : liste des erreurs -->
    <div class="carte carte-erreur">
        <ul class="liste-erreurs">
            <?php foreach ($erreurs as $champ => $messageErreur): ?>
                <li><?= htmlspecialchars($messageErreur) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
```

**Important** : on utilise `htmlspecialchars()` une derniere fois a l'affichage pour se proteger meme si les donnees sont deja nettoyees (defense en profondeur).

---

## 11. Securite des formulaires

### Les attaques courantes

| Attaque | Description | Protection |
|---------|-------------|------------|
| **XSS** | Injection de code JavaScript via un champ | `htmlspecialchars()` |
| **Injection SQL** | Manipulation des requetes SQL | Requetes preparees (PDO) |
| **CSRF** | Soumission de formulaire depuis un autre site | Token CSRF |
| **Brute force** | Tentatives multiples (login) | Limitation de tentatives |

### La defense en profondeur

Notre formulaire applique plusieurs couches de protection :

1. **Attributs HTML** (`required`, `minlength`, `pattern`) : premier filtre
2. **Validation JavaScript** : retour visuel instantane
3. **Sanitization PHP** (`Sanitizer`) : nettoyage des donnees
4. **Validation PHP** (`Validateur`) : verification des regles metier
5. **Echappement a l'affichage** (`htmlspecialchars()`) : derniere protection

### `declare(strict_types=1)`

```php
<?php
declare(strict_types=1);
```

Cette declaration en debut de fichier active le **typage strict** en PHP. Sans elle, PHP convertit automatiquement les types (ex: `"42"` passe comme un `int`). Avec le typage strict, un `string` passe a un parametre `int` leve une erreur.

---

## 12. Exercices progressifs

### Exercice 1 : Ajouter un champ "Ville" (Debutant)

Ajoutez un champ "Ville" au formulaire existant. Ce champ doit :
- Etre obligatoire
- Contenir entre 2 et 100 caracteres
- Accepter les lettres, accents, espaces et tirets

Vous devez modifier les 3 fichiers :
1. **`index.html`** : ajouter le champ HTML avec son label et son conteneur d'erreur
2. **`script.js`** : ajouter la fonction `validerVille()`, l'ecouteur `input`, et l'appel dans `validerTout()`
3. **`traitement.php`** : ajouter le nettoyage dans le constructeur de `Validateur`, la methode `validerVille()`, l'appel dans `validerTout()`, et l'affichage dans le tableau recapitulatif

<details>
<summary>Voir la solution</summary>

**index.html** (apres le champ telephone) :
```html
<div class="champ-groupe">
    <label for="ville">Ville <span class="requis">*</span></label>
    <input
        type="text"
        id="ville"
        name="ville"
        placeholder="Entrez votre ville"
        required
        minlength="2"
        maxlength="100"
    >
    <div class="error-message" id="erreur-ville" aria-live="polite"></div>
</div>
```

**script.js** :
```javascript
const champVille = document.getElementById('ville');
const REGEX_VILLE = /^[a-zA-ZÀ-ÿ\s\-']{2,100}$/;

function validerVille() {
    const valeur = champVille.value.trim();

    if (valeur === '') {
        afficherErreur(champVille, 'erreur-ville', 'La ville est obligatoire.');
        return false;
    }

    if (!REGEX_VILLE.test(valeur)) {
        afficherErreur(champVille, 'erreur-ville',
            'La ville doit contenir entre 2 et 100 caracteres (lettres uniquement).');
        return false;
    }

    effacerErreur(champVille, 'erreur-ville');
    return true;
}

// Ecouteur temps reel
champVille.addEventListener('input', function () {
    if (this.value.trim() === '') {
        reinitialiserChamp(this, 'erreur-ville');
        return;
    }
    validerVille();
});

// Ajouter validerVille() dans le tableau de validerTout()
```

**traitement.php** :
```php
// Dans le constructeur du Validateur :
'ville' => Sanitizer::nettoyerChaine($donneesPost['ville'] ?? ''),

// Nouvelle methode :
public function validerVille(): void
{
    $ville = $this->donnees['ville'];

    if (empty($ville)) {
        $this->erreurs['ville'] = 'La ville est obligatoire.';
        return;
    }

    $villeDecode = html_entity_decode($ville, ENT_QUOTES, 'UTF-8');

    if (mb_strlen($villeDecode) < 2 || mb_strlen($villeDecode) > 100) {
        $this->erreurs['ville'] = 'La ville doit contenir entre 2 et 100 caracteres.';
        return;
    }

    if (!preg_match('/^[a-zA-ZÀ-ÿ\s\-\']+$/u', $villeDecode)) {
        $this->erreurs['ville'] = 'La ville ne peut contenir que des lettres.';
    }
}

// Ajouter $this->validerVille() dans validerTout()
```

</details>

---

### Exercice 2 : Formulaire de connexion (Debutant-Intermediaire)

Creez un formulaire de connexion avec seulement 2 champs :
- **Email** (obligatoire, format email valide)
- **Mot de passe** (obligatoire, minimum 8 caracteres)

Fichiers a creer :
1. `connexion.html` : le formulaire HTML
2. `connexion.js` : validation cote client (temps reel + soumission)
3. `connexion.php` : validation cote serveur avec les classes `Sanitizer` et `ValidateurConnexion`

Le formulaire doit afficher un message d'erreur generique "Email ou mot de passe incorrect" (sans preciser lequel est faux, pour des raisons de securite).

<details>
<summary>Voir la solution</summary>

**connexion.html** :
```html
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="site-header">
        <h1>Connexion</h1>
    </header>

    <main class="site-main">
        <form id="formulaire-connexion" action="connexion.php" method="POST" novalidate>
            <div class="champ-groupe">
                <label for="email">Adresse e-mail <span class="requis">*</span></label>
                <input type="email" id="email" name="email" placeholder="exemple@domaine.fr" required>
                <div class="error-message" id="erreur-email" aria-live="polite"></div>
            </div>

            <div class="champ-groupe">
                <label for="mot-de-passe">Mot de passe <span class="requis">*</span></label>
                <input type="password" id="mot-de-passe" name="mot_de_passe"
                       placeholder="Votre mot de passe" required minlength="8">
                <div class="error-message" id="erreur-mot-de-passe" aria-live="polite"></div>
            </div>

            <div class="groupe-boutons">
                <button type="submit" class="btn btn-envoyer">Se connecter</button>
            </div>
        </form>
    </main>

    <script src="connexion.js"></script>
</body>
</html>
```

**connexion.js** :
```javascript
document.addEventListener('DOMContentLoaded', function () {
    const formulaire = document.getElementById('formulaire-connexion');
    const champEmail = document.getElementById('email');
    const champMotDePasse = document.getElementById('mot-de-passe');

    const REGEX_EMAIL = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,10}$/;

    function afficherErreur(champ, idErreur, message) {
        document.getElementById(idErreur).textContent = message;
        champ.classList.add('erreur');
        champ.classList.remove('valide');
    }

    function effacerErreur(champ, idErreur) {
        document.getElementById(idErreur).textContent = '';
        champ.classList.remove('erreur');
        champ.classList.add('valide');
    }

    function validerEmail() {
        const valeur = champEmail.value.trim();
        if (valeur === '') {
            afficherErreur(champEmail, 'erreur-email', 'L\'email est obligatoire.');
            return false;
        }
        if (!REGEX_EMAIL.test(valeur)) {
            afficherErreur(champEmail, 'erreur-email', 'Format d\'email invalide.');
            return false;
        }
        effacerErreur(champEmail, 'erreur-email');
        return true;
    }

    function validerMotDePasse() {
        const valeur = champMotDePasse.value;
        if (valeur === '') {
            afficherErreur(champMotDePasse, 'erreur-mot-de-passe',
                'Le mot de passe est obligatoire.');
            return false;
        }
        if (valeur.length < 8) {
            afficherErreur(champMotDePasse, 'erreur-mot-de-passe',
                'Le mot de passe doit contenir au moins 8 caracteres.');
            return false;
        }
        effacerErreur(champMotDePasse, 'erreur-mot-de-passe');
        return true;
    }

    champEmail.addEventListener('input', validerEmail);
    champMotDePasse.addEventListener('input', validerMotDePasse);

    formulaire.addEventListener('submit', function (e) {
        const resultats = [validerEmail(), validerMotDePasse()];
        if (!resultats.every(r => r === true)) {
            e.preventDefault();
        }
    });
});
```

**connexion.php** :
```php
<?php
declare(strict_types=1);

class ValidateurConnexion
{
    private array $erreurs = [];
    private array $donnees = [];

    public function __construct(array $donneesPost)
    {
        $this->donnees = [
            'email'        => Sanitizer::nettoyerEmail($donneesPost['email'] ?? ''),
            'mot_de_passe' => $donneesPost['mot_de_passe'] ?? '',
        ];
    }

    public function validerTout(): bool
    {
        $this->erreurs = [];
        $email = $this->donnees['email'];
        $motDePasse = $this->donnees['mot_de_passe'];

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)
            || empty($motDePasse) || strlen($motDePasse) < 8) {
            // Message generique pour ne pas reveler quel champ est faux
            $this->erreurs['connexion'] = 'Email ou mot de passe incorrect.';
        }

        return empty($this->erreurs);
    }

    public function getErreurs(): array { return $this->erreurs; }
    public function estValide(): bool { return empty($this->erreurs); }
}

// Reutiliser la classe Sanitizer du fichier traitement.php
// ou la copier ici

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $validateur = new ValidateurConnexion($_POST);
    $validateur->validerTout();
} else {
    header('Location: connexion.html');
    exit;
}
?>
<!-- Affichage du resultat -->
```

</details>

---

### Exercice 3 : Validation avancee du telephone (Intermediaire)

Ameliorez la validation du telephone pour accepter plusieurs formats :
- `0612345678` (sans separateur)
- `06 12 34 56 78` (avec espaces)
- `06.12.34.56.78` (avec points)
- `06-12-34-56-78` (avec tirets)
- `+33 6 12 34 56 78` (format international)

Modifiez la validation JavaScript **et** la validation PHP pour accepter ces formats. Le nettoyage PHP doit normaliser le numero (ne garder que les chiffres, gerer le `+33`).

<details>
<summary>Voir la solution</summary>

**script.js** :
```javascript
const REGEX_TELEPHONE = /^(?:(?:\+33|0033)\s?[1-9]|0[1-9])(?:[\s.\-]?[0-9]{2}){4}$/;

function validerTelephone() {
    const valeur = champTelephone.value.trim();

    if (valeur === '') {
        afficherErreur(champTelephone, 'erreur-telephone',
            'Le numero de telephone est obligatoire.');
        return false;
    }

    if (!REGEX_TELEPHONE.test(valeur)) {
        afficherErreur(champTelephone, 'erreur-telephone',
            'Format accepte : 0612345678, 06 12 34 56 78, +33 6 12 34 56 78');
        return false;
    }

    effacerErreur(champTelephone, 'erreur-telephone');
    return true;
}
```

**traitement.php** (classe Sanitizer) :
```php
public static function nettoyerTelephone(string $telephone): string
{
    $telephone = trim($telephone);

    // Gerer le format international +33 ou 0033
    if (preg_match('/^(?:\+33|0033)/', $telephone)) {
        // Supprimer tout sauf les chiffres
        $telephone = preg_replace('/[^0-9]/', '', $telephone);
        // Remplacer le prefixe 33 par 0
        $telephone = '0' . substr($telephone, 2);
    } else {
        // Supprimer tout sauf les chiffres
        $telephone = preg_replace('/[^0-9]/', '', $telephone);
    }

    return $telephone;
}
```

Apres nettoyage, la validation PHP reste identique (10 chiffres commencant par 0), car le nettoyage a normalise tous les formats.

</details>

---

### Exercice 4 : Classe de validation reutilisable (Intermediaire)

Refactorisez la validation en creant une classe `RegleValidation` generique et reutilisable :

1. Une classe `RegleValidation` avec des methodes statiques :
   - `obligatoire(string $valeur, string $nomChamp): ?string` (retourne le message d'erreur ou `null`)
   - `longueurMin(string $valeur, int $min, string $nomChamp): ?string`
   - `longueurMax(string $valeur, int $max, string $nomChamp): ?string`
   - `formatRegex(string $valeur, string $regex, string $message): ?string`
   - `emailValide(string $valeur): ?string`

2. Refactorisez `Validateur` pour utiliser `RegleValidation` au lieu de dupliquer la logique dans chaque methode.

<details>
<summary>Voir la solution</summary>

```php
<?php
declare(strict_types=1);

class RegleValidation
{
    public static function obligatoire(string $valeur, string $nomChamp): ?string
    {
        if (empty($valeur)) {
            return "Le champ $nomChamp est obligatoire.";
        }
        return null;
    }

    public static function longueurMin(string $valeur, int $min, string $nomChamp): ?string
    {
        $decode = html_entity_decode($valeur, ENT_QUOTES, 'UTF-8');
        if (mb_strlen($decode) < $min) {
            return "Le champ $nomChamp doit contenir au moins $min caracteres.";
        }
        return null;
    }

    public static function longueurMax(string $valeur, int $max, string $nomChamp): ?string
    {
        $decode = html_entity_decode($valeur, ENT_QUOTES, 'UTF-8');
        if (mb_strlen($decode) > $max) {
            return "Le champ $nomChamp ne peut pas depasser $max caracteres.";
        }
        return null;
    }

    public static function formatRegex(string $valeur, string $regex, string $message): ?string
    {
        $decode = html_entity_decode($valeur, ENT_QUOTES, 'UTF-8');
        if (!preg_match($regex, $decode)) {
            return $message;
        }
        return null;
    }

    public static function emailValide(string $valeur): ?string
    {
        if (!filter_var($valeur, FILTER_VALIDATE_EMAIL)) {
            return "L'adresse e-mail n'est pas valide.";
        }
        return null;
    }
}

// Utilisation dans le Validateur :
class Validateur
{
    private array $erreurs = [];
    private array $donnees = [];

    // ... constructeur identique ...

    public function validerNom(): void
    {
        $nom = $this->donnees['nom'];

        // Chaque regle retourne null (OK) ou un message (erreur)
        $erreur = RegleValidation::obligatoire($nom, 'nom')
            ?? RegleValidation::longueurMin($nom, 2, 'nom')
            ?? RegleValidation::longueurMax($nom, 50, 'nom')
            ?? RegleValidation::formatRegex(
                $nom,
                '/^[a-zA-ZÀ-ÿ\s\-\']+$/u',
                'Le nom ne peut contenir que des lettres.'
            );

        if ($erreur !== null) {
            $this->erreurs['nom'] = $erreur;
        }
    }

    // Les autres methodes suivent le meme patron...
}
```

L'operateur `??` (null coalescing) chaine les verifications : si la premiere retourne `null` (pas d'erreur), on passe a la suivante. La premiere erreur trouvee est retenue.

</details>

---

### Exercice 5 : Formulaire multi-etapes avec sauvegarde (Avance)

Creez un formulaire d'inscription en 3 etapes avec sauvegarde dans `sessionStorage` :

**Etape 1** : Informations personnelles (nom, prenom, email)
**Etape 2** : Securite (mot de passe, confirmation)
**Etape 3** : Preferences (centres d'interet, message, conditions)

Fonctionnalites :
1. Navigation "Suivant" / "Precedent" entre les etapes
2. Un indicateur de progression visuel (etape 1/3, 2/3, 3/3)
3. Validation de chaque etape avant de passer a la suivante
4. Sauvegarde des donnees dans `sessionStorage` pour ne pas les perdre au rechargement
5. Soumission finale vers un fichier PHP qui valide **tout**

**Indice** : utilisez des `<div>` avec `display: none/block` pour afficher/masquer les etapes. Chaque bouton "Suivant" valide uniquement les champs de l'etape courante.

<details>
<summary>Voir la solution</summary>

**inscription.html** :
```html
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription multi-etapes</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .etape { display: none; }
        .etape.active { display: block; }
        .progression {
            display: flex; justify-content: center; gap: 1rem;
            margin-bottom: 2rem;
        }
        .progression .pastille {
            width: 40px; height: 40px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            background-color: #d1d5db; color: white; font-weight: bold;
        }
        .progression .pastille.active { background-color: #2563eb; }
        .progression .pastille.complete { background-color: #16a34a; }
    </style>
</head>
<body>
    <header class="site-header">
        <h1>Inscription</h1>
    </header>

    <main class="site-main">
        <!-- Indicateur de progression -->
        <div class="progression">
            <div class="pastille active" id="pastille-1">1</div>
            <div class="pastille" id="pastille-2">2</div>
            <div class="pastille" id="pastille-3">3</div>
        </div>

        <form id="formulaire-inscription" action="traitement.php" method="POST" novalidate>
            <!-- Etape 1 -->
            <div class="etape active" id="etape-1">
                <h2>Informations personnelles</h2>
                <div class="champ-groupe">
                    <label for="nom">Nom <span class="requis">*</span></label>
                    <input type="text" id="nom" name="nom" required minlength="2">
                    <div class="error-message" id="erreur-nom" aria-live="polite"></div>
                </div>
                <div class="champ-groupe">
                    <label for="prenom">Prenom <span class="requis">*</span></label>
                    <input type="text" id="prenom" name="prenom" required minlength="2">
                    <div class="error-message" id="erreur-prenom" aria-live="polite"></div>
                </div>
                <div class="champ-groupe">
                    <label for="email">Email <span class="requis">*</span></label>
                    <input type="email" id="email" name="email" required>
                    <div class="error-message" id="erreur-email" aria-live="polite"></div>
                </div>
                <div class="groupe-boutons">
                    <button type="button" class="btn btn-envoyer" id="btn-suivant-1">Suivant</button>
                </div>
            </div>

            <!-- Etape 2 -->
            <div class="etape" id="etape-2">
                <h2>Securite</h2>
                <div class="champ-groupe">
                    <label for="mot-de-passe">Mot de passe <span class="requis">*</span></label>
                    <input type="password" id="mot-de-passe" name="mot_de_passe" required minlength="8">
                    <div class="error-message" id="erreur-mot-de-passe" aria-live="polite"></div>
                </div>
                <div class="champ-groupe">
                    <label for="confirmation-mdp">Confirmation <span class="requis">*</span></label>
                    <input type="password" id="confirmation-mdp" name="confirmation_mdp" required>
                    <div class="error-message" id="erreur-confirmation-mdp" aria-live="polite"></div>
                </div>
                <div class="groupe-boutons">
                    <button type="button" class="btn btn-reinitialiser" id="btn-precedent-2">Precedent</button>
                    <button type="button" class="btn btn-envoyer" id="btn-suivant-2">Suivant</button>
                </div>
            </div>

            <!-- Etape 3 -->
            <div class="etape" id="etape-3">
                <h2>Preferences</h2>
                <div class="champ-groupe">
                    <label for="message">Message <span class="requis">*</span></label>
                    <textarea id="message" name="message" required minlength="10" rows="4"></textarea>
                    <div class="error-message" id="erreur-message" aria-live="polite"></div>
                </div>
                <div class="champ-groupe champ-conditions">
                    <div class="option-checkbox">
                        <input type="checkbox" id="conditions" name="conditions" value="accepte" required>
                        <label for="conditions">J'accepte les conditions <span class="requis">*</span></label>
                    </div>
                    <div class="error-message" id="erreur-conditions" aria-live="polite"></div>
                </div>
                <div class="groupe-boutons">
                    <button type="button" class="btn btn-reinitialiser" id="btn-precedent-3">Precedent</button>
                    <button type="submit" class="btn btn-envoyer">S'inscrire</button>
                </div>
            </div>
        </form>
    </main>

    <script src="inscription.js"></script>
</body>
</html>
```

**inscription.js** :
```javascript
document.addEventListener('DOMContentLoaded', function () {
    let etapeCourante = 1;
    const totalEtapes = 3;

    // --- Fonctions utilitaires (identiques au formulaire principal) ---
    function afficherErreur(champ, idErreur, message) {
        document.getElementById(idErreur).textContent = message;
        champ.classList.add('erreur');
        champ.classList.remove('valide');
    }

    function effacerErreur(champ, idErreur) {
        document.getElementById(idErreur).textContent = '';
        champ.classList.remove('erreur');
        champ.classList.add('valide');
    }

    // --- Navigation entre les etapes ---
    function afficherEtape(numero) {
        // Masquer toutes les etapes
        document.querySelectorAll('.etape').forEach(function (div) {
            div.classList.remove('active');
        });

        // Afficher l'etape demandee
        document.getElementById('etape-' + numero).classList.add('active');

        // Mettre a jour les pastilles
        for (let i = 1; i <= totalEtapes; i++) {
            const pastille = document.getElementById('pastille-' + i);
            pastille.classList.remove('active', 'complete');
            if (i < numero) {
                pastille.classList.add('complete');
            } else if (i === numero) {
                pastille.classList.add('active');
            }
        }

        etapeCourante = numero;
    }

    // --- Sauvegarde dans sessionStorage ---
    function sauvegarder() {
        const champs = document.querySelectorAll('#formulaire-inscription input, #formulaire-inscription textarea');
        champs.forEach(function (champ) {
            if (champ.type === 'password') return; // Ne pas sauvegarder les mots de passe
            if (champ.type === 'checkbox') {
                sessionStorage.setItem(champ.id, champ.checked);
            } else {
                sessionStorage.setItem(champ.id, champ.value);
            }
        });
    }

    function restaurer() {
        const champs = document.querySelectorAll('#formulaire-inscription input, #formulaire-inscription textarea');
        champs.forEach(function (champ) {
            if (champ.type === 'password') return;
            const valeur = sessionStorage.getItem(champ.id);
            if (valeur !== null) {
                if (champ.type === 'checkbox') {
                    champ.checked = valeur === 'true';
                } else {
                    champ.value = valeur;
                }
            }
        });
    }

    // Restaurer les donnees au chargement
    restaurer();

    // Sauvegarder a chaque saisie
    document.querySelectorAll('#formulaire-inscription input, #formulaire-inscription textarea')
        .forEach(function (champ) {
            champ.addEventListener('input', sauvegarder);
            champ.addEventListener('change', sauvegarder);
        });

    // --- Validations par etape ---
    const REGEX_NOM = /^[a-zA-ZÀ-ÿ\s\-']{2,50}$/;
    const REGEX_EMAIL = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,10}$/;

    function validerEtape1() {
        const nom = document.getElementById('nom');
        const prenom = document.getElementById('prenom');
        const email = document.getElementById('email');
        let valide = true;

        if (!REGEX_NOM.test(nom.value.trim())) {
            afficherErreur(nom, 'erreur-nom', 'Nom invalide (2-50 caracteres, lettres uniquement).');
            valide = false;
        } else { effacerErreur(nom, 'erreur-nom'); }

        if (!REGEX_NOM.test(prenom.value.trim())) {
            afficherErreur(prenom, 'erreur-prenom', 'Prenom invalide.');
            valide = false;
        } else { effacerErreur(prenom, 'erreur-prenom'); }

        if (!REGEX_EMAIL.test(email.value.trim())) {
            afficherErreur(email, 'erreur-email', 'Email invalide.');
            valide = false;
        } else { effacerErreur(email, 'erreur-email'); }

        return valide;
    }

    function validerEtape2() {
        const mdp = document.getElementById('mot-de-passe');
        const confirm = document.getElementById('confirmation-mdp');
        let valide = true;

        if (mdp.value.length < 8) {
            afficherErreur(mdp, 'erreur-mot-de-passe', 'Minimum 8 caracteres.');
            valide = false;
        } else { effacerErreur(mdp, 'erreur-mot-de-passe'); }

        if (confirm.value !== mdp.value || confirm.value === '') {
            afficherErreur(confirm, 'erreur-confirmation-mdp', 'Les mots de passe ne correspondent pas.');
            valide = false;
        } else { effacerErreur(confirm, 'erreur-confirmation-mdp'); }

        return valide;
    }

    function validerEtape3() {
        const message = document.getElementById('message');
        const conditions = document.getElementById('conditions');
        let valide = true;

        if (message.value.trim().length < 10) {
            afficherErreur(message, 'erreur-message', 'Minimum 10 caracteres.');
            valide = false;
        } else { effacerErreur(message, 'erreur-message'); }

        if (!conditions.checked) {
            document.getElementById('erreur-conditions').textContent =
                'Vous devez accepter les conditions.';
            valide = false;
        } else {
            document.getElementById('erreur-conditions').textContent = '';
        }

        return valide;
    }

    // --- Boutons de navigation ---
    document.getElementById('btn-suivant-1').addEventListener('click', function () {
        if (validerEtape1()) afficherEtape(2);
    });

    document.getElementById('btn-suivant-2').addEventListener('click', function () {
        if (validerEtape2()) afficherEtape(3);
    });

    document.getElementById('btn-precedent-2').addEventListener('click', function () {
        afficherEtape(1);
    });

    document.getElementById('btn-precedent-3').addEventListener('click', function () {
        afficherEtape(2);
    });

    // --- Soumission finale ---
    document.getElementById('formulaire-inscription').addEventListener('submit', function (e) {
        if (!validerEtape3()) {
            e.preventDefault();
        } else {
            // Nettoyer le sessionStorage apres soumission reussie
            sessionStorage.clear();
        }
    });
});
```

La validation cote serveur (PHP) reste identique a celle de `traitement.php`, car elle recoit tous les champs d'un coup via `$_POST`.

</details>

---

## Recapitulatif

| Concept | Cote client (JS) | Cote serveur (PHP) |
|---------|-------------------|--------------------|
| But | UX, retour rapide | Securite |
| Obligatoire ? | Non (amelioration) | **Oui** (indispensable) |
| Contournable ? | Oui (JS desactivable) | Non |
| Nettoyage | `trim()` | `htmlspecialchars()`, `filter_var()`, `preg_replace()` |
| Validation | Regex JS, conditions | Regex PHP, `filter_var()`, whitelist |
| Affichage erreurs | DOM : `textContent`, `classList` | PHP : `foreach` sur `$erreurs` |
| Architecture | Fonctions + ecouteurs | Classes OOP (`Sanitizer`, `Validateur`) |

---

**Felicitations !** Vous avez appris a creer un formulaire complet avec validation client et serveur. La prochaine etape est de connecter le formulaire a une base de donnees (avec PDO et les requetes preparees) pour stocker les donnees de maniere securisee.
