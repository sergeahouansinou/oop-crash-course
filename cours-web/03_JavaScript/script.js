// ============================================================================
// COURS COMPLET JAVASCRIPT - FICHIER DE DEMONSTRATION
// Chaque ligne est commentee en francais pour les debutants
// ============================================================================


// ############################################################################
// SECTION 1 : VARIABLES ET TYPES DE DONNEES
// ############################################################################

// --- var (ancienne methode, a eviter) ---
var ancienneVariable = "Je suis declaree avec var (a eviter)";
// var a une portee de fonction, pas de bloc, ce qui cause des bugs

// --- let (pour les valeurs qui peuvent changer) ---
let age = 25; // On declare une variable "age" avec la valeur 25
age = 26; // On peut modifier la valeur d'une variable let
console.log("Age :", age); // Affiche "Age : 26" dans la console

// --- const (pour les valeurs qui ne changent pas) ---
const NOM_DU_SITE = "Mon Super Site"; // Constante : ne peut pas etre reassignee
// NOM_DU_SITE = "Autre"; // ERREUR ! On ne peut pas modifier une constante
console.log("Nom du site :", NOM_DU_SITE); // Affiche le nom du site

// --- Les differents types de donnees ---

// String (chaine de caracteres)
const prenom = "Alice"; // Guillemets doubles
const nom = 'Dupont'; // Guillemets simples (equivalent)
const phrase = `Bonjour ${prenom}`; // Backticks (template literal avec interpolation)
console.log("Type de prenom :", typeof prenom); // "string"

// Number (nombre)
const entier = 42; // Nombre entier
const decimal = 3.14; // Nombre decimal (on utilise le point, pas la virgule)
const negatif = -10; // Nombre negatif
console.log("Type de entier :", typeof entier); // "number"

// Boolean (booleen : vrai ou faux)
const estMajeur = true; // Vrai
const estMineur = false; // Faux
console.log("Type de estMajeur :", typeof estMajeur); // "boolean"

// Null (valeur vide intentionnelle)
const resultatVide = null; // Assignation volontaire de "rien"
console.log("Type de null :", typeof resultatVide); // "object" (bug historique de JS)

// Undefined (variable declaree mais sans valeur)
let nonDefini; // Aucune valeur assignee
console.log("Type de nonDefini :", typeof nonDefini); // "undefined"

// Array (tableau : liste ordonnee)
const fruits = ["pomme", "banane", "orange"]; // Tableau de 3 chaines
console.log("Type de fruits :", typeof fruits); // "object" (les tableaux sont des objets)
console.log("Est un tableau :", Array.isArray(fruits)); // true (la bonne facon de verifier)

// Object (objet : paires cle-valeur)
const personne = { // Un objet avec 3 proprietes
    prenom: "Alice", // Propriete "prenom" avec la valeur "Alice"
    age: 25, // Propriete "age" avec la valeur 25
    ville: "Paris" // Propriete "ville" avec la valeur "Paris"
};
console.log("Type de personne :", typeof personne); // "object"


// ############################################################################
// SECTION 2 : METHODES DES CHAINES DE CARACTERES (STRINGS)
// ############################################################################

const texte = "  Bonjour le Monde JavaScript  ";
console.log("\n--- METHODES DES STRINGS ---");

// .length : retourne le nombre de caracteres (propriete, pas une methode)
console.log("Longueur :", texte.length); // 31 (espaces inclus)

// .toUpperCase() : convertit en majuscules (retourne une NOUVELLE chaine)
console.log("Majuscules :", texte.toUpperCase()); // "  BONJOUR LE MONDE JAVASCRIPT  "

// .toLowerCase() : convertit en minuscules
console.log("Minuscules :", texte.toLowerCase()); // "  bonjour le monde javascript  "

// .trim() : supprime les espaces au debut et a la fin
console.log("Trim :", texte.trim()); // "Bonjour le Monde JavaScript"

// .includes() : verifie si le texte contient une sous-chaine (retourne true/false)
console.log("Contient 'Monde' :", texte.includes("Monde")); // true
console.log("Contient 'Python' :", texte.includes("Python")); // false

// .indexOf() : retourne la position de la premiere occurrence (-1 si absente)
console.log("Position de 'le' :", texte.indexOf("le")); // 10
console.log("Position de 'xyz' :", texte.indexOf("xyz")); // -1 (non trouve)

// .slice(debut, fin) : extrait une portion de la chaine
const texteTrim = texte.trim(); // On retire d'abord les espaces
console.log("Slice(0, 7) :", texteTrim.slice(0, 7)); // "Bonjour"
// Extrait de l'index 0 (inclus) a l'index 7 (exclus)
console.log("Slice(8) :", texteTrim.slice(8)); // "le Monde JavaScript"
// Extrait de l'index 8 jusqu'a la fin
console.log("Slice(-10) :", texteTrim.slice(-10)); // "JavaScript"
// Les 10 derniers caracteres

// .split(separateur) : decoupe la chaine en tableau
const listeCSV = "rouge,vert,bleu,jaune";
const couleurs = listeCSV.split(","); // Decoupe a chaque virgule
console.log("Split :", couleurs); // ["rouge", "vert", "bleu", "jaune"]

const mots = texteTrim.split(" "); // Decoupe a chaque espace
console.log("Mots :", mots); // ["Bonjour", "le", "Monde", "JavaScript"]

// .replace() : remplace une occurrence
console.log("Replace :", texteTrim.replace("Monde", "World")); // "Bonjour le World JavaScript"

// .startsWith() et .endsWith() : verifie le debut ou la fin
console.log("Commence par 'Bonjour' :", texteTrim.startsWith("Bonjour")); // true
console.log("Finit par 'Script' :", texteTrim.endsWith("Script")); // true

// .repeat() : repete la chaine N fois
console.log("Repeat :", "Ha".repeat(3)); // "HaHaHa"

// Concatenation classique avec +
const nomComplet = prenom + " " + nom; // Colle les chaines ensemble
console.log("Concatenation :", nomComplet); // "Alice Dupont"

// Template literal (methode moderne et recommandee)
const presentation = `Je suis ${prenom} ${nom}, j'ai ${age} ans.`;
console.log("Template literal :", presentation); // "Je suis Alice Dupont, j'ai 26 ans."


// ############################################################################
// SECTION 3 : CONDITIONS
// ############################################################################

console.log("\n--- CONDITIONS ---");

// --- if / else ---
const temperature = 22; // On definit la temperature

if (temperature > 30) {
    // Ce bloc s'execute si temperature > 30
    console.log("Il fait tres chaud !");
} else if (temperature > 20) {
    // Ce bloc s'execute si temperature > 20 (et <= 30)
    console.log("Il fait bon."); // C'est celui-ci qui s'affiche (22 > 20)
} else if (temperature > 10) {
    // Ce bloc s'execute si temperature > 10 (et <= 20)
    console.log("Il fait frais.");
} else {
    // Ce bloc s'execute si aucune condition precedente n'est vraie
    console.log("Il fait froid !");
}

// --- Comparaison == vs === ---
const valeur = "5"; // C'est une chaine de caracteres

console.log("5 == '5' :", 5 == "5"); // true : compare SEULEMENT la valeur (conversion auto)
console.log("5 === '5' :", 5 === "5"); // false : compare la valeur ET le type
// TOUJOURS utiliser === pour eviter les surprises !

// --- Operateurs logiques ---
const ageUtilisateur = 20;
const aUnPermis = true;

// && (ET) : les deux conditions doivent etre vraies
if (ageUtilisateur >= 18 && aUnPermis) {
    console.log("Peut conduire"); // S'affiche car les deux conditions sont vraies
}

// || (OU) : au moins une condition doit etre vraie
if (ageUtilisateur < 18 || aUnPermis) {
    console.log("Au moins une condition est vraie"); // S'affiche car aUnPermis est true
}

// ! (NON) : inverse la valeur
console.log("!true :", !true); // false
console.log("!false :", !false); // true

// --- Operateur ternaire ---
// Syntaxe : condition ? valeurSiVrai : valeurSiFaux
const statut = ageUtilisateur >= 18 ? "majeur" : "mineur";
console.log("Statut :", statut); // "majeur"

// --- switch ---
const jour = "mercredi"; // On definit le jour

switch (jour) {
    case "lundi":
        console.log("Debut de semaine");
        break; // OBLIGATOIRE : sans break, le code continue dans le case suivant
    case "mardi":
    case "mercredi":
    case "jeudi":
        // Plusieurs cases peuvent partager le meme bloc
        console.log("Milieu de semaine"); // C'est celui-ci qui s'affiche
        break;
    case "vendredi":
        console.log("Bientot le weekend !");
        break;
    case "samedi":
    case "dimanche":
        console.log("Weekend !");
        break;
    default:
        // S'execute si aucun case ne correspond
        console.log("Jour inconnu");
}

// --- Valeurs falsy et truthy ---
// Les valeurs falsy en JavaScript : false, 0, "", null, undefined, NaN
console.log("Boolean('') :", Boolean("")); // false (chaine vide est falsy)
console.log("Boolean('texte') :", Boolean("texte")); // true (chaine non-vide est truthy)
console.log("Boolean(0) :", Boolean(0)); // false (zero est falsy)
console.log("Boolean(42) :", Boolean(42)); // true (nombre non-zero est truthy)


// ############################################################################
// SECTION 4 : BOUCLES
// ############################################################################

console.log("\n--- BOUCLES ---");

// --- Boucle for ---
// for (initialisation; condition; incrementation)
console.log("Boucle for :");
for (let i = 0; i < 5; i++) {
    // i commence a 0, continue tant que i < 5, ajoute 1 a chaque tour
    console.log(`  Tour ${i}`); // Affiche Tour 0, Tour 1, ..., Tour 4
}

// --- Boucle for avec tableau ---
const animaux = ["chat", "chien", "oiseau", "poisson"];
console.log("Parcourir un tableau avec for :");
for (let i = 0; i < animaux.length; i++) {
    // animaux.length = 4, donc i va de 0 a 3
    console.log(`  ${i + 1}. ${animaux[i]}`); // Affiche chaque animal numerote
}

// --- Boucle while ---
let compteur = 0; // On initialise le compteur a 0
console.log("Boucle while :");
while (compteur < 3) {
    // S'execute tant que compteur < 3
    console.log(`  Compteur : ${compteur}`);
    compteur++; // IMPORTANT : sans ca, boucle infinie !
}
// Affiche : Compteur : 0, Compteur : 1, Compteur : 2

// --- Boucle do...while ---
let nombre = 10; // On initialise a 10
console.log("Boucle do...while :");
do {
    // Le bloc s'execute AU MOINS UNE FOIS, meme si la condition est fausse
    console.log(`  Nombre : ${nombre}`); // Affiche "Nombre : 10"
    nombre++;
} while (nombre < 5);
// La condition est fausse (10 < 5 = false), donc la boucle s'arrete apres 1 tour

// --- Boucle for...of (pour les tableaux et iterables) ---
const langages = ["JavaScript", "Python", "Java", "C++"];
console.log("Boucle for...of :");
for (const langage of langages) {
    // A chaque tour, "langage" prend la VALEUR de l'element suivant
    console.log(`  ${langage}`);
}

// --- Boucle for...in (pour les objets) ---
const voiture = {
    marque: "Peugeot",
    modele: "208",
    annee: 2023,
    couleur: "bleu"
};
console.log("Boucle for...in :");
for (const propriete in voiture) {
    // A chaque tour, "propriete" prend la CLE (le nom de la propriete)
    console.log(`  ${propriete} : ${voiture[propriete]}`);
}

// --- break et continue ---
console.log("Break (arrete la boucle) :");
for (let i = 0; i < 10; i++) {
    if (i === 5) {
        break; // On sort de la boucle quand i vaut 5
    }
    console.log(`  ${i}`); // Affiche 0, 1, 2, 3, 4
}

console.log("Continue (saute un tour) :");
for (let i = 0; i < 6; i++) {
    if (i === 3) {
        continue; // On saute le tour quand i vaut 3
    }
    console.log(`  ${i}`); // Affiche 0, 1, 2, 4, 5 (le 3 est saute)
}


// ############################################################################
// SECTION 5 : FONCTIONS
// ############################################################################

console.log("\n--- FONCTIONS ---");

// --- Declaration de fonction ---
function saluer(prenom) {
    // "prenom" est un parametre : une variable locale a la fonction
    return `Bonjour ${prenom} !`;
    // return renvoie une valeur et arrete l'execution de la fonction
}
// On appelle la fonction en lui passant un argument
console.log(saluer("Alice")); // "Bonjour Alice !"
console.log(saluer("Bob")); // "Bonjour Bob !"

// --- Fonction avec plusieurs parametres ---
function additionner(a, b) {
    // a et b sont les deux parametres
    return a + b; // Retourne la somme
}
console.log("Addition :", additionner(3, 5)); // 8

// --- Parametres par defaut ---
function creerSalutation(prenom, formule = "Bonjour") {
    // Si formule n'est pas fourni, il vaut "Bonjour"
    return `${formule} ${prenom} !`;
}
console.log(creerSalutation("Alice")); // "Bonjour Alice !" (valeur par defaut)
console.log(creerSalutation("Alice", "Salut")); // "Salut Alice !"

// --- Expression de fonction ---
const multiplier = function(a, b) {
    // La fonction est stockee dans une variable
    return a * b;
}; // Notez le point-virgule (c'est une affectation de variable)
console.log("Multiplication :", multiplier(4, 5)); // 20

// --- Fonctions flechees (Arrow Functions) ---

// Syntaxe complete avec accolades et return
const soustraire = (a, b) => {
    return a - b;
};
console.log("Soustraction :", soustraire(10, 3)); // 7

// Syntaxe raccourcie (une seule expression = return implicite)
const diviser = (a, b) => a / b;
// Pas besoin de {} ni de return quand il n'y a qu'une expression
console.log("Division :", diviser(20, 4)); // 5

// Un seul parametre : les parentheses sont optionnelles
const doubler = n => n * 2;
console.log("Doubler :", doubler(7)); // 14

// Pas de parametre : parentheses vides obligatoires
const obtenirDate = () => new Date().toLocaleDateString("fr-FR");
console.log("Date :", obtenirDate()); // Date du jour au format francais

// --- Fonction qui prend une autre fonction en parametre (callback) ---
function appliquerOperation(a, b, operation) {
    // "operation" est une fonction passee en argument (callback)
    return operation(a, b);
}
console.log("Callback add :", appliquerOperation(5, 3, additionner)); // 8
console.log("Callback mul :", appliquerOperation(5, 3, multiplier)); // 15
// On passe la reference de la fonction (sans parentheses)

// --- Fonction recursive (qui s'appelle elle-meme) ---
function factorielle(n) {
    if (n <= 1) {
        return 1; // Cas de base : on arrete la recursion
    }
    return n * factorielle(n - 1); // Appel recursif
    // factorielle(5) = 5 * factorielle(4) = 5 * 4 * factorielle(3) = ... = 120
}
console.log("Factorielle de 5 :", factorielle(5)); // 120


// ############################################################################
// SECTION 6 : METHODES DES TABLEAUX (ARRAYS)
// ############################################################################

console.log("\n--- METHODES DES TABLEAUX ---");

// --- Creation ---
let mesFruits = ["pomme", "banane", "orange", "kiwi", "fraise"];
console.log("Tableau initial :", mesFruits);

// --- Ajout / Suppression ---

// push : ajoute a la FIN du tableau
mesFruits.push("mangue");
console.log("Apres push('mangue') :", mesFruits);
// ["pomme", "banane", "orange", "kiwi", "fraise", "mangue"]

// pop : retire le DERNIER element
const dernierFruit = mesFruits.pop(); // Retire et retourne "mangue"
console.log("pop() a retire :", dernierFruit); // "mangue"
console.log("Apres pop() :", mesFruits);

// unshift : ajoute au DEBUT du tableau
mesFruits.unshift("cerise");
console.log("Apres unshift('cerise') :", mesFruits);
// ["cerise", "pomme", "banane", "orange", "kiwi", "fraise"]

// shift : retire le PREMIER element
const premierFruit = mesFruits.shift(); // Retire et retourne "cerise"
console.log("shift() a retire :", premierFruit); // "cerise"

// splice : ajouter/supprimer a une position precise
mesFruits.splice(2, 1); // A l'index 2, supprime 1 element (supprime "orange")
console.log("Apres splice(2, 1) :", mesFruits);
// ["pomme", "banane", "kiwi", "fraise"]

mesFruits.splice(1, 0, "ananas", "melon"); // A l'index 1, supprime 0, insere 2 elements
console.log("Apres splice(1, 0, 'ananas', 'melon') :", mesFruits);
// ["pomme", "ananas", "melon", "banane", "kiwi", "fraise"]

// slice : extraire une portion SANS modifier l'original
const selection = mesFruits.slice(1, 4); // De l'index 1 (inclus) a 4 (exclus)
console.log("slice(1, 4) :", selection); // ["ananas", "melon", "banane"]
console.log("Original inchange :", mesFruits); // Le tableau original n'est pas modifie

// --- Recherche ---

// indexOf : position de la premiere occurrence (-1 si absent)
console.log("indexOf('kiwi') :", mesFruits.indexOf("kiwi")); // 4
console.log("indexOf('mangue') :", mesFruits.indexOf("mangue")); // -1 (non trouve)

// includes : verifie si un element existe (true/false)
console.log("includes('banane') :", mesFruits.includes("banane")); // true
console.log("includes('raisin') :", mesFruits.includes("raisin")); // false

// find : retourne le PREMIER element qui satisfait la condition
const nombres = [3, 7, 12, 5, 18, 2, 25];
const premierGrand = nombres.find(n => n > 10);
// Parcourt le tableau et retourne le premier nombre > 10
console.log("find(n > 10) :", premierGrand); // 12

// findIndex : comme find, mais retourne l'INDEX au lieu de la valeur
const indexGrand = nombres.findIndex(n => n > 10);
console.log("findIndex(n > 10) :", indexGrand); // 2 (l'index de 12)

// --- Transformation ---

// filter : retourne un NOUVEAU tableau avec les elements qui passent le test
const grandsNombres = nombres.filter(n => n > 10);
// Garde seulement les nombres superieurs a 10
console.log("filter(n > 10) :", grandsNombres); // [12, 18, 25]

// map : retourne un NOUVEAU tableau en transformant chaque element
const doubles = nombres.map(n => n * 2);
// Multiplie chaque element par 2
console.log("map(n * 2) :", doubles); // [6, 14, 24, 10, 36, 4, 50]

// forEach : execute une fonction pour chaque element (ne retourne rien)
console.log("forEach :");
nombres.forEach((nombre, index) => {
    // Recoit la valeur et l'index a chaque tour
    console.log(`  Index ${index} -> Valeur ${nombre}`);
});

// reduce : reduit le tableau a une seule valeur
const somme = nombres.reduce((accumulateur, valeur) => {
    // accumulateur accumule le resultat a chaque tour
    // valeur est l'element courant du tableau
    return accumulateur + valeur;
}, 0); // 0 est la valeur initiale de l'accumulateur
console.log("reduce (somme) :", somme); // 72

// sort : trie le tableau (MODIFIE l'original)
const noms = ["Charlie", "Alice", "Eve", "Bob", "David"];
noms.sort(); // Tri alphabetique par defaut
console.log("sort() alphabetique :", noms); // ["Alice", "Bob", "Charlie", "David", "Eve"]

// ATTENTION : sort() sans comparateur trie les nombres comme des chaines !
const nums = [10, 5, 40, 25, 1000, 3];
nums.sort((a, b) => a - b); // Tri numerique croissant
// Si a - b < 0, a vient avant b. Si a - b > 0, b vient avant a.
console.log("sort() numerique croissant :", nums); // [3, 5, 10, 25, 40, 1000]

nums.sort((a, b) => b - a); // Tri numerique decroissant
console.log("sort() numerique decroissant :", nums); // [1000, 40, 25, 10, 5, 3]

// reverse : inverse l'ordre des elements (MODIFIE l'original)
const lettres = ["a", "b", "c", "d"];
lettres.reverse();
console.log("reverse() :", lettres); // ["d", "c", "b", "a"]

// --- Enchainement de methodes (chaining) ---
console.log("\n--- CHAINING ---");
const notes = [12, 8, 15, 6, 18, 9, 14, 7, 16, 11];

const resultatChaining = notes
    .filter(note => note >= 10) // Etape 1 : garder les notes >= 10 -> [12, 15, 18, 14, 16, 11]
    .map(note => note + 1) // Etape 2 : ajouter 1 point bonus -> [13, 16, 19, 15, 17, 12]
    .sort((a, b) => b - a) // Etape 3 : trier en decroissant -> [19, 17, 16, 15, 13, 12]
    .slice(0, 3); // Etape 4 : garder les 3 meilleures -> [19, 17, 16]

console.log("Top 3 des notes (avec bonus) :", resultatChaining);

const moyenneTop3 = resultatChaining.reduce((acc, n) => acc + n, 0) / resultatChaining.length;
// Calcule la moyenne des 3 meilleures notes
console.log("Moyenne top 3 :", moyenneTop3.toFixed(2)); // Arrondi a 2 decimales


// ############################################################################
// SECTION 7 : OBJETS
// ############################################################################

console.log("\n--- OBJETS ---");

// --- Creation d'un objet ---
const etudiant = {
    prenom: "Marie", // Propriete de type string
    nom: "Curie", // Propriete de type string
    age: 22, // Propriete de type number
    notes: [15, 18, 12, 16], // Propriete de type tableau
    estInscrit: true, // Propriete de type boolean
    adresse: { // Objet imbrique
        rue: "10 rue de la Science",
        ville: "Paris",
        codePostal: "75005"
    },

    // Methode : fonction definie dans l'objet
    sePresenter() {
        // "this" fait reference a l'objet etudiant
        return `Je suis ${this.prenom} ${this.nom}, j'ai ${this.age} ans.`;
    },

    // Methode qui utilise les proprietes de l'objet
    calculerMoyenne() {
        // this.notes est le tableau de notes de cet objet
        const somme = this.notes.reduce((acc, note) => acc + note, 0);
        return (somme / this.notes.length).toFixed(2); // Arrondi a 2 decimales
    },

    // Methode qui modifie l'objet
    ajouterNote(note) {
        this.notes.push(note); // Ajoute la note au tableau
        console.log(`Note ${note} ajoutee. Nouvelle moyenne : ${this.calculerMoyenne()}`);
    }
};

// --- Acces aux proprietes ---

// Notation par point (recommandee)
console.log("Prenom :", etudiant.prenom); // "Marie"
console.log("Ville :", etudiant.adresse.ville); // "Paris" (acces imbrique)

// Notation par crochet (utile avec des variables)
const propriete = "age"; // Le nom de la propriete est dans une variable
console.log("Age :", etudiant[propriete]); // 22

// Appel de methodes
console.log(etudiant.sePresenter()); // "Je suis Marie Curie, j'ai 22 ans."
console.log("Moyenne :", etudiant.calculerMoyenne()); // Calcule et affiche la moyenne
etudiant.ajouterNote(17); // Ajoute une note et affiche la nouvelle moyenne

// --- Modification d'un objet ---
etudiant.age = 23; // Modifier une propriete existante
etudiant.email = "marie@curie.fr"; // Ajouter une nouvelle propriete
delete etudiant.estInscrit; // Supprimer une propriete
console.log("Apres modifications :", etudiant);

// --- Destructuration ---
const { prenom: p, nom: n, age: a } = etudiant;
// Extrait prenom dans "p", nom dans "n", age dans "a"
console.log("Destructuration :", p, n, a); // "Marie" "Curie" 23

// Destructuration avec valeur par defaut
const { email, telephone = "Non renseigne" } = etudiant;
console.log("Email :", email); // "marie@curie.fr"
console.log("Telephone :", telephone); // "Non renseigne" (propriete absente)

// Destructuration imbriquee
const { adresse: { ville, codePostal } } = etudiant;
console.log("Ville :", ville); // "Paris"
console.log("Code postal :", codePostal); // "75005"

// --- Methodes utiles pour les objets ---
console.log("Cles :", Object.keys(etudiant)); // Tableau de toutes les cles
console.log("Valeurs :", Object.values(etudiant)); // Tableau de toutes les valeurs
console.log("Entrees :", Object.entries(etudiant)); // Tableau de paires [cle, valeur]

// Parcourir un objet avec Object.entries et destructuration
for (const [cle, valeur] of Object.entries(etudiant)) {
    console.log(`  ${cle} :`, valeur);
}


// ############################################################################
// SECTION 8 : MANIPULATION DU DOM
// ############################################################################

// NOTA : Ces exemples fonctionnent dans un navigateur avec une page HTML.
// Ils sont commentes pour eviter les erreurs dans un environnement sans DOM.
// Decommentez-les quand vous les utilisez avec un fichier HTML.

console.log("\n--- MANIPULATION DU DOM ---");

/*
// --- Selectionner des elements ---

// getElementById : selectionne par l'attribut id (UN seul element)
const titre = document.getElementById("titre-principal");
// Selectionne l'element qui a id="titre-principal"

// querySelector : selectionne le PREMIER element qui correspond au selecteur CSS
const premierParagraphe = document.querySelector("p");
// Selectionne le premier <p> de la page

const elementClasse = document.querySelector(".ma-classe");
// Selectionne le premier element avec la classe "ma-classe"

const elementId = document.querySelector("#mon-id");
// Selectionne l'element avec l'id "mon-id"

// querySelectorAll : selectionne TOUS les elements correspondants
const tousLesParagraphes = document.querySelectorAll("p");
// Retourne une NodeList de tous les <p> de la page

// Parcourir le resultat de querySelectorAll
tousLesParagraphes.forEach(paragraphe => {
    console.log(paragraphe.textContent); // Affiche le texte de chaque <p>
});

// --- Modifier le contenu ---

// textContent : modifie le texte brut (securise)
titre.textContent = "Nouveau titre de la page";
// Remplace tout le texte de l'element

// innerHTML : modifie le HTML (permet d'inserer des balises)
titre.innerHTML = "Titre <strong>important</strong>";
// ATTENTION : risque de securite (XSS) avec des donnees utilisateur

// --- Modifier les styles ---

// .style : modifie le style en ligne
const element = document.querySelector(".boite");
element.style.color = "red"; // Couleur du texte en rouge
element.style.backgroundColor = "#f0f0f0"; // Fond gris clair
element.style.fontSize = "18px"; // Taille de police
element.style.padding = "10px"; // Espacement interne
element.style.border = "2px solid blue"; // Bordure bleue

// .classList : manipuler les classes CSS (RECOMMANDE)
element.classList.add("active"); // Ajoute la classe "active"
element.classList.remove("hidden"); // Supprime la classe "hidden"
element.classList.toggle("visible"); // Ajoute si absente, supprime si presente
const aLaClasse = element.classList.contains("active"); // true ou false

// --- Modifier les attributs ---

const lien = document.querySelector("a");
lien.setAttribute("href", "https://www.example.com"); // Modifie l'attribut href
lien.setAttribute("target", "_blank"); // Ouvre le lien dans un nouvel onglet

const url = lien.getAttribute("href"); // Lit la valeur de l'attribut
console.log("URL du lien :", url);

// --- Creer et supprimer des elements ---

// Creer un element
const nouveauParagraphe = document.createElement("p");
// Cree un <p> en memoire (pas encore sur la page)

nouveauParagraphe.textContent = "Je suis un nouveau paragraphe !";
// On lui donne du contenu

nouveauParagraphe.classList.add("nouveau", "important");
// On lui ajoute des classes CSS

// Ajouter l'element a la page
const conteneur = document.querySelector("#conteneur");
conteneur.appendChild(nouveauParagraphe);
// appendChild ajoute l'element comme dernier enfant du conteneur

// Autres methodes d'insertion
conteneur.prepend(nouveauParagraphe); // Comme premier enfant
conteneur.insertBefore(nouveauParagraphe, conteneur.firstChild); // Avant un enfant specifique

// Supprimer un element
nouveauParagraphe.remove();
// Supprime l'element de la page
*/


// ############################################################################
// SECTION 9 : EVENEMENTS
// ############################################################################

console.log("\n--- EVENEMENTS ---");

/*
// --- addEventListener ---

// Recuperer le bouton
const bouton = document.querySelector("#mon-bouton");

// Ajouter un ecouteur d'evenement "click"
bouton.addEventListener("click", function(event) {
    // Cette fonction s'execute a chaque clic sur le bouton
    console.log("Bouton clique !");
    console.log("Type d'evenement :", event.type); // "click"
    console.log("Element cible :", event.target); // Le bouton lui-meme
});

// --- Avec une fonction flechee ---
bouton.addEventListener("dblclick", (e) => {
    // dblclick = double clic
    console.log("Double clic detecte !");
});

// --- Evenement submit sur un formulaire ---
const formulaire = document.querySelector("#mon-formulaire");

formulaire.addEventListener("submit", (e) => {
    e.preventDefault();
    // preventDefault() empeche le comportement par defaut
    // Pour un formulaire, ca empeche le rechargement de la page

    const prenom = document.querySelector("#champ-prenom").value;
    // .value recupere la valeur saisie dans le champ

    console.log(`Formulaire soumis avec le prenom : ${prenom}`);
});

// --- Evenement input (saisie en temps reel) ---
const champRecherche = document.querySelector("#recherche");

champRecherche.addEventListener("input", (e) => {
    // Se declenche a CHAQUE caractere tape
    console.log("Texte actuel :", e.target.value);
    // e.target est l'element qui a declenche l'evenement
});

// --- Evenement keydown (touche du clavier) ---
document.addEventListener("keydown", (e) => {
    console.log("Touche pressee :", e.key); // Le nom de la touche
    console.log("Code de la touche :", e.code); // Le code physique

    if (e.key === "Escape") {
        console.log("Touche Echap pressee !");
    }
    if (e.key === "Enter") {
        console.log("Touche Entree pressee !");
    }
});

// --- Evenement mouseover / mouseout ---
const zone = document.querySelector("#zone-survol");

zone.addEventListener("mouseover", () => {
    // Se declenche quand la souris ENTRE dans l'element
    zone.style.backgroundColor = "lightblue";
    console.log("Souris sur la zone");
});

zone.addEventListener("mouseout", () => {
    // Se declenche quand la souris QUITTE l'element
    zone.style.backgroundColor = "";
    console.log("Souris hors de la zone");
});

// --- DOMContentLoaded : quand le HTML est pret ---
document.addEventListener("DOMContentLoaded", () => {
    console.log("Le DOM est entierement charge et pret !");
    // C'est ICI qu'on met le code qui a besoin que le HTML soit charge
});
*/


// ############################################################################
// SECTION 10 : SPREAD OPERATOR ET REST PARAMETERS
// ############################################################################

console.log("\n--- SPREAD OPERATOR & REST PARAMETERS ---");

// --- Spread avec les tableaux ---
const legumes = ["carotte", "poireau", "courgette"];
const fruitsListe = ["pomme", "banane", "orange"];

// Fusionner deux tableaux
const aliments = [...fruitsListe, ...legumes];
// Le spread (...) "etale" chaque element du tableau
console.log("Fusion :", aliments);
// ["pomme", "banane", "orange", "carotte", "poireau", "courgette"]

// Copier un tableau (copie independante)
const copieFruits = [...fruitsListe];
copieFruits.push("kiwi"); // Ajoute a la copie
console.log("Original :", fruitsListe); // Inchange : ["pomme", "banane", "orange"]
console.log("Copie :", copieFruits); // Modifie : ["pomme", "banane", "orange", "kiwi"]

// Ajouter des elements au milieu
const avecAjout = [...fruitsListe.slice(0, 1), "fraise", ...fruitsListe.slice(1)];
console.log("Avec ajout :", avecAjout); // ["pomme", "fraise", "banane", "orange"]

// --- Spread avec les objets ---
const infoBase = { prenom: "Alice", age: 25 };
const infoAdresse = { ville: "Paris", pays: "France" };

// Fusionner des objets
const profilComplet = { ...infoBase, ...infoAdresse };
console.log("Profil complet :", profilComplet);
// { prenom: "Alice", age: 25, ville: "Paris", pays: "France" }

// Copier et modifier (tres utilise en React par exemple)
const profilMisAJour = { ...infoBase, age: 26, email: "alice@mail.com" };
// Copie tout de infoBase, puis ecrase "age" et ajoute "email"
console.log("Profil mis a jour :", profilMisAJour);
// { prenom: "Alice", age: 26, email: "alice@mail.com" }

// --- Rest parameters ---

// Rassemble les arguments restants dans un tableau
function afficherEquipe(capitaine, ...joueurs) {
    // Le premier argument va dans "capitaine"
    // Tous les arguments restants vont dans le tableau "joueurs"
    console.log(`Capitaine : ${capitaine}`);
    console.log(`Joueurs : ${joueurs.join(", ")}`);
    console.log(`Taille de l'equipe : ${joueurs.length + 1}`);
}
afficherEquipe("Alice", "Bob", "Charlie", "David", "Eve");
// Capitaine : Alice
// Joueurs : Bob, Charlie, David, Eve
// Taille de l'equipe : 5

// Fonction somme avec un nombre variable d'arguments
function sommeFlexible(...nombres) {
    // "nombres" est un tableau contenant tous les arguments
    return nombres.reduce((acc, n) => acc + n, 0);
}
console.log("Somme :", sommeFlexible(1, 2, 3)); // 6
console.log("Somme :", sommeFlexible(10, 20, 30, 40, 50)); // 150

// Destructuration avec rest
const [premier, deuxieme, ...reste] = [1, 2, 3, 4, 5];
console.log("Premier :", premier); // 1
console.log("Deuxieme :", deuxieme); // 2
console.log("Reste :", reste); // [3, 4, 5]


// ############################################################################
// SECTION 11 : FETCH API
// ############################################################################

console.log("\n--- FETCH API ---");

// --- Methode 1 : avec .then() (Promesses) ---
function chargerUtilisateursAvecThen() {
    console.log("Chargement avec .then()...");

    fetch("https://jsonplaceholder.typicode.com/users") // Fait une requete GET
        .then(response => {
            // Le premier .then recoit la reponse brute du serveur
            if (!response.ok) {
                // Verifie si la reponse est un succes (statut 200-299)
                throw new Error(`Erreur HTTP : ${response.status}`);
            }
            return response.json(); // Convertit la reponse en JSON (retourne une promesse)
        })
        .then(utilisateurs => {
            // Le deuxieme .then recoit les donnees converties
            console.log(`${utilisateurs.length} utilisateurs charges :`);
            utilisateurs.slice(0, 3).forEach(user => {
                // Affiche les 3 premiers utilisateurs
                console.log(`  - ${user.name} (${user.email})`);
            });
        })
        .catch(erreur => {
            // .catch attrape toutes les erreurs de la chaine
            console.error("Erreur lors du chargement :", erreur.message);
        });
}

// --- Methode 2 : avec async/await (recommandee) ---
async function chargerUtilisateursAsync() {
    // "async" permet d'utiliser "await" dans la fonction
    console.log("Chargement avec async/await...");

    try {
        const response = await fetch("https://jsonplaceholder.typicode.com/users");
        // "await" attend que la promesse soit resolue
        // Le code "pause" ici jusqu'a recevoir la reponse

        if (!response.ok) {
            throw new Error(`Erreur HTTP : ${response.status}`);
        }

        const utilisateurs = await response.json();
        // Attend la conversion JSON

        console.log(`${utilisateurs.length} utilisateurs charges :`);
        utilisateurs.slice(0, 3).forEach(user => {
            console.log(`  - ${user.name} (${user.email})`);
        });

        return utilisateurs; // On peut retourner les donnees
    } catch (erreur) {
        // try/catch gere les erreurs avec async/await
        console.error("Erreur :", erreur.message);
    }
}

// --- Charger un utilisateur specifique ---
async function chargerUtilisateur(id) {
    try {
        const response = await fetch(`https://jsonplaceholder.typicode.com/users/${id}`);
        // On insere l'id dans l'URL avec un template literal

        if (!response.ok) {
            throw new Error("Utilisateur non trouve");
        }

        const utilisateur = await response.json();
        console.log("Utilisateur charge :", utilisateur.name);
        return utilisateur;
    } catch (erreur) {
        console.error("Erreur :", erreur.message);
    }
}

// Decommentez pour tester (necessite une connexion internet) :
// chargerUtilisateursAvecThen();
// chargerUtilisateursAsync();
// chargerUtilisateur(1);


// ############################################################################
// SECTION 12 : LOCALSTORAGE
// ############################################################################

console.log("\n--- LOCALSTORAGE ---");

// NOTA : localStorage fonctionne uniquement dans un navigateur

/*
// --- Stocker une valeur simple ---
localStorage.setItem("prenom", "Alice");
// Stocke la paire cle="prenom", valeur="Alice"

localStorage.setItem("age", "25");
// Les valeurs sont TOUJOURS des chaines de caracteres

// --- Recuperer une valeur ---
const prenomSauve = localStorage.getItem("prenom");
console.log("Prenom sauve :", prenomSauve); // "Alice"

// --- Verifier si une donnee existe ---
const donneeInexistante = localStorage.getItem("cle_qui_nexiste_pas");
console.log("Donnee inexistante :", donneeInexistante); // null

if (donneeInexistante === null) {
    console.log("Aucune donnee pour cette cle");
}

// --- Supprimer une valeur ---
localStorage.removeItem("age");
// Supprime la paire cle-valeur "age"

// --- Stocker des donnees complexes (tableaux et objets) ---
// localStorage ne peut stocker que des chaines, donc on utilise JSON

const panier = [
    { nom: "T-shirt", prix: 19.99, quantite: 2 },
    { nom: "Jean", prix: 49.99, quantite: 1 },
    { nom: "Chaussures", prix: 89.99, quantite: 1 }
];

// Sauvegarder : on convertit en JSON avec JSON.stringify
localStorage.setItem("panier", JSON.stringify(panier));
// JSON.stringify transforme le tableau d'objets en chaine JSON

// Recuperer : on reconvertit en objet avec JSON.parse
const panierRecupere = JSON.parse(localStorage.getItem("panier"));
// JSON.parse transforme la chaine JSON en tableau d'objets JavaScript
console.log("Panier recupere :", panierRecupere);

// On peut maintenant utiliser le tableau normalement
panierRecupere.forEach(article => {
    console.log(`${article.nom} - ${article.prix}€ x${article.quantite}`);
});

// --- Vider tout le localStorage ---
// localStorage.clear(); // Supprime TOUTES les donnees stockees
*/


// ############################################################################
// SECTION 13 : GESTION DES ERREURS (TRY/CATCH)
// ############################################################################

console.log("\n--- GESTION DES ERREURS ---");

// --- try/catch basique ---
try {
    // Le code qui pourrait generer une erreur
    const donnees = JSON.parse("ceci n'est pas du JSON valide");
    // JSON.parse va echouer et lancer une SyntaxError
    console.log("Ce code ne sera jamais atteint");
} catch (erreur) {
    // Ce bloc s'execute UNIQUEMENT si une erreur se produit dans le try
    console.error("Erreur attrapee :", erreur.message);
    // erreur.message = le message d'erreur
    console.error("Type d'erreur :", erreur.name);
    // erreur.name = le nom du type d'erreur (SyntaxError, TypeError, etc.)
} finally {
    // Ce bloc s'execute TOUJOURS, qu'il y ait une erreur ou non
    console.log("Finally : ce code s'execute dans tous les cas");
}

// --- Lancer ses propres erreurs avec throw ---
function diviser(a, b) {
    if (typeof a !== "number" || typeof b !== "number") {
        throw new TypeError("Les parametres doivent etre des nombres");
        // TypeError pour les erreurs de type
    }
    if (b === 0) {
        throw new Error("Division par zero impossible !");
        // Error generique pour les autres erreurs
    }
    return a / b;
}

// Tester la fonction avec try/catch
try {
    console.log("10 / 2 =", diviser(10, 2)); // 5 (pas d'erreur)
    console.log("10 / 0 =", diviser(10, 0)); // Lance une erreur !
} catch (erreur) {
    console.error("Erreur :", erreur.message); // "Division par zero impossible !"
}

try {
    diviser("dix", 2); // Lance un TypeError
} catch (erreur) {
    console.error(`${erreur.name} : ${erreur.message}`);
    // "TypeError : Les parametres doivent etre des nombres"
}

// --- Validation de donnees ---
function validerInscription(nom, email, age) {
    const erreurs = []; // Tableau pour collecter les erreurs

    if (!nom || nom.trim().length < 2) {
        erreurs.push("Le nom doit contenir au moins 2 caracteres");
    }
    if (!email || !email.includes("@")) {
        erreurs.push("L'email doit contenir un @");
    }
    if (typeof age !== "number" || age < 0 || age > 150) {
        erreurs.push("L'age doit etre un nombre entre 0 et 150");
    }

    if (erreurs.length > 0) {
        // S'il y a des erreurs, on les lance toutes
        throw new Error(erreurs.join(" | "));
    }

    return { nom: nom.trim(), email: email.trim(), age };
}

try {
    const utilisateur = validerInscription("A", "pasdeemail", -5);
    console.log("Utilisateur valide :", utilisateur);
} catch (erreur) {
    console.error("Erreurs de validation :", erreur.message);
    // "Le nom doit contenir au moins 2 caracteres | L'email doit contenir un @ | L'age doit etre un nombre entre 0 et 150"
}

try {
    const utilisateur = validerInscription("Alice Dupont", "alice@email.com", 25);
    console.log("Utilisateur valide :", utilisateur);
    // { nom: "Alice Dupont", email: "alice@email.com", age: 25 }
} catch (erreur) {
    console.error("Erreurs :", erreur.message);
}


// ############################################################################
// SECTION 14 : MINI-PROJET - APPLICATION TODO LIST
// ############################################################################

// Ce mini-projet combine : DOM, evenements, tableaux, objets, localStorage
// Il cree une todo list interactive et fonctionnelle

console.log("\n--- MINI-PROJET : TODO LIST ---");

/*
// ================================================================
// HTML necessaire pour ce projet (a mettre dans un fichier .html) :
// ================================================================
//
// <div id="app-todo">
//     <h1>Ma Todo List</h1>
//     <form id="formulaire-todo">
//         <input type="text" id="champ-tache" placeholder="Ajouter une tache...">
//         <button type="submit">Ajouter</button>
//     </form>
//     <div id="filtres">
//         <button class="filtre actif" data-filtre="toutes">Toutes</button>
//         <button class="filtre" data-filtre="actives">Actives</button>
//         <button class="filtre" data-filtre="terminees">Terminees</button>
//     </div>
//     <ul id="liste-taches"></ul>
//     <p id="compteur-taches"></p>
// </div>
// ================================================================

// --- Variables globales ---
let taches = []; // Tableau qui contiendra toutes les taches
let filtreActuel = "toutes"; // Le filtre actuellement actif

// --- Charger les taches depuis localStorage au demarrage ---
function chargerTaches() {
    // On essaie de recuperer les taches sauvegardees
    const tachesSauvegardees = localStorage.getItem("taches");

    if (tachesSauvegardees !== null) {
        // Si des taches existent dans localStorage
        taches = JSON.parse(tachesSauvegardees);
        // JSON.parse convertit la chaine JSON en tableau d'objets
    }
    // Si rien n'est sauvegarde, on garde le tableau vide
}

// --- Sauvegarder les taches dans localStorage ---
function sauvegarderTaches() {
    localStorage.setItem("taches", JSON.stringify(taches));
    // JSON.stringify convertit le tableau d'objets en chaine JSON
    // Les donnees persistent meme apres fermeture du navigateur
}

// --- Generer un identifiant unique ---
function genererID() {
    return Date.now().toString(36) + Math.random().toString(36).substr(2);
    // Combine le timestamp et un nombre aleatoire pour un ID unique
}

// --- Ajouter une tache ---
function ajouterTache(texte) {
    // Verifie que le texte n'est pas vide
    if (texte.trim() === "") {
        return; // On sort de la fonction si le texte est vide
    }

    // Cree un objet tache avec toutes les proprietes necessaires
    const nouvelleTache = {
        id: genererID(), // Identifiant unique
        texte: texte.trim(), // Le texte de la tache (sans espaces en trop)
        terminee: false, // Par defaut, la tache n'est pas terminee
        dateCreation: new Date().toLocaleDateString("fr-FR") // Date de creation
    };

    taches.push(nouvelleTache); // Ajoute au tableau
    sauvegarderTaches(); // Sauvegarde dans localStorage
    afficherTaches(); // Met a jour l'affichage
}

// --- Basculer l'etat d'une tache (terminee / non terminee) ---
function basculerTache(id) {
    // Trouve la tache par son id
    const tache = taches.find(t => t.id === id);

    if (tache) {
        tache.terminee = !tache.terminee;
        // Inverse le booleen : true devient false, false devient true
        sauvegarderTaches();
        afficherTaches();
    }
}

// --- Supprimer une tache ---
function supprimerTache(id) {
    // Filtre le tableau pour garder toutes les taches SAUF celle avec cet id
    taches = taches.filter(t => t.id !== id);
    sauvegarderTaches();
    afficherTaches();
}

// --- Filtrer les taches ---
function obtenirTachesFiltrees() {
    switch (filtreActuel) {
        case "actives":
            // Retourne seulement les taches non terminees
            return taches.filter(t => !t.terminee);
        case "terminees":
            // Retourne seulement les taches terminees
            return taches.filter(t => t.terminee);
        default:
            // Par defaut ("toutes"), retourne tout
            return taches;
    }
}

// --- Afficher les taches dans le DOM ---
function afficherTaches() {
    const listeTaches = document.querySelector("#liste-taches");
    const compteur = document.querySelector("#compteur-taches");

    // Vider la liste actuelle
    listeTaches.innerHTML = "";

    // Obtenir les taches filtrees
    const tachesFiltrees = obtenirTachesFiltrees();

    if (tachesFiltrees.length === 0) {
        // Si aucune tache a afficher
        listeTaches.innerHTML = "<li class='vide'>Aucune tache a afficher</li>";
    } else {
        // Creer un element <li> pour chaque tache
        tachesFiltrees.forEach(tache => {
            const li = document.createElement("li");
            // Cree un element <li>

            li.className = tache.terminee ? "tache terminee" : "tache";
            // Ajoute la classe "terminee" si la tache est terminee

            li.innerHTML = `
                <input type="checkbox" ${tache.terminee ? "checked" : ""}>
                <span class="texte-tache">${tache.texte}</span>
                <span class="date-tache">${tache.dateCreation}</span>
                <button class="btn-supprimer" data-id="${tache.id}">✕</button>
            `;
            // Construit le HTML de la tache avec :
            // - Une checkbox (cochee si terminee)
            // - Le texte de la tache
            // - La date de creation
            // - Un bouton supprimer avec l'id dans un data-attribute

            // Ecouteur pour la checkbox (basculer l'etat)
            const checkbox = li.querySelector("input[type='checkbox']");
            checkbox.addEventListener("change", () => {
                basculerTache(tache.id);
            });

            // Ecouteur pour le bouton supprimer
            const btnSupprimer = li.querySelector(".btn-supprimer");
            btnSupprimer.addEventListener("click", () => {
                supprimerTache(tache.id);
            });

            listeTaches.appendChild(li);
            // Ajoute le <li> dans le <ul>
        });
    }

    // Mettre a jour le compteur
    const tachesActives = taches.filter(t => !t.terminee).length;
    // Compte les taches non terminees
    compteur.textContent = `${tachesActives} tache(s) restante(s) sur ${taches.length}`;
}

// --- Initialisation de l'application ---
document.addEventListener("DOMContentLoaded", () => {
    // Ce code s'execute quand le HTML est pret

    // Charger les taches sauvegardees
    chargerTaches();

    // Afficher les taches
    afficherTaches();

    // Ecouteur sur le formulaire d'ajout
    const formulaire = document.querySelector("#formulaire-todo");
    const champTache = document.querySelector("#champ-tache");

    formulaire.addEventListener("submit", (e) => {
        e.preventDefault(); // Empeche le rechargement de la page
        ajouterTache(champTache.value); // Ajoute la tache
        champTache.value = ""; // Vide le champ
        champTache.focus(); // Remet le focus sur le champ
    });

    // Ecouteurs sur les boutons de filtre
    const boutonsFiltre = document.querySelectorAll(".filtre");
    boutonsFiltre.forEach(bouton => {
        bouton.addEventListener("click", () => {
            // Retirer la classe "actif" de tous les boutons
            boutonsFiltre.forEach(b => b.classList.remove("actif"));
            // Ajouter la classe "actif" au bouton clique
            bouton.classList.add("actif");
            // Mettre a jour le filtre actuel
            filtreActuel = bouton.dataset.filtre;
            // dataset.filtre lit la valeur de l'attribut data-filtre
            afficherTaches(); // Reafficher les taches avec le nouveau filtre
        });
    });

    // Ecouteur clavier : ajouter en appuyant sur Entree dans le champ
    champTache.addEventListener("keydown", (e) => {
        if (e.key === "Enter") {
            // Si la touche Entree est pressee
            e.preventDefault();
            formulaire.dispatchEvent(new Event("submit"));
            // Declenche l'evenement submit du formulaire
        }
    });

    console.log("Application Todo List initialisee !");
});
*/

// ============================================================================
// FIN DU FICHIER DE DEMONSTRATION
// ============================================================================

// Resume des concepts couverts :
// 1.  Variables (var, let, const)
// 2.  Types de donnees (string, number, boolean, null, undefined, array, object)
// 3.  Methodes des chaines (length, toUpperCase, toLowerCase, trim, includes, etc.)
// 4.  Conditions (if/else, switch, ternaire, falsy/truthy)
// 5.  Boucles (for, while, do...while, for...of, for...in, break, continue)
// 6.  Fonctions (declaration, expression, flechees, parametres par defaut, callbacks)
// 7.  Methodes des tableaux (push, pop, shift, unshift, splice, slice, etc.)
// 8.  Methodes de transformation (filter, map, forEach, reduce, sort, reverse)
// 9.  Objets (creation, acces, methodes, this, destructuration)
// 10. Manipulation du DOM (selection, modification, creation, suppression)
// 11. Evenements (addEventListener, click, submit, input, keydown, etc.)
// 12. Spread operator et rest parameters
// 13. Fetch API (avec .then et async/await)
// 14. LocalStorage (setItem, getItem, JSON.stringify, JSON.parse)
// 15. Gestion des erreurs (try/catch/finally, throw)
// 16. Mini-projet Todo List (combine DOM, evenements, tableaux, objets, localStorage)

console.log("\n=== Fin du cours JavaScript ===");
console.log("Decommentez les sections DOM, evenements et Todo List");
console.log("dans un fichier HTML pour les tester dans le navigateur !");
