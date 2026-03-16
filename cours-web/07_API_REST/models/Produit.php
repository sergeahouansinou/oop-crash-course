<?php
/**
 * ============================================================
 * MODELE PRODUIT - OPERATIONS CRUD
 * ============================================================
 *
 * Cette classe represente un produit du magasin et contient
 * toutes les methodes pour interagir avec la table "produits"
 * de la base de donnees.
 *
 * CRUD signifie :
 * - Create (Creer)   => methode creer()
 * - Read (Lire)       => methodes lireTous() et lireParId()
 * - Update (Modifier) => methode modifier()
 * - Delete (Supprimer)=> methode supprimer()
 *
 * Chaque methode utilise des REQUETES PREPAREES (prepare + execute)
 * pour se proteger contre les injections SQL.
 *
 * ============================================================
 */

declare(strict_types=1);


class Produit
{
    // ============================================================
    // PROPRIETES
    // ============================================================

    /**
     * Connexion PDO a la base de donnees.
     * Injectee via le constructeur (injection de dependances).
     */
    private PDO $connexion;

    /** Nom de la table dans la base de donnees */
    private string $table = 'produits';

    // Proprietes correspondant aux colonnes de la table
    public ?int $id = null;
    public string $nom = '';
    public ?string $description = null;
    public float $prix = 0.0;
    public int $stock = 0;
    public ?string $categorie = null;
    public ?string $dateCreation = null;


    // ============================================================
    // CONSTRUCTEUR
    // ============================================================

    /**
     * Recoit la connexion PDO par injection de dependances.
     *
     * L'injection de dependances signifie que la classe recoit
     * ses dependances (ici la connexion BDD) de l'exterieur,
     * au lieu de les creer elle-meme. Avantages :
     * - La classe est plus facile a tester
     * - On peut changer de base de donnees sans modifier la classe
     *
     * @param PDO $connexion - La connexion PDO active
     */
    public function __construct(PDO $connexion)
    {
        $this->connexion = $connexion;
    }


    // ============================================================
    // READ : LIRE TOUS LES PRODUITS
    // ============================================================

    /**
     * Recupere tous les produits de la table.
     *
     * Correspond a :  GET /index.php?action=produits
     * Requete SQL :   SELECT * FROM produits ORDER BY date_creation DESC
     *
     * @return array - Tableau de tous les produits (tableaux associatifs)
     */
    public function lireTous(): array
    {
        // Preparer la requete SQL
        // ORDER BY date_creation DESC : les plus recents en premier
        $requete = $this->connexion->prepare(
            "SELECT id, nom, description, prix, stock, categorie, date_creation
             FROM {$this->table}
             ORDER BY date_creation DESC"
        );

        // Executer la requete
        $requete->execute();

        // fetchAll() recupere TOUTES les lignes d'un coup
        // Chaque ligne est un tableau associatif grace a PDO::FETCH_ASSOC
        return $requete->fetchAll();
    }


    // ============================================================
    // READ : LIRE UN SEUL PRODUIT PAR SON ID
    // ============================================================

    /**
     * Recupere un produit par son identifiant.
     *
     * Correspond a :  GET /index.php?action=produit&id=1
     * Requete SQL :   SELECT * FROM produits WHERE id = :id
     *
     * @param int $id - L'identifiant du produit a lire
     * @return array|false - Le produit trouve, ou false si inexistant
     */
    public function lireParId(int $id): array|false
    {
        /**
         * REQUETE PREPAREE avec parametre nomme :id
         *
         * On utilise :id comme placeholder (marqueur de position).
         * La valeur reelle sera fournie par execute().
         * PDO s'occupe de l'echappement => pas d'injection SQL possible.
         */
        $requete = $this->connexion->prepare(
            "SELECT id, nom, description, prix, stock, categorie, date_creation
             FROM {$this->table}
             WHERE id = :id
             LIMIT 1"
        );

        // Associer la valeur au parametre :id
        // PDO::PARAM_INT indique que c'est un entier
        $requete->bindValue(':id', $id, PDO::PARAM_INT);

        // Executer la requete
        $requete->execute();

        // fetch() recupere UNE seule ligne (ou false si aucun resultat)
        return $requete->fetch();
    }


    // ============================================================
    // CREATE : CREER UN NOUVEAU PRODUIT
    // ============================================================

    /**
     * Insere un nouveau produit dans la base de donnees.
     *
     * Correspond a :  POST /index.php?action=creer
     * Requete SQL :   INSERT INTO produits (...) VALUES (...)
     *
     * Les donnees du produit doivent etre definies sur les proprietes
     * de l'objet AVANT d'appeler cette methode :
     *   $produit->nom = "Clavier";
     *   $produit->prix = 89.99;
     *   $produit->creer();
     *
     * @return bool - true si l'insertion a reussi
     */
    public function creer(): bool
    {
        $requete = $this->connexion->prepare(
            "INSERT INTO {$this->table} (nom, description, prix, stock, categorie)
             VALUES (:nom, :description, :prix, :stock, :categorie)"
        );

        /**
         * bindValue() associe chaque parametre nomme a sa valeur.
         *
         * On utilise les proprietes de l'objet ($this->nom, $this->prix...)
         * qui ont ete remplies avec les donnees du client avant l'appel.
         */
        $requete->bindValue(':nom', $this->nom, PDO::PARAM_STR);
        $requete->bindValue(':description', $this->description, PDO::PARAM_STR);
        $requete->bindValue(':prix', $this->prix);
        $requete->bindValue(':stock', $this->stock, PDO::PARAM_INT);
        $requete->bindValue(':categorie', $this->categorie, PDO::PARAM_STR);

        // execute() retourne true si l'insertion a reussi
        if ($requete->execute()) {
            // lastInsertId() recupere l'id auto-genere par MySQL
            $this->id = (int) $this->connexion->lastInsertId();
            return true;
        }

        return false;
    }


    // ============================================================
    // UPDATE : MODIFIER UN PRODUIT EXISTANT
    // ============================================================

    /**
     * Met a jour un produit existant dans la base de donnees.
     *
     * Correspond a :  PUT /index.php?action=modifier&id=1
     * Requete SQL :   UPDATE produits SET ... WHERE id = :id
     *
     * @return bool - true si la modification a reussi
     */
    public function modifier(): bool
    {
        $requete = $this->connexion->prepare(
            "UPDATE {$this->table}
             SET nom = :nom,
                 description = :description,
                 prix = :prix,
                 stock = :stock,
                 categorie = :categorie
             WHERE id = :id"
        );

        $requete->bindValue(':id', $this->id, PDO::PARAM_INT);
        $requete->bindValue(':nom', $this->nom, PDO::PARAM_STR);
        $requete->bindValue(':description', $this->description, PDO::PARAM_STR);
        $requete->bindValue(':prix', $this->prix);
        $requete->bindValue(':stock', $this->stock, PDO::PARAM_INT);
        $requete->bindValue(':categorie', $this->categorie, PDO::PARAM_STR);

        return $requete->execute();
    }


    // ============================================================
    // DELETE : SUPPRIMER UN PRODUIT
    // ============================================================

    /**
     * Supprime un produit de la base de donnees.
     *
     * Correspond a :  DELETE /index.php?action=supprimer&id=1
     * Requete SQL :   DELETE FROM produits WHERE id = :id
     *
     * @param int $id - L'identifiant du produit a supprimer
     * @return bool - true si la suppression a reussi
     */
    public function supprimer(int $id): bool
    {
        $requete = $this->connexion->prepare(
            "DELETE FROM {$this->table}
             WHERE id = :id"
        );

        $requete->bindValue(':id', $id, PDO::PARAM_INT);
        $requete->execute();

        /**
         * rowCount() retourne le nombre de lignes affectees.
         * Si le produit existait et a ete supprime, rowCount() = 1.
         * Si l'id n'existait pas, rowCount() = 0.
         */
        return $requete->rowCount() > 0;
    }
}
