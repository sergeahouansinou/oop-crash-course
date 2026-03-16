<?php
declare(strict_types=1);

/**
 * =============================================================================
 * COURS POO PHP - 04 : POLYMORPHISME
 * =============================================================================
 *
 * Ce fichier demontre le polymorphisme en PHP 8+ :
 * - Definition d'interfaces avec plusieurs methodes
 * - Plusieurs classes qui implementent la meme interface
 * - Implementation de PLUSIEURS interfaces par une seule classe
 * - Fonctions qui acceptent un type d'interface (type hinting)
 * - Exemple pratique : systeme de paiement avec differents moyens
 *
 * Le POLYMORPHISME signifie "plusieurs formes". C'est la capacite de traiter
 * des objets DIFFERENTS de la MEME maniere, tant qu'ils partagent une
 * interface ou une classe parent commune.
 *
 * Executez ce fichier avec : php 04_polymorphisme.php
 */

echo "=== 1. DEFINITION D'INTERFACES ===\n\n";

/**
 * INTERFACE : un contrat que les classes doivent respecter.
 *
 * Une interface definit des methodes SANS les implementer.
 * Toute classe qui "implements" une interface DOIT fournir
 * le code de CHAQUE methode definie dans l'interface.
 *
 * C'est comme un cahier des charges : on dit CE QUI doit etre fait,
 * mais pas COMMENT le faire. Chaque classe decide du "comment".
 */
interface MoyenDePaiement
{
    /**
     * Effectuer un paiement du montant donne.
     * Retourne true si le paiement a reussi, false sinon.
     */
    public function payer(float $montant): bool;

    /**
     * Rembourser un montant.
     */
    public function rembourser(float $montant): bool;

    /**
     * Obtenir le nom du moyen de paiement.
     */
    public function getNom(): string;

    /**
     * Obtenir le solde disponible ou la limite restante.
     */
    public function getSoldeDisponible(): float;
}

/**
 * DEUXIEME INTERFACE : pour montrer l'implementation multiple.
 *
 * Cette interface definit des methodes de journalisation (logging).
 * Un moyen de paiement qui implemente cette interface peut
 * enregistrer et consulter l'historique de ses operations.
 */
interface Journalisable
{
    /**
     * Enregistrer une entree dans le journal.
     */
    public function enregistrer(string $message): void;

    /**
     * Obtenir toutes les entrees du journal.
     * @return string[]
     */
    public function getJournal(): array;
}


echo "=== 2. CLASSES IMPLEMENTANT LES INTERFACES ===\n\n";

/**
 * PREMIERE IMPLEMENTATION : Carte Bancaire.
 *
 * Cette classe implemente LES DEUX interfaces : MoyenDePaiement ET Journalisable.
 * Elle DOIT donc fournir le code de TOUTES les methodes des deux interfaces.
 */
class CarteBancaire implements MoyenDePaiement, Journalisable
{
    // Proprietes specifiques a la carte bancaire
    private float $solde;

    // Journal des operations (pour l'interface Journalisable)
    private array $journal = [];

    /**
     * Constructeur avec promotion des proprietes (PHP 8).
     */
    public function __construct(
        private string $numeroCarte,
        private string $titulaire,
        float $soldeInitial
    ) {
        $this->solde = $soldeInitial;
        $this->enregistrer("Carte initialisee avec un solde de {$soldeInitial} EUR");
    }

    // --- Implementation de MoyenDePaiement ---

    public function payer(float $montant): bool
    {
        if ($montant <= 0) {
            $this->enregistrer("Paiement refuse : montant invalide ({$montant} EUR)");
            return false;
        }

        if ($montant > $this->solde) {
            $this->enregistrer("Paiement de {$montant} EUR refuse : solde insuffisant");
            return false;
        }

        $this->solde -= $montant;
        $this->enregistrer("Paiement de {$montant} EUR effectue. Solde restant : {$this->solde} EUR");
        return true;
    }

    public function rembourser(float $montant): bool
    {
        if ($montant <= 0) {
            return false;
        }

        $this->solde += $montant;
        $this->enregistrer("Remboursement de {$montant} EUR. Nouveau solde : {$this->solde} EUR");
        return true;
    }

    public function getNom(): string
    {
        // On masque le numero de carte pour la securite (affiche seulement les 4 derniers chiffres)
        $carteMasquee = "****-" . substr($this->numeroCarte, -4);
        return "Carte Bancaire ({$carteMasquee})";
    }

    public function getSoldeDisponible(): float
    {
        return $this->solde;
    }

    // --- Implementation de Journalisable ---

    public function enregistrer(string $message): void
    {
        $this->journal[] = "[CB] " . date('H:i:s') . " - " . $message;
    }

    public function getJournal(): array
    {
        return $this->journal;
    }
}


/**
 * DEUXIEME IMPLEMENTATION : PayPal.
 *
 * Meme interfaces, mais implementation DIFFERENTE.
 * PayPal a sa propre logique (frais de transaction, email, etc.).
 */
class PayPal implements MoyenDePaiement, Journalisable
{
    private float $solde;
    private array $journal = [];

    // Constante specifique a PayPal : frais de transaction (2.9%)
    private const FRAIS_TRANSACTION = 0.029;

    public function __construct(
        private string $email,
        float $soldeInitial
    ) {
        $this->solde = $soldeInitial;
        $this->enregistrer("Compte PayPal initialise ({$this->email})");
    }

    public function payer(float $montant): bool
    {
        // PayPal ajoute des frais de transaction !
        $frais = $montant * self::FRAIS_TRANSACTION;
        $montantTotal = $montant + $frais;

        if ($montantTotal > $this->solde) {
            $this->enregistrer("Paiement refuse : solde insuffisant " .
                "(montant: {$montant} EUR + frais: " . round($frais, 2) . " EUR)");
            return false;
        }

        $this->solde -= $montantTotal;
        $this->enregistrer("Paiement de {$montant} EUR + frais " . round($frais, 2) .
            " EUR. Solde : {$this->solde} EUR");
        return true;
    }

    public function rembourser(float $montant): bool
    {
        if ($montant <= 0) {
            return false;
        }

        // PayPal rembourse le montant SANS les frais
        $this->solde += $montant;
        $this->enregistrer("Remboursement de {$montant} EUR. Solde : {$this->solde} EUR");
        return true;
    }

    public function getNom(): string
    {
        return "PayPal ({$this->email})";
    }

    public function getSoldeDisponible(): float
    {
        return $this->solde;
    }

    public function enregistrer(string $message): void
    {
        $this->journal[] = "[PP] " . date('H:i:s') . " - " . $message;
    }

    public function getJournal(): array
    {
        return $this->journal;
    }
}


/**
 * TROISIEME IMPLEMENTATION : Virement Bancaire.
 *
 * Celui-ci n'implemente QUE l'interface MoyenDePaiement (pas Journalisable).
 * C'est permis : une classe choisit quelles interfaces elle implemente.
 */
class VirementBancaire implements MoyenDePaiement
{
    private float $solde;

    public function __construct(
        private string $iban,
        private string $nomBanque,
        float $soldeInitial
    ) {
        $this->solde = $soldeInitial;
    }

    public function payer(float $montant): bool
    {
        if ($montant <= 0 || $montant > $this->solde) {
            return false;
        }

        // Le virement prend 1 a 3 jours (simulation)
        $this->solde -= $montant;
        echo "    [Virement] Traitement en cours (delai : 1-3 jours ouvrables)\n";
        return true;
    }

    public function rembourser(float $montant): bool
    {
        if ($montant <= 0) {
            return false;
        }

        $this->solde += $montant;
        echo "    [Virement] Remboursement en cours (delai : 3-5 jours ouvrables)\n";
        return true;
    }

    public function getNom(): string
    {
        // On masque l'IBAN partiellement
        $ibanMasque = substr($this->iban, 0, 4) . "****" . substr($this->iban, -4);
        return "Virement ({$this->nomBanque} - {$ibanMasque})";
    }

    public function getSoldeDisponible(): float
    {
        return $this->solde;
    }
}


/**
 * QUATRIEME IMPLEMENTATION : Portefeuille Crypto (pour varier les exemples).
 *
 * Implemente les deux interfaces.
 */
class PortefeuilleCrypto implements MoyenDePaiement, Journalisable
{
    private float $soldeEuros;
    private array $journal = [];

    public function __construct(
        private string $adresseWallet,
        private string $cryptoMonnaie,
        float $soldeEuros
    ) {
        $this->soldeEuros = $soldeEuros;
        $this->enregistrer("Portefeuille {$this->cryptoMonnaie} initialise");
    }

    public function payer(float $montant): bool
    {
        if ($montant > $this->soldeEuros) {
            $this->enregistrer("Paiement refuse : fonds insuffisants");
            return false;
        }

        $this->soldeEuros -= $montant;
        $this->enregistrer("Paiement de {$montant} EUR en {$this->cryptoMonnaie}");
        return true;
    }

    public function rembourser(float $montant): bool
    {
        $this->soldeEuros += $montant;
        $this->enregistrer("Remboursement de {$montant} EUR");
        return true;
    }

    public function getNom(): string
    {
        $adresseCourte = substr($this->adresseWallet, 0, 6) . "...";
        return "{$this->cryptoMonnaie} ({$adresseCourte})";
    }

    public function getSoldeDisponible(): float
    {
        return $this->soldeEuros;
    }

    public function enregistrer(string $message): void
    {
        $this->journal[] = "[CRYPTO] " . date('H:i:s') . " - " . $message;
    }

    public function getJournal(): array
    {
        return $this->journal;
    }
}


echo "=== 3. POLYMORPHISME EN ACTION ===\n\n";

/**
 * Voici la puissance du polymorphisme :
 * cette fonction accepte N'IMPORTE QUEL objet qui implemente MoyenDePaiement.
 *
 * Elle ne sait pas et N'A PAS BESOIN DE SAVOIR si c'est une carte,
 * du PayPal, un virement ou de la crypto. Elle utilise simplement
 * les methodes definies dans l'interface.
 *
 * Le TYPE HINTING (MoyenDePaiement $moyen) garantit que l'objet
 * possede bien les methodes payer(), rembourser(), getNom(), etc.
 */
function effectuerPaiement(MoyenDePaiement $moyen, float $montant): void
{
    echo "  Moyen de paiement : {$moyen->getNom()}\n";
    echo "  Solde disponible : {$moyen->getSoldeDisponible()} EUR\n";
    echo "  Montant a payer : {$montant} EUR\n";

    // Appel polymorphe : selon l'objet reel, c'est une implementation differente
    // qui sera executee (carte, PayPal, virement, crypto...)
    if ($moyen->payer($montant)) {
        echo "  Resultat : PAIEMENT REUSSI\n";
    } else {
        echo "  Resultat : PAIEMENT ECHOUE\n";
    }

    echo "  Solde apres operation : {$moyen->getSoldeDisponible()} EUR\n";
    echo "\n";
}

/**
 * Fonction qui accepte UNIQUEMENT les objets qui implementent Journalisable.
 * Cela illustre le type hinting avec une interface specifique.
 */
function afficherJournal(Journalisable $objet): void
{
    $journal = $objet->getJournal();
    if (empty($journal)) {
        echo "  (journal vide)\n";
        return;
    }
    foreach ($journal as $entree) {
        echo "  $entree\n";
    }
}


// --- CREATION DES MOYENS DE PAIEMENT ---
// Chacun a sa propre implementation, mais tous respectent le contrat MoyenDePaiement

$carte = new CarteBancaire("4242424242421234", "Alice Dupont", 500.0);
$paypal = new PayPal("alice@exemple.fr", 300.0);
$virement = new VirementBancaire("FR7612345678901234", "Credit Agricole", 1000.0);
$crypto = new PortefeuilleCrypto("0xAbC123DeF456", "Ethereum", 200.0);

// --- POLYMORPHISME : on traite tous les moyens de la meme facon ---

echo "--- Paiements avec differents moyens ---\n\n";

// La meme fonction effectuerPaiement() fonctionne avec TOUS les types
effectuerPaiement($carte, 120.0);    // Carte bancaire
effectuerPaiement($paypal, 50.0);    // PayPal (avec frais)
effectuerPaiement($virement, 250.0); // Virement bancaire
effectuerPaiement($crypto, 75.0);    // Crypto-monnaie


echo "\n=== 4. BOUCLE POLYMORPHE SUR UN TABLEAU ===\n\n";

/**
 * On peut stocker des objets de types DIFFERENTS dans un seul tableau,
 * tant qu'ils partagent la meme interface.
 * Puis on les parcourt avec une seule boucle.
 */

// Tableau de moyens de paiement (types differents, interface commune)
$moyens = [$carte, $paypal, $virement, $crypto];

echo "--- Paiement d'une commande de 30 EUR avec chaque moyen ---\n\n";

foreach ($moyens as $moyen) {
    // Grace au polymorphisme, on appelle payer() sans se soucier du type reel
    echo "  {$moyen->getNom()} : ";

    if ($moyen->payer(30.0)) {
        echo "30 EUR payes. Solde restant : {$moyen->getSoldeDisponible()} EUR\n";
    } else {
        echo "Echec du paiement.\n";
    }
}


echo "\n\n=== 5. TYPE HINTING AVEC INTERFACE SPECIFIQUE ===\n\n";

/**
 * On peut aussi filtrer les objets selon les interfaces qu'ils implementent.
 * Le mot-cle "instanceof" verifie si un objet implemente une interface donnee.
 */

echo "--- Journaux des moyens de paiement qui supportent le journal ---\n\n";

foreach ($moyens as $moyen) {
    echo "  {$moyen->getNom()} :\n";

    // On verifie si l'objet implemente l'interface Journalisable
    if ($moyen instanceof Journalisable) {
        // Ce moyen supporte le journal => on peut appeler afficherJournal()
        afficherJournal($moyen);
    } else {
        echo "    (ce moyen de paiement ne supporte pas la journalisation)\n";
    }
    echo "\n";
}


echo "\n=== 6. AJOUTER UN NOUVEAU MOYEN SANS MODIFIER LE CODE EXISTANT ===\n\n";

/**
 * La grande force du polymorphisme : on peut AJOUTER un nouveau moyen
 * de paiement sans MODIFIER aucune fonction existante.
 *
 * Il suffit de creer une nouvelle classe qui implemente l'interface.
 * Toutes les fonctions qui acceptent MoyenDePaiement fonctionneront
 * automatiquement avec ce nouveau type.
 *
 * C'est le PRINCIPE OUVERT/FERME :
 * - OUVERT a l'extension (on peut ajouter de nouveaux types)
 * - FERME a la modification (on ne modifie pas le code existant)
 */
class ChequeVacances implements MoyenDePaiement
{
    private float $solde;

    public function __construct(
        private string $numeroCheque,
        float $valeur
    ) {
        $this->solde = $valeur;
    }

    public function payer(float $montant): bool
    {
        // Les cheques vacances ne permettent que certains types d'achats
        // (simplifie ici : on verifie juste le solde)
        if ($montant > $this->solde) {
            echo "    [Cheque Vacances] Montant superieur a la valeur du cheque.\n";
            return false;
        }
        $this->solde -= $montant;
        return true;
    }

    public function rembourser(float $montant): bool
    {
        echo "    [Cheque Vacances] Les cheques vacances ne sont pas remboursables.\n";
        return false;
    }

    public function getNom(): string
    {
        return "Cheque Vacances (#{$this->numeroCheque})";
    }

    public function getSoldeDisponible(): float
    {
        return $this->solde;
    }
}

// Ce NOUVEAU moyen fonctionne IMMEDIATEMENT avec la fonction existante
$cheque = new ChequeVacances("CV-2024-001", 150.0);
effectuerPaiement($cheque, 80.0);  // Fonctionne sans modifier effectuerPaiement() !


echo "\n=== 7. EXEMPLE PRATIQUE COMPLET : TRAITEMENT D'UNE COMMANDE ===\n\n";

/**
 * Un exemple plus complet qui montre comment le polymorphisme
 * est utilise dans un vrai projet.
 */
class Commande
{
    /** @var array<string, float> Liste des articles (nom => prix) */
    private array $articles = [];

    public function __construct(
        private int $numero
    ) {}

    public function ajouterArticle(string $nom, float $prix): void
    {
        $this->articles[$nom] = $prix;
    }

    public function getTotal(): float
    {
        return array_sum($this->articles);
    }

    /**
     * METHODE POLYMORPHE : elle accepte N'IMPORTE QUEL moyen de paiement.
     * Elle ne connait pas et n'a pas besoin de connaitre le type reel.
     */
    public function finaliser(MoyenDePaiement $moyen): bool
    {
        $total = $this->getTotal();

        echo "  Commande #{$this->numero}\n";
        echo "  Articles :\n";
        foreach ($this->articles as $nom => $prix) {
            echo "    - {$nom} : {$prix} EUR\n";
        }
        echo "  Total : {$total} EUR\n";
        echo "  Paiement par : {$moyen->getNom()}\n";

        // Appel polymorphe a payer()
        if ($moyen->payer($total)) {
            echo "  => Commande VALIDEE !\n";
            return true;
        }

        echo "  => Commande REFUSEE (paiement echoue)\n";
        return false;
    }
}

// --- Simulation ---

$commande = new Commande(12345);
$commande->ajouterArticle("Livre PHP", 29.99);
$commande->ajouterArticle("Clavier USB", 19.99);
$commande->ajouterArticle("Souris", 14.99);

// On peut payer avec n'importe quel moyen, la commande s'en fiche !
$moyenChoisi = new CarteBancaire("5555666677778888", "Bob Martin", 200.0);

echo "--- Finalisation de la commande ---\n\n";
$commande->finaliser($moyenChoisi);

// On pourrait aussi payer avec PayPal, crypto, virement... sans changer Commande !
echo "\n--- Meme commande, autre moyen de paiement ---\n\n";
$commande2 = new Commande(12346);
$commande2->ajouterArticle("Formation en ligne", 99.99);

$autreMethode = new PayPal("bob@exemple.fr", 150.0);
$commande2->finaliser($autreMethode);


echo "\n\n=== RESUME ===\n";
echo "----------------------------------------------------------------------\n";
echo "| Concept               | Syntaxe / Explication                     |\n";
echo "----------------------------------------------------------------------\n";
echo "| Interface             | interface NomInterface { }                |\n";
echo "| Implementer           | class X implements InterfaceA, InterfaceB |\n";
echo "| Methode d'interface   | public function methode(): type;         |\n";
echo "| Type hinting          | function f(MonInterface \$obj): void      |\n";
echo "| instanceof            | if (\$obj instanceof MonInterface)         |\n";
echo "| Polymorphisme         | Traiter des objets differents de la meme |\n";
echo "|                       | facon via une interface commune           |\n";
echo "| Principe Ouvert/Ferme | Ajouter des classes sans modifier le     |\n";
echo "|                       | code existant                            |\n";
echo "----------------------------------------------------------------------\n";
echo "\nRetenir : le polymorphisme permet d'ecrire du code FLEXIBLE\n";
echo "et EXTENSIBLE. On programme pour une INTERFACE, pas pour une\n";
echo "implementation concrete.\n";
