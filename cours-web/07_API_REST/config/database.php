<?php
/**
 * ============================================================
 * CONFIGURATION DE LA BASE DE DONNEES
 * ============================================================
 *
 * Cette classe gere la connexion a la base de donnees MySQL
 * en utilisant PDO (PHP Data Objects).
 *
 * PDO est l'interface recommandee en PHP pour interagir avec
 * les bases de donnees. Elle offre :
 * - La portabilite (fonctionne avec MySQL, PostgreSQL, SQLite...)
 * - Les requetes preparees (protection contre l'injection SQL)
 * - La gestion des erreurs via les exceptions
 *
 * Utilisation :
 *   $db = new Database();
 *   $connexion = $db->getConnexion();
 *
 * ============================================================
 */

declare(strict_types=1);


class Database
{
    // ============================================================
    // PARAMETRES DE CONNEXION
    // ============================================================

    /**
     * Adresse du serveur MySQL.
     * "localhost" signifie que MySQL tourne sur la meme machine.
     */
    private string $hote = 'localhost';

    /**
     * Nom de la base de donnees a utiliser.
     * Doit correspondre a celle creee par database.sql.
     */
    private string $nomBase = 'magasin';

    /**
     * Nom d'utilisateur MySQL.
     * "root" est l'utilisateur par defaut de MAMP.
     */
    private string $utilisateur = 'root';

    /**
     * Mot de passe MySQL.
     * "root" est le mot de passe par defaut de MAMP.
     */
    private string $motDePasse = 'root';

    /**
     * Port MySQL de MAMP (par defaut : 8889 sur Mac).
     * Modifiez si votre configuration MAMP est differente.
     */
    private int $port = 8889;

    /**
     * Instance de la connexion PDO.
     * Initialisee a null, remplie par getConnexion().
     */
    private ?PDO $connexion = null;


    // ============================================================
    // METHODE DE CONNEXION
    // ============================================================

    /**
     * Etablit et retourne la connexion PDO a la base de donnees.
     *
     * Si la connexion existe deja, on la reutilise (pattern Singleton simple).
     * Cela evite d'ouvrir une nouvelle connexion a chaque requete.
     *
     * @return PDO - L'objet de connexion PDO
     */
    public function getConnexion(): PDO
    {
        // Si la connexion n'existe pas encore, on la cree
        if ($this->connexion === null) {

            try {
                /**
                 * Le DSN (Data Source Name) est la chaine qui decrit
                 * comment se connecter a la base de donnees.
                 *
                 * Format : "mysql:host=HOTE;port=PORT;dbname=BASE;charset=utf8mb4"
                 */
                $dsn = "mysql:host={$this->hote};port={$this->port};dbname={$this->nomBase};charset=utf8mb4";

                /**
                 * Creation de l'objet PDO avec les options :
                 *
                 * ATTR_ERRMODE => ERRMODE_EXCEPTION
                 *   PDO lance une exception en cas d'erreur SQL
                 *   (au lieu de retourner false silencieusement)
                 *
                 * ATTR_DEFAULT_FETCH_MODE => FETCH_ASSOC
                 *   Les resultats sont retournes sous forme de tableau associatif
                 *   (cle = nom de la colonne, valeur = donnee)
                 *
                 * ATTR_EMULATE_PREPARES => false
                 *   Desactive l'emulation des requetes preparees
                 *   pour une meilleure securite (les parametres sont envoyes
                 *   separement de la requete SQL au serveur MySQL)
                 */
                $this->connexion = new PDO(
                    $dsn,
                    $this->utilisateur,
                    $this->motDePasse,
                    [
                        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES   => false,
                    ]
                );

            } catch (PDOException $exception) {
                /**
                 * En cas d'erreur de connexion :
                 * - En developpement : on affiche le message pour debugger
                 * - En production : on afficherait un message generique
                 *   et on loggerait l'erreur dans un fichier
                 */
                http_response_code(500);
                echo json_encode([
                    'erreur'  => true,
                    'message' => 'Erreur de connexion a la base de donnees.',
                    'detail'  => $exception->getMessage()
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }
        }

        return $this->connexion;
    }
}
