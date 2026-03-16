<?php
/**
 * ============================================================
 * POINT D'ENTREE DE L'API REST - ROUTEUR
 * ============================================================
 *
 * Ce fichier est le point d'entree unique de l'API.
 * Toutes les requetes HTTP passent par ce fichier.
 *
 * Son role est de :
 * 1. Configurer les en-tetes HTTP (JSON, CORS)
 * 2. Charger les dependances (Database, Produit)
 * 3. Lire la methode HTTP et le parametre "action"
 * 4. Router la requete vers la bonne operation CRUD
 * 5. Retourner la reponse en JSON
 *
 * Endpoints disponibles :
 * ---------------------------------------------------------------
 * | Methode | URL                             | Action          |
 * ---------------------------------------------------------------
 * | GET     | /index.php?action=produits      | Lister tout     |
 * | GET     | /index.php?action=produit&id=1  | Lire un produit |
 * | POST    | /index.php?action=creer         | Creer           |
 * | PUT     | /index.php?action=modifier&id=1 | Modifier        |
 * | DELETE  | /index.php?action=supprimer&id=1| Supprimer       |
 * ---------------------------------------------------------------
 *
 * Test avec Postman :
 *   URL de base : http://localhost:8888/oop-crash-course/cours-web/06_API_REST/
 *
 * ============================================================
 */

declare(strict_types=1);


// ============================================================
// 1. EN-TETES HTTP
// ============================================================

/**
 * Content-Type : indique au client que la reponse est du JSON.
 * Tous les navigateurs et outils (Postman, fetch) comprendront
 * que les donnees retournees sont au format JSON.
 */
header('Content-Type: application/json; charset=UTF-8');

/**
 * Access-Control-Allow-Origin : autorise les requetes depuis
 * n'importe quelle origine (CORS).
 * En production, on remplacerait * par l'URL exacte du frontend.
 */
header('Access-Control-Allow-Origin: *');

/**
 * Access-Control-Allow-Methods : liste les methodes HTTP autorisees.
 * GET = lire, POST = creer, PUT = modifier, DELETE = supprimer.
 */
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

/**
 * Access-Control-Allow-Headers : autorise le header Content-Type
 * dans les requetes du client (necessaire pour envoyer du JSON).
 */
header('Access-Control-Allow-Headers: Content-Type');

/**
 * Requete preflight OPTIONS :
 * Avant certaines requetes (PUT, DELETE), le navigateur envoie
 * une requete OPTIONS pour verifier les permissions CORS.
 * On repond immediatement avec un code 200 sans traitement.
 */
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}


// ============================================================
// 2. CHARGEMENT DES DEPENDANCES
// ============================================================

// Charger la classe de connexion a la base de donnees
require_once __DIR__ . '/config/database.php';

// Charger le modele Produit
require_once __DIR__ . '/models/Produit.php';


// ============================================================
// 3. INITIALISATION
// ============================================================

// Creer la connexion a la base de donnees
$database = new Database();
$connexion = $database->getConnexion();

// Creer une instance du modele Produit
$produit = new Produit($connexion);

// Lire la methode HTTP utilisee (GET, POST, PUT, DELETE)
$methode = $_SERVER['REQUEST_METHOD'];

// Lire le parametre "action" dans l'URL (ex: ?action=produits)
$action = $_GET['action'] ?? '';

// Lire le parametre "id" dans l'URL (ex: ?id=1)
$id = isset($_GET['id']) ? (int) $_GET['id'] : null;


// ============================================================
// 4. ROUTEUR - DISPATCH SELON L'ACTION ET LA METHODE HTTP
// ============================================================

/**
 * Le routeur utilise un match (PHP 8) pour determiner
 * quelle operation executer selon l'action demandee.
 *
 * Chaque action verifie que la methode HTTP est correcte :
 * - GET pour les lectures
 * - POST pour la creation
 * - PUT pour la modification
 * - DELETE pour la suppression
 */

match ($action) {

    // ========================================
    // LISTER TOUS LES PRODUITS
    // GET /index.php?action=produits
    // ========================================
    'produits' => (function () use ($methode, $produit): void {

        // Verifier que la methode est bien GET
        if ($methode !== 'GET') {
            repondreErreur(405, 'Methode non autorisee. Utilisez GET.');
            return;
        }

        // Recuperer tous les produits depuis la base de donnees
        $resultats = $produit->lireTous();

        // Repondre avec le code 200 (OK) et les donnees
        repondreSucces(200, [
            'nombre'   => count($resultats),
            'produits' => $resultats,
        ]);
    })(),


    // ========================================
    // LIRE UN SEUL PRODUIT
    // GET /index.php?action=produit&id=1
    // ========================================
    'produit' => (function () use ($methode, $produit, $id): void {

        if ($methode !== 'GET') {
            repondreErreur(405, 'Methode non autorisee. Utilisez GET.');
            return;
        }

        // Verifier que l'id est fourni
        if ($id === null || $id <= 0) {
            repondreErreur(400, 'Le parametre "id" est obligatoire et doit etre un entier positif.');
            return;
        }

        // Chercher le produit dans la base
        $resultat = $produit->lireParId($id);

        // Si le produit n'existe pas
        if ($resultat === false) {
            repondreErreur(404, "Aucun produit trouve avec l'id $id.");
            return;
        }

        repondreSucces(200, ['produit' => $resultat]);
    })(),


    // ========================================
    // CREER UN NOUVEAU PRODUIT
    // POST /index.php?action=creer
    // ========================================
    'creer' => (function () use ($methode, $produit): void {

        if ($methode !== 'POST') {
            repondreErreur(405, 'Methode non autorisee. Utilisez POST.');
            return;
        }

        // Lire le corps de la requete (les donnees JSON envoyees par le client)
        $donnees = lireDonneesJSON();

        if ($donnees === null) {
            repondreErreur(400, 'Le corps de la requete doit contenir du JSON valide.');
            return;
        }

        // Valider les champs obligatoires
        $erreurs = validerDonneesProduit($donnees);
        if (!empty($erreurs)) {
            repondreErreur(422, 'Donnees invalides.', $erreurs);
            return;
        }

        // Remplir les proprietes du modele avec les donnees recues
        $produit->nom         = trim($donnees['nom']);
        $produit->description = isset($donnees['description']) ? trim($donnees['description']) : null;
        $produit->prix        = (float) $donnees['prix'];
        $produit->stock       = (int) ($donnees['stock'] ?? 0);
        $produit->categorie   = isset($donnees['categorie']) ? trim($donnees['categorie']) : null;

        // Inserer dans la base de donnees
        if ($produit->creer()) {
            // Code 201 = "Created" (ressource creee avec succes)
            repondreSucces(201, [
                'message' => 'Produit cree avec succes.',
                'produit' => [
                    'id'          => $produit->id,
                    'nom'         => $produit->nom,
                    'description' => $produit->description,
                    'prix'        => $produit->prix,
                    'stock'       => $produit->stock,
                    'categorie'   => $produit->categorie,
                ],
            ]);
        } else {
            repondreErreur(500, 'Erreur lors de la creation du produit.');
        }
    })(),


    // ========================================
    // MODIFIER UN PRODUIT EXISTANT
    // PUT /index.php?action=modifier&id=1
    // ========================================
    'modifier' => (function () use ($methode, $produit, $id): void {

        if ($methode !== 'PUT') {
            repondreErreur(405, 'Methode non autorisee. Utilisez PUT.');
            return;
        }

        if ($id === null || $id <= 0) {
            repondreErreur(400, 'Le parametre "id" est obligatoire et doit etre un entier positif.');
            return;
        }

        // Verifier que le produit existe avant de le modifier
        $existant = $produit->lireParId($id);
        if ($existant === false) {
            repondreErreur(404, "Aucun produit trouve avec l'id $id.");
            return;
        }

        // Lire les nouvelles donnees
        $donnees = lireDonneesJSON();

        if ($donnees === null) {
            repondreErreur(400, 'Le corps de la requete doit contenir du JSON valide.');
            return;
        }

        // Valider les donnees
        $erreurs = validerDonneesProduit($donnees);
        if (!empty($erreurs)) {
            repondreErreur(422, 'Donnees invalides.', $erreurs);
            return;
        }

        // Remplir les proprietes du modele
        $produit->id          = $id;
        $produit->nom         = trim($donnees['nom']);
        $produit->description = isset($donnees['description']) ? trim($donnees['description']) : null;
        $produit->prix        = (float) $donnees['prix'];
        $produit->stock       = (int) ($donnees['stock'] ?? 0);
        $produit->categorie   = isset($donnees['categorie']) ? trim($donnees['categorie']) : null;

        if ($produit->modifier()) {
            repondreSucces(200, [
                'message' => 'Produit modifie avec succes.',
                'produit' => [
                    'id'          => $produit->id,
                    'nom'         => $produit->nom,
                    'description' => $produit->description,
                    'prix'        => $produit->prix,
                    'stock'       => $produit->stock,
                    'categorie'   => $produit->categorie,
                ],
            ]);
        } else {
            repondreErreur(500, 'Erreur lors de la modification du produit.');
        }
    })(),


    // ========================================
    // SUPPRIMER UN PRODUIT
    // DELETE /index.php?action=supprimer&id=1
    // ========================================
    'supprimer' => (function () use ($methode, $produit, $id): void {

        if ($methode !== 'DELETE') {
            repondreErreur(405, 'Methode non autorisee. Utilisez DELETE.');
            return;
        }

        if ($id === null || $id <= 0) {
            repondreErreur(400, 'Le parametre "id" est obligatoire et doit etre un entier positif.');
            return;
        }

        if ($produit->supprimer($id)) {
            repondreSucces(200, [
                'message' => "Produit #$id supprime avec succes.",
            ]);
        } else {
            repondreErreur(404, "Aucun produit trouve avec l'id $id.");
        }
    })(),


    // ========================================
    // ACTION INCONNUE OU MANQUANTE
    // ========================================
    default => repondreErreur(400, 'Action inconnue ou manquante. Actions disponibles : produits, produit, creer, modifier, supprimer.'),
};


// ============================================================
// 5. FONCTIONS UTILITAIRES
// ============================================================

/**
 * Lit et decode le corps de la requete JSON.
 *
 * Quand un client (Postman, fetch) envoie des donnees en JSON,
 * elles arrivent dans le "body" de la requete HTTP.
 * php://input permet de lire ce body brut.
 *
 * @return array|null - Les donnees decodees, ou null si le JSON est invalide
 */
function lireDonneesJSON(): ?array
{
    // php://input lit le corps brut de la requete
    $json = file_get_contents('php://input');

    // json_decode convertit le JSON en tableau associatif (true = tableau, pas objet)
    $donnees = json_decode($json, true);

    // json_last_error() retourne JSON_ERROR_NONE si le decodage a reussi
    if (json_last_error() !== JSON_ERROR_NONE) {
        return null;
    }

    return $donnees;
}


/**
 * Valide les donnees d'un produit.
 *
 * Verifie que les champs obligatoires sont presents et valides.
 * Retourne un tableau d'erreurs (vide si tout est valide).
 *
 * @param array $donnees - Les donnees a valider
 * @return array - Tableau des erreurs de validation
 */
function validerDonneesProduit(array $donnees): array
{
    $erreurs = [];

    // Nom : obligatoire, entre 1 et 100 caracteres
    if (empty($donnees['nom']) || !is_string($donnees['nom'])) {
        $erreurs[] = 'Le champ "nom" est obligatoire (chaine de caracteres).';
    } elseif (mb_strlen(trim($donnees['nom'])) > 100) {
        $erreurs[] = 'Le champ "nom" ne peut pas depasser 100 caracteres.';
    }

    // Prix : obligatoire, nombre positif
    if (!isset($donnees['prix'])) {
        $erreurs[] = 'Le champ "prix" est obligatoire.';
    } elseif (!is_numeric($donnees['prix']) || (float) $donnees['prix'] < 0) {
        $erreurs[] = 'Le champ "prix" doit etre un nombre positif.';
    }

    // Stock : optionnel, mais si present doit etre un entier >= 0
    if (isset($donnees['stock'])) {
        if (!is_numeric($donnees['stock']) || (int) $donnees['stock'] < 0) {
            $erreurs[] = 'Le champ "stock" doit etre un entier positif ou zero.';
        }
    }

    // Categorie : optionnel, max 50 caracteres
    if (isset($donnees['categorie']) && is_string($donnees['categorie'])) {
        if (mb_strlen(trim($donnees['categorie'])) > 50) {
            $erreurs[] = 'Le champ "categorie" ne peut pas depasser 50 caracteres.';
        }
    }

    return $erreurs;
}


/**
 * Envoie une reponse JSON de succes.
 *
 * @param int   $codeHTTP - Le code de statut HTTP (200, 201, etc.)
 * @param array $donnees  - Les donnees a inclure dans la reponse
 */
function repondreSucces(int $codeHTTP, array $donnees): void
{
    http_response_code($codeHTTP);
    echo json_encode(
        array_merge(['erreur' => false], $donnees),
        JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
    );
}


/**
 * Envoie une reponse JSON d'erreur.
 *
 * @param int    $codeHTTP - Le code de statut HTTP (400, 404, 500, etc.)
 * @param string $message  - Le message d'erreur principal
 * @param array  $details  - Details supplementaires (optionnel)
 */
function repondreErreur(int $codeHTTP, string $message, array $details = []): void
{
    http_response_code($codeHTTP);
    $reponse = [
        'erreur'  => true,
        'message' => $message,
    ];

    if (!empty($details)) {
        $reponse['details'] = $details;
    }

    echo json_encode($reponse, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}
