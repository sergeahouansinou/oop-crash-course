# Cours complet de CSS pour débutants

## Table des matières

1. [Qu'est-ce que le CSS ?](#1-quest-ce-que-le-css-)
2. [Les 3 façons d'ajouter du CSS](#2-les-3-façons-dajouter-du-css)
3. [Les sélecteurs](#3-les-sélecteurs)
4. [Spécificité et cascade](#4-spécificité-et-cascade)
5. [Le modèle de boîte (Box Model)](#5-le-modèle-de-boîte-box-model)
6. [Les unités](#6-les-unités)
7. [Les couleurs](#7-les-couleurs)
8. [La typographie](#8-la-typographie)
9. [La propriété display](#9-la-propriété-display)
10. [Le positionnement](#10-le-positionnement)
11. [Flexbox](#11-flexbox)
12. [CSS Grid](#12-css-grid)
13. [Le responsive design](#13-le-responsive-design)
14. [Transitions et animations](#14-transitions-et-animations)
15. [Les variables CSS](#15-les-variables-css-propriétés-personnalisées)
16. [Exercices progressifs](#16-exercices-progressifs)

---

## 1. Qu'est-ce que le CSS ?

**CSS** signifie **Cascading Style Sheets**, soit « Feuilles de Style en Cascade » en français.

Le CSS est un langage qui sert à **mettre en forme** les pages web. Si le HTML est le squelette de votre page (la structure, le contenu), le CSS en est la **peau et les vêtements** (les couleurs, les tailles, les espacements, la disposition).

### Le principe de séparation des responsabilités

En développement web, on respecte un principe important : **chaque langage a son rôle**.

| Langage | Rôle |
|---------|------|
| **HTML** | Structure et contenu (les titres, paragraphes, images, liens...) |
| **CSS** | Présentation et mise en forme (couleurs, tailles, disposition...) |
| **JavaScript** | Comportement et interactivité (réagir aux clics, animer...) |

Cette séparation rend le code plus **lisible**, plus **facile à maintenir** et permet de **changer l'apparence** d'un site sans toucher au contenu.

### Syntaxe de base

Une **règle CSS** se compose de 3 éléments :

```css
sélecteur {
    propriété: valeur;
}
```

- **Sélecteur** : indique à quel(s) élément(s) HTML on applique le style.
- **Propriété** : ce qu'on veut modifier (couleur, taille, marge...).
- **Valeur** : la valeur qu'on attribue à la propriété.

Exemple concret :

```css
/* On cible tous les paragraphes */
p {
    color: blue;        /* La couleur du texte sera bleue */
    font-size: 16px;    /* La taille du texte sera de 16 pixels */
}
```

> **Note :** Les commentaires en CSS s'écrivent entre `/*` et `*/`.

---

## 2. Les 3 façons d'ajouter du CSS

Il existe trois méthodes pour appliquer du CSS à une page HTML.

### 2.1 Le CSS en ligne (inline)

On écrit le style directement dans l'attribut `style` d'une balise HTML.

```html
<p style="color: red; font-size: 20px;">Ce texte est rouge et plus grand.</p>
```

**Inconvénients :**
- Mélange le HTML et le CSS (mauvaise pratique).
- Impossible de réutiliser le style pour d'autres éléments.
- Difficile à maintenir.

### 2.2 Le CSS interne (internal)

On écrit le CSS dans une balise `<style>` à l'intérieur du `<head>` de la page HTML.

```html
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ma page</title>
    <style>
        p {
            color: red;
            font-size: 20px;
        }
    </style>
</head>
<body>
    <p>Ce texte est rouge et plus grand.</p>
</body>
</html>
```

**Inconvénients :**
- Le style n'est valable que pour cette page.
- Si on a 50 pages, il faut copier le CSS dans chacune.

### 2.3 Le CSS externe (external) -- RECOMMANDÉ

On écrit le CSS dans un fichier séparé (par exemple `style.css`) et on le lie à la page HTML avec une balise `<link>`.

```html
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ma page</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <p>Ce texte est rouge et plus grand.</p>
</body>
</html>
```

```css
/* Fichier style.css */
p {
    color: red;
    font-size: 20px;
}
```

**Avantages :**
- Séparation claire entre HTML et CSS.
- Un seul fichier CSS peut être partagé par toutes les pages du site.
- Plus facile à maintenir et à organiser.
- Le navigateur met le fichier CSS en cache (le site se charge plus vite).

> **Recommandation :** Utilisez **toujours** la méthode externe pour vos projets.

---

## 3. Les sélecteurs

Les sélecteurs permettent de **cibler** les éléments HTML auxquels on veut appliquer un style.

### 3.1 Sélecteur d'élément (de type)

Cible **toutes** les balises d'un même type.

```css
/* Tous les paragraphes */
p {
    color: gray;
}

/* Tous les titres h1 */
h1 {
    font-size: 32px;
}
```

### 3.2 Sélecteur de classe

Cible tous les éléments qui possèdent une **classe** donnée. On utilise un **point** (`.`) devant le nom de la classe.

```html
<p class="important">Ce paragraphe est important.</p>
<p>Ce paragraphe est normal.</p>
<p class="important">Celui-ci aussi est important.</p>
```

```css
/* Cible tous les éléments ayant la classe "important" */
.important {
    color: red;
    font-weight: bold;  /* Texte en gras */
}
```

> **Note :** Un élément peut avoir **plusieurs classes**, séparées par des espaces : `<p class="important grand">`.

### 3.3 Sélecteur d'identifiant (id)

Cible **un seul élément** qui possède un identifiant unique. On utilise un **dièse** (`#`) devant le nom de l'id.

```html
<h1 id="titre-principal">Bienvenue sur mon site</h1>
```

```css
/* Cible l'élément ayant l'id "titre-principal" */
#titre-principal {
    color: darkblue;
    text-align: center;
}
```

> **Important :** Un `id` doit être **unique** dans toute la page. Préférez les **classes** pour le CSS, car elles sont réutilisables.

### 3.4 Sélecteur universel

Cible **tous** les éléments de la page. On utilise l'**astérisque** (`*`).

```css
/* Applique à tous les éléments */
* {
    margin: 0;
    padding: 0;
}
```

### 3.5 Les combinateurs

Les combinateurs permettent de cibler des éléments **en fonction de leur position** dans le HTML.

#### Combinateur descendant (espace)

Cible un élément qui se trouve **à l'intérieur** d'un autre, même s'il n'est pas un enfant direct.

```css
/* Tous les <a> qui sont à l'intérieur d'un <nav> */
nav a {
    color: white;
    text-decoration: none;  /* Retire le soulignement */
}
```

#### Combinateur enfant direct (`>`)

Cible uniquement les enfants **directs** (premier niveau).

```css
/* Seulement les <li> qui sont enfants directs de <ul> */
ul > li {
    list-style: square;  /* Puces carrées */
}
```

#### Combinateur de frère adjacent (`+`)

Cible l'élément qui vient **immédiatement après** un autre (même parent).

```css
/* Le paragraphe qui suit immédiatement un h2 */
h2 + p {
    font-size: 18px;
    color: gray;
}
```

#### Combinateur de frères généraux (`~`)

Cible **tous** les éléments frères qui suivent un autre élément.

```css
/* Tous les paragraphes qui suivent un h2 (même parent) */
h2 ~ p {
    margin-left: 20px;
}
```

### 3.6 Les pseudo-classes

Les pseudo-classes ciblent un élément dans un **état particulier**. On les écrit avec **un seul deux-points** (`:`).

#### `:hover` -- au survol de la souris

```css
/* Quand la souris passe sur un lien */
a:hover {
    color: orange;
    text-decoration: underline;
}
```

#### `:focus` -- quand l'élément a le focus

Le focus se produit quand on clique ou navigue (avec Tab) vers un champ de formulaire.

```css
/* Quand un champ de saisie a le focus */
input:focus {
    border-color: blue;
    outline: none;  /* Retire le contour par défaut du navigateur */
}
```

#### `:first-child` -- le premier enfant

```css
/* Le premier <li> de chaque liste */
li:first-child {
    font-weight: bold;
}
```

#### `:nth-child()` -- le n-ième enfant

```css
/* Chaque ligne paire d'un tableau */
tr:nth-child(even) {
    background-color: #f2f2f2;
}

/* Chaque 3e élément */
li:nth-child(3n) {
    color: red;
}
```

> `even` = pair, `odd` = impair. On peut aussi écrire `2n` pour pair, `2n+1` pour impair.

### 3.7 Les pseudo-éléments

Les pseudo-éléments ciblent **une partie spécifique** d'un élément. On les écrit avec **deux deux-points** (`::`).

#### `::before` et `::after`

Ils ajoutent du contenu **avant** ou **après** le contenu d'un élément, sans modifier le HTML.

```css
/* Ajoute une flèche avant chaque lien */
a::before {
    content: "→ ";  /* La propriété "content" est obligatoire */
}

/* Ajoute un point après chaque élément de liste */
li::after {
    content: " •";
    color: gray;
}
```

> **Important :** La propriété `content` est **obligatoire** pour `::before` et `::after`, même si on la laisse vide (`content: "";`).

---

## 4. Spécificité et cascade

### La cascade

Le « C » de CSS signifie **Cascade**. Quand plusieurs règles ciblent le même élément et la même propriété, le navigateur doit décider laquelle appliquer. Il suit cet ordre de priorité :

1. **Importance** : les règles avec `!important` gagnent toujours (à éviter !).
2. **Spécificité** : plus un sélecteur est précis, plus il est prioritaire.
3. **Ordre d'apparition** : à spécificité égale, la dernière règle écrite gagne.

### La spécificité

Chaque sélecteur a un « poids ». On peut le calculer avec un système à 3 chiffres **(A, B, C)** :

| Catégorie | Élément | Poids |
|-----------|---------|-------|
| A | Identifiant (`#id`) | 1, 0, 0 |
| B | Classe (`.classe`), pseudo-classe (`:hover`), attribut (`[type="text"]`) | 0, 1, 0 |
| C | Élément (`p`, `h1`, `div`), pseudo-élément (`::before`) | 0, 0, 1 |

**Exemples :**

```css
p { }                    /* Spécificité : 0, 0, 1 */
.intro { }               /* Spécificité : 0, 1, 0 */
#header { }              /* Spécificité : 1, 0, 0 */
p.intro { }              /* Spécificité : 0, 1, 1 */
#header .nav a { }       /* Spécificité : 1, 1, 1 */
```

**Résolution d'un conflit :**

```css
p { color: blue; }            /* 0, 0, 1 → perd */
.texte { color: green; }      /* 0, 1, 0 → gagne ! */
```

```html
<p class="texte">Ce texte sera vert.</p>
```

Le sélecteur `.texte` (0, 1, 0) est plus spécifique que `p` (0, 0, 1), donc le texte sera **vert**.

### Bonnes pratiques

- **Évitez `!important`** autant que possible (cela rend le code difficile à maintenir).
- **Préférez les classes** aux identifiants pour le CSS.
- **Gardez une spécificité basse** : écrivez des sélecteurs simples.

---

## 5. Le modèle de boîte (Box Model)

En CSS, **chaque élément HTML est une boîte rectangulaire**. Cette boîte est composée de 4 couches, de l'intérieur vers l'extérieur :

```
┌─────────────────────────────────────────────┐
│                  MARGIN                      │
│   ┌─────────────────────────────────────┐   │
│   │              BORDER                  │   │
│   │   ┌─────────────────────────────┐   │   │
│   │   │          PADDING             │   │   │
│   │   │   ┌─────────────────────┐   │   │   │
│   │   │   │                     │   │   │   │
│   │   │   │      CONTENU        │   │   │   │
│   │   │   │   (width x height)  │   │   │   │
│   │   │   │                     │   │   │   │
│   │   │   └─────────────────────┘   │   │   │
│   │   │                              │   │   │
│   │   └─────────────────────────────┘   │   │
│   │                                      │   │
│   └─────────────────────────────────────┘   │
│                                              │
└─────────────────────────────────────────────┘
```

### Les 4 couches

| Couche | Description | Propriété CSS |
|--------|-------------|---------------|
| **Content** (contenu) | Le texte, l'image, etc. | `width`, `height` |
| **Padding** (marge interne) | Espace entre le contenu et la bordure | `padding` |
| **Border** (bordure) | Le cadre autour du padding | `border` |
| **Margin** (marge externe) | Espace entre la bordure et les autres éléments | `margin` |

### Exemples

```css
.boite {
    /* Contenu */
    width: 300px;               /* Largeur du contenu */
    height: 200px;              /* Hauteur du contenu */

    /* Marge interne (padding) */
    padding: 20px;              /* 20px de chaque côté */
    padding-top: 10px;          /* Seulement en haut */
    padding: 10px 20px;         /* 10px haut/bas, 20px gauche/droite */
    padding: 10px 20px 15px 5px; /* haut, droite, bas, gauche (sens horaire) */

    /* Bordure */
    border: 2px solid black;     /* épaisseur style couleur */
    border-radius: 10px;         /* Coins arrondis */

    /* Marge externe (margin) */
    margin: 20px;                /* 20px de chaque côté */
    margin: 0 auto;              /* Centrer horizontalement un bloc */
}
```

### Le problème de calcul de taille

Par défaut, `width` et `height` ne concernent que le **contenu**. Le padding et la bordure s'ajoutent en plus !

```css
.boite {
    width: 300px;
    padding: 20px;      /* +40px (20 à gauche + 20 à droite) */
    border: 2px solid;  /* +4px (2 à gauche + 2 à droite) */
}
/* Largeur totale = 300 + 40 + 4 = 344px ! */
```

### La solution : `box-sizing: border-box`

Avec `border-box`, le `width` inclut le padding et la bordure. C'est beaucoup plus intuitif.

```css
/* Bonne pratique : appliquer à tous les éléments */
*, *::before, *::after {
    box-sizing: border-box;
}
```

Maintenant, si on écrit `width: 300px`, la boîte fera toujours **exactement 300px** de large, quels que soient le padding et la bordure.

---

## 6. Les unités

### Unités absolues

| Unité | Description |
|-------|-------------|
| `px` | **Pixels**. Unité fixe, la plus courante. 1px = 1 point sur l'écran. |

```css
p {
    font-size: 16px;  /* Taille fixe de 16 pixels */
}
```

### Unités relatives

Les unités relatives s'adaptent en fonction d'un élément de référence.

| Unité | Référence | Description |
|-------|-----------|-------------|
| `em` | Taille de police du **parent** | 1em = la taille de police de l'élément parent |
| `rem` | Taille de police de la **racine** (`<html>`) | 1rem = la taille de police définie sur `<html>` (par défaut 16px) |
| `%` | Le **parent** | Pourcentage de la taille du parent |
| `vh` | La **hauteur** de la fenêtre | 1vh = 1% de la hauteur de la fenêtre du navigateur |
| `vw` | La **largeur** de la fenêtre | 1vw = 1% de la largeur de la fenêtre du navigateur |

```css
html {
    font-size: 16px;   /* Base de référence pour rem */
}

h1 {
    font-size: 2rem;    /* 2 × 16px = 32px */
}

.conteneur {
    width: 80%;         /* 80% de la largeur du parent */
}

.hero {
    height: 100vh;      /* Prend toute la hauteur de la fenêtre */
    width: 100vw;       /* Prend toute la largeur de la fenêtre */
}

.texte {
    font-size: 1.2em;   /* 1.2 × la taille du parent */
}
```

> **Conseil :** Préférez `rem` pour les tailles de police (prévisible) et `%` ou `vw`/`vh` pour les dimensions de mise en page.

---

## 7. Les couleurs

CSS offre plusieurs façons d'exprimer une couleur.

### Noms de couleurs

CSS connaît 147 noms de couleurs prédéfinis.

```css
p { color: red; }
p { color: tomato; }
p { color: darkblue; }
p { color: transparent; }  /* Couleur transparente */
```

### Hexadécimal (hex)

Un code commençant par `#`, suivi de 6 caractères (ou 3 en version courte).

```css
p { color: #ff0000; }   /* Rouge pur */
p { color: #f00; }      /* Version courte de #ff0000 */
p { color: #333333; }   /* Gris foncé */
p { color: #333; }      /* Version courte */
```

Chaque paire de caractères représente : **RR GG BB** (Rouge, Vert, Bleu). Les valeurs vont de `00` (rien) à `ff` (maximum).

### RGB

On indique la quantité de Rouge, Vert et Bleu de 0 à 255.

```css
p { color: rgb(255, 0, 0); }     /* Rouge pur */
p { color: rgb(100, 100, 100); }  /* Gris */
```

### RGBA

Comme RGB, mais avec un 4e paramètre : l'**opacité** (alpha), de 0 (invisible) à 1 (opaque).

```css
p { color: rgba(255, 0, 0, 0.5); }   /* Rouge à 50% d'opacité */
p { background: rgba(0, 0, 0, 0.3); } /* Fond noir semi-transparent */
```

### HSL

HSL signifie **Hue** (teinte), **Saturation**, **Lightness** (luminosité).

- **Hue** : angle de 0 à 360 (0 = rouge, 120 = vert, 240 = bleu).
- **Saturation** : de 0% (gris) à 100% (couleur pure).
- **Lightness** : de 0% (noir) à 100% (blanc), 50% = normal.

```css
p { color: hsl(0, 100%, 50%); }    /* Rouge pur */
p { color: hsl(120, 100%, 50%); }  /* Vert pur */
p { color: hsl(240, 100%, 50%); }  /* Bleu pur */
p { color: hsl(0, 0%, 50%); }      /* Gris moyen */
```

> **Astuce :** HSL est souvent plus intuitif pour créer des palettes de couleurs : on garde la même teinte et on fait varier la luminosité.

---

## 8. La typographie

### Famille de police : `font-family`

On indique une police, suivie de polices de secours (au cas où la première ne serait pas disponible), et on termine par une famille générique.

```css
body {
    /* Si Arial n'est pas disponible, on essaie Helvetica, sinon n'importe quelle sans-serif */
    font-family: Arial, Helvetica, sans-serif;
}

h1 {
    font-family: 'Georgia', 'Times New Roman', serif;
}

code {
    font-family: 'Courier New', Courier, monospace;
}
```

Les familles génériques : `serif` (avec empattements), `sans-serif` (sans empattements), `monospace` (largeur fixe).

### Taille de police : `font-size`

```css
p {
    font-size: 16px;    /* En pixels */
    font-size: 1rem;    /* En rem (recommandé) */
    font-size: 1.2em;   /* Relatif au parent */
}
```

### Graisse (épaisseur) : `font-weight`

```css
p {
    font-weight: normal;   /* Épaisseur normale (équivaut à 400) */
    font-weight: bold;     /* Gras (équivaut à 700) */
    font-weight: 300;      /* Léger (light) */
    font-weight: 600;      /* Semi-gras (semi-bold) */
}
```

### Hauteur de ligne : `line-height`

L'espace entre les lignes de texte. On utilise souvent un nombre sans unité (multiplicateur).

```css
p {
    line-height: 1.6;    /* 1.6 fois la taille de la police */
}
```

> **Conseil :** Une `line-height` entre 1.4 et 1.8 rend le texte plus lisible.

### Alignement du texte : `text-align`

```css
h1 { text-align: center; }   /* Centré */
p  { text-align: left; }     /* À gauche (par défaut) */
p  { text-align: right; }    /* À droite */
p  { text-align: justify; }  /* Justifié (aligné des deux côtés) */
```

### Décoration du texte : `text-decoration`

```css
a { text-decoration: none; }         /* Retire le soulignement des liens */
p { text-decoration: underline; }    /* Souligner */
p { text-decoration: line-through; } /* Barrer */
p { text-decoration: overline; }     /* Ligne au-dessus */
```

### Transformation du texte : `text-transform`

```css
h1 { text-transform: uppercase; }    /* TOUT EN MAJUSCULES */
h2 { text-transform: lowercase; }    /* tout en minuscules */
h3 { text-transform: capitalize; }   /* Première Lettre En Majuscule */
```

---

## 9. La propriété `display`

La propriété `display` contrôle **comment un élément se comporte** dans le flux de la page.

### `display: block`

L'élément prend **toute la largeur** disponible et commence sur une **nouvelle ligne**.

Éléments block par défaut : `<div>`, `<p>`, `<h1>`...`<h6>`, `<section>`, `<article>`, `<header>`, `<footer>`.

```css
div {
    display: block;    /* Par défaut pour <div> */
    width: 50%;        /* On peut définir une largeur */
    height: 100px;     /* Et une hauteur */
}
```

### `display: inline`

L'élément prend **seulement la place de son contenu** et reste sur la **même ligne** que ses voisins.

Éléments inline par défaut : `<span>`, `<a>`, `<strong>`, `<em>`, `<img>`.

```css
span {
    display: inline;    /* Par défaut pour <span> */
    /* width et height n'ont AUCUN effet sur un élément inline ! */
    /* Les marges haut/bas n'ont pas d'effet non plus */
}
```

### `display: inline-block`

Un **mélange** des deux : l'élément reste sur la même ligne que ses voisins (comme inline), mais on peut lui donner une largeur et une hauteur (comme block).

```css
.bouton {
    display: inline-block;
    width: 150px;
    height: 40px;
    padding: 10px 20px;
    background-color: blue;
    color: white;
}
```

### `display: none`

L'élément **disparaît complètement** de la page (il ne prend plus aucune place).

```css
.cache {
    display: none;   /* L'élément est invisible ET ne prend pas de place */
}
```

> **Différence avec `visibility: hidden` :** `visibility: hidden` rend l'élément invisible mais il **garde sa place** dans la mise en page.

---

## 10. Le positionnement

La propriété `position` contrôle **comment un élément est placé** dans la page.

### `position: static` (par défaut)

L'élément suit le flux normal du document. Les propriétés `top`, `right`, `bottom`, `left` n'ont **aucun effet**.

```css
div {
    position: static;  /* C'est le comportement par défaut */
}
```

### `position: relative`

L'élément garde sa place dans le flux, mais on peut le **décaler** par rapport à sa position d'origine avec `top`, `right`, `bottom`, `left`.

```css
.decale {
    position: relative;
    top: 20px;     /* Décalé de 20px vers le bas */
    left: 10px;    /* Décalé de 10px vers la droite */
}
```

> L'espace qu'il occupait à l'origine est **conservé** (les autres éléments ne bougent pas).

### `position: absolute`

L'élément est **retiré du flux** et positionné par rapport à son **plus proche ancêtre positionné** (c'est-à-dire un ancêtre qui a `position: relative`, `absolute` ou `fixed`). S'il n'y en a pas, il se positionne par rapport à la page.

```css
.parent {
    position: relative;  /* Sert de référence pour l'enfant */
}

.enfant {
    position: absolute;
    top: 0;              /* Collé en haut du parent */
    right: 0;            /* Collé à droite du parent */
}
```

> **Astuce classique :** Pour positionner un élément enfant dans un conteneur, mettez `position: relative` sur le parent et `position: absolute` sur l'enfant.

### `position: fixed`

L'élément est **retiré du flux** et positionné par rapport à la **fenêtre du navigateur**. Il ne bouge pas quand on fait défiler la page (scroll).

```css
.barre-haut {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    background: white;
    z-index: 100;   /* S'affiche au-dessus des autres éléments */
}
```

### `position: sticky`

Un mélange de `relative` et `fixed`. L'élément se comporte normalement puis **se colle** quand on atteint une certaine position de défilement.

```css
.menu {
    position: sticky;
    top: 0;            /* Se colle en haut quand on scrolle */
    background: white;
}
```

> `sticky` nécessite de définir au moins une propriété `top`, `right`, `bottom` ou `left` pour fonctionner.

### La propriété `z-index`

Quand des éléments se superposent, `z-index` détermine lequel est **au-dessus**. Un `z-index` plus élevé = au premier plan.

```css
.devant {
    position: absolute;
    z-index: 10;    /* Au-dessus des éléments avec un z-index inférieur */
}

.derriere {
    position: absolute;
    z-index: 1;     /* En dessous */
}
```

> `z-index` ne fonctionne que sur les éléments **positionnés** (autre que `static`).

---

## 11. Flexbox

Flexbox (boîte flexible) est un système de mise en page **unidimensionnel** : il gère la disposition sur **un axe** (horizontal ou vertical).

### Activer Flexbox

On applique `display: flex` sur le **conteneur** (le parent). Les enfants directs deviennent des **éléments flex**.

```html
<div class="conteneur">
    <div class="element">1</div>
    <div class="element">2</div>
    <div class="element">3</div>
</div>
```

```css
.conteneur {
    display: flex;   /* Active Flexbox sur ce conteneur */
}
```

### Propriétés du conteneur (parent)

#### `flex-direction` -- direction de l'axe principal

```css
.conteneur {
    display: flex;
    flex-direction: row;             /* De gauche à droite (par défaut) */
    flex-direction: row-reverse;     /* De droite à gauche */
    flex-direction: column;          /* De haut en bas */
    flex-direction: column-reverse;  /* De bas en haut */
}
```

#### `justify-content` -- alignement sur l'axe principal

```css
.conteneur {
    display: flex;
    justify-content: flex-start;     /* Au début (par défaut) */
    justify-content: flex-end;       /* À la fin */
    justify-content: center;         /* Au centre */
    justify-content: space-between;  /* Espace égal entre les éléments */
    justify-content: space-around;   /* Espace égal autour de chaque élément */
    justify-content: space-evenly;   /* Espace parfaitement égal partout */
}
```

#### `align-items` -- alignement sur l'axe secondaire (perpendiculaire)

```css
.conteneur {
    display: flex;
    align-items: stretch;      /* Étire les éléments (par défaut) */
    align-items: flex-start;   /* Alignés en haut */
    align-items: flex-end;     /* Alignés en bas */
    align-items: center;       /* Centrés verticalement */
    align-items: baseline;     /* Alignés sur la ligne de base du texte */
}
```

#### `flex-wrap` -- retour à la ligne

Par défaut, les éléments flex restent sur une seule ligne, même s'ils débordent.

```css
.conteneur {
    display: flex;
    flex-wrap: nowrap;    /* Pas de retour à la ligne (par défaut) */
    flex-wrap: wrap;      /* Les éléments passent à la ligne si nécessaire */
    flex-wrap: wrap-reverse;  /* Retour à la ligne inversé */
}
```

#### `gap` -- espacement entre les éléments

```css
.conteneur {
    display: flex;
    gap: 20px;           /* 20px entre chaque élément */
    gap: 10px 20px;      /* 10px vertical, 20px horizontal */
}
```

### Propriétés des éléments flex (enfants)

#### `flex-grow` -- capacité à grandir

Détermine comment un élément occupe l'espace **restant**.

```css
.element {
    flex-grow: 0;    /* Ne grandit pas (par défaut) */
}

.element-large {
    flex-grow: 1;    /* Prend tout l'espace restant disponible */
}
```

Si plusieurs éléments ont `flex-grow`, l'espace est réparti proportionnellement.

#### `flex-shrink` -- capacité à rétrécir

Détermine si un élément peut rétrécir quand l'espace manque.

```css
.element {
    flex-shrink: 1;    /* Peut rétrécir (par défaut) */
    flex-shrink: 0;    /* Ne rétrécit jamais */
}
```

#### `flex-basis` -- taille de base

La taille initiale de l'élément avant que `flex-grow` et `flex-shrink` ne s'appliquent.

```css
.element {
    flex-basis: auto;    /* Taille du contenu (par défaut) */
    flex-basis: 200px;   /* Taille de base de 200px */
    flex-basis: 30%;     /* 30% du conteneur */
}
```

#### Raccourci `flex`

```css
.element {
    flex: 1;             /* flex-grow: 1, flex-shrink: 1, flex-basis: 0 */
    flex: 0 0 200px;     /* Ne grandit pas, ne rétrécit pas, 200px de base */
}
```

#### `order` -- ordre d'affichage

Change l'ordre visuel sans modifier le HTML.

```css
.element-1 { order: 2; }  /* S'affiche en 2e */
.element-2 { order: 1; }  /* S'affiche en 1er */
.element-3 { order: 3; }  /* S'affiche en 3e */
```

#### `align-self` -- alignement individuel

Permet à un élément de se placer différemment des autres sur l'axe secondaire.

```css
.conteneur {
    display: flex;
    align-items: flex-start;  /* Tous en haut */
}

.special {
    align-self: flex-end;     /* Celui-ci sera en bas */
}
```

### Centrer parfaitement un élément avec Flexbox

```css
.conteneur {
    display: flex;
    justify-content: center;  /* Centré horizontalement */
    align-items: center;      /* Centré verticalement */
    height: 100vh;            /* Prend toute la hauteur de la fenêtre */
}
```

---

## 12. CSS Grid

CSS Grid est un système de mise en page **bidimensionnel** : il gère les lignes **et** les colonnes en même temps.

### Activer Grid

```css
.grille {
    display: grid;
}
```

### Définir les colonnes et les lignes

#### `grid-template-columns`

```css
.grille {
    display: grid;

    /* 3 colonnes de taille fixe */
    grid-template-columns: 200px 300px 200px;

    /* 3 colonnes égales avec l'unité fr (fraction de l'espace disponible) */
    grid-template-columns: 1fr 1fr 1fr;

    /* Raccourci pour 3 colonnes égales */
    grid-template-columns: repeat(3, 1fr);

    /* Mélange : sidebar fixe + contenu flexible */
    grid-template-columns: 250px 1fr;
}
```

> L'unité **`fr`** (fraction) répartit l'espace disponible. `1fr 2fr` signifie que la 2e colonne est deux fois plus large que la 1re.

#### `grid-template-rows`

```css
.grille {
    display: grid;
    grid-template-columns: 1fr 1fr;
    grid-template-rows: 100px auto 50px;  /* 3 lignes : 100px, auto, 50px */
}
```

### `gap` -- espacement

```css
.grille {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;            /* Même espacement partout */
    gap: 10px 20px;       /* 10px entre les lignes, 20px entre les colonnes */
    row-gap: 10px;        /* Seulement entre les lignes */
    column-gap: 20px;     /* Seulement entre les colonnes */
}
```

### Positionner les éléments dans la grille

#### `grid-column` et `grid-row`

On peut placer un élément en indiquant sur quelles lignes de la grille il commence et finit.

```css
.grille {
    display: grid;
    grid-template-columns: repeat(4, 1fr);  /* 4 colonnes */
    grid-template-rows: auto auto auto;      /* 3 lignes */
    gap: 10px;
}

/* L'élément occupe les colonnes 1 à 3 (sur 4) */
.large {
    grid-column: 1 / 3;   /* De la ligne 1 à la ligne 3 */
}

/* L'élément occupe 2 lignes */
.haut {
    grid-row: 1 / 3;      /* De la ligne 1 à la ligne 3 */
}

/* Raccourci : "s'étend sur 2 colonnes" */
.double {
    grid-column: span 2;
}
```

### Exemple pratique : mise en page classique

```css
.page {
    display: grid;
    grid-template-columns: 250px 1fr;
    grid-template-rows: auto 1fr auto;
    min-height: 100vh;
    gap: 0;
}

.header {
    grid-column: 1 / -1;  /* -1 = jusqu'à la fin */
}

.sidebar {
    grid-column: 1;
    grid-row: 2;
}

.contenu {
    grid-column: 2;
    grid-row: 2;
}

.footer {
    grid-column: 1 / -1;  /* Toute la largeur */
}
```

---

## 13. Le responsive design

Le **responsive design** (design adaptatif) consiste à faire en sorte que votre site s'affiche bien sur **tous les écrans** : ordinateur, tablette, téléphone.

### La balise meta viewport

Indispensable dans le `<head>` de votre HTML pour que le responsive fonctionne sur mobile.

```html
<meta name="viewport" content="width=device-width, initial-scale=1.0">
```

### Les media queries

Les **media queries** (requêtes média) permettent d'appliquer du CSS uniquement quand certaines conditions sont remplies (par exemple, la largeur de l'écran).

```css
/* Ce CSS s'applique uniquement si l'écran fait 768px de large ou moins */
@media (max-width: 768px) {
    .conteneur {
        flex-direction: column;  /* Passer en colonne sur petit écran */
    }

    .sidebar {
        display: none;           /* Cacher la sidebar sur mobile */
    }
}
```

### Breakpoints courants (points de rupture)

| Appareil | Largeur typique |
|----------|----------------|
| Téléphone (portrait) | < 576px |
| Téléphone (paysage) / Petite tablette | 576px - 768px |
| Tablette | 768px - 992px |
| Ordinateur portable | 992px - 1200px |
| Grand écran | > 1200px |

### L'approche mobile-first

Avec l'approche **mobile-first**, on écrit d'abord le CSS pour les **petits écrans**, puis on ajoute des styles pour les écrans plus grands avec `min-width`.

```css
/* === STYLES DE BASE (mobile) === */
.conteneur {
    display: flex;
    flex-direction: column;     /* En colonne sur mobile */
    padding: 10px;
}

.carte {
    width: 100%;                /* Pleine largeur sur mobile */
}

/* === TABLETTE (768px et plus) === */
@media (min-width: 768px) {
    .conteneur {
        flex-direction: row;    /* En ligne sur tablette */
        flex-wrap: wrap;
    }

    .carte {
        width: 48%;             /* 2 cartes par ligne */
    }
}

/* === ORDINATEUR (1024px et plus) === */
@media (min-width: 1024px) {
    .conteneur {
        max-width: 1200px;
        margin: 0 auto;        /* Centrer le conteneur */
    }

    .carte {
        width: 31%;             /* 3 cartes par ligne */
    }
}
```

> **Pourquoi mobile-first ?** La majorité du trafic web vient des téléphones. On commence par le plus simple (mobile) et on enrichit pour les écrans plus grands.

---

## 14. Transitions et animations

### Les transitions

Une **transition** permet de passer **en douceur** d'un état à un autre (par exemple, changer la couleur au survol).

```css
.bouton {
    background-color: blue;
    color: white;
    padding: 10px 20px;

    /* Syntaxe : propriété durée fonction-de-timing délai */
    transition: background-color 0.3s ease;

    /* Plusieurs propriétés */
    transition: background-color 0.3s ease, transform 0.2s ease;

    /* Toutes les propriétés */
    transition: all 0.3s ease;
}

.bouton:hover {
    background-color: darkblue;
    transform: scale(1.05);  /* Agrandit légèrement le bouton */
}
```

#### Les fonctions de timing

| Valeur | Description |
|--------|-------------|
| `ease` | Démarrage et fin lents, milieu rapide (par défaut) |
| `linear` | Vitesse constante |
| `ease-in` | Démarrage lent |
| `ease-out` | Fin lente |
| `ease-in-out` | Démarrage et fin lents |

### Les animations

Les **animations** sont plus puissantes que les transitions : elles peuvent se jouer automatiquement, en boucle, et définir plusieurs étapes.

#### Étape 1 : Définir l'animation avec `@keyframes`

```css
/* Définition de l'animation "apparition" */
@keyframes apparition {
    from {
        opacity: 0;              /* Au début : invisible */
        transform: translateY(20px);  /* Décalé vers le bas */
    }
    to {
        opacity: 1;              /* À la fin : visible */
        transform: translateY(0);     /* En position normale */
    }
}

/* Animation avec plusieurs étapes */
@keyframes rebond {
    0%   { transform: translateY(0); }
    50%  { transform: translateY(-30px); }
    100% { transform: translateY(0); }
}
```

#### Étape 2 : Appliquer l'animation

```css
.carte {
    animation: apparition 0.5s ease forwards;
    /*         nom       durée timing mode-de-remplissage */
}

.balle {
    animation: rebond 1s ease-in-out infinite;
    /*         nom   durée timing    répétition */
}
```

#### Propriétés d'animation

| Propriété | Description |
|-----------|-------------|
| `animation-name` | Nom de l'animation (défini dans `@keyframes`) |
| `animation-duration` | Durée (ex: `0.5s`, `200ms`) |
| `animation-timing-function` | Courbe de vitesse (`ease`, `linear`...) |
| `animation-delay` | Délai avant le début (ex: `0.2s`) |
| `animation-iteration-count` | Nombre de répétitions (`1`, `3`, `infinite`) |
| `animation-direction` | Direction (`normal`, `reverse`, `alternate`) |
| `animation-fill-mode` | État final (`forwards` = garde l'état final, `backwards`, `both`) |

---

## 15. Les variables CSS (propriétés personnalisées)

Les **variables CSS** permettent de stocker des valeurs réutilisables (couleurs, tailles, espacements...) pour éviter la répétition et faciliter les modifications.

### Déclarer des variables

On déclare les variables dans le sélecteur `:root` (qui représente l'élément `<html>`) pour qu'elles soient accessibles partout.

```css
:root {
    /* Les variables commencent toujours par deux tirets -- */
    --couleur-principale: #3498db;
    --couleur-secondaire: #2ecc71;
    --couleur-texte: #333333;
    --couleur-fond: #f4f4f4;

    --taille-texte: 16px;
    --taille-titre: 2rem;

    --espacement: 20px;
    --rayon-bordure: 8px;

    --police-principale: 'Segoe UI', Tahoma, sans-serif;
}
```

### Utiliser des variables

On utilise la fonction `var()` pour récupérer la valeur d'une variable.

```css
body {
    font-family: var(--police-principale);
    font-size: var(--taille-texte);
    color: var(--couleur-texte);
    background-color: var(--couleur-fond);
}

h1 {
    font-size: var(--taille-titre);
    color: var(--couleur-principale);
}

.bouton {
    background-color: var(--couleur-principale);
    border-radius: var(--rayon-bordure);
    padding: var(--espacement);
}
```

### Valeur de secours

On peut fournir une valeur par défaut au cas où la variable n'existe pas.

```css
p {
    color: var(--couleur-texte, black);  /* Utilise black si la variable n'existe pas */
}
```

### Modifier les variables dans un contexte

```css
/* Thème sombre : on redéfinit les variables */
.theme-sombre {
    --couleur-texte: #f4f4f4;
    --couleur-fond: #1a1a1a;
    --couleur-principale: #5dade2;
}
```

Il suffit d'ajouter la classe `theme-sombre` sur le `<body>` pour changer tout le thème du site !

> **Avantage majeur :** Si vous voulez changer la couleur principale du site, il suffit de modifier **une seule variable** au lieu de chercher et remplacer dans tout le fichier CSS.

---

## 16. Exercices progressifs

### Exercice 1 -- Styliser un texte (Débutant)

**Objectif :** Appliquer les bases de la typographie et des couleurs.

Créez un fichier HTML avec :
- Un titre `<h1>` centré, en bleu foncé, de taille 2.5rem.
- Un paragraphe `<p>` avec une police sans-serif, une taille de 1.1rem, une hauteur de ligne de 1.7 et une couleur gris foncé.
- Un lien `<a>` qui change de couleur au survol (`:hover`).

> **Notions utilisées :** font-family, font-size, color, text-align, line-height, pseudo-classe :hover.

---

### Exercice 2 -- Cartes avec le Box Model (Débutant-Intermédiaire)

**Objectif :** Maîtriser le modèle de boîte et `display: inline-block`.

Créez 3 cartes (des `<div>`) côte à côte avec :
- Une largeur de 300px.
- Un padding de 20px.
- Une bordure de 1px solid grise.
- Des coins arrondis de 10px.
- Une ombre portée avec `box-shadow: 0 2px 5px rgba(0,0,0,0.1)`.
- Une marge de 10px entre chaque carte.
- Chaque carte contient un `<h3>` et un `<p>`.

> **Notions utilisées :** width, padding, border, border-radius, box-shadow, margin, display: inline-block (ou Flexbox).

---

### Exercice 3 -- Navigation Flexbox (Intermédiaire)

**Objectif :** Créer une barre de navigation responsive avec Flexbox.

Créez une barre de navigation `<nav>` contenant :
- Un logo à gauche (texte ou image).
- Des liens de navigation à droite.
- Utilisez Flexbox pour aligner le logo à gauche et les liens à droite (`justify-content: space-between`).
- Les liens changent de couleur au survol avec une transition douce.
- Sur petit écran (< 768px), les liens passent sous le logo (utilisez une media query et `flex-direction: column`).

> **Notions utilisées :** display: flex, justify-content, align-items, gap, transition, media queries.

---

### Exercice 4 -- Grille de projets avec CSS Grid (Intermédiaire)

**Objectif :** Créer une grille d'images/projets responsive.

Créez une grille de 6 cartes de projets :
- Sur grand écran : 3 colonnes (`repeat(3, 1fr)`).
- Sur tablette : 2 colonnes.
- Sur mobile : 1 colonne.
- Un `gap` de 20px entre les cartes.
- Le premier projet occupe 2 colonnes (`grid-column: span 2`).
- Chaque carte a une image, un titre et une description.

> **Notions utilisées :** display: grid, grid-template-columns, gap, grid-column: span, media queries.

---

### Exercice 5 -- Page complète responsive (Avancé)

**Objectif :** Combiner toutes les notions pour créer une page web complète.

Créez une page avec :
- Des **variables CSS** pour les couleurs et les espacements.
- Un **header** fixe (`position: fixed`) avec une navigation Flexbox.
- Une section **hero** (bandeau d'accueil) centrée avec Flexbox, hauteur 100vh.
- Une section **services** avec 3 cartes en Grid.
- Un **footer** avec 3 colonnes en Flexbox.
- Le tout **responsive** avec l'approche mobile-first.
- Des **transitions** sur les boutons et les cartes au survol.
- Une **animation** d'apparition pour le titre du hero.

> **Notions utilisées :** Variables CSS, Flexbox, Grid, position: fixed, media queries, transitions, @keyframes, unités rem/vh/vw.

---

## Récapitulatif des bonnes pratiques

1. **Utilisez un fichier CSS externe.**
2. **Appliquez `box-sizing: border-box`** sur tous les éléments.
3. **Préférez les classes** aux id pour le style.
4. **Utilisez des variables CSS** pour les valeurs réutilisables.
5. **Adoptez l'approche mobile-first** pour le responsive.
6. **Gardez une spécificité basse** (évitez les sélecteurs trop complexes et `!important`).
7. **Utilisez `rem`** pour les tailles de police et les espacements.
8. **Commentez votre code** pour le rendre compréhensible.
9. **Utilisez Flexbox** pour les alignements en une dimension.
10. **Utilisez Grid** pour les mises en page en deux dimensions.
