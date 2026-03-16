<?php
declare(strict_types=1);

/**
 * =============================================================================
 * COURS POO PHP - 02 : HERITAGE
 * =============================================================================
 *
 * Ce fichier demontre l'heritage en PHP 8+ :
 * - Classe parent (de base) et classes enfants
 * - Le mot-cle "extends"
 * - Appel du constructeur parent avec parent::__construct()
 * - Redefinition (override) de methodes
 * - Classes abstraites et methodes abstraites
 *
 * L'heritage permet a une classe "enfant" de reprendre toutes les proprietes
 * et methodes d'une classe "parent", puis d'ajouter ou modifier des elements.
 *
 * Executez ce fichier avec : php 02_heritage.php
 */

echo "=== 1. HERITAGE DE BASE ===\n\n";

/**
 * Classe PARENT (aussi appelee "classe de base" ou "superclasse").
 *
 * C'est le modele general dont vont heriter les classes enfants.
 * Ici, un vehicule possede des caracteristiques communes a tous les vehicules.
 */
class Vehicule
{
    /**
     * On utilise "protected" pour que les classes enfants puissent
     * acceder a ces proprietes directement.
     *
     * Rappel des visibilites :
     * - public    : accessible partout
     * - protected : accessible dans cette classe ET dans les classes enfants
     * - private   : accessible UNIQUEMENT dans cette classe
     */
    public function __construct(
        protected string $marque,
        protected string $modele,
        protected int $annee,
        protected float $vitesseMax,      // Vitesse maximale en km/h
        protected float $vitesseActuelle = 0.0  // Vitesse actuelle, commence a 0
    ) {
        echo "  [Vehicule] Creation d'un {$this->marque} {$this->modele}\n";
    }

    /**
     * Methode pour accelerer.
     * Les classes enfants heritent de cette methode automatiquement.
     */
    public function accelerer(float $kmh): void
    {
        $nouvelleVitesse = $this->vitesseActuelle + $kmh;

        // On ne depasse pas la vitesse maximale
        $this->vitesseActuelle = min($nouvelleVitesse, $this->vitesseMax);

        echo "  {$this->marque} {$this->modele} accelere a {$this->vitesseActuelle} km/h\n";
    }

    /**
     * Methode pour freiner.
     */
    public function freiner(float $kmh): void
    {
        // La vitesse ne peut pas descendre sous 0
        $this->vitesseActuelle = max(0, $this->vitesseActuelle - $kmh);
        echo "  {$this->marque} {$this->modele} freine a {$this->vitesseActuelle} km/h\n";
    }

    /**
     * Methode qui affiche les informations du vehicule.
     * Les classes enfants pourront la REDEFINIR (override) pour ajouter des infos.
     */
    public function afficher(): string
    {
        return "{$this->marque} {$this->modele} ({$this->annee}) - " .
               "Max: {$this->vitesseMax} km/h - " .
               "Actuelle: {$this->vitesseActuelle} km/h";
    }

    /**
     * Methode qui decrit le type de vehicule.
     * Les classes enfants DEVRONT la redefinir.
     */
    public function decrire(): string
    {
        return "Je suis un vehicule generique.";
    }
}


/**
 * Classe ENFANT : Voiture.
 *
 * "extends" signifie "herite de".
 * Voiture herite de TOUTES les proprietes et methodes de Vehicule.
 * Elle peut ensuite :
 * - Ajouter ses propres proprietes et methodes
 * - Redefinir (override) les methodes du parent
 */
class Voiture extends Vehicule
{
    /**
     * Le constructeur de la classe enfant.
     * On appelle parent::__construct() pour initialiser les proprietes du parent.
     */
    public function __construct(
        string $marque,
        string $modele,
        int $annee,
        float $vitesseMax,
        private int $nombrePortes,     // Propriete SPECIFIQUE a Voiture
        private string $typeCarburant  // Propriete SPECIFIQUE a Voiture
    ) {
        // parent:: appelle le constructeur de la classe parent (Vehicule)
        // C'est OBLIGATOIRE si le parent a un constructeur avec des parametres
        parent::__construct($marque, $modele, $annee, $vitesseMax);

        echo "  [Voiture] C'est une voiture {$this->nombrePortes} portes ({$this->typeCarburant})\n";
    }

    /**
     * REDEFINITION (override) de la methode afficher() du parent.
     * On remplace completement la methode du parent par notre propre version.
     */
    public function afficher(): string
    {
        // On peut appeler la methode du parent avec parent::
        // puis ajouter nos propres informations
        return parent::afficher() . " | Portes: {$this->nombrePortes} | Carburant: {$this->typeCarburant}";
    }

    /**
     * REDEFINITION de la methode decrire() du parent.
     */
    public function decrire(): string
    {
        return "Je suis une voiture {$this->marque} {$this->modele} a {$this->typeCarburant}.";
    }

    /**
     * Methode SPECIFIQUE a Voiture (n'existe pas dans le parent).
     */
    public function klaxonner(): string
    {
        return "TUUUT TUUUT !";
    }
}


/**
 * Classe ENFANT : Moto.
 * Elle aussi herite de Vehicule.
 */
class Moto extends Vehicule
{
    public function __construct(
        string $marque,
        string $modele,
        int $annee,
        float $vitesseMax,
        private int $cylindree  // Propriete specifique : cylindree en cm3
    ) {
        parent::__construct($marque, $modele, $annee, $vitesseMax);
        echo "  [Moto] Cylindree : {$this->cylindree} cm3\n";
    }

    public function afficher(): string
    {
        return parent::afficher() . " | Cylindree: {$this->cylindree} cm3";
    }

    public function decrire(): string
    {
        return "Je suis une moto {$this->marque} {$this->modele} de {$this->cylindree} cm3.";
    }

    /**
     * Methode specifique a Moto.
     */
    public function faireRoueArriere(): string
    {
        if ($this->vitesseActuelle > 20 && $this->vitesseActuelle < 80) {
            return "WHEEELIE ! La moto se cabre !";
        }
        return "Conditions non adaptees pour un wheeling.";
    }
}


// --- UTILISATION ---

echo "Creation des vehicules :\n";
$voiture = new Voiture("Peugeot", "308", 2023, 220, 5, "essence");
echo "\n";
$moto = new Moto("Yamaha", "MT-07", 2024, 200, 689);

echo "\n\nInformations :\n";
echo "  " . $voiture->afficher() . "\n";
echo "  " . $moto->afficher() . "\n";

echo "\nDescriptions :\n";
echo "  " . $voiture->decrire() . "\n";
echo "  " . $moto->decrire() . "\n";

echo "\nMethodes heritees (les deux vehicules peuvent accelerer et freiner) :\n";
$voiture->accelerer(80);    // Methode heritee de Vehicule
$voiture->accelerer(50);    // Continue d'accelerer
$voiture->freiner(30);      // Methode heritee de Vehicule

echo "\n";
$moto->accelerer(60);
echo "  " . $moto->faireRoueArriere() . "\n";  // Methode specifique a Moto

echo "\nMethodes specifiques :\n";
echo "  Voiture : " . $voiture->klaxonner() . "\n";  // Methode specifique a Voiture
// $moto->klaxonner();  // ERREUR : la methode klaxonner() n'existe pas dans Moto


echo "\n\n=== 2. CLASSES ABSTRAITES ET METHODES ABSTRAITES ===\n\n";

/**
 * Une classe ABSTRAITE est une classe qu'on ne peut PAS instancier directement.
 * Elle sert de MODELE pour d'autres classes.
 *
 * "abstract" devant "class" signifie :
 * - On NE PEUT PAS faire "new Animal()"
 * - Les classes enfants DOIVENT implementer les methodes abstraites
 *
 * Une methode ABSTRAITE est une methode SANS corps (sans code).
 * Les classes enfants sont OBLIGEES de lui donner un corps.
 */
abstract class Animal
{
    /**
     * Constructeur avec promotion des proprietes.
     * "protected" car les classes enfants doivent pouvoir y acceder.
     */
    public function __construct(
        protected string $nom,
        protected string $espece,
        protected int $age,
        protected float $poids  // En kg
    ) {}

    /**
     * METHODE ABSTRAITE : elle n'a PAS de corps (pas d'accolades { }).
     * Chaque animal fait un son different, donc chaque classe enfant
     * DOIT definir sa propre version de cette methode.
     */
    abstract public function crier(): string;

    /**
     * METHODE ABSTRAITE : chaque animal se deplace differemment.
     */
    abstract public function seDeplacer(): string;

    /**
     * Methode NORMALE (non abstraite) : elle a un corps.
     * Les classes enfants en heritent directement, sans obligation
     * de la redefinir (mais elles peuvent le faire si elles veulent).
     */
    public function sePresenter(): string
    {
        return "Je suis {$this->nom}, un(e) {$this->espece} de {$this->age} an(s) " .
               "pesant {$this->poids} kg.";
    }

    /**
     * Methode normale : manger est commun a tous les animaux.
     */
    public function manger(string $nourriture): string
    {
        return "{$this->nom} mange {$nourriture}.";
    }

    public function getNom(): string
    {
        return $this->nom;
    }
}


/**
 * Classe enfant : Chien.
 * Elle DOIT implementer toutes les methodes abstraites (crier et seDeplacer).
 */
class Chien extends Animal
{
    /**
     * On ajoute un parametre specifique : la race.
     */
    public function __construct(
        string $nom,
        int $age,
        float $poids,
        private string $race
    ) {
        // On appelle le constructeur parent en fixant l'espece a "Chien"
        parent::__construct($nom, "Chien", $age, $poids);
    }

    /**
     * IMPLEMENTATION OBLIGATOIRE de la methode abstraite crier().
     * Chaque classe enfant donne sa propre implementation.
     */
    public function crier(): string
    {
        return "Wouf ! Wouf !";
    }

    /**
     * IMPLEMENTATION OBLIGATOIRE de la methode abstraite seDeplacer().
     */
    public function seDeplacer(): string
    {
        return "{$this->nom} court joyeusement a quatre pattes !";
    }

    /**
     * REDEFINITION de sePresenter() pour ajouter la race.
     * Ce n'est pas obligatoire (ce n'est pas une methode abstraite),
     * mais on choisit de l'enrichir.
     */
    public function sePresenter(): string
    {
        // parent:: appelle la version du parent, puis on ajoute la race
        return parent::sePresenter() . " Race : {$this->race}.";
    }

    /**
     * Methode specifique au Chien.
     */
    public function rapporterBalle(): string
    {
        return "{$this->nom} rapporte la balle en remuant la queue !";
    }
}


/**
 * Classe enfant : Oiseau.
 */
class Oiseau extends Animal
{
    public function __construct(
        string $nom,
        int $age,
        float $poids,
        private float $envergure  // Envergure des ailes en cm
    ) {
        parent::__construct($nom, "Oiseau", $age, $poids);
    }

    // Implementation obligatoire
    public function crier(): string
    {
        return "Cui-cui ! Piou-piou !";
    }

    // Implementation obligatoire
    public function seDeplacer(): string
    {
        return "{$this->nom} s'envole avec une envergure de {$this->envergure} cm !";
    }

    /**
     * Methode specifique a Oiseau.
     */
    public function voler(float $altitude): string
    {
        return "{$this->nom} vole a {$altitude} metres d'altitude.";
    }
}


/**
 * Classe enfant : Poisson.
 */
class Poisson extends Animal
{
    public function __construct(
        string $nom,
        int $age,
        float $poids,
        private bool $eauDouce  // true = eau douce, false = eau salee
    ) {
        parent::__construct($nom, "Poisson", $age, $poids);
    }

    public function crier(): string
    {
        return "... (les poissons ne crient pas !)";
    }

    public function seDeplacer(): string
    {
        $typeEau = $this->eauDouce ? "douce" : "salee";
        return "{$this->nom} nage dans l'eau {$typeEau}.";
    }
}


// --- UTILISATION ---

// new Animal("Test", "Test", 1, 1.0);  // ERREUR ! On ne peut pas instancier une classe abstraite

$rex = new Chien("Rex", 5, 32.5, "Berger Allemand");
$piaf = new Oiseau("Piaf", 2, 0.3, 25.0);
$nemo = new Poisson("Nemo", 1, 0.1, false);

echo "Presentations :\n";
echo "  " . $rex->sePresenter() . "\n";   // Inclut la race (methode redefinie)
echo "  " . $piaf->sePresenter() . "\n";  // Version du parent (non redefinie)
echo "  " . $nemo->sePresenter() . "\n";

echo "\nCris :\n";
echo "  {$rex->getNom()} : " . $rex->crier() . "\n";    // Wouf ! Wouf !
echo "  {$piaf->getNom()} : " . $piaf->crier() . "\n";   // Cui-cui ! Piou-piou !
echo "  {$nemo->getNom()} : " . $nemo->crier() . "\n";   // ... (les poissons ne crient pas !)

echo "\nDeplacements :\n";
echo "  " . $rex->seDeplacer() . "\n";
echo "  " . $piaf->seDeplacer() . "\n";
echo "  " . $nemo->seDeplacer() . "\n";

echo "\nMethodes specifiques :\n";
echo "  " . $rex->rapporterBalle() . "\n";
echo "  " . $piaf->voler(150) . "\n";

echo "\nMethodes communes (heritees du parent) :\n";
echo "  " . $rex->manger("des croquettes") . "\n";
echo "  " . $piaf->manger("des graines") . "\n";
echo "  " . $nemo->manger("du plancton") . "\n";

// On peut stocker des types differents dans un tableau grace au type commun
echo "\nBoucle sur un tableau d'animaux (polymorphisme grace a l'heritage) :\n";
$animaux = [$rex, $piaf, $nemo];

foreach ($animaux as $animal) {
    // Chaque animal a sa propre implementation de crier() et seDeplacer()
    echo "  {$animal->getNom()} dit '{$animal->crier()}' et {$animal->seDeplacer()}\n";
}


echo "\n\n=== RESUME ===\n";
echo "----------------------------------------------------------------------\n";
echo "| Concept              | Syntaxe                                     |\n";
echo "----------------------------------------------------------------------\n";
echo "| Heritage             | class Enfant extends Parent { }             |\n";
echo "| Constructeur parent  | parent::__construct(...)                    |\n";
echo "| Methode parent       | parent::nomMethode()                       |\n";
echo "| Redefinition         | Redecrire la methode dans la classe enfant  |\n";
echo "| Classe abstraite     | abstract class NomClasse { }               |\n";
echo "| Methode abstraite    | abstract public function nom(): type;      |\n";
echo "| protected            | Accessible dans la classe et ses enfants    |\n";
echo "----------------------------------------------------------------------\n";
