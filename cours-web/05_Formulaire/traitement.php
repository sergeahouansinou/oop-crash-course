<?php
/**
 * ============================================================
 * TRAITEMENT DU FORMULAIRE - VALIDATION COTE SERVEUR
 * ============================================================
 *
 * Ce fichier PHP gere la validation des donnees envoyees
 * par le formulaire de contact (methode POST).
 *
 * Architecture Orientee Objet (POO) avec PHP 8.0+ :
 * - Classe Sanitizer  : nettoyage et assainissement des donnees
 * - Classe Validateur : validation de chaque champ
 *
 * IMPORTANT : La validation cote serveur est INDISPENSABLE
 * meme si une validation cote client (JavaScript) existe.
 * Le JavaScript peut etre desactive ou contourne par l'utilisateur.
 *
 * ============================================================
 */

declare(strict_types=1);


// ============================================================
// CLASSE SANITIZER
// Responsable du nettoyage et de l'assainissement des donnees
// Utilise des methodes statiques car elle ne stocke aucun etat
// ============================================================

/**
 * Classe utilitaire pour nettoyer les donnees utilisateur.
 *
 * Le nettoyage (sanitization) consiste a :
 * - Supprimer les espaces inutiles (trim)
 * - Convertir les caracteres speciaux HTML (htmlspecialchars)
 * - Utiliser les filtres PHP (filter_var)
 *
 * Cela protege contre les attaques XSS (Cross-Site Scripting).
 */
class Sanitizer
{
    /**
     * Nettoie une chaine de caracteres generique.
     * - Supprime les espaces en debut et fin (trim)
     * - Supprime les antislashs (stripslashes)
     * - Convertit les caracteres speciaux HTML en entites
     *
     * @param string $donnee - La chaine brute a nettoyer
     * @return string - La chaine nettoyee et securisee
     */
    public static function nettoyerChaine(string $donnee): string
    {
        // 1. Supprimer les espaces superflus
        $donnee = trim($donnee);

        // 2. Supprimer les antislashs (protection basique)
        $donnee = stripslashes($donnee);

        // 3. Convertir les caracteres speciaux en entites HTML
        //    ENT_QUOTES : convertit les guillemets simples ET doubles
        //    'UTF-8'    : encodage des caracteres
        $donnee = htmlspecialchars($donnee, ENT_QUOTES, 'UTF-8');

        return $donnee;
    }

    /**
     * Nettoie et valide une adresse email.
     * Utilise le filtre FILTER_SANITIZE_EMAIL de PHP
     * qui supprime tous les caracteres non autorises dans un email.
     *
     * @param string $email - L'adresse email brute
     * @return string - L'email nettoye
     */
    public static function nettoyerEmail(string $email): string
    {
        // 1. Supprimer les espaces
        $email = trim($email);

        // 2. Appliquer le filtre de nettoyage email
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        // 3. Convertir en minuscules (les emails ne sont pas sensibles a la casse)
        $email = strtolower($email);

        return $email;
    }

    /**
     * Nettoie un numero de telephone.
     * Ne conserve que les chiffres.
     *
     * @param string $telephone - Le numero brut
     * @return string - Le numero contenant uniquement des chiffres
     */
    public static function nettoyerTelephone(string $telephone): string
    {
        // Supprimer les espaces
        $telephone = trim($telephone);

        // Ne garder que les chiffres (supprimer espaces, tirets, points, etc.)
        $telephone = preg_replace('/[^0-9]/', '', $telephone);

        return $telephone;
    }

    /**
     * Nettoie un nombre entier.
     * Utilise le filtre FILTER_SANITIZE_NUMBER_INT de PHP.
     *
     * @param string $nombre - La valeur brute
     * @return int - Le nombre entier nettoye
     */
    public static function nettoyerEntier(string $nombre): int
    {
        $nombre = trim($nombre);
        $nombre = filter_var($nombre, FILTER_SANITIZE_NUMBER_INT);

        return (int) $nombre;
    }
}


// ============================================================
// CLASSE VALIDATEUR
// Responsable de la validation de chaque champ du formulaire
// ============================================================

/**
 * Classe principale de validation du formulaire.
 *
 * Fonctionnement :
 * 1. Le constructeur recoit les donnees POST et les nettoie
 * 2. La methode validerTout() execute toutes les validations
 * 3. Les erreurs sont stockees dans un tableau interne
 * 4. On peut verifier si le formulaire est valide avec estValide()
 */
class Validateur
{
    /**
     * Tableau des erreurs de validation.
     * Cle = nom du champ, Valeur = message d'erreur
     *
     * @var array<string, string>
     */
    private array $erreurs = [];

    /**
     * Tableau des donnees nettoyees (sanitized).
     * Contient les valeurs propres apres nettoyage.
     *
     * @var array<string, mixed>
     */
    private array $donnees = [];


    /**
     * Constructeur : recoit les donnees brutes et les nettoie.
     *
     * @param array $donneesPost - Les donnees brutes de $_POST
     */
    public function __construct(array $donneesPost)
    {
        // Nettoyer chaque champ avec la classe Sanitizer
        $this->donnees = [
            'nom'              => Sanitizer::nettoyerChaine($donneesPost['nom'] ?? ''),
            'prenom'           => Sanitizer::nettoyerChaine($donneesPost['prenom'] ?? ''),
            'email'            => Sanitizer::nettoyerEmail($donneesPost['email'] ?? ''),
            'telephone'        => Sanitizer::nettoyerTelephone($donneesPost['telephone'] ?? ''),
            'age'              => Sanitizer::nettoyerEntier($donneesPost['age'] ?? '0'),
            'mot_de_passe'     => $donneesPost['mot_de_passe'] ?? '',      // Pas de htmlspecialchars sur le mdp
            'confirmation_mdp' => $donneesPost['confirmation_mdp'] ?? '',  // Idem
            'genre'            => Sanitizer::nettoyerChaine($donneesPost['genre'] ?? ''),
            'interets'         => $donneesPost['interets'] ?? [],          // Tableau de checkboxes
            'message'          => Sanitizer::nettoyerChaine($donneesPost['message'] ?? ''),
            'conditions'       => Sanitizer::nettoyerChaine($donneesPost['conditions'] ?? ''),
        ];

        // Nettoyer les centres d'interet (c'est un tableau)
        if (is_array($this->donnees['interets'])) {
            $this->donnees['interets'] = array_map(
                [Sanitizer::class, 'nettoyerChaine'],
                $this->donnees['interets']
            );
        } else {
            $this->donnees['interets'] = [];
        }
    }


    // ============================================================
    // METHODES DE VALIDATION INDIVIDUELLES
    // Chaque methode valide un champ et ajoute une erreur si besoin
    // ============================================================

    /**
     * Valide le champ "Nom".
     * - Obligatoire
     * - Minimum 2 caracteres, maximum 50
     * - Lettres, accents, tirets et espaces uniquement
     */
    public function validerNom(): void
    {
        $nom = $this->donnees['nom'];

        if (empty($nom)) {
            $this->erreurs['nom'] = 'Le nom est obligatoire.';
            return;
        }

        // Decoder les entites HTML pour tester la vraie longueur
        $nomDecode = html_entity_decode($nom, ENT_QUOTES, 'UTF-8');

        if (mb_strlen($nomDecode) < 2) {
            $this->erreurs['nom'] = 'Le nom doit contenir au moins 2 caracteres.';
            return;
        }

        if (mb_strlen($nomDecode) > 50) {
            $this->erreurs['nom'] = 'Le nom ne peut pas depasser 50 caracteres.';
            return;
        }

        // Verifier le format (lettres, accents, tirets, espaces, apostrophes)
        if (!preg_match('/^[a-zA-ZÀ-ÿ\s\-\']+$/u', $nomDecode)) {
            $this->erreurs['nom'] = 'Le nom ne peut contenir que des lettres, accents, tirets et espaces.';
        }
    }

    /**
     * Valide le champ "Prenom".
     * Memes regles que le nom.
     */
    public function validerPrenom(): void
    {
        $prenom = $this->donnees['prenom'];

        if (empty($prenom)) {
            $this->erreurs['prenom'] = 'Le prenom est obligatoire.';
            return;
        }

        $prenomDecode = html_entity_decode($prenom, ENT_QUOTES, 'UTF-8');

        if (mb_strlen($prenomDecode) < 2) {
            $this->erreurs['prenom'] = 'Le prenom doit contenir au moins 2 caracteres.';
            return;
        }

        if (mb_strlen($prenomDecode) > 50) {
            $this->erreurs['prenom'] = 'Le prenom ne peut pas depasser 50 caracteres.';
            return;
        }

        if (!preg_match('/^[a-zA-ZÀ-ÿ\s\-\']+$/u', $prenomDecode)) {
            $this->erreurs['prenom'] = 'Le prenom ne peut contenir que des lettres, accents, tirets et espaces.';
        }
    }

    /**
     * Valide le champ "Email".
     * Utilise le filtre FILTER_VALIDATE_EMAIL de PHP
     * qui est la methode la plus fiable pour valider un email.
     */
    public function validerEmail(): void
    {
        $email = $this->donnees['email'];

        if (empty($email)) {
            $this->erreurs['email'] = 'L\'adresse e-mail est obligatoire.';
            return;
        }

        // Utiliser le filtre PHP integre pour valider l'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->erreurs['email'] = 'L\'adresse e-mail n\'est pas valide.';
        }
    }

    /**
     * Valide le champ "Telephone".
     * Format francais : 10 chiffres commencant par 0.
     */
    public function validerTelephone(): void
    {
        $telephone = $this->donnees['telephone'];

        if (empty($telephone)) {
            $this->erreurs['telephone'] = 'Le numero de telephone est obligatoire.';
            return;
        }

        // Verifier le format : commence par 0, suivi de 9 chiffres
        if (!preg_match('/^0[1-9][0-9]{8}$/', $telephone)) {
            $this->erreurs['telephone'] = 'Le telephone doit contenir 10 chiffres et commencer par 0.';
        }
    }

    /**
     * Valide le champ "Age".
     * Doit etre un nombre entre 13 et 120.
     */
    public function validerAge(): void
    {
        $age = $this->donnees['age'];

        if ($age === 0 && ($this->donnees['age'] === '' || $this->donnees['age'] === 0)) {
            // Verifier si le champ etait vide a l'origine
            $this->erreurs['age'] = 'L\'age est obligatoire.';
            return;
        }

        if ($age < 13) {
            $this->erreurs['age'] = 'Vous devez avoir au moins 13 ans.';
            return;
        }

        if ($age > 120) {
            $this->erreurs['age'] = 'L\'age ne peut pas depasser 120 ans.';
        }
    }

    /**
     * Valide le champ "Mot de passe".
     * Regles :
     * - Minimum 8 caracteres
     * - Au moins 1 majuscule, 1 minuscule, 1 chiffre, 1 caractere special
     * - La confirmation doit correspondre
     */
    public function validerMotDePasse(): void
    {
        $motDePasse = $this->donnees['mot_de_passe'];
        $confirmation = $this->donnees['confirmation_mdp'];

        // Verifier que le mot de passe n'est pas vide
        if (empty($motDePasse)) {
            $this->erreurs['mot_de_passe'] = 'Le mot de passe est obligatoire.';
            return;
        }

        // Verifier la longueur minimale (8 caracteres)
        if (strlen($motDePasse) < 8) {
            $this->erreurs['mot_de_passe'] = 'Le mot de passe doit contenir au moins 8 caracteres.';
            return;
        }

        // Verifier la presence d'au moins une majuscule
        if (!preg_match('/[A-Z]/', $motDePasse)) {
            $this->erreurs['mot_de_passe'] = 'Le mot de passe doit contenir au moins une lettre majuscule.';
            return;
        }

        // Verifier la presence d'au moins une minuscule
        if (!preg_match('/[a-z]/', $motDePasse)) {
            $this->erreurs['mot_de_passe'] = 'Le mot de passe doit contenir au moins une lettre minuscule.';
            return;
        }

        // Verifier la presence d'au moins un chiffre
        if (!preg_match('/[0-9]/', $motDePasse)) {
            $this->erreurs['mot_de_passe'] = 'Le mot de passe doit contenir au moins un chiffre.';
            return;
        }

        // Verifier la presence d'au moins un caractere special
        if (!preg_match('/[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?`~]/', $motDePasse)) {
            $this->erreurs['mot_de_passe'] = 'Le mot de passe doit contenir au moins un caractere special.';
            return;
        }

        // Verifier que la confirmation correspond
        if ($motDePasse !== $confirmation) {
            $this->erreurs['confirmation_mdp'] = 'Les mots de passe ne correspondent pas.';
        }
    }

    /**
     * Valide le champ "Message" (textarea).
     * Doit contenir entre 10 et 1000 caracteres.
     */
    public function validerMessage(): void
    {
        $message = $this->donnees['message'];

        if (empty($message)) {
            $this->erreurs['message'] = 'Le message est obligatoire.';
            return;
        }

        // Decoder les entites HTML pour compter les vrais caracteres
        $messageDecode = html_entity_decode($message, ENT_QUOTES, 'UTF-8');

        if (mb_strlen($messageDecode) < 10) {
            $this->erreurs['message'] = 'Le message doit contenir au moins 10 caracteres.';
            return;
        }

        if (mb_strlen($messageDecode) > 1000) {
            $this->erreurs['message'] = 'Le message ne peut pas depasser 1000 caracteres.';
        }
    }

    /**
     * Valide que les conditions generales sont acceptees.
     */
    public function validerConditions(): void
    {
        $conditions = $this->donnees['conditions'];

        if (empty($conditions) || $conditions !== 'accepte') {
            $this->erreurs['conditions'] = 'Vous devez accepter les conditions generales d\'utilisation.';
        }
    }

    /**
     * Valide le genre (doit etre une valeur autorisee).
     */
    public function validerGenre(): void
    {
        $genre = $this->donnees['genre'];
        $genresAutorises = ['homme', 'femme', 'autre'];

        if (empty($genre)) {
            $this->erreurs['genre'] = 'Veuillez selectionner un genre.';
            return;
        }

        // Verifier que la valeur fait partie des options autorisees
        // (protection contre la falsification des donnees)
        if (!in_array($genre, $genresAutorises, true)) {
            $this->erreurs['genre'] = 'La valeur du genre n\'est pas valide.';
        }
    }


    // ============================================================
    // METHODE DE VALIDATION GLOBALE
    // ============================================================

    /**
     * Execute toutes les validations en une seule fois.
     *
     * @return bool - true si le formulaire est entierement valide
     */
    public function validerTout(): bool
    {
        // Reinitialiser les erreurs
        $this->erreurs = [];

        // Executer chaque validation
        $this->validerNom();
        $this->validerPrenom();
        $this->validerEmail();
        $this->validerTelephone();
        $this->validerAge();
        $this->validerMotDePasse();
        $this->validerGenre();
        $this->validerMessage();
        $this->validerConditions();

        // Le formulaire est valide s'il n'y a aucune erreur
        return empty($this->erreurs);
    }


    // ============================================================
    // GETTERS (methodes d'acces aux proprietes privees)
    // ============================================================

    /**
     * Retourne le tableau des erreurs de validation.
     *
     * @return array<string, string>
     */
    public function getErreurs(): array
    {
        return $this->erreurs;
    }

    /**
     * Retourne le tableau des donnees nettoyees.
     *
     * @return array<string, mixed>
     */
    public function getDonnees(): array
    {
        return $this->donnees;
    }

    /**
     * Verifie si le formulaire est valide (aucune erreur).
     *
     * @return bool
     */
    public function estValide(): bool
    {
        return empty($this->erreurs);
    }
}


// ============================================================
// TRAITEMENT DU FORMULAIRE
// ============================================================

// Verifier que la requete est bien de type POST
// (le formulaire a ete soumis)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Creer une instance du validateur avec les donnees POST
    $validateur = new Validateur($_POST);

    // Lancer la validation de tous les champs
    $validateur->validerTout();

    // Recuperer les donnees nettoyees et les erreurs
    $donnees = $validateur->getDonnees();
    $erreurs = $validateur->getErreurs();

} else {
    // Si la page est accedee directement (GET), rediriger vers le formulaire
    header('Location: index.html');
    exit;
}

?>
<!DOCTYPE html>
<!--
    ============================================================
    PAGE DE RESULTAT DU TRAITEMENT DU FORMULAIRE
    Affiche soit un message de succes, soit les erreurs
    ============================================================
-->
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultat du formulaire</title>

    <!-- Styles en ligne pour cette page de resultat -->
    <style>
        /* ============================================================
           STYLES DE BASE POUR LA PAGE DE RESULTAT
           ============================================================ */

        /* Reset et police par defaut */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #1f2937;
            background-color: #f9fafb;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* En-tete */
        header {
            background-color: #2563eb;
            color: white;
            text-align: center;
            padding: 2rem 1rem;
        }

        header h1 {
            font-size: 2rem;
        }

        /* Contenu principal */
        main {
            flex: 1;
            max-width: 700px;
            width: 100%;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        /* ============================================================
           CARTE DE RESULTAT (succes ou erreur)
           ============================================================ */
        .carte {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-bottom: 1.5rem;
        }

        /* --- Style pour le message de succes --- */
        .carte-succes {
            border-left: 5px solid #16a34a;
        }

        .carte-succes h2 {
            color: #16a34a;
            margin-bottom: 1rem;
        }

        /* --- Style pour le message d'erreur --- */
        .carte-erreur {
            border-left: 5px solid #dc2626;
        }

        .carte-erreur h2 {
            color: #dc2626;
            margin-bottom: 1rem;
        }

        /* ============================================================
           TABLEAU RECAPITULATIF DES DONNEES
           ============================================================ */
        .tableau-recap {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        .tableau-recap th,
        .tableau-recap td {
            padding: 0.75rem 1rem;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        .tableau-recap th {
            background-color: #f3f4f6;
            font-weight: 600;
            width: 35%;
            color: #374151;
        }

        .tableau-recap td {
            color: #1f2937;
        }

        /* ============================================================
           LISTE DES ERREURS
           ============================================================ */
        .liste-erreurs {
            list-style: none;
            padding: 0;
        }

        .liste-erreurs li {
            padding: 0.75rem 1rem;
            margin-bottom: 0.5rem;
            background-color: #fef2f2;
            border-left: 3px solid #dc2626;
            border-radius: 0 4px 4px 0;
            color: #dc2626;
            font-weight: 500;
        }

        /* ============================================================
           BOUTON RETOUR
           ============================================================ */
        .btn-retour {
            display: inline-block;
            padding: 0.85rem 2rem;
            background-color: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            transition: background-color 0.2s ease, transform 0.2s ease;
            margin-top: 1.5rem;
        }

        .btn-retour:hover {
            background-color: #1d4ed8;
            transform: translateY(-2px);
        }

        /* Centrage du bouton */
        .centre {
            text-align: center;
        }

        /* ============================================================
           PIED DE PAGE
           ============================================================ */
        footer {
            background-color: #1f2937;
            color: #9ca3af;
            text-align: center;
            padding: 1rem;
            font-size: 0.9rem;
        }

        /* ============================================================
           RESPONSIVE
           ============================================================ */
        @media (max-width: 768px) {
            header h1 {
                font-size: 1.5rem;
            }

            .carte {
                padding: 1.5rem;
            }

            .tableau-recap th,
            .tableau-recap td {
                padding: 0.5rem;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>

    <!-- En-tete de la page de resultat -->
    <header>
        <h1>Resultat du formulaire</h1>
    </header>

    <main>

        <?php if ($validateur->estValide()): ?>
            <!-- ============================================================
                 CAS 1 : FORMULAIRE VALIDE - AFFICHAGE DU RECAPITULATIF
                 ============================================================ -->

            <div class="carte carte-succes">
                <h2>&#10004; Formulaire envoye avec succes !</h2>
                <p>Merci <strong><?= htmlspecialchars($donnees['prenom'] . ' ' . $donnees['nom']) ?></strong>,
                   vos informations ont ete validees et enregistrees.</p>
            </div>

            <!-- Tableau recapitulatif des donnees soumises -->
            <div class="carte">
                <h3>Recapitulatif de vos informations :</h3>

                <table class="tableau-recap">
                    <tbody>
                        <!-- Nom -->
                        <tr>
                            <th>Nom</th>
                            <td><?= htmlspecialchars($donnees['nom']) ?></td>
                        </tr>

                        <!-- Prenom -->
                        <tr>
                            <th>Prenom</th>
                            <td><?= htmlspecialchars($donnees['prenom']) ?></td>
                        </tr>

                        <!-- Email -->
                        <tr>
                            <th>E-mail</th>
                            <td><?= htmlspecialchars($donnees['email']) ?></td>
                        </tr>

                        <!-- Telephone -->
                        <tr>
                            <th>Telephone</th>
                            <td><?= htmlspecialchars($donnees['telephone']) ?></td>
                        </tr>

                        <!-- Age -->
                        <tr>
                            <th>Age</th>
                            <td><?= (int) $donnees['age'] ?> ans</td>
                        </tr>

                        <!-- Genre -->
                        <tr>
                            <th>Genre</th>
                            <td><?= htmlspecialchars(ucfirst($donnees['genre'])) ?></td>
                        </tr>

                        <!-- Centres d'interet -->
                        <tr>
                            <th>Centres d'interet</th>
                            <td>
                                <?php if (!empty($donnees['interets'])): ?>
                                    <?= htmlspecialchars(implode(', ', array_map('ucfirst', $donnees['interets']))) ?>
                                <?php else: ?>
                                    <em>Aucun selectionne</em>
                                <?php endif; ?>
                            </td>
                        </tr>

                        <!-- Message -->
                        <tr>
                            <th>Message</th>
                            <td><?= nl2br(htmlspecialchars($donnees['message'])) ?></td>
                        </tr>

                        <!-- Conditions -->
                        <tr>
                            <th>Conditions</th>
                            <td>&#10004; Acceptees</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Bouton retour vers le formulaire -->
            <div class="centre">
                <a href="index.html" class="btn-retour">&#8592; Retour au formulaire</a>
            </div>

        <?php else: ?>
            <!-- ============================================================
                 CAS 2 : FORMULAIRE INVALIDE - AFFICHAGE DES ERREURS
                 ============================================================ -->

            <div class="carte carte-erreur">
                <h2>&#10008; Des erreurs ont ete detectees</h2>
                <p>Veuillez corriger les erreurs suivantes et soumettre le formulaire a nouveau :</p>

                <!-- Liste de toutes les erreurs -->
                <ul class="liste-erreurs">
                    <?php foreach ($erreurs as $champ => $messageErreur): ?>
                        <!-- Affichage de chaque erreur avec le nom du champ -->
                        <li>
                            <strong><?= htmlspecialchars(ucfirst(str_replace('_', ' ', $champ))) ?> :</strong>
                            <?= htmlspecialchars($messageErreur) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Bouton retour pour corriger le formulaire -->
            <div class="centre">
                <a href="index.html" class="btn-retour">&#8592; Corriger le formulaire</a>
            </div>

        <?php endif; ?>

    </main>

    <!-- Pied de page -->
    <footer>
        <p>&copy; 2026 - Cours Formulaire - Validation Client &amp; Serveur</p>
    </footer>

</body>
</html>
