<?php
declare(strict_types=1);

/**
 * =============================================================================
 * COURS POO PHP - 01 : CLASSES ET OBJETS
 * =============================================================================
 *
 * Ce fichier demontre les bases de la POO en PHP 8+ :
 * - Definition d'une classe avec proprietes et methodes
 * - Constructeur classique et promotion du constructeur (PHP 8)
 * - Le mot-cle $this
 * - Les constantes de classe
 * - Les proprietes et methodes statiques
 * - Creation et utilisation d'objets
 *
 * Executez ce fichier avec : php 01_classes.php
 */

echo "=== 1. CLASSE SIMPLE AVEC PROPRIETES ET METHODES ===\n\n";

/**
 * Classe Produit : notre premiere classe.
 *
 * Une classe est un "modele" qui definit :
 * - Des proprietes (les donnees, aussi appelees "attributs")
 * - Des methodes (les fonctions qui appartiennent a la classe)
 */
class Produit
{
    // --- PROPRIETES ---
    // Ce sont les caracteristiques de notre produit.
    // "public" signifie qu'elles sont accessibles depuis partout.
    // On type chaque propriete (string, float, int) pour plus de securite.
    public string $nom;
    public float $prix;
    public int $stock;

    // --- CONSTRUCTEUR ---
    // __construct() est appele automatiquement quand on fait "new Produit(...)".
    // Il sert a initialiser l'objet avec des valeurs de depart.
    public function __construct(string $nom, float $prix, int $stock = 0)
    {
        // $this fait reference a l'objet en cours de creation.
        // On affecte les parametres recus aux proprietes de l'objet.
        $this->nom = $nom;       // La propriete $nom recoit la valeur du parametre $nom
        $this->prix = $prix;     // La propriete $prix recoit la valeur du parametre $prix
        $this->stock = $stock;   // La propriete $stock recoit la valeur du parametre $stock
    }

    // --- METHODES ---
    // Les methodes sont les "comportements" de notre objet.

    /**
     * Affiche les informations du produit.
     * ": string" signifie que cette methode retourne une chaine de caracteres.
     */
    public function afficher(): string
    {
        // $this->nom accede a la propriete "nom" de CET objet precis
        return "{$this->nom} - {$this->prix} EUR (stock: {$this->stock})";
    }

    /**
     * Calcule le prix TTC (Toutes Taxes Comprises).
     * Le parametre $tva a une valeur par defaut de 20.0 (20%).
     */
    public function calculerPrixTTC(float $tva = 20.0): float
    {
        return $this->prix * (1 + $tva / 100);
    }

    /**
     * Verifie si le produit est en stock.
     * ": bool" signifie que la methode retourne true ou false.
     */
    public function estEnStock(): bool
    {
        return $this->stock > 0;
    }

    /**
     * Reduit le stock d'une quantite donnee.
     * ": void" signifie que la methode ne retourne RIEN.
     */
    public function vendre(int $quantite = 1): void
    {
        // On verifie qu'il y a assez de stock
        if ($quantite > $this->stock) {
            echo "  [ERREUR] Stock insuffisant pour '{$this->nom}' ! " .
                 "(demande: $quantite, disponible: {$this->stock})\n";
            return; // On sort de la methode sans rien faire
        }

        $this->stock -= $quantite; // On reduit le stock
        echo "  Vente de $quantite x '{$this->nom}'. Stock restant : {$this->stock}\n";
    }
}

// --- CREATION D'OBJETS ---
// Le mot-cle "new" cree une nouvelle instance (un nouvel objet) de la classe.
// Le constructeur est appele automatiquement avec les arguments fournis.

$clavier = new Produit("Clavier mecanique", 89.99, 15);
$souris = new Produit("Souris sans fil", 39.99, 25);
$ecran = new Produit("Ecran 27 pouces", 299.99);  // stock par defaut = 0

// --- UTILISATION DES OBJETS ---

// Acceder aux proprietes avec la fleche ->
echo "Nom du produit : " . $clavier->nom . "\n";
echo "Prix du produit : " . $clavier->prix . " EUR\n";

// Appeler des methodes
echo "\nInformations des produits :\n";
echo "  " . $clavier->afficher() . "\n";
echo "  " . $souris->afficher() . "\n";
echo "  " . $ecran->afficher() . "\n";

// Utilisation des methodes
echo "\nPrix TTC du clavier (TVA 20%) : " . $clavier->calculerPrixTTC() . " EUR\n";
echo "Prix TTC du clavier (TVA 5.5%) : " . $clavier->calculerPrixTTC(5.5) . " EUR\n";

echo "\nL'ecran est en stock ? " . ($ecran->estEnStock() ? "Oui" : "Non") . "\n";
echo "Le clavier est en stock ? " . ($clavier->estEnStock() ? "Oui" : "Non") . "\n";

echo "\nVentes :\n";
$clavier->vendre(3);     // Vend 3 claviers
$clavier->vendre(1);     // Vend 1 clavier
$ecran->vendre(1);       // Erreur : pas de stock


echo "\n\n=== 2. PROMOTION DU CONSTRUCTEUR (PHP 8.0) ===\n\n";

/**
 * En PHP 8, on peut declarer ET initialiser les proprietes directement
 * dans les parametres du constructeur. C'est la "promotion du constructeur".
 *
 * Avantage : on evite le code repetitif ($this->prop = $prop).
 */
class Utilisateur
{
    /**
     * Avec la promotion : chaque parametre prefixe de "public", "protected"
     * ou "private" devient automatiquement une propriete de la classe.
     *
     * PHP cree automatiquement :
     * - $this->nom = $nom
     * - $this->email = $email
     * - $this->age = $age
     */
    public function __construct(
        private string $nom,              // Cree la propriete privee $this->nom
        private string $email,            // Cree la propriete privee $this->email
        private int $age,                 // Cree la propriete privee $this->age
        private string $role = "membre"   // Valeur par defaut possible
    ) {
        // Le corps peut etre vide ! Tout est deja fait automatiquement.
        // Mais on peut ajouter de la logique supplementaire si besoin :
        echo "  Nouvel utilisateur cree : {$this->nom} ({$this->role})\n";
    }

    /**
     * Methode pour afficher les informations de l'utilisateur.
     * On accede aux proprietes avec $this-> comme d'habitude.
     */
    public function afficher(): string
    {
        return "{$this->nom} ({$this->email}) - {$this->age} ans - Role: {$this->role}";
    }
}

// Creation d'utilisateurs
$alice = new Utilisateur("Alice", "alice@exemple.fr", 28);
$bob = new Utilisateur("Bob", "bob@exemple.fr", 35, "admin");

echo "\n" . $alice->afficher() . "\n";
echo $bob->afficher() . "\n";


echo "\n\n=== 3. LE MOT-CLE \$this ===\n\n";

/**
 * $this est une reference a l'objet courant.
 * Il permet d'acceder aux proprietes et aux methodes de l'objet
 * depuis l'interieur de la classe.
 */
class Compteur
{
    private int $valeur = 0;   // Propriete avec valeur initiale
    private string $nom;

    public function __construct(string $nom)
    {
        // $this->nom est la PROPRIETE de l'objet
        // $nom est le PARAMETRE de la methode
        $this->nom = $nom;
    }

    public function incrementer(): void
    {
        // $this->valeur designe la propriete "valeur" de CET objet
        $this->valeur++;
    }

    public function decrementer(): void
    {
        $this->valeur--;
    }

    public function getValeur(): int
    {
        return $this->valeur;
    }

    /**
     * Exemple : une methode qui appelle d'autres methodes du meme objet.
     * On utilise $this-> pour appeler les methodes internes.
     */
    public function incrementerDe(int $n): void
    {
        for ($i = 0; $i < $n; $i++) {
            $this->incrementer(); // Appel a une autre methode du meme objet
        }
        // $this->afficherEtat() est aussi possible
        $this->afficherEtat();
    }

    public function afficherEtat(): void
    {
        echo "  Compteur '{$this->nom}' = {$this->valeur}\n";
    }
}

// Chaque objet a ses propres valeurs (ses propres proprietes)
$compteurA = new Compteur("Alpha");
$compteurB = new Compteur("Beta");

$compteurA->incrementer();          // Alpha = 1
$compteurA->incrementer();          // Alpha = 2
$compteurA->incrementer();          // Alpha = 3
$compteurA->afficherEtat();         // Affiche : Compteur 'Alpha' = 3

$compteurB->incrementerDe(5);       // Beta = 5 (appelle incrementer 5 fois)

// Les deux compteurs sont independants
echo "\n  Alpha = " . $compteurA->getValeur() . "\n"; // 3
echo "  Beta = " . $compteurB->getValeur() . "\n";    // 5


echo "\n\n=== 4. CONSTANTES DE CLASSE ===\n\n";

/**
 * Les constantes sont des valeurs qui ne changent JAMAIS.
 * - On les definit avec "const" (pas "public string $...")
 * - Par convention, elles sont en MAJUSCULES
 * - On y accede avec self:: (dans la classe) ou NomClasse:: (a l'exterieur)
 * - Elles n'ont PAS de $ devant leur nom
 */
class Configuration
{
    // Constantes publiques : accessibles de partout
    public const VERSION = "2.0.0";
    public const NOM_APPLICATION = "MonApp";
    public const MAX_TENTATIVES = 5;

    // Constante privee : accessible uniquement dans cette classe
    private const CLE_SECRETE = "abc123xyz";

    /**
     * Dans la classe, on accede aux constantes avec self::
     * (et non $this->, car ce ne sont PAS des proprietes)
     */
    public function afficherVersion(): string
    {
        return self::NOM_APPLICATION . " v" . self::VERSION;
    }

    public function verifierCle(string $cle): bool
    {
        // On peut acceder a la constante privee depuis la meme classe
        return $cle === self::CLE_SECRETE;
    }
}

// Depuis l'exterieur, on accede avec NomClasse::NOM_CONSTANTE
echo "Application : " . Configuration::NOM_APPLICATION . "\n";
echo "Version : " . Configuration::VERSION . "\n";
echo "Max tentatives : " . Configuration::MAX_TENTATIVES . "\n";

// On peut aussi utiliser un objet, mais NomClasse:: est prefere
$config = new Configuration();
echo $config->afficherVersion() . "\n";

// Configuration::CLE_SECRETE; // ERREUR : constante privee, inaccessible


echo "\n\n=== 5. PROPRIETES ET METHODES STATIQUES ===\n\n";

/**
 * Les elements "static" appartiennent a LA CLASSE elle-meme,
 * pas a un objet particulier.
 *
 * - On n'a PAS besoin de creer un objet pour les utiliser
 * - On y accede avec self:: (dans la classe) ou NomClasse:: (a l'exterieur)
 * - Les proprietes statiques sont PARTAGEES entre tous les objets
 * - Dans une methode statique, on ne peut PAS utiliser $this
 */
class Identifiant
{
    // Propriete statique : une seule copie pour TOUTE la classe
    // Chaque nouvel objet incrementera ce compteur
    private static int $prochainId = 1;

    // Propriete normale (non statique) : chaque objet a la sienne
    private int $id;

    public function __construct(
        private string $nom
    ) {
        // self:: accede a la propriete statique
        $this->id = self::$prochainId;
        self::$prochainId++;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function afficher(): string
    {
        return "#{$this->id} - {$this->nom}";
    }

    /**
     * Methode statique : on peut l'appeler SANS creer d'objet.
     * Utile pour des operations qui ne dependent pas d'un objet precis.
     */
    public static function getProchainId(): int
    {
        return self::$prochainId;
    }

    /**
     * Methode statique de type "fabrique" (factory method).
     * Elle cree et retourne un objet.
     * "self" fait reference a la classe elle-meme.
     */
    public static function creerAvecPrefixe(string $prefixe, string $nom): self
    {
        return new self("[$prefixe] $nom");
    }
}

// Appel de methode statique SANS creer d'objet
echo "Prochain ID disponible : " . Identifiant::getProchainId() . "\n";

// Creation d'objets : chacun recoit un ID unique grace a la propriete statique
$user1 = new Identifiant("Alice");
$user2 = new Identifiant("Bob");
$user3 = Identifiant::creerAvecPrefixe("VIP", "Charlie"); // Methode fabrique

echo $user1->afficher() . "\n";  // #1 - Alice
echo $user2->afficher() . "\n";  // #2 - Bob
echo $user3->afficher() . "\n";  // #3 - [VIP] Charlie

echo "Prochain ID disponible : " . Identifiant::getProchainId() . "\n"; // 4


echo "\n\n=== 6. CREATION DE PLUSIEURS OBJETS ===\n\n";

/**
 * Demontrons qu'on peut creer autant d'objets qu'on veut
 * a partir d'une seule classe. Chaque objet est independant.
 */
class Couleur
{
    public function __construct(
        private int $rouge,
        private int $vert,
        private int $bleu
    ) {
        // Validation : les valeurs RGB doivent etre entre 0 et 255
        $this->rouge = max(0, min(255, $rouge));
        $this->vert = max(0, min(255, $vert));
        $this->bleu = max(0, min(255, $bleu));
    }

    /**
     * Retourne la couleur au format hexadecimal (#RRGGBB)
     */
    public function enHex(): string
    {
        return sprintf("#%02X%02X%02X", $this->rouge, $this->vert, $this->bleu);
    }

    public function afficher(): string
    {
        return "RGB({$this->rouge}, {$this->vert}, {$this->bleu}) = {$this->enHex()}";
    }

    /**
     * Methode statique : des couleurs predefinies
     */
    public static function rouge(): self
    {
        return new self(255, 0, 0);
    }

    public static function vert(): self
    {
        return new self(0, 255, 0);
    }

    public static function bleu(): self
    {
        return new self(0, 0, 255);
    }
}

// Creation de plusieurs objets : chacun a ses propres valeurs
$rouge = Couleur::rouge();         // Methode statique fabrique
$vert = Couleur::vert();
$bleu = Couleur::bleu();
$custom = new Couleur(128, 64, 255); // Creation directe

echo $rouge->afficher() . "\n";   // RGB(255, 0, 0) = #FF0000
echo $vert->afficher() . "\n";    // RGB(0, 255, 0) = #00FF00
echo $bleu->afficher() . "\n";    // RGB(0, 0, 255) = #0000FF
echo $custom->afficher() . "\n";  // RGB(128, 64, 255) = #8040FF

// Stocker des objets dans un tableau
$palette = [$rouge, $vert, $bleu, $custom];
echo "\nPalette de couleurs :\n";
foreach ($palette as $index => $couleur) {
    echo "  Couleur $index : " . $couleur->enHex() . "\n";
}


echo "\n\n=== RESUME ===\n";
echo "---------------------------------------------------------------\n";
echo "| Concept               | Syntaxe                            |\n";
echo "---------------------------------------------------------------\n";
echo "| Definir une classe    | class NomClasse { }                |\n";
echo "| Creer un objet        | \$obj = new NomClasse()             |\n";
echo "| Acceder a propriete   | \$obj->propriete                    |\n";
echo "| Appeler une methode   | \$obj->methode()                    |\n";
echo "| Constructeur          | public function __construct()      |\n";
echo "| Promotion PHP 8       | __construct(private string \$nom)   |\n";
echo "| Reference a l'objet   | \$this->propriete                   |\n";
echo "| Constante de classe   | const NOM = 'valeur'               |\n";
echo "| Acceder a constante   | self::NOM ou NomClasse::NOM        |\n";
echo "| Propriete statique    | private static int \$compteur       |\n";
echo "| Methode statique      | public static function faire()     |\n";
echo "| Acceder au statique   | self::\$prop ou NomClasse::\$prop    |\n";
echo "---------------------------------------------------------------\n";
