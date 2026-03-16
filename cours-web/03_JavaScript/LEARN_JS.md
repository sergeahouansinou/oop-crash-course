# Cours Complet JavaScript pour Debutants

---

## Table des matieres

1. [Introduction a JavaScript](#1-introduction-a-javascript)
2. [Les variables](#2-les-variables)
3. [Les types de donnees](#3-les-types-de-donnees)
4. [Les operateurs](#4-les-operateurs)
5. [Les chaines de caracteres (Strings)](#5-les-chaines-de-caracteres-strings)
6. [Les conditions](#6-les-conditions)
7. [Les boucles](#7-les-boucles)
8. [Les fonctions](#8-les-fonctions)
9. [Les tableaux (Arrays)](#9-les-tableaux-arrays)
10. [Les objets](#10-les-objets)
11. [La manipulation du DOM](#11-la-manipulation-du-dom)
12. [Les evenements](#12-les-evenements)
13. [Template literals et interpolation](#13-template-literals-et-interpolation)
14. [Spread operator et rest parameters](#14-spread-operator-et-rest-parameters)
15. [LocalStorage](#15-localstorage)
16. [Fetch API](#16-fetch-api)
17. [Gestion des erreurs : try/catch](#17-gestion-des-erreurs--trycatch)
18. [Exercices progressifs](#18-exercices-progressifs)

---

## 1. Introduction a JavaScript

### Qu'est-ce que JavaScript ?

JavaScript (souvent abrege **JS**) est un langage de programmation qui permet de rendre les pages web **interactives et dynamiques**. C'est l'un des trois piliers du developpement web :

| Technologie | Role |
|---|---|
| **HTML** | La structure de la page (le squelette) |
| **CSS** | L'apparence de la page (le style, les couleurs) |
| **JavaScript** | Le comportement de la page (les interactions, la logique) |

### A quoi sert JavaScript ?

- **Reagir aux actions de l'utilisateur** : clic sur un bouton, saisie dans un formulaire, survol d'un element...
- **Modifier le contenu d'une page** sans la recharger (ajouter, supprimer, modifier des elements)
- **Valider des formulaires** avant envoi au serveur
- **Communiquer avec un serveur** pour envoyer ou recevoir des donnees (AJAX, Fetch API)
- **Stocker des donnees localement** dans le navigateur (LocalStorage)
- **Creer des animations** et des effets visuels

### Ou ecrire du JavaScript ?

Il y a deux facons principales d'ajouter du JavaScript a une page HTML :

**1. Directement dans le fichier HTML (deconseille pour les gros projets) :**

```html
<script>
  // Votre code JavaScript ici
  alert("Bonjour le monde !");
</script>
```

**2. Dans un fichier externe (recommande) :**

```html
<!-- A placer juste avant la fermeture de </body> -->
<script src="script.js"></script>
```

> **Bonne pratique** : Placez toujours votre balise `<script>` juste avant `</body>` ou utilisez l'attribut `defer` dans le `<head>`. Cela garantit que le HTML est entierement charge avant l'execution du JavaScript.

### La console du navigateur

La console est votre meilleur outil pour tester du JavaScript. Pour l'ouvrir :
- **Chrome / Edge** : `F12` ou `Ctrl + Shift + J` (Windows) / `Cmd + Option + J` (Mac)
- **Firefox** : `F12` ou `Ctrl + Shift + K`

```javascript
// Affiche un message dans la console du navigateur
console.log("Bonjour depuis la console !");
```

`console.log()` est la fonction la plus utilisee pour afficher des informations dans la console. Elle est indispensable pour **deboguer** (trouver les erreurs dans votre code).

---

## 2. Les variables

Une **variable** est un conteneur qui stocke une valeur en memoire. C'est comme une boite avec une etiquette : l'etiquette est le nom de la variable, et le contenu de la boite est sa valeur.

### Les trois mots-cles pour declarer une variable

#### `var` (ancienne methode - a eviter)

```javascript
var prenom = "Alice";
// var est l'ancienne facon de declarer une variable
// Elle a des comportements surprenants (portee globale ou de fonction)
// On ne l'utilise plus dans le code moderne
```

#### `let` (recommande pour les valeurs qui changent)

```javascript
let age = 25;
// let permet de declarer une variable dont la valeur peut changer
age = 26; // On peut reassigner une nouvelle valeur
// let a une portee de bloc {} ce qui est plus previsible
```

#### `const` (recommande pour les valeurs fixes)

```javascript
const PI = 3.14159;
// const declare une constante : une fois assignee, sa valeur ne peut plus changer
// PI = 3.14; // ERREUR ! On ne peut pas reassigner une constante
```

> **Regle d'or** : Utilisez `const` par defaut. Utilisez `let` uniquement quand vous savez que la valeur devra changer. N'utilisez jamais `var`.

### Regles de nommage des variables

```javascript
// VALIDE :
let monPrenom = "Alice";     // camelCase (recommande)
let mon_prenom = "Alice";    // snake_case (acceptable)
let _prive = true;           // commence par un underscore
let $element = "div";        // commence par $
let prenom2 = "Bob";         // contient un chiffre (mais pas au debut)

// INVALIDE :
// let 2prenom = "Alice";    // ne peut PAS commencer par un chiffre
// let mon-prenom = "Alice"; // le tiret n'est pas autorise
// let let = "Alice";        // ne peut PAS utiliser un mot reserve du langage
```

> **Convention** : En JavaScript, on utilise le **camelCase** : le premier mot en minuscule, puis chaque mot suivant commence par une majuscule. Exemple : `monNomComplet`, `couleurPreferee`, `nombreDeClics`.

---

## 3. Les types de donnees

JavaScript possede plusieurs types de donnees. Comprendre ces types est essentiel.

### Les types primitifs

#### String (chaine de caracteres)

```javascript
const prenom = "Alice";       // entre guillemets doubles
const nom = 'Dupont';         // entre guillemets simples (equivalent)
const phrase = `Bonjour !`;   // entre backticks (accent grave) - template literal

// Les trois syntaxes sont valides pour creer une chaine de caracteres
```

#### Number (nombre)

```javascript
const entier = 42;          // un nombre entier
const decimal = 3.14;       // un nombre decimal (on utilise le point, pas la virgule)
const negatif = -10;        // un nombre negatif

// En JavaScript, il n'y a pas de difference entre entier et decimal
// Tout est de type "number"
```

#### Boolean (booleen)

```javascript
const estMajeur = true;     // vrai
const estConnecte = false;  // faux

// Un booleen ne peut avoir que deux valeurs : true (vrai) ou false (faux)
// Tres utile pour les conditions (if/else)
```

#### Null

```javascript
const resultat = null;
// null signifie "aucune valeur" de maniere intentionnelle
// C'est le programmeur qui assigne null volontairement
```

#### Undefined

```javascript
let nom;
console.log(nom); // undefined
// undefined signifie que la variable a ete declaree mais qu'aucune valeur ne lui a ete assignee
// C'est JavaScript qui met undefined automatiquement
```

### Les types complexes

#### Array (tableau)

```javascript
const fruits = ["pomme", "banane", "orange"];
// Un tableau est une liste ordonnee de valeurs
// Chaque element a un index (position) qui commence a 0
// fruits[0] = "pomme", fruits[1] = "banane", fruits[2] = "orange"
```

#### Object (objet)

```javascript
const personne = {
    prenom: "Alice",    // propriete prenom avec la valeur "Alice"
    age: 25,            // propriete age avec la valeur 25
    estEtudiant: true   // propriete estEtudiant avec la valeur true
};
// Un objet regroupe des donnees sous forme de paires cle: valeur
```

### Verifier le type d'une variable avec `typeof`

```javascript
console.log(typeof "Bonjour");   // "string"
console.log(typeof 42);          // "number"
console.log(typeof true);        // "boolean"
console.log(typeof undefined);   // "undefined"
console.log(typeof null);        // "object" (c'est un bug historique de JS !)
console.log(typeof [1, 2, 3]);   // "object" (les tableaux sont des objets en JS)
console.log(typeof {a: 1});      // "object"
```

---

## 4. Les operateurs

### Operateurs arithmetiques

```javascript
let a = 10;
let b = 3;

console.log(a + b);   // 13  - Addition
console.log(a - b);   // 7   - Soustraction
console.log(a * b);   // 30  - Multiplication
console.log(a / b);   // 3.333... - Division
console.log(a % b);   // 1   - Modulo (reste de la division : 10 / 3 = 3 reste 1)
console.log(a ** b);  // 1000 - Exponentiation (10 puissance 3)

// Operateurs d'affectation raccourcis
let x = 10;
x += 5;   // equivalent a : x = x + 5  -> x vaut maintenant 15
x -= 3;   // equivalent a : x = x - 3  -> x vaut maintenant 12
x *= 2;   // equivalent a : x = x * 2  -> x vaut maintenant 24
x /= 4;   // equivalent a : x = x / 4  -> x vaut maintenant 6

// Incrementation et decrementation
let compteur = 0;
compteur++;  // equivalent a compteur = compteur + 1 -> compteur vaut 1
compteur--;  // equivalent a compteur = compteur - 1 -> compteur vaut 0
```

### Operateurs de comparaison

```javascript
let x = 5;

// Comparaison simple (== et !=) : compare les VALEURS uniquement
console.log(x == 5);     // true  - 5 est egal a 5
console.log(x == "5");   // true  - JS convertit "5" en 5 avant de comparer !
console.log(x != 3);     // true  - 5 est different de 3

// Comparaison stricte (=== et !==) : compare les VALEURS ET les TYPES
console.log(x === 5);    // true  - meme valeur ET meme type (number)
console.log(x === "5");  // false - meme valeur MAIS type different (number vs string)
console.log(x !== "5");  // true  - pas strictement egal

// Autres comparaisons
console.log(x > 3);      // true  - 5 est superieur a 3
console.log(x < 3);      // false - 5 n'est pas inferieur a 3
console.log(x >= 5);     // true  - 5 est superieur ou egal a 5
console.log(x <= 4);     // false - 5 n'est pas inferieur ou egal a 4
```

> **Regle importante** : Utilisez TOUJOURS `===` et `!==` (comparaison stricte). La comparaison simple `==` peut donner des resultats inattendus a cause de la conversion automatique des types.

### Operateurs logiques

```javascript
let age = 20;
let aPermis = true;

// ET logique (&&) : les DEUX conditions doivent etre vraies
console.log(age >= 18 && aPermis);  // true - car 20 >= 18 ET aPermis est true

// OU logique (||) : au moins UNE condition doit etre vraie
console.log(age < 18 || aPermis);   // true - car aPermis est true (meme si age < 18 est faux)

// NON logique (!) : inverse la valeur
console.log(!aPermis);              // false - l'inverse de true est false
console.log(!false);                // true  - l'inverse de false est true
```

### Operateur ternaire

L'operateur ternaire est un raccourci pour un `if/else` simple :

```javascript
// Syntaxe : condition ? valeurSiVrai : valeurSiFaux

let age = 20;
let statut = age >= 18 ? "majeur" : "mineur";
// Si age >= 18, statut = "majeur", sinon statut = "mineur"
console.log(statut); // "majeur"

// C'est equivalent a :
// let statut;
// if (age >= 18) {
//     statut = "majeur";
// } else {
//     statut = "mineur";
// }
```

---

## 5. Les chaines de caracteres (Strings)

### Concatenation

La concatenation consiste a assembler des chaines de caracteres :

```javascript
// Methode classique avec l'operateur +
let prenom = "Alice";
let nom = "Dupont";
let nomComplet = prenom + " " + nom;
// On colle prenom, un espace, et nom ensemble
console.log(nomComplet); // "Alice Dupont"

// Concatenation avec un nombre
let age = 25;
let message = "J'ai " + age + " ans";
// JS convertit automatiquement le nombre en chaine
console.log(message); // "J'ai 25 ans"
```

### Template literals (backticks)

Les template literals utilisent les backticks (accent grave : `` ` ``) et permettent d'inserer des expressions directement dans la chaine :

```javascript
let prenom = "Alice";
let age = 25;

// Avec les template literals, on utilise ${...} pour inserer des variables
let presentation = `Bonjour, je suis ${prenom} et j'ai ${age} ans.`;
console.log(presentation); // "Bonjour, je suis Alice et j'ai 25 ans."

// On peut aussi inserer des expressions (calculs, appels de fonctions, etc.)
let message = `Dans 5 ans, j'aurai ${age + 5} ans.`;
console.log(message); // "Dans 5 ans, j'aurai 30 ans."

// Les template literals permettent aussi les chaines multi-lignes
let html = `
  <div>
    <h1>${prenom}</h1>
    <p>Age : ${age}</p>
  </div>
`;
// Plus besoin de \n ou de concatener plusieurs lignes avec +
```

### Methodes courantes des chaines

```javascript
let texte = "  Bonjour le Monde  ";

// .length - retourne le nombre de caracteres (ce n'est pas une methode mais une propriete)
console.log(texte.length); // 20 (espaces inclus)

// .toUpperCase() - convertit tout en majuscules
console.log(texte.toUpperCase()); // "  BONJOUR LE MONDE  "

// .toLowerCase() - convertit tout en minuscules
console.log(texte.toLowerCase()); // "  bonjour le monde  "

// .trim() - supprime les espaces au debut et a la fin
console.log(texte.trim()); // "Bonjour le Monde"

// .includes(recherche) - verifie si la chaine contient le texte recherche
console.log(texte.includes("Bonjour")); // true
console.log(texte.includes("salut"));   // false

// .indexOf(recherche) - retourne la position de la premiere occurrence (-1 si non trouve)
let phrase = "Bonjour le monde";
console.log(phrase.indexOf("le"));     // 8  (commence a la position 8)
console.log(phrase.indexOf("salut"));  // -1 (non trouve)

// .slice(debut, fin) - extrait une portion de la chaine
// L'index de debut est inclus, l'index de fin est exclus
console.log(phrase.slice(0, 7));   // "Bonjour" (de la position 0 a 6)
console.log(phrase.slice(8));      // "le monde" (de la position 8 jusqu'a la fin)
console.log(phrase.slice(-5));     // "monde" (les 5 derniers caracteres)

// .split(separateur) - decoupe la chaine en un tableau
let liste = "pomme,banane,orange";
let fruits = liste.split(",");
// Decoupe la chaine a chaque virgule
console.log(fruits); // ["pomme", "banane", "orange"]

let mots = phrase.split(" ");
// Decoupe la chaine a chaque espace
console.log(mots); // ["Bonjour", "le", "monde"]
```

---

## 6. Les conditions

Les conditions permettent d'executer du code uniquement si certaines conditions sont remplies.

### if / else

```javascript
let age = 20;

// Si la condition entre parentheses est vraie, le bloc {} s'execute
if (age >= 18) {
    console.log("Vous etes majeur.");
    // Ce code s'execute car 20 >= 18 est true
}

// Avec else : si la condition est fausse, c'est le bloc else qui s'execute
if (age >= 18) {
    console.log("Vous etes majeur.");
} else {
    console.log("Vous etes mineur.");
}
```

### else if

```javascript
let note = 15;

// On peut enchainer plusieurs conditions avec else if
if (note >= 16) {
    console.log("Tres bien !");
    // S'execute si note >= 16
} else if (note >= 14) {
    console.log("Bien !");
    // S'execute si note >= 14 (et < 16)
} else if (note >= 12) {
    console.log("Assez bien.");
    // S'execute si note >= 12 (et < 14)
} else if (note >= 10) {
    console.log("Passable.");
    // S'execute si note >= 10 (et < 12)
} else {
    console.log("Insuffisant.");
    // S'execute si aucune condition precedente n'est vraie (note < 10)
}
// Resultat : "Bien !" car 15 >= 14
```

### switch

Le `switch` est utile quand on compare une variable a plusieurs valeurs precises :

```javascript
let jour = "lundi";

switch (jour) {
    case "lundi":
        console.log("Debut de semaine !");
        break; // break est OBLIGATOIRE pour sortir du switch
    case "mardi":
    case "mercredi":
    case "jeudi":
        console.log("Milieu de semaine.");
        break;
    case "vendredi":
        console.log("Bientot le weekend !");
        break;
    case "samedi":
    case "dimanche":
        console.log("C'est le weekend !");
        break;
    default:
        // default s'execute si aucun case ne correspond
        console.log("Jour invalide.");
}
// Resultat : "Debut de semaine !"
```

> **Attention** : Si vous oubliez le `break`, le code continue dans le `case` suivant ! C'est ce qu'on appelle le "fall-through".

### Les valeurs "falsy" et "truthy"

En JavaScript, certaines valeurs sont considerees comme "fausses" dans une condition :

```javascript
// Valeurs FALSY (considerees comme false) :
// false, 0, "" (chaine vide), null, undefined, NaN

if ("") {
    console.log("Ce code ne s'execute pas car une chaine vide est falsy");
}

if ("Bonjour") {
    console.log("Ce code s'execute car une chaine non-vide est truthy");
}

// Utilisation pratique :
let nom = "";
if (!nom) {
    console.log("Le nom est vide !"); // S'affiche car "" est falsy
}
```

---

## 7. Les boucles

Les boucles permettent de repeter un bloc de code plusieurs fois.

### La boucle `for`

C'est la boucle la plus utilisee quand on connait le nombre de repetitions :

```javascript
// for (initialisation; condition; incrementation)
for (let i = 0; i < 5; i++) {
    console.log(`Tour numero ${i}`);
}
// let i = 0      -> on commence a 0
// i < 5          -> on continue tant que i est inferieur a 5
// i++            -> on ajoute 1 a i apres chaque tour
// Affiche : Tour numero 0, Tour numero 1, ..., Tour numero 4

// Parcourir un tableau avec for
const fruits = ["pomme", "banane", "orange"];
for (let i = 0; i < fruits.length; i++) {
    console.log(`Fruit ${i + 1} : ${fruits[i]}`);
}
// fruits.length = 3, donc i va de 0 a 2
// fruits[0] = "pomme", fruits[1] = "banane", fruits[2] = "orange"
```

### La boucle `while`

Elle s'execute tant que la condition est vraie :

```javascript
let compteur = 0;

while (compteur < 3) {
    console.log(`Compteur : ${compteur}`);
    compteur++; // IMPORTANT : sans cette ligne, la boucle serait infinie !
}
// Affiche : Compteur : 0, Compteur : 1, Compteur : 2
// La boucle s'arrete quand compteur atteint 3 (3 < 3 est false)
```

### La boucle `do...while`

Similaire a `while`, mais le bloc s'execute **au moins une fois** :

```javascript
let nombre = 10;

do {
    console.log(`Nombre : ${nombre}`);
    nombre++;
} while (nombre < 3);
// Affiche : "Nombre : 10"
// Le bloc s'execute une fois AVANT de verifier la condition
// Comme 10 < 3 est false, la boucle s'arrete apres le premier tour
```

### La boucle `for...of`

Parfaite pour parcourir les elements d'un tableau ou d'une chaine :

```javascript
const couleurs = ["rouge", "vert", "bleu"];

// for...of donne directement la VALEUR de chaque element
for (const couleur of couleurs) {
    console.log(couleur);
}
// Affiche : rouge, vert, bleu
// Plus simple que for classique : pas besoin d'index

// Fonctionne aussi avec les chaines de caracteres
for (const lettre of "Bonjour") {
    console.log(lettre);
}
// Affiche : B, o, n, j, o, u, r
```

### La boucle `for...in`

Utilisee pour parcourir les **proprietes** d'un objet :

```javascript
const personne = {
    prenom: "Alice",
    age: 25,
    ville: "Paris"
};

// for...in donne la CLE (le nom de la propriete) a chaque tour
for (const cle in personne) {
    console.log(`${cle} : ${personne[cle]}`);
}
// Affiche :
// prenom : Alice
// age : 25
// ville : Paris
```

> **Attention** : N'utilisez pas `for...in` avec des tableaux, car l'ordre n'est pas garanti. Utilisez `for...of` ou `for` classique a la place.

### `break` et `continue`

```javascript
// break : arrete completement la boucle
for (let i = 0; i < 10; i++) {
    if (i === 5) {
        break; // On sort de la boucle quand i vaut 5
    }
    console.log(i);
}
// Affiche : 0, 1, 2, 3, 4

// continue : passe directement au tour suivant
for (let i = 0; i < 5; i++) {
    if (i === 2) {
        continue; // On saute le tour quand i vaut 2
    }
    console.log(i);
}
// Affiche : 0, 1, 3, 4 (le 2 est saute)
```

---

## 8. Les fonctions

Une fonction est un bloc de code reutilisable qui effectue une tache specifique.

### Declaration de fonction

```javascript
// On declare une fonction avec le mot-cle function
function saluer(prenom) {
    // prenom est un PARAMETRE : une variable locale a la fonction
    console.log(`Bonjour ${prenom} !`);
}

// On APPELLE la fonction en utilisant son nom suivi de parentheses
saluer("Alice");  // Affiche : "Bonjour Alice !"
saluer("Bob");    // Affiche : "Bonjour Bob !"
// "Alice" et "Bob" sont des ARGUMENTS : les valeurs passees a la fonction
```

### Fonction avec retour (return)

```javascript
function additionner(a, b) {
    // a et b sont les parametres
    return a + b;
    // return renvoie une valeur et arrete l'execution de la fonction
    // Tout code apres return ne sera jamais execute
}

let resultat = additionner(3, 5);
// La fonction renvoie 8, qui est stocke dans la variable resultat
console.log(resultat); // 8

// On peut utiliser le retour directement
console.log(additionner(10, 20)); // 30
```

### Parametres par defaut

```javascript
function saluer(prenom = "inconnu") {
    // Si aucun argument n'est passe, prenom vaudra "inconnu"
    console.log(`Bonjour ${prenom} !`);
}

saluer("Alice");  // "Bonjour Alice !"
saluer();         // "Bonjour inconnu !" (la valeur par defaut est utilisee)
```

### Expression de fonction

```javascript
// On peut stocker une fonction dans une variable
const multiplier = function(a, b) {
    return a * b;
};
// Attention au point-virgule a la fin (c'est une affectation de variable)

console.log(multiplier(4, 5)); // 20
```

### Fonctions flechees (Arrow Functions)

Syntaxe plus courte introduite avec ES6 :

```javascript
// Syntaxe complete
const additionner = (a, b) => {
    return a + b;
};

// Syntaxe raccourcie (une seule expression : le return est implicite)
const additionner = (a, b) => a + b;
// Quand le corps de la fonction contient UNE seule expression,
// on peut supprimer les {} et le mot return

// Un seul parametre : les parentheses sont optionnelles
const doubler = nombre => nombre * 2;

// Pas de parametre : les parentheses vides sont obligatoires
const direBonjour = () => console.log("Bonjour !");

// Exemples d'utilisation
console.log(additionner(3, 5)); // 8
console.log(doubler(4));        // 8
direBonjour();                  // "Bonjour !"
```

> **Quand utiliser les fonctions flechees ?** Elles sont ideales pour les fonctions courtes, surtout comme callbacks (fonctions passees en argument a d'autres fonctions, comme avec `map`, `filter`, etc.).

---

## 9. Les tableaux (Arrays)

Un tableau est une structure de donnees qui stocke une liste ordonnee d'elements.

### Creation et acces

```javascript
// Creer un tableau
const fruits = ["pomme", "banane", "orange", "kiwi"];
const nombres = [1, 2, 3, 4, 5];
const mixte = ["texte", 42, true, null]; // Un tableau peut contenir des types differents

// Acceder aux elements (les index commencent a 0)
console.log(fruits[0]);  // "pomme" (premier element)
console.log(fruits[1]);  // "banane" (deuxieme element)
console.log(fruits[3]);  // "kiwi" (quatrieme element)
console.log(fruits[fruits.length - 1]); // "kiwi" (dernier element)

// Modifier un element
fruits[1] = "fraise";
console.log(fruits); // ["pomme", "fraise", "orange", "kiwi"]

// Connaitre la taille du tableau
console.log(fruits.length); // 4
```

### Methodes pour ajouter et supprimer des elements

```javascript
let fruits = ["pomme", "banane", "orange"];

// .push() - ajoute un ou plusieurs elements A LA FIN
fruits.push("kiwi");
console.log(fruits); // ["pomme", "banane", "orange", "kiwi"]

// .pop() - supprime le DERNIER element et le retourne
let dernier = fruits.pop();
console.log(dernier); // "kiwi"
console.log(fruits);  // ["pomme", "banane", "orange"]

// .unshift() - ajoute un ou plusieurs elements AU DEBUT
fruits.unshift("fraise");
console.log(fruits); // ["fraise", "pomme", "banane", "orange"]

// .shift() - supprime le PREMIER element et le retourne
let premier = fruits.shift();
console.log(premier); // "fraise"
console.log(fruits);  // ["pomme", "banane", "orange"]

// .splice(index, combienSupprimer, elementsAajouter...)
// Methode tres polyvalente pour ajouter, supprimer ou remplacer des elements
fruits.splice(1, 1);
// A partir de l'index 1, supprime 1 element
console.log(fruits); // ["pomme", "orange"]

fruits.splice(1, 0, "fraise", "kiwi");
// A partir de l'index 1, supprime 0 element, et insere "fraise" et "kiwi"
console.log(fruits); // ["pomme", "fraise", "kiwi", "orange"]

// .slice(debut, fin) - retourne une copie d'une portion du tableau (sans modifier l'original)
let selection = fruits.slice(1, 3);
// Copie de l'index 1 (inclus) a l'index 3 (exclus)
console.log(selection); // ["fraise", "kiwi"]
console.log(fruits);    // ["pomme", "fraise", "kiwi", "orange"] (inchange)
```

### Methodes de recherche

```javascript
const fruits = ["pomme", "banane", "orange", "banane"];

// .indexOf(element) - retourne l'index de la premiere occurrence (-1 si non trouve)
console.log(fruits.indexOf("banane"));  // 1
console.log(fruits.indexOf("kiwi"));    // -1

// .includes(element) - verifie si l'element existe dans le tableau (true/false)
console.log(fruits.includes("orange")); // true
console.log(fruits.includes("kiwi"));   // false

// .find(callback) - retourne le PREMIER element qui satisfait la condition
const nombres = [3, 7, 12, 5, 18, 2];
let premierGrand = nombres.find(n => n > 10);
// Parcourt le tableau et retourne le premier nombre superieur a 10
console.log(premierGrand); // 12
```

### Methodes de transformation

```javascript
const nombres = [1, 2, 3, 4, 5];

// .filter(callback) - retourne un NOUVEAU tableau avec les elements qui passent le test
let pairs = nombres.filter(n => n % 2 === 0);
// Garde uniquement les nombres dont le reste de la division par 2 est 0
console.log(pairs); // [2, 4]

// .map(callback) - retourne un NOUVEAU tableau en transformant chaque element
let doubles = nombres.map(n => n * 2);
// Multiplie chaque element par 2
console.log(doubles); // [2, 4, 6, 8, 10]

// .forEach(callback) - execute une fonction pour chaque element (ne retourne rien)
nombres.forEach((nombre, index) => {
    console.log(`Index ${index} : ${nombre}`);
});
// Affiche : Index 0 : 1, Index 1 : 2, ..., Index 4 : 5
// Contrairement a map, forEach ne cree pas de nouveau tableau

// .reduce(callback, valeurInitiale) - reduit le tableau a une seule valeur
let somme = nombres.reduce((accumulateur, valeurCourante) => {
    return accumulateur + valeurCourante;
}, 0);
// 0 est la valeur initiale de l'accumulateur
// Tour 1 : accumulateur = 0, valeurCourante = 1, retourne 1
// Tour 2 : accumulateur = 1, valeurCourante = 2, retourne 3
// Tour 3 : accumulateur = 3, valeurCourante = 3, retourne 6
// Tour 4 : accumulateur = 6, valeurCourante = 4, retourne 10
// Tour 5 : accumulateur = 10, valeurCourante = 5, retourne 15
console.log(somme); // 15

// .sort() - trie le tableau (ATTENTION : trie par ordre alphabetique par defaut !)
let noms = ["Charlie", "Alice", "Bob"];
noms.sort();
console.log(noms); // ["Alice", "Bob", "Charlie"]

// Pour trier des nombres correctement, il faut une fonction de comparaison :
let nums = [10, 5, 40, 25, 1000];
nums.sort((a, b) => a - b); // Tri croissant
console.log(nums); // [5, 10, 25, 40, 1000]
nums.sort((a, b) => b - a); // Tri decroissant
console.log(nums); // [1000, 40, 25, 10, 5]

// .reverse() - inverse l'ordre des elements
let lettres = ["a", "b", "c"];
lettres.reverse();
console.log(lettres); // ["c", "b", "a"]
```

### Enchainement de methodes (chaining)

```javascript
const nombres = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];

// On peut enchainer les methodes car filter et map retournent de nouveaux tableaux
let resultat = nombres
    .filter(n => n % 2 === 0)   // Garde les pairs : [2, 4, 6, 8, 10]
    .map(n => n * 3)             // Multiplie par 3 : [6, 12, 18, 24, 30]
    .reduce((acc, n) => acc + n, 0); // Additionne tout : 90

console.log(resultat); // 90
```

---

## 10. Les objets

Un objet est une collection de proprietes, chacune etant une paire cle-valeur.

### Creation et acces

```javascript
// Creer un objet avec la notation literale {}
const personne = {
    prenom: "Alice",
    nom: "Dupont",
    age: 25,
    ville: "Paris",
    estEtudiant: true
};

// Acceder aux proprietes - notation par point (recommandee)
console.log(personne.prenom);  // "Alice"
console.log(personne.age);     // 25

// Acceder aux proprietes - notation par crochet
console.log(personne["ville"]); // "Paris"
// La notation par crochet est utile quand le nom de la propriete est dans une variable :
let cle = "nom";
console.log(personne[cle]); // "Dupont"

// Modifier une propriete
personne.age = 26;
console.log(personne.age); // 26

// Ajouter une nouvelle propriete
personne.email = "alice@email.com";
console.log(personne.email); // "alice@email.com"

// Supprimer une propriete
delete personne.estEtudiant;
```

### Methodes d'un objet

```javascript
const personne = {
    prenom: "Alice",
    nom: "Dupont",
    age: 25,

    // Une methode est une fonction definie dans un objet
    sePresenter() {
        // "this" fait reference a l'objet lui-meme
        return `Je suis ${this.prenom} ${this.nom}, j'ai ${this.age} ans.`;
    },

    anniversaire() {
        this.age++; // Modifie la propriete age de l'objet
        return `Joyeux anniversaire ! J'ai maintenant ${this.age} ans.`;
    }
};

console.log(personne.sePresenter());
// "Je suis Alice Dupont, j'ai 25 ans."

console.log(personne.anniversaire());
// "Joyeux anniversaire ! J'ai maintenant 26 ans."
```

> **Le mot-cle `this`** : A l'interieur d'une methode d'objet, `this` fait reference a l'objet qui contient la methode. C'est ainsi qu'une methode peut acceder aux autres proprietes de son objet.

### Destructuration d'objet

La destructuration permet d'extraire des proprietes d'un objet dans des variables :

```javascript
const personne = {
    prenom: "Alice",
    nom: "Dupont",
    age: 25,
    ville: "Paris"
};

// Sans destructuration :
// let prenom = personne.prenom;
// let age = personne.age;

// Avec destructuration :
const { prenom, age, ville } = personne;
// Cree trois variables a partir des proprietes correspondantes de l'objet
console.log(prenom); // "Alice"
console.log(age);    // 25
console.log(ville);  // "Paris"

// Renommer une variable lors de la destructuration
const { prenom: p, nom: n } = personne;
console.log(p); // "Alice"
console.log(n); // "Dupont"

// Valeur par defaut si la propriete n'existe pas
const { pays = "France" } = personne;
console.log(pays); // "France" (personne n'a pas de propriete "pays")
```

### Methodes utiles pour les objets

```javascript
const personne = { prenom: "Alice", age: 25, ville: "Paris" };

// Object.keys() - retourne un tableau des cles
console.log(Object.keys(personne));   // ["prenom", "age", "ville"]

// Object.values() - retourne un tableau des valeurs
console.log(Object.values(personne)); // ["Alice", 25, "Paris"]

// Object.entries() - retourne un tableau de paires [cle, valeur]
console.log(Object.entries(personne));
// [["prenom", "Alice"], ["age", 25], ["ville", "Paris"]]

// Parcourir un objet avec Object.entries et for...of
for (const [cle, valeur] of Object.entries(personne)) {
    console.log(`${cle} : ${valeur}`);
}
```

---

## 11. La manipulation du DOM

Le **DOM** (Document Object Model) est la representation en memoire de la page HTML sous forme d'arbre d'objets. JavaScript peut manipuler cet arbre pour modifier dynamiquement la page.

### Selectionner des elements

```javascript
// getElementById - selectionne UN element par son attribut id
let titre = document.getElementById("titre-principal");
// Selectionne l'element qui a id="titre-principal"

// querySelector - selectionne le PREMIER element qui correspond au selecteur CSS
let premierParagraphe = document.querySelector("p");
// Selectionne le premier <p> de la page

let elementClasse = document.querySelector(".ma-classe");
// Selectionne le premier element avec class="ma-classe"

let elementId = document.querySelector("#mon-id");
// Selectionne l'element avec id="mon-id" (equivalent a getElementById)

// querySelectorAll - selectionne TOUS les elements qui correspondent au selecteur
let tousLesParagraphes = document.querySelectorAll("p");
// Retourne une NodeList (similaire a un tableau) de tous les <p>

// Parcourir les resultats de querySelectorAll
tousLesParagraphes.forEach(paragraphe => {
    console.log(paragraphe.textContent);
});
```

### Modifier le contenu

```javascript
let titre = document.querySelector("h1");

// .textContent - modifie le texte brut (recommande pour du texte simple)
titre.textContent = "Nouveau titre";
// Remplace tout le contenu texte de l'element

// .innerHTML - modifie le contenu HTML (permet d'inserer des balises)
titre.innerHTML = "Titre <em>important</em>";
// ATTENTION : n'utilisez innerHTML qu'avec des donnees de confiance
// car il peut executer du code malveillant (risque XSS)
```

### Modifier les styles et les classes

```javascript
let element = document.querySelector(".boite");

// .style - modifie le style en ligne (inline style)
element.style.color = "red";
element.style.backgroundColor = "yellow";
// Notez : en JS, on utilise le camelCase (backgroundColor au lieu de background-color)
element.style.fontSize = "20px";
element.style.border = "2px solid blue";

// .classList - manipuler les classes CSS (RECOMMANDE plutot que .style)
element.classList.add("active");      // Ajoute la classe "active"
element.classList.remove("hidden");   // Supprime la classe "hidden"
element.classList.toggle("visible");  // Ajoute si absente, supprime si presente
element.classList.contains("active"); // Retourne true si l'element a la classe "active"
```

### Modifier les attributs

```javascript
let lien = document.querySelector("a");

// .setAttribute(nom, valeur) - modifie ou cree un attribut
lien.setAttribute("href", "https://www.example.com");
lien.setAttribute("target", "_blank");

// .getAttribute(nom) - lit la valeur d'un attribut
let url = lien.getAttribute("href");
console.log(url); // "https://www.example.com"

// Raccourcis pour les attributs courants
let image = document.querySelector("img");
image.src = "photo.jpg";   // Equivalent a setAttribute("src", "photo.jpg")
image.alt = "Ma photo";    // Equivalent a setAttribute("alt", "Ma photo")
```

### Creer et supprimer des elements

```javascript
// Creer un nouvel element
let nouveauParagraphe = document.createElement("p");
// Cree un element <p> en memoire (pas encore visible sur la page)

nouveauParagraphe.textContent = "Je suis un nouveau paragraphe.";
nouveauParagraphe.classList.add("nouveau");
// On configure l'element avant de l'ajouter a la page

// Ajouter l'element a la page
let conteneur = document.querySelector("#conteneur");
conteneur.appendChild(nouveauParagraphe);
// appendChild ajoute l'element comme DERNIER enfant du conteneur

// Autres methodes pour inserer
conteneur.prepend(nouveauParagraphe);      // Ajoute comme PREMIER enfant
conteneur.before(nouveauParagraphe);       // Ajoute AVANT le conteneur
conteneur.after(nouveauParagraphe);        // Ajoute APRES le conteneur

// Supprimer un element
nouveauParagraphe.remove();
// Supprime l'element de la page
```

---

## 12. Les evenements

Les evenements permettent de reagir aux actions de l'utilisateur.

### addEventListener

```javascript
let bouton = document.querySelector("#mon-bouton");

// addEventListener(typeEvenement, fonctionCallback)
bouton.addEventListener("click", function() {
    console.log("Le bouton a ete clique !");
});
// Quand l'utilisateur clique sur le bouton, la fonction s'execute

// Avec une fonction flechee
bouton.addEventListener("click", () => {
    console.log("Clic avec une arrow function !");
});

// Avec une fonction nommee (utile pour retirer l'evenement plus tard)
function gererClic() {
    console.log("Clic gere !");
}
bouton.addEventListener("click", gererClic);
// Pour retirer : bouton.removeEventListener("click", gererClic);
```

### Evenements courants

```javascript
// click - quand on clique sur un element
element.addEventListener("click", () => {
    console.log("Element clique !");
});

// submit - quand un formulaire est soumis
let formulaire = document.querySelector("form");
formulaire.addEventListener("submit", (e) => {
    e.preventDefault(); // Empeche le rechargement de la page
    console.log("Formulaire soumis !");
});

// input - quand la valeur d'un champ change (en temps reel)
let champ = document.querySelector("input");
champ.addEventListener("input", (e) => {
    console.log(`Valeur actuelle : ${e.target.value}`);
    // e.target est l'element qui a declenche l'evenement
    // .value contient la valeur actuelle du champ
});

// keydown - quand une touche du clavier est enfoncee
document.addEventListener("keydown", (e) => {
    console.log(`Touche pressee : ${e.key}`);
    // e.key contient le nom de la touche (ex: "Enter", "Escape", "a")
});

// mouseover - quand la souris survole un element
element.addEventListener("mouseover", () => {
    console.log("Souris sur l'element !");
});

// DOMContentLoaded - quand le HTML est entierement charge
document.addEventListener("DOMContentLoaded", () => {
    console.log("La page est prete !");
    // C'est ici qu'on met le code qui doit s'executer au chargement
});
```

### L'objet evenement (event)

```javascript
let bouton = document.querySelector("button");

bouton.addEventListener("click", function(event) {
    // L'objet event (souvent abrege "e") contient des informations sur l'evenement

    console.log(event.type);      // "click" - le type d'evenement
    console.log(event.target);    // l'element qui a declenche l'evenement
    console.log(event.clientX);   // position X de la souris
    console.log(event.clientY);   // position Y de la souris

    event.preventDefault();
    // Empeche le comportement par defaut (ex: empeche un lien de naviguer,
    // empeche un formulaire de se soumettre)

    event.stopPropagation();
    // Empeche l'evenement de se propager aux elements parents
});
```

---

## 13. Template literals et interpolation

Nous avons deja vu les bases dans la section Strings. Voici des usages plus avances :

```javascript
// Expressions dans les template literals
let prix = 19.99;
let quantite = 3;
console.log(`Total : ${prix * quantite}€`);
// "Total : 59.97€" - on peut mettre des calculs dans ${}

// Appels de fonctions
let nom = "alice";
console.log(`Nom : ${nom.toUpperCase()}`);
// "Nom : ALICE" - on peut appeler des methodes dans ${}

// Conditions ternaires
let age = 20;
console.log(`Statut : ${age >= 18 ? "Majeur" : "Mineur"}`);
// "Statut : Majeur"

// Chaines multi-lignes
let carte = `
┌─────────────────┐
│  ${nom.toUpperCase().padEnd(15)} │
│  Age: ${String(age).padEnd(12)} │
└─────────────────┘
`;
console.log(carte);
```

---

## 14. Spread operator et rest parameters

### Spread operator (`...`)

Le spread "etale" les elements d'un tableau ou les proprietes d'un objet :

```javascript
// Avec les tableaux
const fruits = ["pomme", "banane"];
const legumes = ["carotte", "poireau"];

// Fusionner deux tableaux
const aliments = [...fruits, ...legumes];
// Equivalent a : ["pomme", "banane", "carotte", "poireau"]
console.log(aliments);

// Copier un tableau (copie superficielle)
const copie = [...fruits];
// Cree un nouveau tableau avec les memes elements
copie.push("orange");
console.log(fruits); // ["pomme", "banane"] (l'original n'est pas modifie)
console.log(copie);  // ["pomme", "banane", "orange"]

// Avec les objets
const personne = { prenom: "Alice", age: 25 };
const adresse = { ville: "Paris", pays: "France" };

// Fusionner deux objets
const profilComplet = { ...personne, ...adresse };
console.log(profilComplet);
// { prenom: "Alice", age: 25, ville: "Paris", pays: "France" }

// Copier et modifier un objet
const personneMiseAJour = { ...personne, age: 26 };
// Copie toutes les proprietes puis ecrase "age" avec 26
console.log(personneMiseAJour); // { prenom: "Alice", age: 26 }
```

### Rest parameters (`...`)

Le rest rassemble plusieurs arguments en un seul tableau :

```javascript
// Les rest parameters collectent les arguments restants dans un tableau
function somme(...nombres) {
    // nombres est un tableau contenant tous les arguments
    return nombres.reduce((acc, n) => acc + n, 0);
}

console.log(somme(1, 2, 3));       // 6
console.log(somme(1, 2, 3, 4, 5)); // 15
// On peut passer autant d'arguments qu'on veut

// Combiner parametres normaux et rest
function afficherEquipe(capitaine, ...joueurs) {
    console.log(`Capitaine : ${capitaine}`);
    console.log(`Joueurs : ${joueurs.join(", ")}`);
}

afficherEquipe("Alice", "Bob", "Charlie", "David");
// Capitaine : Alice
// Joueurs : Bob, Charlie, David
// Le premier argument va dans "capitaine", le reste dans le tableau "joueurs"
```

---

## 15. LocalStorage

`localStorage` permet de stocker des donnees dans le navigateur de l'utilisateur. Les donnees persistent meme apres la fermeture du navigateur.

```javascript
// Stocker une valeur (cle, valeur) - les deux doivent etre des chaines
localStorage.setItem("prenom", "Alice");
localStorage.setItem("age", "25"); // Meme les nombres doivent etre en string

// Recuperer une valeur
let prenom = localStorage.getItem("prenom");
console.log(prenom); // "Alice"

// Supprimer une valeur
localStorage.removeItem("prenom");

// Vider tout le localStorage
localStorage.clear();

// Stocker des donnees complexes (tableaux, objets)
// On doit convertir en JSON (chaine de caracteres)
const panier = ["pomme", "banane", "orange"];

// Sauvegarder : objet/tableau -> JSON string
localStorage.setItem("panier", JSON.stringify(panier));
// JSON.stringify convertit le tableau en chaine : '["pomme","banane","orange"]'

// Recuperer : JSON string -> objet/tableau
let panierRecupere = JSON.parse(localStorage.getItem("panier"));
// JSON.parse convertit la chaine JSON en tableau JavaScript
console.log(panierRecupere); // ["pomme", "banane", "orange"]

// Verifier si une donnee existe
let donnee = localStorage.getItem("clef_inexistante");
console.log(donnee); // null (retourne null si la cle n'existe pas)

if (donnee === null) {
    console.log("Aucune donnee trouvee pour cette cle.");
}
```

> **Limites** : localStorage ne peut stocker que des chaines de caracteres (d'ou l'utilisation de JSON.stringify/JSON.parse). La capacite est generalement limitee a ~5 MB par domaine.

---

## 16. Fetch API

`fetch` permet de communiquer avec un serveur pour envoyer ou recevoir des donnees (par exemple, depuis une API REST).

### Avec `.then()` (Promesses)

```javascript
// Requete GET simple
fetch("https://jsonplaceholder.typicode.com/users")
    .then(response => {
        // "response" est la reponse brute du serveur
        // .ok est true si le statut HTTP est entre 200 et 299
        if (!response.ok) {
            throw new Error(`Erreur HTTP : ${response.status}`);
        }
        return response.json();
        // .json() convertit la reponse en objet JavaScript
        // C'est aussi une promesse, d'ou le return
    })
    .then(donnees => {
        // "donnees" contient maintenant les donnees converties en JS
        console.log(donnees);
        donnees.forEach(utilisateur => {
            console.log(utilisateur.name);
        });
    })
    .catch(erreur => {
        // .catch attrape toutes les erreurs (reseau, JSON invalide, etc.)
        console.error("Erreur :", erreur.message);
    });
```

### Avec `async/await` (syntaxe moderne et recommandee)

```javascript
// async/await rend le code asynchrone plus lisible
async function chargerUtilisateurs() {
    // "async" devant function permet d'utiliser "await" a l'interieur
    try {
        const response = await fetch("https://jsonplaceholder.typicode.com/users");
        // "await" attend que la promesse soit resolue avant de continuer
        // Le code "pause" ici jusqu'a ce que la reponse arrive

        if (!response.ok) {
            throw new Error(`Erreur HTTP : ${response.status}`);
        }

        const donnees = await response.json();
        // On attend aussi la conversion JSON

        console.log(donnees);
        return donnees;
    } catch (erreur) {
        // try/catch gere les erreurs avec async/await
        console.error("Erreur :", erreur.message);
    }
}

// Appeler la fonction async
chargerUtilisateurs();
```

> **Pourquoi `async/await` ?** Le code ressemble a du code synchrone (ligne par ligne), ce qui le rend beaucoup plus facile a lire et a comprendre que les chaines de `.then()`.

---

## 17. Gestion des erreurs : try/catch

`try/catch` permet de gerer les erreurs sans que le programme ne s'arrete brutalement.

```javascript
// Structure de base
try {
    // Le code qui pourrait provoquer une erreur
    let resultat = JSON.parse("ceci n'est pas du JSON");
    // JSON.parse va echouer car la chaine n'est pas du JSON valide
} catch (erreur) {
    // Ce bloc s'execute SI une erreur se produit dans le try
    console.error("Une erreur est survenue :", erreur.message);
    // erreur.message contient le message d'erreur
    // erreur.name contient le type d'erreur (ex: "SyntaxError")
} finally {
    // Ce bloc s'execute TOUJOURS, qu'il y ait une erreur ou non
    console.log("Ce code s'execute dans tous les cas.");
}

// Lancer ses propres erreurs avec throw
function diviser(a, b) {
    if (b === 0) {
        throw new Error("Division par zero impossible !");
        // throw cree et lance une erreur
    }
    return a / b;
}

try {
    let resultat = diviser(10, 0);
    console.log(resultat); // Ne s'execute pas car une erreur est lancee
} catch (erreur) {
    console.error(erreur.message); // "Division par zero impossible !"
}

// Exemple pratique : valider des donnees
function validerAge(age) {
    if (typeof age !== "number") {
        throw new TypeError("L'age doit etre un nombre.");
    }
    if (age < 0 || age > 150) {
        throw new RangeError("L'age doit etre entre 0 et 150.");
    }
    return true;
}

try {
    validerAge("vingt");
} catch (erreur) {
    console.error(`${erreur.name} : ${erreur.message}`);
    // "TypeError : L'age doit etre un nombre."
}
```

---

## 18. Exercices progressifs

### Exercice 1 : Calculateur de prix TTC (Variables, operateurs, fonctions)

**Objectif** : Creer une fonction qui calcule le prix TTC a partir d'un prix HT et d'un taux de TVA.

```
Consignes :
1. Creer une fonction "calculerTTC" qui prend en parametres :
   - prixHT (nombre)
   - tauxTVA (nombre, valeur par defaut : 20)
2. La fonction doit retourner le prix TTC arrondi a 2 decimales
3. Afficher le resultat avec un template literal :
   "Le prix TTC est de XX.XX€ (TVA : XX%)"
4. Tester avec : 100€ a 20%, 50€ a 5.5%, 75€ sans preciser la TVA
```

<details>
<summary>Voir la solution</summary>

```javascript
function calculerTTC(prixHT, tauxTVA = 20) {
    const montantTVA = prixHT * (tauxTVA / 100);
    const prixTTC = prixHT + montantTVA;
    const prixArrondi = Math.round(prixTTC * 100) / 100;
    console.log(`Le prix TTC est de ${prixArrondi}€ (TVA : ${tauxTVA}%)`);
    return prixArrondi;
}

calculerTTC(100, 20);   // "Le prix TTC est de 120€ (TVA : 20%)"
calculerTTC(50, 5.5);   // "Le prix TTC est de 52.75€ (TVA : 5.5%)"
calculerTTC(75);         // "Le prix TTC est de 90€ (TVA : 20%)"
```
</details>

---

### Exercice 2 : Gestionnaire de liste de courses (Tableaux, boucles, conditions)

**Objectif** : Gerer une liste de courses avec des fonctions d'ajout, de suppression et d'affichage.

```
Consignes :
1. Creer un tableau vide "listeCourses"
2. Creer les fonctions suivantes :
   - ajouterArticle(nom) : ajoute un article s'il n'existe pas deja
   - supprimerArticle(nom) : supprime un article s'il existe
   - afficherListe() : affiche chaque article numerote
   - rechercherArticle(terme) : retourne les articles qui contiennent le terme
3. Tester toutes les fonctions
```

<details>
<summary>Voir la solution</summary>

```javascript
let listeCourses = [];

function ajouterArticle(nom) {
    const nomMinuscule = nom.toLowerCase().trim();
    if (listeCourses.includes(nomMinuscule)) {
        console.log(`"${nom}" est deja dans la liste.`);
        return;
    }
    listeCourses.push(nomMinuscule);
    console.log(`"${nom}" a ete ajoute.`);
}

function supprimerArticle(nom) {
    const index = listeCourses.indexOf(nom.toLowerCase().trim());
    if (index === -1) {
        console.log(`"${nom}" n'est pas dans la liste.`);
        return;
    }
    listeCourses.splice(index, 1);
    console.log(`"${nom}" a ete supprime.`);
}

function afficherListe() {
    if (listeCourses.length === 0) {
        console.log("La liste est vide.");
        return;
    }
    console.log("--- Liste de courses ---");
    listeCourses.forEach((article, index) => {
        console.log(`${index + 1}. ${article}`);
    });
}

function rechercherArticle(terme) {
    return listeCourses.filter(article =>
        article.includes(terme.toLowerCase())
    );
}

ajouterArticle("Pommes");
ajouterArticle("Lait");
ajouterArticle("Pain");
ajouterArticle("Pommes"); // Deja dans la liste
afficherListe();
supprimerArticle("Lait");
afficherListe();
console.log(rechercherArticle("p")); // ["pommes", "pain"]
```
</details>

---

### Exercice 3 : Carnet de contacts (Objets, methodes, destructuration)

**Objectif** : Creer un carnet de contacts avec des objets.

```
Consignes :
1. Creer un tableau "contacts" contenant des objets contact avec :
   prenom, nom, telephone, email, ville
2. Creer les fonctions :
   - ajouterContact(contact) : ajoute un contact au carnet
   - trouverParVille(ville) : retourne tous les contacts d'une ville
   - afficherContact(contact) : affiche un contact de facon formatee
     en utilisant la destructuration
   - trouverParNom(recherche) : cherche dans prenom ET nom
3. Utiliser les methodes de tableau (filter, find, forEach)
```

<details>
<summary>Voir la solution</summary>

```javascript
let contacts = [];

function ajouterContact(contact) {
    contacts.push(contact);
    console.log(`Contact "${contact.prenom} ${contact.nom}" ajoute.`);
}

function trouverParVille(ville) {
    return contacts.filter(c => c.ville.toLowerCase() === ville.toLowerCase());
}

function afficherContact(contact) {
    const { prenom, nom, telephone, email, ville } = contact;
    console.log(`
    Nom    : ${prenom} ${nom}
    Tel    : ${telephone}
    Email  : ${email}
    Ville  : ${ville}
    `);
}

function trouverParNom(recherche) {
    const terme = recherche.toLowerCase();
    return contacts.filter(c =>
        c.prenom.toLowerCase().includes(terme) ||
        c.nom.toLowerCase().includes(terme)
    );
}

ajouterContact({ prenom: "Alice", nom: "Dupont", telephone: "0601020304", email: "alice@email.com", ville: "Paris" });
ajouterContact({ prenom: "Bob", nom: "Martin", telephone: "0605060708", email: "bob@email.com", ville: "Lyon" });
ajouterContact({ prenom: "Charlie", nom: "Durand", telephone: "0609101112", email: "charlie@email.com", ville: "Paris" });

const parisiens = trouverParVille("Paris");
parisiens.forEach(c => afficherContact(c));

const resultat = trouverParNom("dup");
console.log(resultat);
```
</details>

---

### Exercice 4 : Validation de formulaire avec le DOM (DOM, evenements)

**Objectif** : Valider un formulaire d'inscription cote client.

```
Consignes :
Creer un fichier HTML avec un formulaire contenant :
- Champ "nom" (obligatoire, minimum 2 caracteres)
- Champ "email" (obligatoire, doit contenir @)
- Champ "mot de passe" (obligatoire, minimum 8 caracteres)
- Champ "confirmation mot de passe" (doit correspondre)
- Bouton "S'inscrire"

En JavaScript :
1. Ecouter l'evenement "submit" du formulaire
2. Empecher le rechargement de la page avec preventDefault()
3. Valider chaque champ et afficher les erreurs sous chaque champ
4. Si tout est valide, afficher un message de succes
5. Ajouter un evenement "input" sur chaque champ pour effacer
   l'erreur quand l'utilisateur corrige
```

<details>
<summary>Voir la solution</summary>

```html
<!-- HTML -->
<form id="formulaire-inscription">
    <div>
        <label for="nom">Nom :</label>
        <input type="text" id="nom">
        <span class="erreur" id="erreur-nom"></span>
    </div>
    <div>
        <label for="email">Email :</label>
        <input type="email" id="email">
        <span class="erreur" id="erreur-email"></span>
    </div>
    <div>
        <label for="mdp">Mot de passe :</label>
        <input type="password" id="mdp">
        <span class="erreur" id="erreur-mdp"></span>
    </div>
    <div>
        <label for="mdp-confirm">Confirmation :</label>
        <input type="password" id="mdp-confirm">
        <span class="erreur" id="erreur-mdp-confirm"></span>
    </div>
    <button type="submit">S'inscrire</button>
    <p id="message-succes" style="color: green; display: none;">Inscription reussie !</p>
</form>
```

```javascript
// JavaScript
document.addEventListener("DOMContentLoaded", () => {
    const formulaire = document.querySelector("#formulaire-inscription");
    const champNom = document.querySelector("#nom");
    const champEmail = document.querySelector("#email");
    const champMdp = document.querySelector("#mdp");
    const champMdpConfirm = document.querySelector("#mdp-confirm");

    // Efface l'erreur quand l'utilisateur tape
    [champNom, champEmail, champMdp, champMdpConfirm].forEach(champ => {
        champ.addEventListener("input", () => {
            const erreurSpan = document.querySelector(`#erreur-${champ.id}`);
            if (erreurSpan) erreurSpan.textContent = "";
        });
    });

    formulaire.addEventListener("submit", (e) => {
        e.preventDefault();
        let estValide = true;

        // Reinitialiser les erreurs
        document.querySelectorAll(".erreur").forEach(el => el.textContent = "");
        document.querySelector("#message-succes").style.display = "none";

        // Validation du nom
        if (champNom.value.trim().length < 2) {
            document.querySelector("#erreur-nom").textContent = "Le nom doit avoir au moins 2 caracteres.";
            estValide = false;
        }

        // Validation de l'email
        if (!champEmail.value.includes("@")) {
            document.querySelector("#erreur-email").textContent = "L'email doit contenir un @.";
            estValide = false;
        }

        // Validation du mot de passe
        if (champMdp.value.length < 8) {
            document.querySelector("#erreur-mdp").textContent = "Le mot de passe doit avoir au moins 8 caracteres.";
            estValide = false;
        }

        // Validation de la confirmation
        if (champMdp.value !== champMdpConfirm.value) {
            document.querySelector("#erreur-mdp-confirm").textContent = "Les mots de passe ne correspondent pas.";
            estValide = false;
        }

        if (estValide) {
            document.querySelector("#message-succes").style.display = "block";
            formulaire.reset();
        }
    });
});
```
</details>

---

### Exercice 5 : Application Meteo avec Fetch API (Fetch, async/await, DOM, try/catch)

**Objectif** : Creer une mini-application qui affiche la meteo en utilisant une API.

```
Consignes :
1. Creer une interface avec un champ de recherche pour entrer une ville
   et un bouton "Rechercher"
2. Utiliser l'API gratuite : https://wttr.in/{ville}?format=j1
3. Au clic sur le bouton :
   a. Recuperer la ville saisie
   b. Faire un appel Fetch vers l'API (avec async/await)
   c. Afficher : nom de la ville, temperature, description
   d. Gerer les erreurs (ville non trouvee, pas de connexion)
4. Sauvegarder la derniere ville recherchee dans localStorage
5. Au chargement de la page, recharger la derniere ville recherchee
```

<details>
<summary>Voir la solution</summary>

```javascript
document.addEventListener("DOMContentLoaded", () => {
    const champVille = document.querySelector("#ville");
    const boutonRecherche = document.querySelector("#rechercher");
    const resultatDiv = document.querySelector("#resultat");

    async function rechercherMeteo(ville) {
        try {
            resultatDiv.textContent = "Chargement...";

            const response = await fetch(`https://wttr.in/${ville}?format=j1`);

            if (!response.ok) {
                throw new Error("Ville non trouvee.");
            }

            const donnees = await response.json();
            const actuel = donnees.current_condition[0];
            const temperature = actuel.temp_C;
            const description = actuel.weatherDesc[0].value;

            resultatDiv.innerHTML = `
                <h2>Meteo a ${ville}</h2>
                <p>Temperature : ${temperature}°C</p>
                <p>Conditions : ${description}</p>
            `;

            localStorage.setItem("derniereVille", ville);
        } catch (erreur) {
            resultatDiv.textContent = `Erreur : ${erreur.message}`;
        }
    }

    boutonRecherche.addEventListener("click", () => {
        const ville = champVille.value.trim();
        if (ville) {
            rechercherMeteo(ville);
        }
    });

    // Charger la derniere ville au demarrage
    const derniereVille = localStorage.getItem("derniereVille");
    if (derniereVille) {
        champVille.value = derniereVille;
        rechercherMeteo(derniereVille);
    }
});
```
</details>

---

## Resume des bonnes pratiques

| Pratique | Recommandation |
|---|---|
| Variables | Utiliser `const` par defaut, `let` si necessaire, jamais `var` |
| Comparaison | Toujours utiliser `===` et `!==` |
| Chaines | Preferer les template literals (backticks) |
| Fonctions | Preferer les fonctions flechees pour les callbacks |
| DOM | Utiliser `querySelector` plutot que `getElementById` |
| Styles | Manipuler les classes CSS plutot que les styles en ligne |
| Async | Preferer `async/await` plutot que `.then()` |
| Erreurs | Toujours utiliser `try/catch` avec les appels reseau |
| Stockage | Utiliser `JSON.stringify/parse` avec localStorage |

---

*Ce cours couvre les bases essentielles de JavaScript. La prochaine etape est de pratiquer en realisant des projets concrets : une todo list, un quiz, un jeu simple, un panier d'achat... La pratique est la cle de la maitrise !*
