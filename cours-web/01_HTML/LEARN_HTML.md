# Cours HTML pour Debutants

## Table des matieres

1. [Introduction : qu'est-ce que le HTML ?](#1-introduction--quest-ce-que-le-html-)
2. [Structure d'un document HTML](#2-structure-dun-document-html)
3. [Les balises essentielles du texte](#3-les-balises-essentielles-du-texte)
4. [Les liens et les images](#4-les-liens-et-les-images)
5. [Les listes](#5-les-listes)
6. [Les tableaux](#6-les-tableaux)
7. [Les formulaires](#7-les-formulaires)
8. [Le HTML5 semantique](#8-le-html5-semantique)
9. [Les attributs importants](#9-les-attributs-importants)
10. [Bonnes pratiques et accessibilite](#10-bonnes-pratiques-et-accessibilite)
11. [Exercices progressifs](#11-exercices-progressifs)

---

## 1. Introduction : qu'est-ce que le HTML ?

### Definition

**HTML** signifie **HyperText Markup Language**, soit en francais : **langage de balisage hypertexte**.

- **Hypertexte** : du texte qui contient des liens vers d'autres textes ou pages.
- **Balisage** : on utilise des **balises** (des mots-cles entre chevrons `< >`) pour structurer le contenu.
- **Langage** : c'est un ensemble de regles que le navigateur comprend.

### Le role du HTML dans le web

Quand vous visitez un site web, votre navigateur (Chrome, Firefox, Safari...) recoit trois types de fichiers principaux :

| Technologie | Role | Analogie |
|-------------|------|----------|
| **HTML** | Structure et contenu de la page | Le squelette d'une maison |
| **CSS** | Apparence visuelle (couleurs, mise en page) | La peinture et la decoration |
| **JavaScript** | Interactions et comportements dynamiques | L'electricite et la plomberie |

Le HTML est donc la **fondation**. Sans HTML, il n'y a pas de page web. C'est toujours par lui qu'on commence.

### Comment ca marche ?

Le HTML utilise des **balises** pour dire au navigateur : "ceci est un titre", "ceci est un paragraphe", "ceci est une image", etc.

Une balise s'ecrit entre chevrons. La plupart des balises vont par paires : une balise **ouvrante** et une balise **fermante** (avec un `/`).

```html
<p>Ceci est un paragraphe.</p>
```

- `<p>` : balise ouvrante (debut du paragraphe)
- `Ceci est un paragraphe.` : le contenu
- `</p>` : balise fermante (fin du paragraphe)

Certaines balises sont **auto-fermantes** (elles n'ont pas de contenu entre deux balises) :

```html
<img src="photo.jpg" alt="Une photo">
<br>
<hr>
```

---

## 2. Structure d'un document HTML

Tout document HTML suit une structure precise. Voici le squelette minimal :

```html
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ma premiere page</title>
</head>
<body>
    <p>Bonjour le monde !</p>
</body>
</html>
```

Expliquons chaque ligne :

### `<!DOCTYPE html>`

Cette ligne dit au navigateur : "Ce document utilise la version HTML5" (la version actuelle). On la met toujours en premiere ligne. Ce n'est pas une balise HTML a proprement parler, c'est une **declaration**.

### `<html lang="fr">`

C'est la balise **racine** qui enveloppe tout le contenu de la page. L'attribut `lang="fr"` indique que la page est en francais. Cela aide les moteurs de recherche et les lecteurs d'ecran.

### `<head>` : l'en-tete invisible

Le `<head>` contient des informations **sur** la page, mais qui ne s'affichent pas directement a l'ecran :

- `<meta charset="UTF-8">` : definit l'encodage des caracteres. UTF-8 permet d'afficher les accents (e, a, u...) et les caracteres speciaux.
- `<meta name="viewport" ...>` : rend la page adaptee aux ecrans de telephone (responsive).
- `<title>` : le texte qui apparait dans l'onglet du navigateur.

### `<body>` : le corps visible

Tout ce qui est dans `<body>` s'affiche a l'ecran. C'est la que vous mettez votre contenu : textes, images, liens, formulaires, etc.

---

## 3. Les balises essentielles du texte

### Les titres : `<h1>` a `<h6>`

HTML propose 6 niveaux de titres. `<h1>` est le plus important, `<h6>` le moins important.

```html
<h1>Titre principal de la page</h1>
<h2>Sous-titre important</h2>
<h3>Sous-sous-titre</h3>
<h4>Titre de niveau 4</h4>
<h5>Titre de niveau 5</h5>
<h6>Titre de niveau 6</h6>
```

**Regles importantes :**
- Il ne doit y avoir qu'un seul `<h1>` par page (c'est le titre principal).
- Respectez la hierarchie : ne passez pas de `<h2>` directement a `<h4>`.
- Les titres ne servent pas a mettre du texte en gros ou en gras. Ils servent a **structurer** le contenu.

### Les paragraphes : `<p>`

Pour ecrire du texte courant, on utilise la balise `<p>` :

```html
<p>Ceci est mon premier paragraphe. Le navigateur ajoute automatiquement
un espace avant et apres chaque paragraphe.</p>

<p>Ceci est un deuxieme paragraphe. Remarquez qu'un simple retour a la
ligne dans le code ne cree PAS de retour a la ligne a l'ecran.</p>
```

### Le retour a la ligne : `<br>`

Si vous voulez forcer un retour a la ligne a l'interieur d'un paragraphe :

```html
<p>Premiere ligne<br>Deuxieme ligne<br>Troisieme ligne</p>
```

### La ligne horizontale : `<hr>`

Pour creer une separation visuelle entre deux sections :

```html
<p>Section 1</p>
<hr>
<p>Section 2</p>
```

### Mise en forme du texte

```html
<p>Du texte en <strong>gras important</strong> et en <em>italique pour l'emphase</em>.</p>
<p>Du texte <mark>surligne</mark> et du <small>texte plus petit</small>.</p>
<p>Une formule : H<sub>2</sub>O et une puissance : x<sup>2</sup>.</p>
```

- `<strong>` : texte important en gras (sens : importance).
- `<em>` : texte en italique (sens : emphase, accentuation).
- `<mark>` : texte surligne.
- `<small>` : texte de petite taille (mentions legales, notes).
- `<sub>` : indice (en bas), `<sup>` : exposant (en haut).

---

## 4. Les liens et les images

### Les liens : `<a>`

Un lien permet de naviguer vers une autre page ou un autre site. On utilise la balise `<a>` (pour "ancre") :

```html
<!-- Lien vers un site externe -->
<a href="https://www.mozilla.org">Visiter Mozilla</a>

<!-- Lien vers une autre page de votre site -->
<a href="contact.html">Page de contact</a>

<!-- Lien qui s'ouvre dans un nouvel onglet -->
<a href="https://www.google.com" target="_blank">Google (nouvel onglet)</a>

<!-- Lien vers une section de la meme page (ancre) -->
<a href="#section-contact">Aller au contact</a>
```

Explications :
- `href` : l'adresse (URL) de destination. C'est l'attribut obligatoire d'un lien.
- `target="_blank"` : ouvre le lien dans un nouvel onglet.
- `#section-contact` : pointe vers un element ayant `id="section-contact"` sur la meme page.

### Les images : `<img>`

Pour afficher une image, on utilise la balise auto-fermante `<img>` :

```html
<!-- Image locale (dans le meme dossier) -->
<img src="photo.jpg" alt="Description de la photo" width="400">

<!-- Image depuis internet -->
<img src="https://example.com/image.png" alt="Logo du site">
```

Explications :
- `src` : le chemin ou l'adresse de l'image (**obligatoire**).
- `alt` : le texte alternatif affiche si l'image ne charge pas, et lu par les lecteurs d'ecran (**obligatoire** pour l'accessibilite).
- `width` / `height` : largeur / hauteur en pixels (optionnel, on prefere souvent le CSS).

### Combiner liens et images

On peut rendre une image cliquable en la placant a l'interieur d'un lien :

```html
<a href="https://www.example.com">
    <img src="logo.png" alt="Cliquez pour visiter le site">
</a>
```

---

## 5. Les listes

### Liste non ordonnee (a puces) : `<ul>`

Utilisee quand l'ordre des elements n'a pas d'importance :

```html
<ul>
    <li>Pommes</li>
    <li>Bananes</li>
    <li>Oranges</li>
</ul>
```

- `<ul>` : **U**nordered **L**ist (liste non ordonnee). Cree la liste.
- `<li>` : **L**ist **I**tem (element de liste). Chaque element est enveloppe dans `<li>`.

Resultat a l'ecran :
- Pommes
- Bananes
- Oranges

### Liste ordonnee (numerotee) : `<ol>`

Utilisee quand l'ordre compte (etapes, classement...) :

```html
<ol>
    <li>Prechauffer le four a 180°C</li>
    <li>Melanger les ingredients</li>
    <li>Enfourner pendant 30 minutes</li>
</ol>
```

Resultat a l'ecran :
1. Prechauffer le four a 180°C
2. Melanger les ingredients
3. Enfourner pendant 30 minutes

### Listes imbriquees

On peut mettre une liste dans une liste :

```html
<ul>
    <li>Fruits
        <ul>
            <li>Pommes</li>
            <li>Poires</li>
        </ul>
    </li>
    <li>Legumes
        <ul>
            <li>Carottes</li>
            <li>Tomates</li>
        </ul>
    </li>
</ul>
```

---

## 6. Les tableaux

Les tableaux servent a presenter des **donnees structurees** (horaires, comparaisons, statistiques...). On ne les utilise **jamais** pour mettre en page un site (on utilise le CSS pour ca).

### Structure de base

```html
<table>
    <thead>
        <tr>
            <th>Prenom</th>
            <th>Age</th>
            <th>Ville</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Alice</td>
            <td>25</td>
            <td>Paris</td>
        </tr>
        <tr>
            <td>Bob</td>
            <td>30</td>
            <td>Lyon</td>
        </tr>
    </tbody>
</table>
```

Explications ligne par ligne :
- `<table>` : cree le tableau.
- `<thead>` : regroupe la ligne d'en-tete (les titres des colonnes).
- `<tbody>` : regroupe les lignes de donnees.
- `<tr>` : **T**able **R**ow, une ligne du tableau.
- `<th>` : **T**able **H**eader, une cellule d'en-tete (texte en gras et centre par defaut).
- `<td>` : **T**able **D**ata, une cellule de donnees.

### Fusionner des cellules

```html
<table>
    <tr>
        <td colspan="2">Cette cellule occupe 2 colonnes</td>
    </tr>
    <tr>
        <td>Cellule 1</td>
        <td>Cellule 2</td>
    </tr>
</table>
```

- `colspan="2"` : la cellule s'etend sur 2 colonnes.
- `rowspan="2"` : la cellule s'etend sur 2 lignes.

---

## 7. Les formulaires

Les formulaires permettent a l'utilisateur d'envoyer des donnees (inscription, contact, recherche...).

### Structure de base

```html
<form action="/traitement.php" method="POST">
    <!-- Les champs du formulaire vont ici -->
    <button type="submit">Envoyer</button>
</form>
```

- `<form>` : enveloppe tout le formulaire.
- `action` : l'adresse (URL) ou les donnees seront envoyees.
- `method` : la methode d'envoi. `POST` pour envoyer des donnees, `GET` pour une recherche.

### Les champs de saisie : `<input>`

L'element `<input>` change de comportement selon son attribut `type` :

```html
<!-- Champ texte simple -->
<label for="nom">Votre nom :</label>
<input type="text" id="nom" name="nom" placeholder="Jean Dupont">

<!-- Champ email (le navigateur verifie le format) -->
<label for="email">Email :</label>
<input type="email" id="email" name="email" placeholder="jean@exemple.fr" required>

<!-- Champ mot de passe (le texte est masque) -->
<label for="mdp">Mot de passe :</label>
<input type="password" id="mdp" name="mdp">

<!-- Champ nombre -->
<label for="age">Age :</label>
<input type="number" id="age" name="age" min="1" max="120">

<!-- Case a cocher -->
<input type="checkbox" id="accepter" name="accepter">
<label for="accepter">J'accepte les conditions</label>

<!-- Boutons radio (un seul choix possible dans le groupe) -->
<input type="radio" id="homme" name="genre" value="homme">
<label for="homme">Homme</label>
<input type="radio" id="femme" name="genre" value="femme">
<label for="femme">Femme</label>

<!-- Champ date -->
<label for="naissance">Date de naissance :</label>
<input type="date" id="naissance" name="naissance">
```

**Points importants :**
- `<label>` : l'etiquette associee au champ. L'attribut `for` doit correspondre a l'`id` du champ. Cela permet de cliquer sur le texte pour activer le champ.
- `name` : le nom de la donnee envoyee au serveur. **Obligatoire** pour que le serveur recoive la valeur.
- `placeholder` : texte d'exemple affiche en gris dans le champ vide.
- `required` : rend le champ obligatoire (le formulaire ne s'envoie pas si le champ est vide).

### Zone de texte multiligne : `<textarea>`

Pour les messages longs (commentaires, descriptions...) :

```html
<label for="message">Votre message :</label>
<textarea id="message" name="message" rows="5" cols="40"
          placeholder="Ecrivez votre message ici..."></textarea>
```

- `rows` : nombre de lignes visibles.
- `cols` : nombre de colonnes visibles (largeur en caracteres).

### Liste deroulante : `<select>`

```html
<label for="pays">Votre pays :</label>
<select id="pays" name="pays">
    <option value="">-- Choisissez --</option>
    <option value="fr">France</option>
    <option value="be">Belgique</option>
    <option value="ch">Suisse</option>
    <option value="ca">Canada</option>
</select>
```

- `<select>` : cree la liste deroulante.
- `<option>` : chaque choix possible. L'attribut `value` est la valeur envoyee au serveur.

### Les boutons

```html
<!-- Bouton d'envoi du formulaire -->
<button type="submit">Envoyer</button>

<!-- Bouton de reinitialisation (remet tous les champs a zero) -->
<button type="reset">Effacer</button>

<!-- Bouton generique (pour JavaScript) -->
<button type="button">Cliquez-moi</button>
```

### Exemple complet de formulaire

```html
<form action="/inscription" method="POST">
    <fieldset>
        <legend>Formulaire d'inscription</legend>

        <label for="prenom">Prenom :</label>
        <input type="text" id="prenom" name="prenom" required>

        <label for="courriel">Email :</label>
        <input type="email" id="courriel" name="courriel" required>

        <label for="bio">Presentez-vous :</label>
        <textarea id="bio" name="bio" rows="4"></textarea>

        <label for="niveau">Niveau :</label>
        <select id="niveau" name="niveau">
            <option value="debutant">Debutant</option>
            <option value="intermediaire">Intermediaire</option>
            <option value="avance">Avance</option>
        </select>

        <button type="submit">S'inscrire</button>
    </fieldset>
</form>
```

- `<fieldset>` : regroupe visuellement des champs lies (dessine un cadre autour).
- `<legend>` : le titre du groupe de champs.

---

## 8. Le HTML5 semantique

### Qu'est-ce que le HTML semantique ?

"Semantique" signifie "qui a du sens". Les balises semantiques **decrivent le role** de leur contenu, pas seulement son apparence.

Avant HTML5, on utilisait beaucoup de `<div>` (divisions generiques) partout. Le probleme : un `<div>` ne dit rien sur ce qu'il contient.

Comparaison :

```html
<!-- AVANT (non semantique) : on ne sait pas ce que fait chaque div -->
<div class="header">...</div>
<div class="nav">...</div>
<div class="content">...</div>
<div class="footer">...</div>

<!-- APRES (semantique) : le nom de la balise decrit son role -->
<header>...</header>
<nav>...</nav>
<main>...</main>
<footer>...</footer>
```

### Les balises semantiques principales

```html
<body>
    <!-- En-tete du site : logo, titre, slogan -->
    <header>
        <h1>Mon Site Web</h1>
        <p>Bienvenue sur mon site</p>
    </header>

    <!-- Navigation principale : le menu -->
    <nav>
        <ul>
            <li><a href="#accueil">Accueil</a></li>
            <li><a href="#projets">Projets</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>
    </nav>

    <!-- Contenu principal de la page (un seul <main> par page) -->
    <main>
        <!-- Section : un regroupement thematique -->
        <section id="accueil">
            <h2>A propos de moi</h2>
            <p>Je suis developpeur web.</p>
        </section>

        <!-- Article : un contenu autonome (billet de blog, actualite) -->
        <article>
            <h2>Mon premier article</h2>
            <p>Contenu de l'article...</p>
        </article>

        <!-- Aside : contenu complementaire (barre laterale, info bonus) -->
        <aside>
            <h3>Le saviez-vous ?</h3>
            <p>HTML a ete cree en 1991 par Tim Berners-Lee.</p>
        </aside>
    </main>

    <!-- Pied de page : copyright, liens legaux, contact -->
    <footer>
        <p>&copy; 2026 Mon Site. Tous droits reserves.</p>
    </footer>
</body>
```

Resume des balises semantiques :

| Balise | Role |
|--------|------|
| `<header>` | En-tete de la page ou d'une section |
| `<nav>` | Menu de navigation |
| `<main>` | Contenu principal (unique par page) |
| `<section>` | Regroupement thematique de contenu |
| `<article>` | Contenu autonome et independant |
| `<aside>` | Contenu complementaire ou lateral |
| `<footer>` | Pied de page ou d'une section |

### Pourquoi c'est important ?

1. **Accessibilite** : les lecteurs d'ecran (utilises par les personnes malvoyantes) comprennent la structure de la page.
2. **Referencement (SEO)** : les moteurs de recherche comprennent mieux votre contenu.
3. **Lisibilite du code** : votre code est plus facile a lire et a maintenir.

---

## 9. Les attributs importants

Les **attributs** sont des informations supplementaires ajoutees a une balise. Ils se placent dans la balise ouvrante.

Syntaxe generale :

```html
<balise attribut="valeur">Contenu</balise>
```

### Attributs universels (utilisables sur toute balise)

#### `id` : identifiant unique

```html
<!-- Un id doit etre unique dans toute la page -->
<section id="contact">
    <h2>Contactez-moi</h2>
</section>

<!-- On peut creer un lien vers cet id -->
<a href="#contact">Aller au contact</a>
```

- Sert a identifier un element de maniere unique.
- Utilise pour les liens d'ancrage, le CSS, et le JavaScript.

#### `class` : classe reutilisable

```html
<!-- Plusieurs elements peuvent avoir la meme classe -->
<p class="important">Ce paragraphe est important.</p>
<p class="important souligne">Celui-ci aussi, et il est souligne.</p>
```

- Sert a regrouper des elements pour leur appliquer le meme style CSS.
- Un element peut avoir **plusieurs classes** (separees par des espaces).

#### `style` : style en ligne

```html
<p style="color: blue; font-size: 20px;">Texte bleu en taille 20 pixels.</p>
```

- Permet d'appliquer du CSS directement sur un element.
- A eviter : on prefere mettre le CSS dans un fichier separe.

#### `title` : info-bulle

```html
<p title="Ceci apparait au survol de la souris">Survolez-moi !</p>
```

### Attributs specifiques courants

| Attribut | Balises | Role |
|----------|---------|------|
| `src` | `<img>`, `<script>`, `<video>` | Chemin vers le fichier source |
| `href` | `<a>`, `<link>` | Adresse de destination |
| `alt` | `<img>` | Texte alternatif (accessibilite) |
| `placeholder` | `<input>`, `<textarea>` | Texte d'exemple dans un champ |
| `required` | `<input>`, `<select>`, `<textarea>` | Rend le champ obligatoire |
| `disabled` | `<input>`, `<button>`, `<select>` | Desactive l'element |
| `value` | `<input>`, `<option>` | Valeur par defaut ou envoyee |
| `target` | `<a>` | Ou ouvrir le lien (`_blank` = nouvel onglet) |
| `type` | `<input>`, `<button>` | Type du champ ou du bouton |
| `name` | Elements de formulaire | Nom de la donnee envoyee |
| `for` | `<label>` | Lie le label a un champ par son `id` |

---

## 10. Bonnes pratiques et accessibilite

### Bonnes pratiques generales

1. **Toujours declarer le DOCTYPE**

```html
<!DOCTYPE html>
```

2. **Indenter son code** : utilisez 2 ou 4 espaces pour chaque niveau d'imbrication. Cela rend le code lisible.

```html
<!-- Bien indente (facile a lire) -->
<ul>
    <li>Element 1</li>
    <li>Element 2</li>
</ul>

<!-- Mal indente (difficile a lire) -->
<ul>
<li>Element 1</li>
<li>Element 2</li>
</ul>
```

3. **Fermer toutes les balises** : meme si le navigateur tolere parfois les oublis, fermez toujours vos balises.

4. **Utiliser des noms significatifs** pour les `id` et `class` :

```html
<!-- Bien : le nom decrit le contenu -->
<div id="menu-principal">
<p class="texte-important">

<!-- Mal : le nom decrit l'apparence -->
<div id="div1">
<p class="rouge-gras">
```

5. **Commenter son code** pour expliquer les sections complexes :

```html
<!-- Debut de la section contact -->
<section id="contact">
    ...
</section>
<!-- Fin de la section contact -->
```

6. **Valider son code** : utilisez le validateur officiel du W3C (https://validator.w3.org/) pour verifier que votre HTML est correct.

### Accessibilite (a11y)

L'accessibilite web, c'est rendre votre site utilisable par **tout le monde**, y compris les personnes en situation de handicap.

#### Les regles de base :

1. **Toujours mettre un `alt` sur les images** :

```html
<!-- Bien : description utile -->
<img src="chat.jpg" alt="Un chat roux assis sur un canape">

<!-- Bien : image decorative (alt vide, mais present) -->
<img src="decoration.png" alt="">

<!-- Mal : pas de alt -->
<img src="chat.jpg">
```

2. **Utiliser les balises semantiques** (voir section 8) au lieu de `<div>` partout.

3. **Associer chaque champ de formulaire a un `<label>`** :

```html
<!-- Bien : le label est lie au champ -->
<label for="email">Email :</label>
<input type="email" id="email" name="email">

<!-- Mal : pas de label -->
<input type="email" name="email">
```

4. **Respecter la hierarchie des titres** : `<h1>` puis `<h2>` puis `<h3>`, sans sauter de niveaux.

5. **Utiliser des contrastes de couleurs suffisants** entre le texte et le fond (ceci releve du CSS, mais c'est bon a savoir des le debut).

6. **Indiquer la langue de la page** :

```html
<html lang="fr">
```

7. **Rendre les liens explicites** :

```html
<!-- Bien : on comprend la destination -->
<a href="tarifs.html">Voir nos tarifs</a>

<!-- Mal : on ne sait pas ou ca mene -->
<a href="tarifs.html">Cliquez ici</a>
```

---

## 11. Exercices progressifs

### Exercice 1 : Ma premiere page HTML (niveau facile)

Creez un fichier `exercice1.html` contenant :
- La structure de base d'un document HTML (DOCTYPE, html, head, body)
- Un titre de page (dans `<title>`) : "Ma premiere page"
- Un titre `<h1>` avec votre prenom
- Deux paragraphes `<p>` vous presentant (passions, etudes, ville...)
- Une ligne horizontale `<hr>` entre les deux paragraphes

---

### Exercice 2 : Liens et images (niveau facile)

Creez un fichier `exercice2.html` contenant :
- Un titre `<h1>` : "Mes sites preferes"
- Une liste non ordonnee `<ul>` avec 3 liens `<a>` vers vos sites web preferes (chaque lien doit s'ouvrir dans un nouvel onglet)
- Un titre `<h2>` : "Ma photo"
- Une image `<img>` (vous pouvez utiliser une image placeholder comme `https://picsum.photos/400/300`) avec un texte alternatif `alt` descriptif

---

### Exercice 3 : Un tableau de competences (niveau moyen)

Creez un fichier `exercice3.html` contenant :
- Un titre `<h1>` : "Mes competences"
- Un tableau avec :
  - Un en-tete (`<thead>`) avec 3 colonnes : "Competence", "Niveau", "Experience"
  - Au moins 4 lignes de donnees (`<tbody>`) avec vos competences (reelles ou inventees)
  - Utilisez `<th>` pour les en-tetes et `<td>` pour les donnees

---

### Exercice 4 : Un formulaire de contact (niveau moyen)

Creez un fichier `exercice4.html` contenant un formulaire avec :
- Un `<fieldset>` et une `<legend>` : "Formulaire de contact"
- Un champ texte pour le nom (obligatoire)
- Un champ email (obligatoire, avec placeholder)
- Un champ telephone (`type="tel"`)
- Une liste deroulante pour le sujet du message (3 options minimum)
- Une zone de texte (`<textarea>`) pour le message
- Un bouton d'envoi et un bouton de reinitialisation
- Chaque champ doit avoir un `<label>` associe

---

### Exercice 5 : Page portfolio complete (niveau avance)

Creez un fichier `exercice5.html` qui est une page portfolio complete utilisant le HTML semantique :

Structure demandee :
- `<header>` : votre nom en `<h1>` et un slogan en `<p>`
- `<nav>` : un menu avec des liens d'ancrage vers chaque section
- `<main>` contenant :
  - `<section id="a-propos">` : un paragraphe de presentation
  - `<section id="competences">` : un tableau de competences
  - `<section id="projets">` : au moins 2 `<article>` decrivant des projets (avec image, titre, description et lien)
  - `<section id="contact">` : un formulaire de contact complet
- `<aside>` : une citation inspirante ou un fait amusant
- `<footer>` : copyright et liens vers vos reseaux sociaux

**Indice** : vous pouvez vous inspirer du fichier `index.html` fourni avec ce cours !

---

## Conclusion

Felicitations ! Vous avez appris les bases du HTML. Voici un resume de ce que vous savez maintenant :

- Creer la structure d'une page HTML
- Utiliser les balises de texte (titres, paragraphes, mise en forme)
- Ajouter des liens et des images
- Creer des listes et des tableaux
- Construire des formulaires
- Structurer une page avec les balises semantiques HTML5
- Appliquer les bonnes pratiques et les bases de l'accessibilite

La prochaine etape sera d'apprendre le **CSS** pour donner du style et de la couleur a vos pages HTML !
