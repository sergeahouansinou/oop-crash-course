# Cours complet : Programmation Orientee Objet (POO) en PHP 8+

---

## Table des matieres

1. [Introduction : Procedural vs Objet](#1-introduction--procedural-vs-objet)
2. [Classes et Objets](#2-classes-et-objets)
3. [Proprietes et Methodes](#3-proprietes-et-methodes)
4. [Le mot-cle $this](#4-le-mot-cle-this)
5. [Constructeur et Destructeur](#5-constructeur-et-destructeur)
6. [Promotion des proprietes dans le constructeur (PHP 8.0)](#6-promotion-des-proprietes-dans-le-constructeur-php-80)
7. [Encapsulation : public, protected, private](#7-encapsulation--public-protected-private)
8. [Getters et Setters](#8-getters-et-setters)
9. [Constantes de classe (const)](#9-constantes-de-classe-const)
10. [Proprietes et methodes statiques](#10-proprietes-et-methodes-statiques)
11. [Heritage (extends)](#11-heritage-extends)
12. [Classes et methodes abstraites](#12-classes-et-methodes-abstraites)
13. [Interfaces](#13-interfaces)
14. [Polymorphisme](#14-polymorphisme)
15. [Declarations de type (PHP 8)](#15-declarations-de-type-php-8)
16. [Arguments nommes (PHP 8.0)](#16-arguments-nommes-php-80)
17. [Expression match (PHP 8.0)](#17-expression-match-php-80)
18. [Les Namespaces (espaces de noms)](#18-les-namespaces-espaces-de-noms)
19. [Exercices progressifs](#19-exercices-progressifs)

---

## 1. Introduction : Procedural vs Objet

### La programmation procedurale

En programmation **procedurale**, on ecrit une suite d'instructions executees les unes apres les autres. Les donnees (variables) et les traitements (fonctions) sont separes.

```php
// Approche procedurale : les donnees et les fonctions sont separees
$nom = "Alice";
$age = 25;
$email = "alice@exemple.fr";

function afficherUtilisateur(string $nom, int $age, string $email): void
{
    echo "Nom : $nom, Age : $age, Email : $email\n";
}

afficherUtilisateur($nom, $age, $email);
```

**Probleme** : quand le projet grossit, on se retrouve avec des dizaines de variables et de fonctions eparpillees. C'est difficile a organiser et a maintenir.

### La programmation orientee objet (POO)

En POO, on **regroupe les donnees et les traitements** dans des structures appelees **classes**. Une classe est comme un plan de construction, et un **objet** est une realisation concrete de ce plan.

```php
// Approche objet : les donnees et les fonctions sont regroupees
class Utilisateur
{
    public string $nom;
    public int $age;
    public string $email;

    public function afficher(): void
    {
        echo "Nom : $this->nom, Age : $this->age, Email : $this->email\n";
    }
}

$alice = new Utilisateur();  // On cree un objet a partir de la classe
$alice->nom = "Alice";       // On definit ses proprietes
$alice->age = 25;
$alice->email = "alice@exemple.fr";
$alice->afficher();          // On appelle sa methode
```

**Avantages de la POO :**
- **Organisation** : le code est structure en blocs logiques
- **Reutilisabilite** : on peut reutiliser les classes dans d'autres projets
- **Maintenabilite** : modifier une classe n'affecte pas le reste du programme
- **Encapsulation** : on protege les donnees internes (on verra ca plus loin)

---

## 2. Classes et Objets

### Qu'est-ce qu'une classe ?

Une **classe** est un **modele** (un plan, un moule) qui definit :
- Des **proprietes** (les donnees, aussi appelees "attributs")
- Des **methodes** (les fonctions qui appartiennent a la classe)

Pensez a une classe comme un plan d'architecte pour une maison.

### Qu'est-ce qu'un objet ?

Un **objet** est une **instance** (une realisation concrete) d'une classe. A partir d'un seul plan, on peut construire plusieurs maisons differentes.

```php
// Definition d'une classe (le plan)
class Voiture
{
    public string $marque;
    public string $couleur;
    public int $vitesse = 0; // Valeur par defaut
}

// Creation d'objets (les realisations concretes)
$maVoiture = new Voiture();       // 1er objet
$maVoiture->marque = "Peugeot";
$maVoiture->couleur = "bleue";

$taVoiture = new Voiture();       // 2e objet, independant du premier
$taVoiture->marque = "Renault";
$taVoiture->couleur = "rouge";

echo $maVoiture->marque; // Affiche : Peugeot
echo $taVoiture->marque; // Affiche : Renault
```

**Points cles :**
- Le mot-cle `new` cree un nouvel objet a partir d'une classe
- Chaque objet a ses propres valeurs pour les proprietes
- On accede aux proprietes avec la fleche `->`

---

## 3. Proprietes et Methodes

### Les proprietes

Les **proprietes** sont les variables qui appartiennent a une classe. Elles decrivent les **caracteristiques** de l'objet.

```php
class Produit
{
    // Ce sont les proprietes (les caracteristiques du produit)
    public string $nom;
    public float $prix;
    public int $stock = 0; // On peut donner une valeur par defaut
}
```

### Les methodes

Les **methodes** sont les fonctions qui appartiennent a une classe. Elles decrivent les **comportements** de l'objet.

```php
class Produit
{
    public string $nom;
    public float $prix;
    public int $stock = 0;

    // Methode : un comportement du produit
    public function afficherPrix(): void
    {
        // void signifie que la methode ne retourne rien
        echo "Le prix de {$this->nom} est {$this->prix} EUR\n";
    }

    // Methode qui retourne une valeur
    public function calculerTTC(float $tva = 20.0): float
    {
        // float signifie que la methode retourne un nombre decimal
        return $this->prix * (1 + $tva / 100);
    }
}

$produit = new Produit();
$produit->nom = "Clavier";
$produit->prix = 49.99;

$produit->afficherPrix();                    // Affiche : Le prix de Clavier est 49.99 EUR
echo $produit->calculerTTC() . " EUR\n";     // Affiche : 59.988 EUR
echo $produit->calculerTTC(5.5) . " EUR\n";  // Affiche : 52.73945 EUR (TVA a 5.5%)
```

---

## 4. Le mot-cle $this

`$this` est une **reference a l'objet en cours**. Quand on est a l'interieur d'une methode, `$this` permet d'acceder aux proprietes et aux autres methodes de **ce meme objet**.

```php
class Compteur
{
    public int $valeur = 0;

    public function incrementer(): void
    {
        // $this->valeur designe la propriete "valeur" de CET objet
        $this->valeur++;
    }

    public function afficher(): void
    {
        // On peut aussi appeler d'autres methodes avec $this
        echo "Valeur actuelle : {$this->valeur}\n";
    }

    public function incrementerEtAfficher(): void
    {
        $this->incrementer(); // Appel a une autre methode du meme objet
        $this->afficher();    // Appel a une autre methode du meme objet
    }
}

$compteur1 = new Compteur();
$compteur1->incrementer();          // valeur passe a 1
$compteur1->incrementer();          // valeur passe a 2
$compteur1->afficher();             // Affiche : Valeur actuelle : 2

$compteur2 = new Compteur();
$compteur2->incrementerEtAfficher(); // Affiche : Valeur actuelle : 1
// $compteur2 a sa propre valeur, independante de $compteur1
```

**Regle simple** : a l'interieur d'une classe, utilisez toujours `$this->` pour acceder aux proprietes et methodes de l'objet.

---

## 5. Constructeur et Destructeur

### Le constructeur : __construct()

Le **constructeur** est une methode speciale qui est **automatiquement appelee** quand on cree un objet avec `new`. Il sert a **initialiser** l'objet.

```php
class Utilisateur
{
    public string $nom;
    public int $age;

    // Le constructeur est appele automatiquement par "new"
    public function __construct(string $nom, int $age)
    {
        // On affecte les parametres aux proprietes de l'objet
        $this->nom = $nom;
        $this->age = $age;
        echo "Objet Utilisateur cree : {$this->nom}\n";
    }
}

// Le constructeur est appele ici automatiquement
$alice = new Utilisateur("Alice", 25);
// Affiche : Objet Utilisateur cree : Alice

$bob = new Utilisateur("Bob", 30);
// Affiche : Objet Utilisateur cree : Bob

echo $alice->nom; // Affiche : Alice
echo $bob->age;   // Affiche : 30
```

### Le destructeur : __destruct()

Le **destructeur** est appele **automatiquement** quand l'objet est detruit (en fin de script ou quand on utilise `unset()`). Il est rarement utilise en pratique, mais utile pour liberer des ressources.

```php
class FichierLog
{
    private string $chemin;

    public function __construct(string $chemin)
    {
        $this->chemin = $chemin;
        echo "Ouverture du fichier log : {$this->chemin}\n";
    }

    public function __destruct()
    {
        // Appele automatiquement a la destruction de l'objet
        echo "Fermeture du fichier log : {$this->chemin}\n";
    }
}

$log = new FichierLog("/var/log/app.log");
// Affiche : Ouverture du fichier log : /var/log/app.log

// ... utilisation du log ...

unset($log);
// Affiche : Fermeture du fichier log : /var/log/app.log
```

---

## 6. Promotion des proprietes dans le constructeur (PHP 8.0)

PHP 8.0 introduit la **promotion des proprietes** : on peut declarer et initialiser les proprietes **directement dans les parametres** du constructeur. Cela evite d'ecrire du code repetitif.

### Avant PHP 8.0 (maniere classique)

```php
class Produit
{
    private string $nom;
    private float $prix;
    private int $stock;

    public function __construct(string $nom, float $prix, int $stock = 0)
    {
        $this->nom = $nom;     // Ligne repetitive
        $this->prix = $prix;   // Ligne repetitive
        $this->stock = $stock; // Ligne repetitive
    }
}
```

### Avec PHP 8.0 (promotion du constructeur)

```php
class Produit
{
    // Les proprietes sont declarees ET initialisees directement dans le constructeur
    public function __construct(
        private string $nom,     // "private" devant le parametre = declaration de propriete
        private float $prix,
        private int $stock = 0   // On peut toujours avoir des valeurs par defaut
    ) {
        // Le corps du constructeur peut etre vide !
        // PHP affecte automatiquement les valeurs aux proprietes
    }

    public function afficher(): void
    {
        echo "{$this->nom} - {$this->prix} EUR (stock: {$this->stock})\n";
    }
}

$produit = new Produit("Souris", 29.99, 50);
$produit->afficher(); // Affiche : Souris - 29.99 EUR (stock: 50)
```

**Ce qui se passe** : en mettant `private`, `protected` ou `public` devant un parametre du constructeur, PHP cree automatiquement la propriete correspondante et lui affecte la valeur. Plus besoin de `$this->nom = $nom;` !

---

## 7. Encapsulation : public, protected, private

L'**encapsulation** est un principe fondamental de la POO. Il consiste a **controler l'acces** aux proprietes et methodes d'une classe grace aux **modificateurs de visibilite**.

| Visibilite   | Depuis la classe | Depuis une classe enfant | Depuis l'exterieur |
|-------------|:----------------:|:------------------------:|:------------------:|
| `public`    | Oui              | Oui                      | Oui                |
| `protected` | Oui              | Oui                      | Non                |
| `private`   | Oui              | Non                      | Non                |

```php
class CompteBancaire
{
    public string $titulaire;      // Accessible partout
    protected string $banque;      // Accessible dans cette classe et ses enfants
    private float $solde = 0.0;    // Accessible UNIQUEMENT dans cette classe

    public function __construct(string $titulaire, string $banque, float $soldeInitial)
    {
        $this->titulaire = $titulaire;
        $this->banque = $banque;
        $this->solde = $soldeInitial;
    }

    // Methode publique : accessible de l'exterieur
    public function getSolde(): float
    {
        return $this->solde;
    }

    // Methode privee : accessible UNIQUEMENT dans cette classe
    private function verifierFonds(float $montant): bool
    {
        return $this->solde >= $montant;
    }

    public function retirer(float $montant): bool
    {
        // On peut appeler la methode privee depuis la meme classe
        if ($this->verifierFonds($montant)) {
            $this->solde -= $montant;
            return true;
        }
        return false;
    }
}

$compte = new CompteBancaire("Alice", "BNP", 1000.0);

echo $compte->titulaire;        // OK : public
// echo $compte->banque;         // ERREUR : protected
// echo $compte->solde;          // ERREUR : private
echo $compte->getSolde();       // OK : on passe par une methode publique
// $compte->verifierFonds(100);  // ERREUR : methode privee
```

**Pourquoi encapsuler ?**
- On **protege** les donnees sensibles (comme un solde bancaire)
- On **controle** comment les donnees sont modifiees (via des methodes)
- On peut **changer l'implementation interne** sans affecter le code exterieur

---

## 8. Getters et Setters

Les **getters** (accesseurs) et **setters** (mutateurs) sont des methodes publiques qui permettent de **lire** et **modifier** des proprietes privees de maniere controlee.

```php
class Utilisateur
{
    public function __construct(
        private string $nom,
        private string $email,
        private int $age
    ) {}

    // GETTER : permet de LIRE la propriete privee $nom
    public function getNom(): string
    {
        return $this->nom;
    }

    // SETTER : permet de MODIFIER la propriete privee $nom
    public function setNom(string $nom): void
    {
        // On peut ajouter de la VALIDATION
        if (strlen($nom) < 2) {
            throw new InvalidArgumentException("Le nom doit contenir au moins 2 caracteres.");
        }
        $this->nom = $nom;
    }

    // GETTER pour l'email
    public function getEmail(): string
    {
        return $this->email;
    }

    // SETTER pour l'email avec validation
    public function setEmail(string $email): void
    {
        // On valide le format de l'email avant de l'accepter
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("L'email '$email' n'est pas valide.");
        }
        $this->email = $email;
    }

    // GETTER pour l'age
    public function getAge(): int
    {
        return $this->age;
    }

    // SETTER pour l'age avec validation
    public function setAge(int $age): void
    {
        if ($age < 0 || $age > 150) {
            throw new InvalidArgumentException("L'age doit etre entre 0 et 150.");
        }
        $this->age = $age;
    }
}

$user = new Utilisateur("Alice", "alice@exemple.fr", 25);

echo $user->getNom();   // Affiche : Alice
$user->setNom("Bob");   // Modifie le nom
echo $user->getNom();   // Affiche : Bob

// $user->setNom("A");  // Lancerait une exception : nom trop court
// $user->setEmail("invalide"); // Lancerait une exception : email invalide
```

**L'interet des setters** : ils permettent de **valider les donnees** avant de les enregistrer. Sans eux, n'importe qui pourrait mettre n'importe quelle valeur dans les proprietes.

---

## 9. Constantes de classe (const)

Les **constantes de classe** sont des valeurs qui **ne changent jamais**. On les definit avec le mot-cle `const`.

```php
class MathUtils
{
    // Les constantes sont ecrites en MAJUSCULES par convention
    public const PI = 3.14159265358979;
    public const E = 2.71828182845905;

    public function perimetre(float $rayon): float
    {
        // On accede a une constante avec self:: (et non $this->)
        return 2 * self::PI * $rayon;
    }
}

// Depuis l'exterieur, on accede avec NomDeLaClasse::NOM_CONSTANTE
echo MathUtils::PI;    // Affiche : 3.14159265358979

$math = new MathUtils();
echo $math->perimetre(5); // Affiche : 31.4159265358979
```

**Differences avec les proprietes :**
- Les constantes n'ont **pas de `$`** devant leur nom
- Elles sont definies avec `const` (pas `public string $...`)
- On y accede avec `self::` (dans la classe) ou `NomClasse::` (a l'exterieur)
- Leur valeur est **fixee definitivement** et ne peut pas etre modifiee

---

## 10. Proprietes et methodes statiques

Les elements **statiques** appartiennent a **la classe elle-meme**, pas a un objet particulier. On n'a pas besoin de creer un objet pour les utiliser.

```php
class Compteur
{
    // Propriete statique : partagee par TOUS les objets de la classe
    private static int $nombreInstances = 0;

    public function __construct(
        private string $nom
    ) {
        // On accede a une propriete statique avec self::
        self::$nombreInstances++;
        echo "Creation de '$this->nom'. Total : " . self::$nombreInstances . "\n";
    }

    // Methode statique : on peut l'appeler SANS creer d'objet
    public static function getNombreInstances(): int
    {
        return self::$nombreInstances;
    }
}

// Appel de la methode statique SANS creer d'objet
echo Compteur::getNombreInstances(); // Affiche : 0

$a = new Compteur("A"); // Affiche : Creation de 'A'. Total : 1
$b = new Compteur("B"); // Affiche : Creation de 'B'. Total : 2

echo Compteur::getNombreInstances(); // Affiche : 2
```

**Quand utiliser le statique ?**
- Pour des **compteurs** partages entre tous les objets
- Pour des **methodes utilitaires** qui n'ont pas besoin d'un objet (ex : `MathUtils::arrondir()`)
- Pour des **fabriques** (factory methods) qui creent des objets

**Attention** : dans une methode statique, on ne peut **pas** utiliser `$this` (car il n'y a pas d'objet).

---

## 11. Heritage (extends)

L'**heritage** permet a une classe (classe **enfant**) de **reprendre** toutes les proprietes et methodes d'une autre classe (classe **parent**). La classe enfant peut ensuite ajouter ses propres elements ou **redefinir** ceux du parent.

```php
// Classe parent (aussi appelee "classe de base" ou "superclasse")
class Animal
{
    public function __construct(
        protected string $nom,    // protected = accessible dans les classes enfants
        protected int $age
    ) {}

    public function sePresenter(): string
    {
        return "Je suis {$this->nom}, j'ai {$this->age} ans.";
    }

    public function parler(): string
    {
        return "...";
    }
}

// Classe enfant : "extends" signifie "herite de"
class Chien extends Animal
{
    public function __construct(
        string $nom,
        int $age,
        private string $race   // Propriete specifique au Chien
    ) {
        // parent:: appelle le constructeur de la classe parent
        parent::__construct($nom, $age);
    }

    // REDEFINITION (override) : on remplace la methode du parent
    public function parler(): string
    {
        return "Wouf ! Wouf !";
    }

    // Methode specifique au Chien
    public function getRace(): string
    {
        return $this->race;
    }
}

class Chat extends Animal
{
    // On redefinit seulement la methode parler()
    public function parler(): string
    {
        return "Miaou !";
    }
}

$chien = new Chien("Rex", 5, "Berger Allemand");
echo $chien->sePresenter(); // Affiche : Je suis Rex, j'ai 5 ans. (herite du parent)
echo $chien->parler();      // Affiche : Wouf ! Wouf ! (methode redefinie)
echo $chien->getRace();     // Affiche : Berger Allemand (methode propre au Chien)

$chat = new Chat("Minou", 3);
echo $chat->sePresenter();  // Affiche : Je suis Minou, j'ai 3 ans.
echo $chat->parler();       // Affiche : Miaou !
```

**Points cles :**
- `extends` = "herite de"
- `parent::` = appeler une methode de la classe parent
- La classe enfant **herite** de tout ce qui est `public` ou `protected`
- Elle peut **redefinir** (override) les methodes du parent
- Elle peut ajouter ses **propres proprietes et methodes**

---

## 12. Classes et methodes abstraites

Une **classe abstraite** est une classe qui **ne peut pas etre instanciee** directement. Elle sert de **modele** pour d'autres classes. Une **methode abstraite** est une methode **sans corps** (sans implementation) : les classes enfants **doivent** l'implementer.

```php
// Le mot-cle "abstract" interdit de faire "new Forme()"
abstract class Forme
{
    public function __construct(
        protected string $couleur
    ) {}

    // Methode abstraite : PAS de corps, juste la signature
    // Les classes enfants DOIVENT l'implementer
    abstract public function calculerAire(): float;

    // Methode abstraite egalement
    abstract public function decrire(): string;

    // Methode normale (non abstraite) : elle a un corps
    // Les classes enfants en heritent directement
    public function getCouleur(): string
    {
        return $this->couleur;
    }
}

class Cercle extends Forme
{
    public function __construct(
        string $couleur,
        private float $rayon
    ) {
        parent::__construct($couleur);
    }

    // OBLIGATOIRE : on implemente la methode abstraite
    public function calculerAire(): float
    {
        return M_PI * $this->rayon ** 2;
    }

    public function decrire(): string
    {
        return "Cercle de rayon {$this->rayon} et de couleur {$this->couleur}";
    }
}

class Rectangle extends Forme
{
    public function __construct(
        string $couleur,
        private float $largeur,
        private float $hauteur
    ) {
        parent::__construct($couleur);
    }

    public function calculerAire(): float
    {
        return $this->largeur * $this->hauteur;
    }

    public function decrire(): string
    {
        return "Rectangle {$this->largeur}x{$this->hauteur} de couleur {$this->couleur}";
    }
}

// $forme = new Forme("rouge"); // ERREUR : on ne peut pas instancier une classe abstraite

$cercle = new Cercle("bleu", 5);
echo $cercle->decrire();       // Affiche : Cercle de rayon 5 et de couleur bleu
echo $cercle->calculerAire();  // Affiche : 78.539816339745

$rect = new Rectangle("vert", 4, 6);
echo $rect->decrire();        // Affiche : Rectangle 4x6 de couleur vert
echo $rect->calculerAire();   // Affiche : 24
```

**Quand utiliser une classe abstraite ?**
- Quand on veut **forcer** les classes enfants a implementer certaines methodes
- Quand on veut **partager du code commun** (methodes non abstraites) entre plusieurs classes

---

## 13. Interfaces

Une **interface** est un **contrat** : elle definit des methodes que les classes **doivent** implementer, mais ne contient **aucun code**. Contrairement a l'heritage (une seule classe parent), une classe peut implementer **plusieurs interfaces**.

```php
// Definir une interface avec le mot-cle "interface"
interface Affichable
{
    // Toutes les methodes d'une interface sont implicitement publiques et abstraites
    public function afficher(): string;
}

interface Exportable
{
    public function exporterEnJson(): string;
    public function exporterEnCsv(): string;
}

// Une classe peut implementer PLUSIEURS interfaces (separer par des virgules)
class Produit implements Affichable, Exportable
{
    public function __construct(
        private string $nom,
        private float $prix
    ) {}

    // OBLIGATOIRE : implementer toutes les methodes de Affichable
    public function afficher(): string
    {
        return "{$this->nom} - {$this->prix} EUR";
    }

    // OBLIGATOIRE : implementer toutes les methodes de Exportable
    public function exporterEnJson(): string
    {
        return json_encode(['nom' => $this->nom, 'prix' => $this->prix]);
    }

    public function exporterEnCsv(): string
    {
        return "{$this->nom};{$this->prix}";
    }
}

// On peut utiliser l'interface comme type dans les parametres de fonction
function afficherElement(Affichable $element): void
{
    echo $element->afficher() . "\n";
}

$produit = new Produit("Clavier", 49.99);
afficherElement($produit); // Affiche : Clavier - 49.99 EUR

// N'importe quelle classe implementant Affichable peut etre passee
```

**Difference entre interface et classe abstraite :**

| Critere                  | Interface                      | Classe abstraite              |
|--------------------------|-------------------------------|-------------------------------|
| Methodes avec du code    | Non (sauf depuis PHP 8.0)     | Oui                           |
| Proprietes               | Non (sauf constantes)         | Oui                           |
| Heritage multiple        | Oui (plusieurs interfaces)    | Non (une seule classe parent) |
| Mot-cle                  | `implements`                  | `extends`                     |

---

## 14. Polymorphisme

Le **polymorphisme** signifie "plusieurs formes". C'est la capacite de **traiter des objets differents de la meme maniere**, tant qu'ils partagent une interface ou une classe parent commune.

### Exemple concret : systeme de paiement

```php
// Interface commune a tous les moyens de paiement
interface MoyenDePaiement
{
    public function payer(float $montant): bool;
    public function getNom(): string;
}

// Premiere implementation : carte bancaire
class CarteBancaire implements MoyenDePaiement
{
    public function __construct(
        private string $numeroCarte
    ) {}

    public function payer(float $montant): bool
    {
        echo "Paiement de {$montant} EUR par carte ({$this->numeroCarte})\n";
        return true; // Simulation : le paiement reussit toujours
    }

    public function getNom(): string
    {
        return "Carte Bancaire";
    }
}

// Deuxieme implementation : PayPal
class PayPal implements MoyenDePaiement
{
    public function __construct(
        private string $email
    ) {}

    public function payer(float $montant): bool
    {
        echo "Paiement de {$montant} EUR via PayPal ({$this->email})\n";
        return true;
    }

    public function getNom(): string
    {
        return "PayPal";
    }
}

// Troisieme implementation : virement bancaire
class VirementBancaire implements MoyenDePaiement
{
    public function __construct(
        private string $iban
    ) {}

    public function payer(float $montant): bool
    {
        echo "Virement de {$montant} EUR vers ({$this->iban})\n";
        return true;
    }

    public function getNom(): string
    {
        return "Virement Bancaire";
    }
}

// POLYMORPHISME : cette fonction accepte N'IMPORTE QUEL moyen de paiement
// Elle ne sait pas (et n'a pas besoin de savoir) lequel c'est !
function effectuerPaiement(MoyenDePaiement $moyen, float $montant): void
{
    echo "Tentative de paiement avec : {$moyen->getNom()}\n";

    if ($moyen->payer($montant)) {
        echo "Paiement reussi !\n";
    } else {
        echo "Paiement echoue.\n";
    }
}

// On peut passer n'importe quel objet qui implemente MoyenDePaiement
$carte = new CarteBancaire("4242-XXXX-XXXX-1234");
$paypal = new PayPal("alice@exemple.fr");
$virement = new VirementBancaire("FR76 1234 5678 9012");

effectuerPaiement($carte, 59.99);
effectuerPaiement($paypal, 29.99);
effectuerPaiement($virement, 149.99);

// On peut aussi les mettre dans un tableau et les parcourir
$moyens = [$carte, $paypal, $virement];
foreach ($moyens as $moyen) {
    effectuerPaiement($moyen, 10.00);
}
```

**Pourquoi le polymorphisme est puissant :**
- La fonction `effectuerPaiement()` fonctionne avec **n'importe quel** moyen de paiement
- Pour ajouter un nouveau moyen (ex: Bitcoin), il suffit de creer une nouvelle classe qui implemente `MoyenDePaiement`
- **Aucune modification** du code existant n'est necessaire (principe Ouvert/Ferme)

---

## 15. Declarations de type (PHP 8)

PHP 8 renforce le **typage** : on peut specifier le type des proprietes, des parametres et des valeurs de retour.

### Types de base

```php
class Exemple
{
    // Proprietes typees (PHP 7.4+)
    public string $texte;
    public int $entier;
    public float $decimal;
    public bool $actif;
    public array $liste;
}
```

### Types de retour

```php
class Calculatrice
{
    // ": float" indique que la methode retourne un float
    public function additionner(float $a, float $b): float
    {
        return $a + $b;
    }

    // ": void" indique que la methode ne retourne RIEN
    public function afficher(string $message): void
    {
        echo $message . "\n";
    }

    // ": self" indique que la methode retourne un objet de cette meme classe
    public function copier(): self
    {
        return clone $this;
    }
}
```

### Types union (PHP 8.0)

Les **types union** permettent d'accepter **plusieurs types** pour un meme parametre.

```php
class Conteneur
{
    // La propriete peut etre soit un string, soit un int
    private string|int $identifiant;

    // Le parametre accepte un int ou un float
    public function setValeur(int|float $valeur): void
    {
        echo "Valeur : $valeur\n";
    }

    // La methode peut retourner un string ou null
    public function trouver(int $id): ?string
    {
        // Le "?" devant le type est un raccourci pour "string|null"
        return $id > 0 ? "Element #$id" : null;
    }
}
```

### Type nullable

```php
class Profil
{
    // Le "?" signifie que la propriete peut aussi valoir null
    private ?string $biographie = null;

    public function setBiographie(?string $bio): void
    {
        $this->biographie = $bio;
    }

    public function getBiographie(): ?string
    {
        return $this->biographie;
    }
}
```

---

## 16. Arguments nommes (PHP 8.0)

Les **arguments nommes** permettent de passer les parametres **dans n'importe quel ordre** en les nommant.

```php
class Email
{
    public function __construct(
        private string $destinataire,
        private string $sujet,
        private string $corps,
        private string $expediteur = "noreply@exemple.fr",
        private bool $html = false,
        private int $priorite = 3
    ) {}

    public function afficher(): void
    {
        echo "De: {$this->expediteur}\n";
        echo "A: {$this->destinataire}\n";
        echo "Sujet: {$this->sujet}\n";
        echo "Priorite: {$this->priorite}\n";
        echo "HTML: " . ($this->html ? "Oui" : "Non") . "\n";
    }
}

// Sans arguments nommes : on doit respecter l'ordre et passer tous les parametres
$email1 = new Email("alice@exemple.fr", "Bonjour", "Contenu du mail", "bob@exemple.fr", true, 1);

// Avec arguments nommes : on choisit ce qu'on passe et dans l'ordre qu'on veut
$email2 = new Email(
    sujet: "Urgent",                      // 2e parametre passe en premier
    destinataire: "alice@exemple.fr",     // 1er parametre passe en second
    corps: "Message important",
    priorite: 1,                          // On saute les parametres par defaut qu'on ne veut pas changer
    html: true
);
// "expediteur" gardera sa valeur par defaut : "noreply@exemple.fr"

$email2->afficher();
```

**Avantages des arguments nommes :**
- Le code est plus **lisible** (on sait a quoi correspond chaque valeur)
- On peut **sauter des parametres optionnels** sans passer les precedents
- L'**ordre n'a plus d'importance**

---

## 17. Expression match (PHP 8.0)

L'expression `match` est une version amelioree de `switch`. Elle est plus courte, plus stricte et **retourne une valeur**.

```php
class Traducteur
{
    public function traduireJour(string $jour): string
    {
        // match compare avec === (comparaison stricte, pas ==)
        // et retourne directement une valeur
        return match($jour) {
            'monday'    => 'lundi',
            'tuesday'   => 'mardi',
            'wednesday' => 'mercredi',
            'thursday'  => 'jeudi',
            'friday'    => 'vendredi',
            'saturday'  => 'samedi',
            'sunday'    => 'dimanche',
            default     => 'jour inconnu', // Obligatoire si tous les cas ne sont pas couverts
        };
    }

    public function categoriserAge(int $age): string
    {
        // match avec des conditions (match(true) + expressions booleennes)
        return match(true) {
            $age < 0      => 'age invalide',
            $age < 3      => 'bebe',
            $age < 12     => 'enfant',
            $age < 18     => 'adolescent',
            $age < 65     => 'adulte',
            default       => 'senior',
        };
    }
}

$traducteur = new Traducteur();
echo $traducteur->traduireJour('friday');    // Affiche : vendredi
echo $traducteur->categoriserAge(25);        // Affiche : adulte
echo $traducteur->categoriserAge(8);         // Affiche : enfant
```

**Differences entre match et switch :**

| Critere              | `switch`                  | `match`                    |
|---------------------|---------------------------|----------------------------|
| Comparaison          | `==` (laxiste)            | `===` (stricte)            |
| Retourne une valeur  | Non                       | Oui                        |
| Besoin de `break`    | Oui                       | Non                        |
| Cas non couvert      | Continue sans erreur      | Lance une erreur           |

---

## 18. Les Namespaces (espaces de noms)

Les **namespaces** (espaces de noms) permettent d'**organiser le code** et d'**eviter les conflits de noms** entre classes. C'est comme des dossiers pour vos classes.

### Le probleme sans namespaces

```php
// Fichier 1 : une classe Utilisateur pour la base de donnees
class Utilisateur { /* ... */ }

// Fichier 2 : une autre classe Utilisateur pour l'API
class Utilisateur { /* ... */ }

// ERREUR : deux classes avec le meme nom !
```

### La solution avec les namespaces

```php
// Fichier : src/Database/Utilisateur.php
namespace App\Database;     // On declare l'espace de noms

class Utilisateur
{
    public function sauvegarder(): void
    {
        echo "Sauvegarde en base de donnees\n";
    }
}
```

```php
// Fichier : src/Api/Utilisateur.php
namespace App\Api;          // Un espace de noms different

class Utilisateur
{
    public function envoyer(): void
    {
        echo "Envoi via l'API\n";
    }
}
```

```php
// Fichier : index.php
// On importe les classes avec "use"
use App\Database\Utilisateur as UtilisateurBdd;
use App\Api\Utilisateur as UtilisateurApi;

$userBdd = new UtilisateurBdd();
$userBdd->sauvegarder();

$userApi = new UtilisateurApi();
$userApi->envoyer();
```

**Points cles :**
- `namespace` se place **tout en haut** du fichier (premiere instruction)
- `use` importe une classe pour pouvoir l'utiliser avec son nom court
- `as` permet de donner un **alias** en cas de conflit de noms
- La convention est d'utiliser le format `Editeur\Projet\Dossier\Classe`

---

## 19. Exercices progressifs

### Exercice 1 : Classe basique (Debutant)

Creez une classe `Livre` avec :
- Des proprietes : `titre` (string), `auteur` (string), `pages` (int), `prix` (float)
- Un constructeur avec promotion des proprietes (PHP 8)
- Une methode `afficher()` qui retourne une chaine avec toutes les informations
- Une methode `estLong()` qui retourne `true` si le livre a plus de 300 pages

Testez en creant 2 livres et en affichant leurs informations.

<details>
<summary>Voir la solution</summary>

```php
<?php
declare(strict_types=1);

class Livre
{
    public function __construct(
        private string $titre,
        private string $auteur,
        private int $pages,
        private float $prix
    ) {}

    public function afficher(): string
    {
        return "{$this->titre} par {$this->auteur} ({$this->pages} pages) - {$this->prix} EUR";
    }

    public function estLong(): bool
    {
        return $this->pages > 300;
    }
}

$livre1 = new Livre("Le Petit Prince", "Saint-Exupery", 96, 7.50);
$livre2 = new Livre("Les Miserables", "Victor Hugo", 1900, 12.99);

echo $livre1->afficher() . "\n"; // Le Petit Prince par Saint-Exupery (96 pages) - 7.5 EUR
echo "Long ? " . ($livre1->estLong() ? "Oui" : "Non") . "\n"; // Non

echo $livre2->afficher() . "\n"; // Les Miserables par Victor Hugo (1900 pages) - 12.99 EUR
echo "Long ? " . ($livre2->estLong() ? "Oui" : "Non") . "\n"; // Oui
```

</details>

---

### Exercice 2 : Encapsulation et validation (Debutant-Intermediaire)

Creez une classe `Temperature` avec :
- Une propriete privee `celsius` (float)
- Un setter `setCelsius()` qui refuse les valeurs en dessous de -273.15 (zero absolu)
- Un getter `getCelsius()`
- Une methode `enFahrenheit()` qui convertit en Fahrenheit (formule : celsius * 9/5 + 32)
- Une methode `enKelvin()` qui convertit en Kelvin (formule : celsius + 273.15)
- Une methode statique `depuisFahrenheit(float $f): self` qui cree un objet a partir de Fahrenheit

<details>
<summary>Voir la solution</summary>

```php
<?php
declare(strict_types=1);

class Temperature
{
    private float $celsius;

    public function __construct(float $celsius)
    {
        $this->setCelsius($celsius);
    }

    public function setCelsius(float $celsius): void
    {
        if ($celsius < -273.15) {
            throw new InvalidArgumentException(
                "La temperature ne peut pas etre inferieure a -273.15°C (zero absolu)."
            );
        }
        $this->celsius = $celsius;
    }

    public function getCelsius(): float
    {
        return $this->celsius;
    }

    public function enFahrenheit(): float
    {
        return $this->celsius * 9 / 5 + 32;
    }

    public function enKelvin(): float
    {
        return $this->celsius + 273.15;
    }

    public static function depuisFahrenheit(float $f): self
    {
        $celsius = ($f - 32) * 5 / 9;
        return new self($celsius);
    }
}

$t1 = new Temperature(100);
echo "Celsius: {$t1->getCelsius()}\n";        // 100
echo "Fahrenheit: {$t1->enFahrenheit()}\n";    // 212
echo "Kelvin: {$t1->enKelvin()}\n";            // 373.15

$t2 = Temperature::depuisFahrenheit(32);
echo "Celsius: {$t2->getCelsius()}\n";         // 0

// $t3 = new Temperature(-300); // Lancerait une exception
```

</details>

---

### Exercice 3 : Heritage (Intermediaire)

Creez un systeme de personnages de jeu video :
1. Une classe abstraite `Personnage` avec :
   - Des proprietes : `nom`, `vie` (int, max 100), `attaque` (int)
   - Une methode `recevoirDegats(int $degats): void` qui reduit la vie (minimum 0)
   - Une methode `estVivant(): bool`
   - Une methode abstraite `competenceSpeciale(): string`
2. Une classe `Guerrier` (vie: 100, attaque: 15) avec une competence "Coup d'epee"
3. Une classe `Mage` (vie: 70, attaque: 25) avec une competence "Boule de feu"
4. Une classe `Archer` (vie: 80, attaque: 20) avec une competence "Pluie de fleches"

Faites combattre deux personnages !

<details>
<summary>Voir la solution</summary>

```php
<?php
declare(strict_types=1);

abstract class Personnage
{
    public function __construct(
        protected string $nom,
        protected int $vie,
        protected int $attaque
    ) {}

    public function recevoirDegats(int $degats): void
    {
        $this->vie = max(0, $this->vie - $degats);
        echo "{$this->nom} recoit $degats degats. Vie restante : {$this->vie}\n";
    }

    public function estVivant(): bool
    {
        return $this->vie > 0;
    }

    public function attaquer(Personnage $cible): void
    {
        echo "{$this->nom} attaque {$cible->nom} !\n";
        $cible->recevoirDegats($this->attaque);
    }

    abstract public function competenceSpeciale(): string;

    public function getNom(): string
    {
        return $this->nom;
    }
}

class Guerrier extends Personnage
{
    public function __construct(string $nom)
    {
        parent::__construct($nom, vie: 100, attaque: 15);
    }

    public function competenceSpeciale(): string
    {
        return "{$this->nom} utilise Coup d'epee ! (degats x2)";
    }
}

class Mage extends Personnage
{
    public function __construct(string $nom)
    {
        parent::__construct($nom, vie: 70, attaque: 25);
    }

    public function competenceSpeciale(): string
    {
        return "{$this->nom} lance une Boule de feu !";
    }
}

class Archer extends Personnage
{
    public function __construct(string $nom)
    {
        parent::__construct($nom, vie: 80, attaque: 20);
    }

    public function competenceSpeciale(): string
    {
        return "{$this->nom} declenche une Pluie de fleches !";
    }
}

$guerrier = new Guerrier("Arthur");
$mage = new Mage("Merlin");

echo $guerrier->competenceSpeciale() . "\n";
echo $mage->competenceSpeciale() . "\n";

$guerrier->attaquer($mage);
$mage->attaquer($guerrier);

while ($guerrier->estVivant() && $mage->estVivant()) {
    $guerrier->attaquer($mage);
    if ($mage->estVivant()) {
        $mage->attaquer($guerrier);
    }
}

$vainqueur = $guerrier->estVivant() ? $guerrier : $mage;
echo "{$vainqueur->getNom()} remporte le combat !\n";
```

</details>

---

### Exercice 4 : Interfaces et Polymorphisme (Intermediaire)

Creez un systeme de notification :
1. Une interface `Notifiable` avec une methode `envoyer(string $message): bool`
2. Une interface `Formatable` avec une methode `formater(string $message): string`
3. Une classe `NotificationEmail` qui implemente les deux interfaces
4. Une classe `NotificationSms` qui implemente les deux interfaces
5. Une classe `NotificationSlack` qui implemente les deux interfaces
6. Une fonction `notifierTous(array $notifieurs, string $message): void`

Chaque classe formate le message differemment et simule l'envoi.

<details>
<summary>Voir la solution</summary>

```php
<?php
declare(strict_types=1);

interface Notifiable
{
    public function envoyer(string $message): bool;
}

interface Formatable
{
    public function formater(string $message): string;
}

class NotificationEmail implements Notifiable, Formatable
{
    public function __construct(private string $adresse) {}

    public function formater(string $message): string
    {
        return "[EMAIL] $message";
    }

    public function envoyer(string $message): bool
    {
        $messageFormate = $this->formater($message);
        echo "Envoi a {$this->adresse} : $messageFormate\n";
        return true;
    }
}

class NotificationSms implements Notifiable, Formatable
{
    public function __construct(private string $numero) {}

    public function formater(string $message): string
    {
        return substr($message, 0, 160); // SMS limite a 160 caracteres
    }

    public function envoyer(string $message): bool
    {
        $messageFormate = $this->formater($message);
        echo "SMS a {$this->numero} : $messageFormate\n";
        return true;
    }
}

class NotificationSlack implements Notifiable, Formatable
{
    public function __construct(private string $canal) {}

    public function formater(string $message): string
    {
        return ":bell: *Notification* : $message";
    }

    public function envoyer(string $message): bool
    {
        $messageFormate = $this->formater($message);
        echo "Slack #{$this->canal} : $messageFormate\n";
        return true;
    }
}

function notifierTous(array $notifieurs, string $message): void
{
    foreach ($notifieurs as $notifieur) {
        if ($notifieur instanceof Notifiable) {
            $notifieur->envoyer($message);
        }
    }
}

$notifieurs = [
    new NotificationEmail("alice@exemple.fr"),
    new NotificationSms("06 12 34 56 78"),
    new NotificationSlack("general"),
];

notifierTous($notifieurs, "Le serveur a redemarrer !");
```

</details>

---

### Exercice 5 : Projet complet - Gestionnaire de bibliotheque (Avance)

Creez un systeme complet de gestion de bibliotheque :

1. Une interface `Empruntable` avec : `emprunter(): bool`, `retourner(): void`, `estDisponible(): bool`
2. Une classe abstraite `Media` avec : `titre`, `annee`, une methode abstraite `getType(): string`
3. Une classe `Livre` (extends Media, implements Empruntable) avec : `auteur`, `isbn`
4. Une classe `DVD` (extends Media, implements Empruntable) avec : `duree` (en minutes)
5. Une classe `Bibliotheque` avec :
   - Un tableau prive de medias
   - `ajouterMedia(Media $media): void`
   - `rechercherParTitre(string $titre): array`
   - `getDisponibles(): array` (seulement les medias empruntables et disponibles)
   - `afficherInventaire(): void`

Utilisez les arguments nommes, le match, et la promotion du constructeur.

<details>
<summary>Voir la solution</summary>

```php
<?php
declare(strict_types=1);

interface Empruntable
{
    public function emprunter(): bool;
    public function retourner(): void;
    public function estDisponible(): bool;
}

abstract class Media
{
    public function __construct(
        protected string $titre,
        protected int $annee
    ) {}

    abstract public function getType(): string;

    public function getTitre(): string
    {
        return $this->titre;
    }

    public function getAnnee(): int
    {
        return $this->annee;
    }

    public function getDescription(): string
    {
        return match($this->getType()) {
            'Livre' => "Livre : {$this->titre} ({$this->annee})",
            'DVD'   => "DVD : {$this->titre} ({$this->annee})",
            default => "Media : {$this->titre} ({$this->annee})",
        };
    }
}

class Livre extends Media implements Empruntable
{
    private bool $disponible = true;

    public function __construct(
        string $titre,
        int $annee,
        private string $auteur,
        private string $isbn
    ) {
        parent::__construct($titre, $annee);
    }

    public function getType(): string { return 'Livre'; }
    public function emprunter(): bool
    {
        if (!$this->disponible) return false;
        $this->disponible = false;
        return true;
    }
    public function retourner(): void { $this->disponible = true; }
    public function estDisponible(): bool { return $this->disponible; }

    public function getDescription(): string
    {
        $statut = $this->disponible ? "Disponible" : "Emprunte";
        return "Livre : {$this->titre} par {$this->auteur} ({$this->annee}) - ISBN: {$this->isbn} [$statut]";
    }
}

class DVD extends Media implements Empruntable
{
    private bool $disponible = true;

    public function __construct(
        string $titre,
        int $annee,
        private int $duree
    ) {
        parent::__construct($titre, $annee);
    }

    public function getType(): string { return 'DVD'; }
    public function emprunter(): bool
    {
        if (!$this->disponible) return false;
        $this->disponible = false;
        return true;
    }
    public function retourner(): void { $this->disponible = true; }
    public function estDisponible(): bool { return $this->disponible; }

    public function getDescription(): string
    {
        $statut = $this->disponible ? "Disponible" : "Emprunte";
        return "DVD : {$this->titre} ({$this->annee}) - {$this->duree} min [$statut]";
    }
}

class Bibliotheque
{
    /** @var Media[] */
    private array $medias = [];

    public function ajouterMedia(Media $media): void
    {
        $this->medias[] = $media;
    }

    /** @return Media[] */
    public function rechercherParTitre(string $titre): array
    {
        return array_filter(
            $this->medias,
            fn(Media $m) => str_contains(
                strtolower($m->getTitre()),
                strtolower($titre)
            )
        );
    }

    /** @return Empruntable[] */
    public function getDisponibles(): array
    {
        return array_filter(
            $this->medias,
            fn(Media $m) => $m instanceof Empruntable && $m->estDisponible()
        );
    }

    public function afficherInventaire(): void
    {
        echo "=== Inventaire de la bibliotheque ===\n";
        foreach ($this->medias as $media) {
            echo "- " . $media->getDescription() . "\n";
        }
        echo "Total : " . count($this->medias) . " medias\n";
    }
}

// Utilisation
$biblio = new Bibliotheque();

$biblio->ajouterMedia(new Livre(
    titre: "Le Petit Prince",
    annee: 1943,
    auteur: "Saint-Exupery",
    isbn: "978-2-07-061275-8"
));
$biblio->ajouterMedia(new Livre(
    titre: "Les Miserables",
    annee: 1862,
    auteur: "Victor Hugo",
    isbn: "978-2-07-040850-4"
));
$biblio->ajouterMedia(new DVD(
    titre: "Le Fabuleux Destin d'Amelie Poulain",
    annee: 2001,
    duree: 122
));

$biblio->afficherInventaire();

// Emprunter un livre
$resultats = $biblio->rechercherParTitre("petit");
foreach ($resultats as $media) {
    if ($media instanceof Empruntable) {
        $media->emprunter();
        echo "\n'{$media->getTitre()}' a ete emprunte !\n\n";
    }
}

$biblio->afficherInventaire();
```

</details>

---

## Recapitulatif

| Concept                | Mot-cle / Syntaxe                    | A retenir                                      |
|------------------------|--------------------------------------|-------------------------------------------------|
| Classe                 | `class NomClasse {}`                 | Le plan de construction                         |
| Objet                  | `new NomClasse()`                    | La realisation concrete                         |
| Propriete              | `public string $nom`                 | Une variable de la classe                       |
| Methode                | `public function faire(): void`      | Une fonction de la classe                       |
| Constructeur           | `__construct()`                      | Initialise l'objet                              |
| $this                  | `$this->propriete`                   | Reference a l'objet courant                     |
| Visibilite             | `public`, `protected`, `private`     | Controle l'acces                                |
| Heritage               | `class Enfant extends Parent`        | Reprend tout du parent                          |
| Classe abstraite       | `abstract class`                     | Ne peut pas etre instanciee                     |
| Interface              | `interface` / `implements`           | Contrat a respecter                             |
| Polymorphisme          | Type hinting avec interface          | Traiter differents objets de la meme facon      |
| Statique               | `static` / `self::`                  | Appartient a la classe, pas a l'objet           |
| Constante              | `const` / `self::NOM`               | Valeur qui ne change jamais                     |
| Promotion constructeur | `public function __construct(private string $nom)` | PHP 8 : declare + initialise en une ligne |
| Arguments nommes       | `new Classe(nom: "valeur")`          | Parametres dans n'importe quel ordre            |
| Match                  | `match($val) { 'a' => 1 }`          | Switch ameliore qui retourne une valeur         |
| Namespace              | `namespace App\Models`               | Organise le code, evite les conflits            |

---

**Felicitations !** Vous avez termine ce cours sur la POO en PHP. Pratiquez en realisant les exercices et n'hesitez pas a creer vos propres classes pour des projets personnels. La POO est un outil puissant qui rendra votre code plus propre, plus organise et plus facile a maintenir.
