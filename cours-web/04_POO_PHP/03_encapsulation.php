<?php
declare(strict_types=1);

/**
 * =============================================================================
 * COURS POO PHP - 03 : ENCAPSULATION
 * =============================================================================
 *
 * Ce fichier demontre l'encapsulation en PHP 8+ :
 * - Les trois niveaux de visibilite : public, protected, private
 * - Les getters (accesseurs) pour LIRE les proprietes privees
 * - Les setters (mutateurs) pour MODIFIER les proprietes privees
 * - La validation des donnees dans les setters
 * - L'acces depuis l'exterieur, depuis la classe, depuis une classe enfant
 *
 * L'encapsulation consiste a PROTEGER les donnees internes d'un objet
 * et a controler comment elles sont lues et modifiees.
 *
 * Executez ce fichier avec : php 03_encapsulation.php
 */

echo "=== 1. LES TROIS NIVEAUX DE VISIBILITE ===\n\n";

/**
 * Classe qui montre les trois niveaux de visibilite.
 *
 * Rappel :
 * - public    : accessible PARTOUT (dans la classe, les enfants, et l'exterieur)
 * - protected : accessible dans la classe et dans les classes ENFANTS
 * - private   : accessible UNIQUEMENT dans la classe ou il est defini
 */
class CompteBancaire
{
    // PUBLIC : tout le monde peut y acceder directement
    public string $titulaire;

    // PROTECTED : accessible dans cette classe et dans les classes enfants
    protected string $banque;

    // PRIVATE : accessible UNIQUEMENT dans cette classe
    // Personne d'autre ne peut y toucher directement
    private float $solde;
    private string $codeSecret;

    // Tableau prive qui stocke l'historique des operations
    private array $historique = [];

    public function __construct(
        string $titulaire,
        string $banque,
        float $soldeInitial,
        string $codeSecret
    ) {
        $this->titulaire = $titulaire;
        $this->banque = $banque;
        $this->solde = $soldeInitial;
        $this->codeSecret = $codeSecret;

        // On enregistre la creation dans l'historique
        $this->ajouterHistorique("Ouverture du compte avec un solde de {$soldeInitial} EUR");
    }

    // --- GETTERS (ACCESSEURS) ---
    // Les getters permettent de LIRE les proprietes privees de maniere controlee

    /**
     * Getter pour le solde.
     * On ne donne acces qu'en LECTURE, pas en ecriture.
     */
    public function getSolde(): float
    {
        return $this->solde;
    }

    /**
     * Getter pour la banque (protected, mais on fournit un acces public en lecture).
     */
    public function getBanque(): string
    {
        return $this->banque;
    }

    /**
     * Getter pour l'historique.
     * On retourne une COPIE du tableau, pas une reference.
     * Ainsi, l'exterieur ne peut pas modifier l'historique.
     */
    public function getHistorique(): array
    {
        return $this->historique;
    }

    // --- METHODES PUBLIQUES AVEC LOGIQUE METIER ---
    // Au lieu de laisser n'importe qui modifier le solde directement,
    // on fournit des methodes qui CONTROLENT les modifications.

    /**
     * Deposer de l'argent sur le compte.
     * La validation empeche les depots negatifs ou nuls.
     */
    public function deposer(float $montant): void
    {
        // VALIDATION : on refuse les montants invalides
        if ($montant <= 0) {
            echo "  [ERREUR] Le montant du depot doit etre positif.\n";
            return;
        }

        $this->solde += $montant;
        $this->ajouterHistorique("Depot de {$montant} EUR");
        echo "  Depot de {$montant} EUR effectue. Nouveau solde : {$this->solde} EUR\n";
    }

    /**
     * Retirer de l'argent du compte.
     * Plusieurs validations protegent les donnees.
     */
    public function retirer(float $montant, string $code): bool
    {
        // VALIDATION 1 : verifier le code secret
        if (!$this->verifierCode($code)) {
            echo "  [ERREUR] Code secret incorrect !\n";
            $this->ajouterHistorique("Tentative de retrait echouee : code incorrect");
            return false;
        }

        // VALIDATION 2 : montant positif
        if ($montant <= 0) {
            echo "  [ERREUR] Le montant du retrait doit etre positif.\n";
            return false;
        }

        // VALIDATION 3 : fonds suffisants
        if (!$this->verifierFonds($montant)) {
            echo "  [ERREUR] Fonds insuffisants ! Solde actuel : {$this->solde} EUR\n";
            $this->ajouterHistorique("Tentative de retrait de {$montant} EUR echouee : fonds insuffisants");
            return false;
        }

        $this->solde -= $montant;
        $this->ajouterHistorique("Retrait de {$montant} EUR");
        echo "  Retrait de {$montant} EUR effectue. Nouveau solde : {$this->solde} EUR\n";
        return true;
    }

    // --- METHODES PRIVEES ---
    // Ces methodes sont des outils internes, non accessibles de l'exterieur.

    /**
     * Methode PRIVEE : verifie si le code secret est correct.
     * Personne en dehors de cette classe ne peut appeler cette methode.
     */
    private function verifierCode(string $code): bool
    {
        return $code === $this->codeSecret;
    }

    /**
     * Methode PRIVEE : verifie si le solde est suffisant.
     */
    private function verifierFonds(float $montant): bool
    {
        return $this->solde >= $montant;
    }

    /**
     * Methode PRIVEE : ajoute une entree a l'historique.
     */
    private function ajouterHistorique(string $operation): void
    {
        $this->historique[] = date('Y-m-d H:i:s') . " - " . $operation;
    }

    /**
     * Affiche un resume du compte.
     */
    public function afficherResume(): void
    {
        echo "  Compte de {$this->titulaire} ({$this->banque})\n";
        echo "  Solde : {$this->solde} EUR\n";
        echo "  Nombre d'operations : " . count($this->historique) . "\n";
    }
}

// --- UTILISATION ---

$compte = new CompteBancaire("Alice Dupont", "BNP Paribas", 1000.0, "1234");

// ACCES PUBLIC : on peut acceder directement au titulaire
echo "Titulaire : " . $compte->titulaire . "\n";

// ACCES VIA GETTER : on lit le solde via une methode publique
echo "Solde initial : " . $compte->getSolde() . " EUR\n";

// Les lignes suivantes provoqueraient des ERREURS si on les decommentait :
// echo $compte->banque;       // ERREUR : "protected", inaccessible de l'exterieur
// echo $compte->solde;        // ERREUR : "private", inaccessible de l'exterieur
// echo $compte->codeSecret;   // ERREUR : "private"
// $compte->solde = 999999;    // ERREUR : on ne peut pas modifier directement
// $compte->verifierCode("x"); // ERREUR : methode privee

echo "\nOperations :\n";
$compte->deposer(500);              // OK : +500 EUR
$compte->deposer(-100);             // REFUSE : montant negatif
$compte->retirer(200, "1234");      // OK : code correct, fonds suffisants
$compte->retirer(200, "0000");      // REFUSE : code incorrect
$compte->retirer(5000, "1234");     // REFUSE : fonds insuffisants

echo "\nResume :\n";
$compte->afficherResume();

echo "\nHistorique :\n";
foreach ($compte->getHistorique() as $entry) {
    echo "  $entry\n";
}


echo "\n\n=== 2. GETTERS ET SETTERS AVEC VALIDATION ===\n\n";

/**
 * Les SETTERS permettent de MODIFIER les proprietes privees.
 * Leur grand interet : ils peuvent VALIDER les donnees avant de les accepter.
 *
 * Sans setters, n'importe qui pourrait mettre n'importe quelle valeur.
 * Avec setters, on controle ce qui entre dans l'objet.
 */
class Etudiant
{
    /**
     * Toutes les proprietes sont PRIVEES.
     * On y accede UNIQUEMENT via les getters et setters.
     */
    public function __construct(
        private string $prenom,
        private string $nom,
        private string $email,
        private int $age,
        private float $moyenne = 0.0
    ) {
        // On valide les donnees initiales via les setters
        // pour appliquer les memes regles qu'en modification
        $this->setEmail($email);
        $this->setAge($age);
        $this->setMoyenne($moyenne);
    }

    // --- GETTERS ---

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    /**
     * Getter qui retourne le nom complet.
     * Un getter peut aussi fournir une version "calculee" ou "formatee".
     */
    public function getNomComplet(): string
    {
        return "{$this->prenom} {$this->nom}";
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function getMoyenne(): float
    {
        return $this->moyenne;
    }

    // --- SETTERS AVEC VALIDATION ---

    /**
     * Setter pour le prenom.
     * Validation : le prenom doit contenir au moins 2 caracteres.
     */
    public function setPrenom(string $prenom): void
    {
        if (strlen(trim($prenom)) < 2) {
            throw new InvalidArgumentException(
                "Le prenom doit contenir au moins 2 caracteres. Recu : '$prenom'"
            );
        }
        // ucfirst() met la premiere lettre en majuscule automatiquement
        $this->prenom = ucfirst(trim($prenom));
    }

    /**
     * Setter pour le nom.
     * On le stocke en majuscules par convention.
     */
    public function setNom(string $nom): void
    {
        if (strlen(trim($nom)) < 2) {
            throw new InvalidArgumentException(
                "Le nom doit contenir au moins 2 caracteres. Recu : '$nom'"
            );
        }
        // strtoupper() met tout en majuscules
        $this->nom = strtoupper(trim($nom));
    }

    /**
     * Setter pour l'email avec validation du format.
     * C'est un excellent exemple de l'utilite des setters !
     */
    public function setEmail(string $email): void
    {
        // filter_var verifie si la chaine ressemble a un email valide
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException(
                "L'email '$email' n'est pas un format valide."
            );
        }
        $this->email = strtolower(trim($email));
    }

    /**
     * Setter pour l'age avec validation des bornes.
     */
    public function setAge(int $age): void
    {
        if ($age < 16 || $age > 99) {
            throw new InvalidArgumentException(
                "L'age d'un etudiant doit etre entre 16 et 99. Recu : $age"
            );
        }
        $this->age = $age;
    }

    /**
     * Setter pour la moyenne avec validation.
     * La moyenne doit etre entre 0 et 20 (systeme francais).
     */
    public function setMoyenne(float $moyenne): void
    {
        if ($moyenne < 0 || $moyenne > 20) {
            throw new InvalidArgumentException(
                "La moyenne doit etre entre 0 et 20. Recu : $moyenne"
            );
        }
        // On arrondit a 2 decimales
        $this->moyenne = round($moyenne, 2);
    }

    /**
     * Methode qui determine la mention en fonction de la moyenne.
     */
    public function getMention(): string
    {
        return match(true) {
            $this->moyenne >= 16 => "Tres Bien",
            $this->moyenne >= 14 => "Bien",
            $this->moyenne >= 12 => "Assez Bien",
            $this->moyenne >= 10 => "Passable",
            default              => "Insuffisant",
        };
    }

    public function afficher(): string
    {
        return "{$this->getNomComplet()} ({$this->email}) - " .
               "{$this->age} ans - Moyenne : {$this->moyenne}/20 ({$this->getMention()})";
    }
}

// --- UTILISATION ---

$etudiant = new Etudiant("alice", "dupont", "alice@universite.fr", 20, 15.5);

echo "Informations de l'etudiant :\n";
echo "  " . $etudiant->afficher() . "\n";

echo "\nModification via setters :\n";

// Le setter formate automatiquement le prenom (premiere lettre majuscule)
$etudiant->setPrenom("  bob  ");   // Les espaces sont supprimes, premiere lettre en majuscule
echo "  Nouveau prenom : " . $etudiant->getPrenom() . "\n";  // "Bob"

// Le setter formate le nom en majuscules
$etudiant->setNom("martin");
echo "  Nouveau nom : " . $etudiant->getNom() . "\n";  // "MARTIN"

// Modification de la moyenne
$etudiant->setMoyenne(17.75);
echo "  Nouvelle moyenne : " . $etudiant->getMoyenne() . "/20\n";
echo "  Mention : " . $etudiant->getMention() . "\n";

echo "\nValidations (les erreurs sont attrapees avec try/catch) :\n";

// Test des validations
$tests = [
    // [description, fonction a tester]
    ["Prenom trop court", fn() => $etudiant->setPrenom("A")],
    ["Email invalide", fn() => $etudiant->setEmail("pas-un-email")],
    ["Age invalide", fn() => $etudiant->setAge(10)],
    ["Moyenne invalide", fn() => $etudiant->setMoyenne(25)],
];

foreach ($tests as [$description, $test]) {
    try {
        $test(); // On essaie d'executer le test
        echo "  [OK] $description - aucune erreur (inattendu !)\n";
    } catch (InvalidArgumentException $e) {
        // Le setter a refuse la valeur et a lance une exception
        echo "  [REFUSE] $description : {$e->getMessage()}\n";
    }
}


echo "\n\n=== 3. ACCES DEPUIS UNE CLASSE ENFANT ===\n\n";

/**
 * Cette section montre la difference d'acces entre protected et private
 * quand on herite d'une classe.
 */
class Employe
{
    public function __construct(
        public string $nom,               // PUBLIC : accessible partout
        protected float $salaireBrut,     // PROTECTED : accessible dans les enfants
        private string $motDePasse        // PRIVATE : inaccessible dans les enfants
    ) {}

    /**
     * Methode PROTECTED : accessible dans les classes enfants.
     */
    protected function calculerCharges(): float
    {
        // Simulation : 23% de charges salariales
        return $this->salaireBrut * 0.23;
    }

    /**
     * Methode PRIVEE : inaccessible dans les classes enfants.
     */
    private function verifierMotDePasse(string $mdp): bool
    {
        return $mdp === $this->motDePasse;
    }

    /**
     * Methode publique qui utilise la methode privee en interne.
     */
    public function seConnecter(string $mdp): bool
    {
        return $this->verifierMotDePasse($mdp);
    }

    public function getSalaireNet(): float
    {
        return $this->salaireBrut - $this->calculerCharges();
    }
}

/**
 * Classe enfant : Manager.
 * Elle peut acceder a tout ce qui est PUBLIC ou PROTECTED du parent.
 * Mais PAS aux elements PRIVATE du parent.
 */
class Manager extends Employe
{
    public function __construct(
        string $nom,
        float $salaireBrut,
        string $motDePasse,
        private float $bonus = 0.0  // Propriete specifique au Manager
    ) {
        parent::__construct($nom, $salaireBrut, $motDePasse);
    }

    /**
     * Le Manager peut acceder aux proprietes et methodes PROTECTED du parent.
     */
    public function getSalaireComplet(): float
    {
        // $this->salaireBrut est PROTECTED : accessible ici (classe enfant)
        $net = $this->salaireBrut - $this->calculerCharges(); // methode PROTECTED : accessible

        // $this->motDePasse est PRIVATE : INACCESSIBLE ici
        // $this->verifierMotDePasse("test"); // ERREUR : methode privee du parent

        return $net + $this->bonus;
    }

    public function afficher(): string
    {
        return "{$this->nom} - Salaire brut: {$this->salaireBrut} EUR " .
               "- Net + bonus: {$this->getSalaireComplet()} EUR";
    }
}

// --- UTILISATION ---

$manager = new Manager("Sophie Martin", 4000.0, "secret123", 500.0);

// PUBLIC : accessible de l'exterieur
echo "Nom : " . $manager->nom . "\n";

// Les proprietes PROTECTED et PRIVATE ne sont pas accessibles de l'exterieur :
// echo $manager->salaireBrut;   // ERREUR : protected
// echo $manager->motDePasse;    // ERREUR : private
// $manager->calculerCharges();  // ERREUR : methode protected

// Mais on passe par des methodes publiques :
echo $manager->afficher() . "\n";
echo "Connexion : " . ($manager->seConnecter("secret123") ? "OK" : "Echouee") . "\n";
echo "Connexion : " . ($manager->seConnecter("mauvais") ? "OK" : "Echouee") . "\n";


echo "\n\n=== 4. POURQUOI L'ENCAPSULATION EST IMPORTANTE ===\n\n";

/**
 * Comparaison : SANS encapsulation vs AVEC encapsulation.
 */

echo "--- SANS encapsulation (DANGEREUX) ---\n\n";

class RectangleSansProtection
{
    // Tout est public : n'importe qui peut mettre n'importe quoi
    public float $largeur;
    public float $hauteur;

    public function __construct(float $largeur, float $hauteur)
    {
        $this->largeur = $largeur;
        $this->hauteur = $hauteur;
    }

    public function aire(): float
    {
        return $this->largeur * $this->hauteur;
    }
}

$rect = new RectangleSansProtection(5.0, 3.0);
echo "  Aire initiale : " . $rect->aire() . "\n";  // 15.0

// PROBLEME : rien n'empeche de mettre des valeurs absurdes !
$rect->largeur = -10;    // Valeur negative : pas de sens pour une largeur
$rect->hauteur = 0;      // Zero : un rectangle de hauteur zero ?
echo "  Aire apres modification absurde : " . $rect->aire() . "\n";  // 0 ou negatif !

echo "\n--- AVEC encapsulation (SECURISE) ---\n\n";

class RectangleProtege
{
    // Tout est prive : on controle les acces
    public function __construct(
        private float $largeur,
        private float $hauteur
    ) {
        // On valide des la creation
        $this->setLargeur($largeur);
        $this->setHauteur($hauteur);
    }

    public function getLargeur(): float
    {
        return $this->largeur;
    }

    public function setLargeur(float $largeur): void
    {
        if ($largeur <= 0) {
            throw new InvalidArgumentException("La largeur doit etre strictement positive. Recu : $largeur");
        }
        $this->largeur = $largeur;
    }

    public function getHauteur(): float
    {
        return $this->hauteur;
    }

    public function setHauteur(float $hauteur): void
    {
        if ($hauteur <= 0) {
            throw new InvalidArgumentException("La hauteur doit etre strictement positive. Recu : $hauteur");
        }
        $this->hauteur = $hauteur;
    }

    public function aire(): float
    {
        return $this->largeur * $this->hauteur;
    }

    public function perimetre(): float
    {
        return 2 * ($this->largeur + $this->hauteur);
    }
}

$rect2 = new RectangleProtege(5.0, 3.0);
echo "  Aire : " . $rect2->aire() . "\n";          // 15.0
echo "  Perimetre : " . $rect2->perimetre() . "\n"; // 16.0

// Modification valide
$rect2->setLargeur(10.0);
echo "  Nouvelle aire : " . $rect2->aire() . "\n";  // 30.0

// Modification invalide : le setter refuse !
try {
    $rect2->setLargeur(-5);
} catch (InvalidArgumentException $e) {
    echo "  [REFUSE] " . $e->getMessage() . "\n";
}

// $rect2->largeur = -5;  // ERREUR : propriete privee, inaccessible


echo "\n\n=== RESUME ===\n";
echo "----------------------------------------------------------------------\n";
echo "| Concept            | Description                                   |\n";
echo "----------------------------------------------------------------------\n";
echo "| public             | Accessible partout                            |\n";
echo "| protected          | Accessible dans la classe et ses enfants      |\n";
echo "| private            | Accessible uniquement dans la classe          |\n";
echo "| Getter             | Methode publique qui LIT une propriete privee |\n";
echo "| Setter             | Methode publique qui MODIFIE avec validation  |\n";
echo "| Encapsulation      | Proteger les donnees, controler les acces     |\n";
echo "----------------------------------------------------------------------\n";
echo "\nRegle d'or : rendez vos proprietes PRIVEES par defaut,\n";
echo "puis fournissez des getters/setters seulement si necessaire.\n";
